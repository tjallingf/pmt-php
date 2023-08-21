<?php
    namespace Tjall\Pmt\Models;

    use Tjall\Pmt\Models\Model;

    class EmployeeModel extends Model {
        protected const MAP = [
            'id'               => 'account_id',
            'name'             => 'name',
            'firstName'        => 'first_name',
            'lastName'         => 'last_name',
            'departmentId'     => 'department_id'
        ];
    }
?>