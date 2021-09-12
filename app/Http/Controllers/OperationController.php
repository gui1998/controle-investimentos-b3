<?php

namespace App\Http\Controllers;

use App\Actions\CreateOrUpdateInvestmentAction;
use App\Actions\CreateResultsAction;
use App\Http\Rules\VerifyIfCanSellStock;
use App\Models\Broker;
use App\Models\Operation;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class OperationController extends Controller
{
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
        $validator = Validator::make($request->all(),
            [
                'operation_date' => 'required|date_format:d/m/Y',
                'cost' => 'gt:0.00',
                'price' => 'required|gt:0.00',
                'stock_amount' => 'required|gt:0',
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
                'stock_id.required' => 'O campo ação é obrigatório.',
                'stock_amount.required' => 'O campo quantidade de ações é obrigatório.',
                'stock_amount.gt' => 'O campo quantidade de ações deve ser maior que 0.',
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
    public function edit($id)
    {
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

        $validator = Validator::make($request->all(),
            [
                'operation_date' => 'required|date_format:d/m/Y',
                'cost' => 'gt:0.00',
                'price' => 'required|gt:0.00',
                'stock_amount' => 'required|gt:0',
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
                'stock_id.required' => 'O campo ação é obrigatório.',
                'stock_amount.required' => 'O campo quantidade de ações é obrigatório.',
                'stock_amount.gt' => 'O campo quantidade de ações deve ser maior que 0.',
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
    public function destroy($id)
    {
        $operation = new Operation;
        $operation->deleteData($id);

        return response()->json(['success' => 'Operation deleted successfully']);
    }
}
