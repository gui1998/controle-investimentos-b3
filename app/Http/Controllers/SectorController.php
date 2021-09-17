<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class SectorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('sectors.index');
    }

    /**
     * Get the data for listing in yajra.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function getSectors(Request $request, Sector $sector)
    {
        $data = $sector->getData();
        try {
            return DataTables::of($data)
                ->addColumn('Actions', function ($data) {
                    return '<button type="button" class="btn btn-success btn-sm" id="getEditSectorData" data-id="' . $data->id . '">Edit</button>
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Sector $sector)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $sector->storeData($request->all());

        return response()->json(['success' => 'Sector added successfully']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Sector $sector
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sector = new Sector;
        $data = $sector->findData($id);


        return view('sectors.edit', ['sector' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Sector $sector
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $sector = new Sector;
        $sector->updateData($id, $request->all());

        if($sector){
            //redirect dengan pesan sukses
            return redirect()->route('sectors.index')->with(['success' => 'Data Berhasil Diupdate!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('sectors.index')->with(['error' => 'Data Gagal Diupdate!']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Sector $sector
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sector = new Sector;

        $stockExists = Sector::with('stocks')->where('id', $id)->first();

        if(!blank($stockExists->stocks)){
            return response()->json(['errors' => 'Setor esta cadastrado em Ativos!']);
        };

        $sector->deleteData($id);

        return response()->json(['success' => 'Sector deleted successfully']);
    }

    public function getListSector()
    {
        return Sector::all()->toArray();
    }

}
