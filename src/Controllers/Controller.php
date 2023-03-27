<?php
    namespace Tjall\Pmt\Controllers;

    use Tjall\Pmt\Sessions\UserSession;
    use Tjall\Pmt\Models\Model;

    abstract class Controller {
        protected UserSession $session;
        protected $model = Model::class;

        function __construct(UserSession $session) {
            $this->session = $session;
        }

        protected function getModelOptions(array $input): array {
            return [];
        }

        public function formatMultiple(array $input): array {
            $data = [];

            foreach ($input as $item) {
                array_push($data, $this->format($item));
            }

            return $data;
        }

        public function format(array $input): array {
            return (new $this->model($input, $this->getModelOptions($input)))->toArray();
        }

        public function request(string $method, string $url, array $options = []) {
            $options = array_replace_recursive($this->session->httpClientOptions, $options);
            return $this->session->httpClient->request($method, $url, $options);
        }
    }