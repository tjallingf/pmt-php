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
            'department.color' => 'department.color'
        ];

        public static function custom__department(int $id, array $data, array $input): array {
            return [];
        }
    }
?>