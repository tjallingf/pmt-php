<?php
    namespace Tjall\Pmt\Sessions;

use GuzzleHttp\Client;

    class UserSession {
        protected string $apiUri;
        protected string $contextToken;
        protected string $userToken;
        protected string $accountId;
        public Client $httpClient;
        public array $httpClientOptions;

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
                'query' => [
                    'account_id' => $account_id
                ],
                'allow_redirects' => false,
                'base_uri' => $this->apiUri,
                'debug' => fopen('./log.txt', 'a')
            ];

            $this->httpClient = new Client($this->httpClientOptions);
        }
    }