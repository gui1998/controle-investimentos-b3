<?php

namespace App\Http\Controllers;

use App\Models\Broker;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

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
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Broker $broker
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $authorize = (new User)->authorizeRoles($request->user('web'), ['admin']);

        if (!$authorize) {
            return response()->json(['errors' => ["message" => "Ação não autorizada!"]]);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $broker = new Broker;
        $broker->updateData($id, $request->all());

        if ($broker) {
            //redirect dengan pesan sukses
            return redirect()->route('brokers.index')->with(['success' => 'Data Berhasil Diupdate!']);
        } else {
            //redirect dengan pesan error
            return redirect()->route('brokers.index')->with(['error' => 'Data Gagal Diupdate!']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Broker $broker
     * @return \Illuminate\Http\Response
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
