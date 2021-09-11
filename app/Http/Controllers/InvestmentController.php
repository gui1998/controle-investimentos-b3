<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(Investment::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function store(Request $request, Investment $investment)
    {

//        $validator = Validator::make($request->all(),
//            [
//                'investment_date' => 'required|date_format:d/m/Y',
//                'cost' => 'required|gt:0.00',
//                'price' => 'required|gt:0.00',
//                'stock_amount' => 'required|gt:0',
//                'stock_id' => 'required',
//            ],
//            [
//                'sector_id.required' => 'O campo setor é obrigatório.',
//                'investment_date.required' => 'O campo data do pagamento é obrigatório.',
//                'investment_date.date_format' => 'O campo data não contem uma data válida.',
//                'cost.required' => 'O campo custo é obrigatório.',
//                'cost.gt' => 'O campo custo deve ser maior que 0.',
//                'price.required' => 'O campo preço é obrigatório.',
//                'price.gt' => 'O campo preço deve ser maior que 0.',
//                'stock_type_id.required' => 'O campo tipo é obrigatório.',
//                'company_name.required' => 'O campo Empresa  é obrigatório.',
//                'code.required' => 'O campo código é obrigatório.',
//                'stock_id.required' => 'O campo ação é obrigatório.',
//                'stock_amount.required' => 'O campo quantidade de ações é obrigatório.',
//                'stock_amount.gt' => 'O campo quantidade de ações deve ser maior que 0.',
//            ]
//        );

//        if ($validator->fails()) {
//            return response()->json(['errors' => $validator->errors()->all()]);
//        }

        return  $investment->storeData($request->all());;
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, Investment $investment)
    {
//        $validator = Validator::make($request->all(),
//            [
//                'investment_date' => 'required|date_format:d/m/Y',
//                'cost' => 'required|gt:0.00',
//                'price' => 'required|gt:0.00',
//                'stock_amount' => 'required|gt:0',
//                'stock_id' => 'required',
//            ],
//            [
//                'sector_id.required' => 'O campo setor é obrigatório.',
//                'investment_date.required' => 'O campo data do pagamento é obrigatório.',
//                'investment_date.date_format' => 'O campo data não contem uma data válida.',
//                'cost.required' => 'O campo custo é obrigatório.',
//                'cost.gt' => 'O campo custo deve ser maior que 0.',
//                'price.required' => 'O campo preço é obrigatório.',
//                'price.gt' => 'O campo preço deve ser maior que 0.',
//                'stock_id.required' => 'O campo ação é obrigatório.',
//                'stock_amount.required' => 'O campo quantidade de ações é obrigatório.',
//                'stock_amount.gt' => 'O campo quantidade de ações deve ser maior que 0.',
//            ]
//        );
//
//
//        if ($validator->fails()) {
//            return Redirect::back()->withErrors($validator);
//        }
        $investment->update($request->all());

        return $investment;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Investment $investment
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $investment = new Investment;
        $investment->deleteData($id);

        return response(['success' => 'Investment deleted successfully'],200);
    }
}
