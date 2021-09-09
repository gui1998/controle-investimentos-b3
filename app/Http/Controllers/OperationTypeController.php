<?php

namespace App\Http\Controllers;

use App\Models\OperationType;
use App\Models\StockType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class OperationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('operationTypes.index');
    }

    /**
     * @param Request $request
     * @param OperationType $operationType
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOperationTypes(Request $request, OperationType $operationType)
    {
        $data = $operationType->getData();
        try {
            return DataTables::of($data)
                ->addColumn('Actions', function ($data) {
                    return '<button type="button" class="btn btn-success btn-sm" id="getEditOperationTypeData" data-id="' . $data->id . '">Editar</button>
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
    public function store(Request $request, OperationType $operationType)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $operationType->storeData($request->all());

        return response()->json(['success' => 'OperationType added successfully']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\OperationType $operationType
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $operationType = new OperationType();

        $operationType = $operationType->findData($id);

        return view('operationTypes.edit', [
            'operation_type' => $operationType,
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, OperationType $operationType)
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
        $operationType->update($request->all());

        return redirect()->route('operationTypes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\OperationType $operationType
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $operationType = new OperationType;
        $operationType->deleteData($id);

        return response()->json(['success' => 'OperationType deleted successfully']);
    }
}
