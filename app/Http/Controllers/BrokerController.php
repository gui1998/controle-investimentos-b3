<?php

namespace App\Http\Controllers;

use App\Models\Broker;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BrokerController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('brokers.index');
  }

  /**
   * Get the data for listing in yajra.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   * @throws \Exception
   */
  public function getBrokers(Request $request, Broker $broker)
  {
    $data = $broker->getData();
    try {
      return DataTables::of($data)
        ->addColumn('Actions', function ($data) {
          return '<button type="button" class="btn btn-success btn-sm" id="getEditBrokerData" data-id="' . $data->id . '">Editar</button>
    <button type="button" data-id="' . $data->id . '" class="btn btn-danger btn-sm" id="getDeleteId">Apagar</button>';
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
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request, Broker $broker)
  {
    $authorize = (new User)->authorizeRoles($request->user('web'), ['admin']);

    if (!$authorize) {
      return response()->json(['errors' => ["message" => "Ação não autorizada!"]]);
    }
    $validator = Validator::make($request->all(),
      [
        'name' => 'required|unique:brokers,name',
      ],
      [
        'name.required' => 'O campo nome é obrigatório.',
        'name.unique' => 'O campo nome é único.',
      ]
    );

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()->all()]);
    }

    $broker->storeData($request->all());

    return response()->json(['success' => 'Broker added successfully']);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param \App\Models\Broker $broker
   * @return \Illuminate\Http\Response
   */
  public function edit(Request $request, $id)
  {
    $authorize = (new User)->authorizeRoles($request->user('web'), ['admin']);

    if (!$authorize) {
      return redirect()->route('brokers.index');
    }
    $broker = new Broker;
    $data = $broker->findData($id);


    return view('brokers.edit', ['broker' => $data]);
  }

  /**
   * @param Request $request
   * @param $id
   * @return JsonResponse|RedirectResponse
   */
  public function update(Request $request, $id)
  {
    $authorize = (new User)->authorizeRoles($request->user('web'), ['admin']);

    if (!$authorize) {
      return response()->json(['errors' => ["message" => "Ação não autorizada!"]]);
    }

    $validator = Validator::make($request->all(),
      [
        'name' => 'required|unique:brokers,name,' . $id,
      ],
      [
        'name.required' => 'O campo nome é obrigatório.',
        'name.unique' => 'O campo nome é único.',
      ]
    );

    if ($validator->fails()) {
      return Redirect::back()->withErrors($validator);
    }

    $broker = new Broker;
    $broker->updateData($id, $request->all());

    if (!blank($broker)) {
      return redirect()->route('brokers.index')->with(['success' => 'Atualizado com sucesso!']);
    } else {
      return redirect()->route('brokers.index')->with(['error' => 'Falha na atualização!']);
    }
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
      return redirect()->route('brokers.index');
    }
    $broker = new Broker;

    $stockExists = Broker::with('operations')->where('id', $id)->first();

    if (!blank($stockExists->operations)) {
      return response()->json(['errors' => 'Setor esta cadastrado em Ativos!']);
    };

    $broker->deleteData($id);

    return response()->json(['success' => 'Broker deleted successfully']);
  }

  public function getListBrokers()
  {
    return Broker::all()->toArray();
  }

}
