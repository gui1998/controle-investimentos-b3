<?php

namespace App\Http\Controllers;

use App\Models\IncomeType;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class IncomeTypeController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * @return Application|Factory|View
   */
  public function index()
  {
    return view('incomeTypes.index');
  }

  /**
   * @param Request $request
   * @param IncomeType $incomeType
   * @return JsonResponse
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
   * @param Request $request
   * @param IncomeType $incomeType
   * @return JsonResponse
   */
  public function store(Request $request, IncomeType $incomeType)
  {

    $authorize = (new User)->authorizeRoles($request->user('web'), ['admin']);

    if (!$authorize) {
      return response()->json(['errors' => ["message" => "Ação não autorizada!"]]);
    }

    $validator = Validator::make($request->all(),
      [
        'name' => 'required|unique:income_types,name',
      ],
      [
        'name.required' => 'O campo nome é obrigatório.',
        'name.unique' => 'O campo nome é único.',
      ]
    );

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()->all()]);
    }

    $incomeType->storeData($request->all());

    return response()->json(['success' => 'IncomeType added successfully']);
  }

  /**
   * @param Request $request
   * @param $id
   * @return Application|Factory|View|RedirectResponse
   */
  public function edit(Request $request, $id)
  {
    $authorize = (new User)->authorizeRoles($request->user('web'), ['admin']);

    if (!$authorize) {
      return redirect()->route('incomeTypes.index');
    }
    $incomeType = new IncomeType();

    $incomeType = $incomeType->findData($id);

    return view('incomeTypes.edit', [
      'income_type' => $incomeType,
    ]);
  }

  /**
   * @param Request $request
   * @param IncomeType $incomeType
   * @return JsonResponse|RedirectResponse
   */
  public function update(Request $request, IncomeType $incomeType)
  {
    $authorize = (new User)->authorizeRoles($request->user('web'), ['admin']);

    if (!$authorize) {
      return response()->json(['errors' => ["message" => "Ação não autorizada!"]]);
    }
    $validator = Validator::make($request->all(),
      [
        'name' => 'required|unique:income_types,name,'.$incomeType->id,
      ],
      [
        'name.required' => 'O campo nome é obrigatório.',
        'name.unique' => 'O campo nome é único.',
      ]
    );

    if ($validator->fails()) {
      return Redirect::back()->withErrors($validator);
    }
    $incomeType->update($request->all());

    return redirect()->route('incomeTypes.index');
  }

  /**
   * @param Request $request
   * @param $id
   * @return JsonResponse|RedirectResponse
   */
  public function destroy(Request $request, $id)
  {
    $authorize = (new User)->authorizeRoles($request->user('web'), ['admin']);

    if (!$authorize) {
      return redirect()->route('incomeTypes.index');
    }

    $incomeType = new IncomeType;

    $incomeExists = IncomeType::with('incomes')->where('id', $id)->first();

    if (!blank($incomeExists->income)) {
      return response()->json(['errors' => 'Tipo rendiemnto esta cadastrado em rendimento!']);
    };

    $incomeType->deleteData($id);

    return response()->json(['success' => 'IncomeType deleted successfully']);
  }

  public function getListIncomeTypes()
  {
    return IncomeType::all()->toArray();
  }
}
