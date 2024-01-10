<?php
    namespace Tjall\Pmt\Models;

    use Tjall\Pmt\Models\Model;

    class ShiftModel extends Model {
        protected const MAP = [
            'id'               => 'shift_id',
            'start'            => ['start_datetime', Model::TYPE_DATETIME],
            'end'              => ['end_datetime', Model::TYPE_DATETIME],
            'created'          => ['created.datetime', Model::TYPE_DATETIME],
            'modified'         => ['modified.datetime', Model::TYPE_DATETIME],
            'department.id'    => 'department.department_id',
            'department.name'  => 'department.department_name',
            'department.color' => 'department.color',
            'breaks'           => ['breaks', Model::CUSTOM]
        ];

        public static function custom__breaks(array $breaks, array $data, array $input): array {
            return array_filter(array_map(function($break) {
                list($hrs, $mins) = explode(":", $break['duration']);
                $float_duration = (int)$hrs + ((int)$mins / 60);
                return $float_duration;
            }, $breaks));
        }
    }
?>