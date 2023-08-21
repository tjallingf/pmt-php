<?php
    namespace Tjall\Pmt\Sessions;

    use GuzzleHttp\Client;
    use Tjall\Pmt\Lib;

    class UserSession {
        protected string $apiUri;
        protected string $contextToken;
        protected string $userToken;
        public string $accountId;
        public Client $httpClient;
        public array $httpClientOptions;
        public array $storeData;
        public array $userData;

        function __construct(string $base_uri, string $account_id, string $context_token, string $user_token) {
            $this->apiUri = trim($base_uri, ' /\\').'/api/v2/';
            $this->contextToken = $context_token;
            $this->userToken = $user_token;
            $this->accountId = $account_id;

            $this->httpClientOptions = [
                'headers' => [
                    'x-api-context' => $context_token,
                    'x-api-user' => $user_token
                ],
                'allow_redirects' => false,
                'base_uri' => $this->apiUri,
                'debug' => fopen('./log.txt', 'a')
            ];

            $this->httpClient = new Client($this->httpClientOptions);
            $this->storeData = Lib::getJson($this->httpClient->request('GET', 'stores'))['result'][0];
            $this->userData = Lib::getJson($this->httpClient->request('GET', 'employees'))['result'][0];
        }
    }