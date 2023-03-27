<?php
    namespace Tjall\Pmt\Models;

    use DateTime;
    use DateTimeZone;
    use Tjall\Pmt\Lib;

    abstract class Model {
        public array $data = [];
        public array $options;

        public function __construct(array $input, array $options = []) {
            $this->options = $options;
            $this->data = $this->parse($input);
        }

        protected function parse(array $input): array {
            if(method_exists($this, 'beforeMap'))
                call_user_func_array([$this, 'beforeMap'], []);
                
            $data = [];
            
            foreach (static::MAP as $keypath_out => $options) {               
                list($keypath_in, $flags) = is_array($options) 
                    ? $options
                    : [ $options, 0];

                $value = null;
                if(is_string($keypath_in)) {
                    $value = Lib::arrayGet($input, $keypath_in);
                }

                if($flags === static::REMAP) {
                    $value = $this->callMethod('remap__'.str_replace('.', '_', $keypath_out), [ $value, $data, $input ]);
                } else if($flags === static::CUSTOM) {
                    $value = $this->callMethod('custom__'.str_replace('.', '_', $keypath_out), [ $value, $data, $input ]);
                }

                if($flags === static::TYPE_DATE) {
                    $value = $this->convertToDate($value, 'Y-m-d');
                } else if($flags === static::TYPE_DATETIME) {
                    $value = $this->convertToDate($value, DATE_ATOM);
                } else if($flags === static::TYPE_DATETIME_OR_NULL && !is_null($value)) {
                    $value = $this->convertToDate($value, DATE_ATOM);
                } else if($flags === static::TYPE_BOOL) {
                    $value = boolval($value);
                } else if($flags === static::TYPE_STRING) {
                    $value = strval($value);
                } else if($flags === static::TYPE_NUMBER) {
                    $value = floatval($value);
                }

                Lib::arraySet($data, $keypath_out, $value);
            }

            return $data;
        }

        protected function convertToDate($value, string $format): string {
            $dt = new DateTime($value, @$this->options['from_timezone']);

            if(@$this->options['to_timezone'] instanceof DateTimeZone)
                $dt->setTimezone($this->options['to_timezone']);
                
            return $dt->format($format);
        }

        protected function callMethod(string $method, array $args): mixed {
            if(!method_exists($this, $method))
                throw new \Exception("Model '".static::class."' does not have method '".$method."'.");

            return call_user_func_array([$this, $method], $args);
        }

        public function set(string $keypath, $value): void {        
            Lib::arraySet($this->data, $keypath, $value);
        }

        public function get(string $keypath): mixed {
            return Lib::arrayGet($this->data, $keypath);
        }

        public function toArray(): array {
            return $this->data;
        }

        public const REMAP  = 'remap';
        public const CUSTOM = 'custom';

        public const TYPE_DATE             = 'date';
        public const TYPE_BOOL             = 'boolean';
        public const TYPE_DATETIME         = 'datetime';
        public const TYPE_DATETIME_OR_NULL = 'datetimeOrNull';
        public const TYPE_STRING           = 'string';
        public const TYPE_NUMBER           = 'number';

        protected const MAP = [];
    }