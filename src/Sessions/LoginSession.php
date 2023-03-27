<?php
    namespace Tjall\Pmt\Sessions;

    use \GuzzleHttp\Client;
    use GuzzleHttp\Exception\RequestException;
    use Tjall\Pmt\Sessions\UserSession;
    use Exception;
    use Tjall\Pmt\Lib;

    class LoginSession {
        protected string $tenant;
        protected string $baseUri;
        protected Client $httpClient;
        protected string $username;
        protected string $password;

        function __construct(string $tenant, string $username, string $password) {
            $base_uri = str_replace('{tenant}', trim($tenant, ' \\/'), self::BASE_URI);
            $this->tenant = $tenant;
            $this->baseUri = $base_uri;
            
            $this->username = $username;
            $this->password = $password;

            $this->httpClient = new Client([
                'base_uri' => $this->baseUri
            ]);
        }

        function submit(): UserSession {
            try {
                $res = $this->httpClient->post('/pmtLoginSso', [
                    'json' => [
                        'username' => $this->username,
                        'password' => $this->password
                    ]
                ]);
            } catch(RequestException $e) {
                if(!$e->hasResponse())
                    throw new Exception('Er is iets misgegaan.');

                switch($e->getResponse()->getStatusCode()) {
                    case 404:
                        throw new Exception("Ongeldige tenant: '{$this->tenant}'.");
                }
            }
            
            $result = Lib::getJson($res)['result'];
            if(@$result['authenticated'] !== true)
                throw new Exception(@$result[0]['message'] ?? 'Er is iets misgegaan.');

            return new UserSession(
                $this->baseUri,
                $result['account_id'],
                $result['context_token'],
                $result['user_token']
            );
        }

        protected const BASE_URI = 'https://{tenant}.personeelstool.nl/';
    }