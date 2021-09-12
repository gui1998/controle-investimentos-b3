<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

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

        $validator = Validator::make($request->all(),
            [
                'broker_id' => 'required',
                'stock_amount' => 'required|gt:0',
                'average_price' => 'required|gt:0',
                'user_id' => 'required',
                'stock_id' => ['required',
                    Rule::unique('investments', 'stock_id')
                        ->where('user_id', $request->get('user_id'))
                ]
            ],
            [
                'average_price.required' => 'O campo preço médio é obrigatório.',
                'average_price.gt' => 'O campo preço médio deve ser maior que 0.',
                'stock_id.required' => 'O campo ação é obrigatório.',
                'stock_id.unique' => 'O campo ação é único por usuário.',
                'broker.required' => 'O campo corretora é obrigatório.',
                'stock_amount.required' => 'O campo quantidade de ações é obrigatório.',
                'stock_amount.gt' => 'O campo quantidade de ações deve ser maior que 0.',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        return $investment->storeData($request->all());;
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, Investment $investment)
    {
        $validator = Validator::make($request->all(),
            [
                'broker_id' => 'required',
                'stock_amount' => 'required|gt:0',
                'average_price' => 'required|gt:0',
                'user_id' => 'required',
                'stock_id' => ['required',
                    Rule::unique('investments', 'stock_id')
                        ->where('user_id', $request->get('user_id'))
                ]
            ],
            [
                'average_price.required' => 'O campo preço médio é obrigatório.',
                'average_price.gt' => 'O campo preço médio deve ser maior que 0.',
                'stock_id.required' => 'O campo ação é obrigatório.',
                'stock_id.unique' => 'O campo ação é único por usuário.',
                'broker.required' => 'O campo corretora é obrigatório.',
                'stock_amount.required' => 'O campo quantidade de ações é obrigatório.',
                'stock_amount.gt' => 'O campo quantidade de ações deve ser maior que 0.',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

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

        return response(['success' => 'Investment deleted successfully'], 200);
    }
}
