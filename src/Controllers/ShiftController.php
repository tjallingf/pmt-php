<?php 
    namespace Tjall\Pmt\Controllers;

    use Tjall\Pmt\Controllers\Controller;
    use Tjall\Pmt\Lib;
    use Tjall\Pmt\Models\ShiftModel;

    class ShiftController extends Controller {
        protected $model = ShiftModel::class;

        function index(int $from_date, int $to_date) {
            $res = $this->request('GET', 'shifts', [
                'query' => [
                    'date' => [
                        'gte' => date('Y-m-d', $from_date),
                        'lte' => date('Y-m-d', $to_date)
                    ]
                ]
            ]);

            $json = Lib::getJson($res);
            $items = $json['result'];

            array_walk($items, function(&$item) use ($json) {
                $item['department'] = Lib::arrayGetWhere(
                    $json['aggregation']['departments'],
                    'department_id',
                    $item['department_id']);
            });

            return $this->formatMultiple($items);
        }
    }
?>