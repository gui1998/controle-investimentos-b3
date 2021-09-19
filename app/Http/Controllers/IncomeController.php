<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use App\Models\Sector;
use App\Models\Income;
use App\Models\IncomeType;
use App\Models\Stock;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return view('incomes.index');
    }

    /**
     * Get the data for listing in yajra.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function getIncomes(Request $request, Income $income)
    {
        $data = $income->getData()->where('user_id', $request->user('web')->id);

        try {
            return DataTables::of($data)
                ->editColumn('payment_date', function ($data) {
                    return date('d/m/Y', strtotime($data->payment_date));
                })
                ->addColumn('income_type', function ($data) {
                    return $data->incomeTypes->name;
                })
                ->addColumn('user', function ($data) {
                    return $data->users->name;
                })
                ->addColumn('net_value', function ($data) {
                    return round((($data->total - $data->discount) * $data->stock_amount),2);
                })
                ->addColumn('stock', function ($data) {
                    return $data->stocks->code;
                })
                ->addColumn('Actions', function ($data) {
                    return '<button type="button" class="btn btn-success btn-sm" id="getEditIncomeData" data-id="' . $data->id . '">Edit</button>
    <button type="button" data-id="' . $data->id . '" class="btn btn-danger btn-sm" id="getDeleteId">Delete</button>';
                })
                ->rawColumns(['Actions'])
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
    public function store(Request $request, Income $income)
    {

        $validator = Validator::make($request->all(),
            [
                'payment_date' => 'required|date_format:d/m/Y',
                'discount' => 'nullable|gt:0.00',
                'total' => 'required|gt:0.00',
                'stock_amount' => 'required|gt:0',
                'stock_id' => 'required',
                'income_type_id' => 'required',
            ],
            [
                'sector_id.required' => 'O campo setor é obrigatório.',
                'payment_date.required' => 'O campo data do pagamento é obrigatório.',
                'payment_date.date_format' => 'O campo data não contem uma data válida.',
                'discount.required' => 'O campo desconto é obrigatório.',
                'discount.gt' => 'O campo desconto deve ser maior que 0.',
                'stock_type_id.required' => 'O campo tipo é obrigatório.',
                'company_name.required' => 'O campo Empresa  é obrigatório.',
                'code.required' => 'O campo código é obrigatório.',
                'stock_id.required' => 'O campo ativo é obrigatório.',
                'stock_amount.required' => 'O campo quantidade de ativos é obrigatório.',
                'stock_amount.gt' => 'O campo quantidade de ativos deve ser maior que 0.',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $date = $request->get('payment_date');
        $date = str_replace('/', '-', $date);
        $date = Carbon::parse($date)->timezone('America/Sao_Paulo');

        $income->storeData(
            array_merge(
                $request->all(),
                [
                    'user_id' => $request->user('web')->id,
                    'payment_date' => $date
                ]
            )
        );

        return response()->json(['success' => 'Income added successfully']);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $income = new Income;
        $incomes = $income->findData($id);

        $incomeTypes = IncomeType::all();
        $stocks = Stock::all();

        return view('incomes.edit', [
            'income' => $incomes,
            'income_types' => $incomeTypes,
            'stocks' => $stocks,
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, Income $income)
    {
        $validator = Validator::make($request->all(),
            [
                'payment_date' => 'required|date_format:d/m/Y',
                'discount' => 'nullable|gt:0.00',
                'total' => 'required|gt:0.00',
                'stock_amount' => 'required|gt:0',
                'stock_id' => 'required',
                'income_type_id' => 'required',
            ],
            [
                'sector_id.required' => 'O campo setor é obrigatório.',
                'payment_date.required' => 'O campo data do pagamento é obrigatório.',
                'payment_date.date_format' => 'O campo data não contem uma data válida.',
                'discount.required' => 'O campo desconto é obrigatório.',
                'discount.gt' => 'O campo desconto deve ser maior que 0.',
                'stock_type_id.required' => 'O campo tipo é obrigatório.',
                'company_name.required' => 'O campo Empresa  é obrigatório.',
                'code.required' => 'O campo código é obrigatório.',
                'stock_id.required' => 'O campo ativo é obrigatório.',
                'stock_amount.required' => 'O campo quantidade de ativos é obrigatório.',
                'stock_amount.gt' => 'O campo quantidade de ativos deve ser maior que 0.',
            ]
        );

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $date = $request->get('payment_date');
        $date = str_replace('/', '-', $date);
        $date = Carbon::parse($date)->timezone('America/Sao_Paulo');

        $income->update(array_merge($request->all(), ['payment_date' => $date]));

        return redirect()->route('incomes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Income $income
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $income = new Income;
        $income->deleteData($id);

        return response()->json(['success' => 'Income deleted successfully']);
    }


    public function getIncomeStatistics(Request $request)
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

        $monthParam = Carbon::now()->subMonths(5);
        $result = Income::query()
            ->where('user_id', $request->user('web')->id)
            ->selectRaw('sum((total - coalesce(discount, 0)) * stock_amount) as total, payment_date, extract(month FROM payment_date) as month, extract(year FROM payment_date) as year')
            ->where('payment_date', '>=', $monthParam)
            ->groupByRaw('extract(month FROM payment_date), payment_date, extract(year FROM payment_date)')
            ->orderBy('payment_date', 'asc')
            ->get();

        $filteredArray = Arr::where($months, function ($value, $key) use($monthParam){
            return ($key >= $monthParam->month) && ($key <= Carbon::now()->month);
        });

        $resultData = [];
        $monthFilled = $monthParam->month;

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
