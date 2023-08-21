<?php 
    namespace Tjall\Pmt\Controllers;

    use DateTimeZone;
    use Tjall\Pmt\Controllers\Controller;
    use Tjall\Pmt\Lib;
    use Tjall\Pmt\Models\EmployeeModel;

    class EmployeeController extends Controller {
        protected $model = EmployeeModel::class;
        protected string $storeId;

        function index() {
            $store_id = $this->session->storeData['store_id'];

            $res = $this->request('GET', "stores/$store_id/employees");
            $items = Lib::getJson($res)['result'];

            return $this->formatMultiple($items);
        }
        
        protected function getModelOptions(array $input): array {
            return [
                'from_timezone' => new DateTimeZone('Europe/Amsterdam'),
                'to_timezone' => new DateTimeZone('UTC')
            ];
        }
    }
?>