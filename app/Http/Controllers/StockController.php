<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use App\Models\Stock;
use App\Models\StockType;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class StockController extends Controller
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
    return view('stocks.index');
  }

  /**
   * Get the data for listing in yajra.
   *
   * @param \Illuminate\Http\Request $request
   * @return JsonResponse
   * @throws \Exception
   */
  public function getStocks(Request $request, Stock $stock)
  {
    $data = $stock->getData();
    try {
      return DataTables::of($data)
        ->addColumn('stock_type', function ($data) {
          return $data->stockTypes->name;
        })
        ->addColumn('sector', function ($data) {
          return $data->sectors->name;
        })
        ->addColumn('Actions', function ($data) {
          return '<button type="button" class="btn btn-success btn-sm" id="getEditStockData" data-id="' . $data->id . '">Editar</button>
    <button type="button" data-id="' . $data->id . '" class="btn btn-danger btn-sm" id="getDeleteId">Deletar</button>';
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
  public function store(Request $request, Stock $stock)
  {
    $authorize = (new User)->authorizeRoles($request->user('web'), ['admin']);

    if (!$authorize) {
      return response()->json(['errors' => ["message" => "Ação não autorizada!"]]);
    }

    $validator = Validator::make($request->all(),
      [
        'code' => 'required|min:4|max:6|unique:stocks,code',
        'stock_type_id' => 'required',
        'sector_id' => 'required',
        'company_name' => 'required',
      ],
      [
        'sector_id.required' => 'O campo setor é obrigatório.',
        'code.min' => 'O campo código deve conter no mínimo 4 caracteres.',
        'code.max' => 'O campo código deve conter no máximo 5 caracteres.',
        'stock_type_id.required' => 'O campo tipo é obrigatório.',
        'company_name.required' => 'O campo Empresa  é obrigatório.',
        'code.required' => 'O campo código é obrigatório.',
        'code.unique' => 'O campo código é único.',
      ]
    );

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()->all()]);
    }

    $stock->storeData(array_merge($request->all(), ["code" => strtoupper($request->get('code'))]));

    return response()->json(['success' => 'Stock added successfully']);
  }

  /**
   * @param $id
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
   */
  public function edit(Request $request, $id)
  {
    $authorize = (new User)->authorizeRoles($request->user('web'), ['admin']);

    if (!$authorize) {
      return redirect()->route('stocks.index');
    }
    $stock = new Stock;
    $stocks = $stock->findData($id);

    $stockTypes = StockType::all();
    $sectors = Sector::all();


    return view('stocks.edit', [
      'stock' => $stocks,
      'sectors' => $sectors,
      'stock_types' => $stockTypes,
    ]);
  }

  /**
   * @param Request $request
   * @param $id
   * @return JsonResponse
   */
  public function update(Request $request, Stock $stock)
  {
    $authorize = (new User)->authorizeRoles($request->user('web'), ['admin']);

    if (!$authorize) {
      return response()->json(['errors' => ["message" => "Ação não autorizada!"]]);
    }

    $validator = Validator::make($request->all(),
      [
        'code' => 'required|min:4|max:6|unique:stocks,code,'.$stock->id,
        'stock_type_id' => 'required',
        'sector_id' => 'required',
        'company_name' => 'required',
      ],
      [
        'sector_id.required' => 'O campo setor é obrigatório.',
        'code.min' => 'O campo código deve conter no mínimo 4 caracteres.',
        'code.max' => 'O campo código deve conter no máximo 5 caracteres.',
        'stock_type_id.required' => 'O campo tipo é obrigatório.',
        'company_name.required' => 'O campo Empresa  é obrigatório.',
        'code.required' => 'O campo código é obrigatório.',
        'code.unique' => 'O campo código é único.',
      ]
    );

    if ($validator->fails()) {
      return Redirect::back()->withErrors($validator);
    }

    $stock->update(array_merge($request->all(), ["code" => strtoupper($request->get('code'))]));

    return redirect()->route('stocks.index');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param \App\Models\Stock $stock
   * @return JsonResponse
   */
  public function destroy(Request $request, $id)
  {
    $authorize = (new User)->authorizeRoles($request->user('web'), ['admin']);

    if (!$authorize) {
      return redirect()->route('stocks.index');
    }
    $stock = new Stock;
    $operationExists = Stock::with('operations')->where('id', $id)->first();

    if (!blank($operationExists->operations)) {
      return response()->json(['errors' => 'Ativo esta cadastrado em Operações!']);
    };

    $incomeExists = Stock::with('incomes')->where('id', $id)->first();

    if (!blank($incomeExists->incomes)) {
      return response()->json(['errors' => 'Ativo esta cadastrado em Rendimentos!']);
    };

    $stock->deleteData($id);

    return response()->json(['success' => 'Stock deleted successfully']);
  }

  public function getListStocks()
  {
    return Stock::all()->toArray();
  }
}
