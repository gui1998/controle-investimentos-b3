<?php

namespace App\Http\Controllers;

use App\Actions\CreateOrUpdateInvestmentAction;
use App\Actions\CreateResultsAction;
use App\Http\Rules\VerifyIfCanSellStock;
use App\Models\Broker;
use App\Models\Operation;
use App\Models\Stock;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class OperationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return view('operations.index');
    }

    /**
     * Get the data for listing in yajra.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function getOperations(Request $request, Operation $operation)
    {
        $data = $operation->getData()->where('user_id', $request->user('web')->id);

        try {
            return DataTables::of($data)
                ->editColumn('operation_date', function ($data) {
                    return date('d/m/Y', strtotime($data->operation_date));
                })
                ->editColumn('buy_r_sell', function ($data) {
                    return ($data->buy_r_sell == 'B') ? 'Compra' : "Venda";
                })
                ->addColumn('user', function ($data) {
                    return $data->users->name;
                })
                ->addColumn('stock', function ($data) {
                    return $data->stocks->code;
                })
                ->addColumn('net_value', function ($data) {
                    return round((($data->price - ($data->cost + $data->irrf)) * $data->stock_amount), 2);
                })
                ->make(true);
        } catch (\Exception $e) {
            print($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function store(Request $request, Operation $operation)
    {
        $authorize = (new User)->authorizeRoles($request->user('web'), ['admin']);

        if (!$authorize) {
            return response()->json(['errors' => ["message" => "Ação não autorizada!"]]);
        }
        $validator = Validator::make($request->all(),
            [
                'operation_date' => 'required|date_format:d/m/Y',
                'cost' => 'gt:0.00|nullable',
                'price' => 'required|gt:0.00',
                'stock_amount' => 'required|gt:0',
                'irrf' => 'gt:0.00|nullable',
                'stock_id' => 'required',
                'buy_r_sell' => 'required|in:B,S',
            ],
            [
                'sector_id.required' => 'O campo setor é obrigatório.',
                'operation_date.required' => 'O campo data da operação é obrigatório.',
                'operation_date.date_format' => 'O campo data não contem uma data válida.',
                'cost.required' => 'O campo custo é obrigatório.',
                'cost.gt' => 'O campo custo deve ser maior que 0.',
                'price.required' => 'O campo preço é obrigatório.',
                'price.gt' => 'O campo preço deve ser maior que 0.',
                'stock_type_id.required' => 'O campo tipo é obrigatório.',
                'company_name.required' => 'O campo Empresa  é obrigatório.',
                'code.required' => 'O campo código é obrigatório.',
                'buy_r_sell.required' => 'O campo Compra ou Venda é obrigatório.',
                'buy_r_sell.in' => 'O campo Compra ou Venda deve conter B ou S.',
                'stock_id.required' => 'O campo ativo é obrigatório.',
                'stock_amount.required' => 'O campo quantidade de ativos é obrigatório.',
                'stock_amount.gt' => 'O campo quantidade de ativos deve ser maior que 0.',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $date = $request->get('operation_date');
        $date = str_replace('/', '-', $date);
        $date = Carbon::parse($date)->timezone('America/Sao_Paulo');

        $Rule = new VerifyIfCanSellStock();

        $data = array_merge(
            $request->all(),
            [
                'user_id' => $request->user('web')->id,
                'operation_date' => $date
            ]
        );

        if (!$canSell = $Rule->passes('operation', $data)) {
            return response()->json(['errors' => [$Rule->message()]]);
        }

        $operationCreated = $operation->storeData($data);

        (new CreateResultsAction())->onQueue()->execute($operationCreated);
        (new CreateOrUpdateInvestmentAction())->onQueue()->execute($operationCreated);

        return response()->json(['success' => 'Operation added successfully']);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Request $request, $id)
    {
        $authorize = (new User)->authorizeRoles($request->user('web'), ['admin']);

        if (!$authorize) {
            return redirect()->route('operations.index');
        }
        $operation = new Operation;
        $operations = $operation->findData($id);

        $stocks = Stock::all();
        $brokers = Broker::all();

        return view('operations.edit', [
            'operation' => $operations,
            'stocks' => $stocks,
            'brokers' => $brokers,
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, Operation $operation)
    {
        $authorize = (new User)->authorizeRoles($request->user('web'), ['admin']);

        if (!$authorize) {
            return response()->json(['errors' => ["message" => "Ação não autorizada!"]]);
        }
        $validator = Validator::make($request->all(),
            [
                'operation_date' => 'required|date_format:d/m/Y',
                'cost' => 'gt:0.00|nullable',
                'price' => 'required|gt:0.00',
                'stock_amount' => 'required|gt:0',
                'irrf' => 'gt:0.00|nullable',
                'stock_id' => 'required',
                'buy_r_sell' => 'required|in:B,S',
            ],
            [
                'sector_id.required' => 'O campo setor é obrigatório.',
                'operation_date.required' => 'O campo data da operação é obrigatório.',
                'operation_date.date_format' => 'O campo data não contem uma data válida.',
                'cost.required' => 'O campo custo é obrigatório.',
                'cost.gt' => 'O campo custo deve ser maior que 0.',
                'price.required' => 'O campo preço é obrigatório.',
                'price.gt' => 'O campo preço deve ser maior que 0.',
                'stock_type_id.required' => 'O campo tipo é obrigatório.',
                'company_name.required' => 'O campo Empresa  é obrigatório.',
                'code.required' => 'O campo código é obrigatório.',
                'buy_r_sell.required' => 'O campo Compra ou Venda é obrigatório.',
                'buy_r_sell.in' => 'O campo Compra ou Venda deve conter B ou S.',
                'stock_id.required' => 'O campo ativo é obrigatório.',
                'stock_amount.required' => 'O campo quantidade de ativos é obrigatório.',
                'stock_amount.gt' => 'O campo quantidade de ativos deve ser maior que 0.',
            ]
        );

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        $date = $request->get('operation_date');
        $date = str_replace('/', '-', $date);
        $date = Carbon::parse($date)->timezone('America/Sao_Paulo');

        $operation->update(array_merge($request->all(), ['operation_date' => $date]));

        return redirect()->route('operations.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Operation $operation
     * @return JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $authorize = (new User)->authorizeRoles($request->user('web'), ['admin']);

        if (!$authorize) {
            return redirect()->route('operations.index');
        }
        $operation = new Operation;
        $operation->deleteData($id);

        return response()->json(['success' => 'Operation deleted successfully']);
    }

    public function getBuyAndSellStatistics(Request $request)
    {
        $months = [
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro',
        ];

        $result = Operation::query()
            ->where('user_id', $request->user('web')->id)
            ->selectRaw('sum(price*stock_amount) as total, buy_r_sell, operation_date, extract(month FROM operation_date) as month, extract(year FROM operation_date) as year')
            ->where('operation_date', '>=', Carbon::now()->subMonths(6))
            ->groupByRaw('extract(month FROM operation_date), buy_r_sell, operation_date, extract(year FROM operation_date)')
            ->orderBy('operation_date', 'asc')
            ->orderBy('buy_r_sell', 'asc')
            ->get();

        $filteredArray = Arr::where($months, function ($value, $key) {
            return ($key >= Carbon::now()->subMonths(5)->month) && ($key <= Carbon::now()->month);
        });

        $resultData = [];
        $monthFilled = Carbon::now()->subMonths(5)->month;

        $resultData = collect($filteredArray)->map(function ($value, $key) use ($resultData, $result, $monthFilled) {
            $resultfiltered = $result->where('month', $key)->all();
            $year = (string)Carbon::now()->subMonths($monthFilled)->year;
            $data = [];
            if (!blank($resultfiltered)) {
                $arrayData = current($resultfiltered);
                if ($arrayData->buy_r_sell == "B") {
                    $data[] = [
                        "month" => $value,
                        "year" => $year,
                        "total" => $arrayData->total,
                        "type" => $arrayData->buy_r_sell
                    ];
                    $total = "0";
                    $arrayData = next($resultfiltered);

                    if ($arrayData) {
                        $total = $arrayData->total;
                    }

                    $data[] = [
                        "month" => $value,
                        "year" => $year,
                        "total" => $total,
                        "type" => "S"
                    ];
                } else {
                    $data[] = [
                        "month" => $value,
                        "year" => $year,
                        "total" => "0",
                        "type" => "B"
                    ];

                    $data[] = [
                        "month" => $value,
                        "year" => $year,
                        "total" => $arrayData->total,
                        "type" => "S"
                    ];
                }
            } else {
                $data[] = [
                    "month" => $value,
                    "year" => $year,
                    "total" => "0",
                    "type" => "B"
                ];
                $data[] = [
                    "month" => $value,
                    "year" => $year,
                    "total" => "0",
                    "type" => "S"
                ];
            }

            $monthFilled++;

            return $data;
        });
        return $resultData->toArray();
    }


    public function getIrrfStatistics(Request $request)
    {
        $months = [
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro',
        ];

        $result = Operation::query()
            ->where('user_id', $request->user('web')->id)
            ->selectRaw('sum(irrf*stock_amount) as total, operation_date, extract(month FROM operation_date) as month, extract(year FROM operation_date) as year')
            ->where('operation_date', '>=', Carbon::now()->subMonths(6))
            ->groupByRaw('extract(month FROM operation_date), operation_date, extract(year FROM operation_date)')
            ->orderBy('operation_date', 'asc')
            ->get();

        $filteredArray = Arr::where($months, function ($value, $key) {
            return ($key >= Carbon::now()->subMonths(5)->month) && ($key <= Carbon::now()->month);
        });

        $resultData = [];
        $monthFilled = Carbon::now()->subMonths(5)->month;

        $resultData = collect($filteredArray)->map(function ($value, $key) use ($resultData, $result, $monthFilled) {
            $resultfiltered = $result->where('month', $key)->first();

            if (!blank($resultfiltered)) {
                return [
                    "month" => $value,
                    "year" => $resultfiltered->year,
                    "total" => (blank($resultfiltered->total)) ? "0" : $resultfiltered->total,
                ];
            }
            return [
                "month" => $value,
                "year" => (string)Carbon::now()->subMonths($monthFilled)->year,
                "total" => "0"
            ];
            $monthFilled++;

        });
        return $resultData->toArray();
    }

}
