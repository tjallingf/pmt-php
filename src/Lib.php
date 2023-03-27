<?php
    namespace Tjall\Pmt;
    
    use Psr\Http\Message\ResponseInterface;

    class Lib {
        public static function arrayGetWhere(array $array, string|int $sibling_key, $sibling_value, ?string $return_key = null) {
            return array_column($array, $return_key, $sibling_key)[$sibling_value];
        }

        public static function arrayGet(array $arr, string $path) {
            if(strpos($path, '.') === false) {
                return @$arr[$path];
            }

            $path_exploded = explode('.', $path);

            $value = $arr;

            foreach ($path_exploded as $key) {
                $value = @$value[$key];
            }

            return $value;
        }

        public static function arraySet(array &$arr, string $path, $data): void {
            if(strpos($path, '.') === false) {
                $arr[$path] = $data;
                return;
            }

            $path_exploded = explode('.', $path);

            $current = &$arr;
            foreach($path_exploded as $key) {
                $current = &$current[$key];
            }

            $current = $data;
        }

        public static function getJson(ResponseInterface $res): mixed {
            return json_decode((string) $res->getBody(), true);
        }
    }