<?php

namespace App\Http\Controllers;

use App\Models\IncomeType;
use App\Models\StockType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class IncomeTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('incomeTypes.index');
    }

    /**
     * @param Request $request
     * @param IncomeType $incomeType
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIncomeTypes(Request $request, IncomeType $incomeType)
    {
        $data = $incomeType->getData();
        try {
            return DataTables::of($data)
                ->addColumn('Actions', function ($data) {
                    return '<button type="button" class="btn btn-success btn-sm" id="getEditIncomeTypeData" data-id="' . $data->id . '">Editar</button>
    <button type="button" data-id="' . $data->id . '" class="btn btn-danger btn-sm" id="getDeleteId">Apagar</button>';
                })
                ->rawColumns(['Actions'])
                ->make(true);
        } catch (\Exception $e) {
            print($e);
        }

        return response()->json('Error');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, IncomeType $incomeType)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $incomeType->storeData($request->all());

        return response()->json(['success' => 'IncomeType added successfully']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\IncomeType $incomeType
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $incomeType = new IncomeType();

        $incomeType = $incomeType->findData($id);

        return view('incomeTypes.edit', [
            'income_type' => $incomeType,
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, IncomeType $incomeType)
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
            ],
            [
                'name.required' => 'O campo nome é obrigatório.',
            ]
        );

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        $incomeType->update($request->all());

        return redirect()->route('incomeTypes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\IncomeType $incomeType
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $incomeType = new IncomeType;
        $incomeType->deleteData($id);

        return response()->json(['success' => 'IncomeType deleted successfully']);
    }

    public function getListIncomeTypes()
    {
        return User::all();
        return IncomeType::all()->toArray();
    }
}
