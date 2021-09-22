<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class SectorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        return view('sectors.index');
    }

    /**
     * @param Request $request
     * @param Sector $sector
     * @return void
     */
    public function getSectors(Request $request, Sector $sector)
    {
        $data = $sector->getData();
        try {
            return DataTables::of($data)
                ->addColumn('Actions', function ($data) {
                    return '<button type="button" class="btn btn-success btn-sm" id="getEditSectorData" data-id="' . $data->id . '">Editar</button>
    <button type="button" data-id="' . $data->id . '" class="btn btn-danger btn-sm" id="getDeleteId">Deletar</button>';
                })
                ->rawColumns(['Actions'])
                ->make(true);
        } catch (\Exception $e) {
            print($e);
        }
    }

    /**
     * @param Request $request
     * @param Sector $sector
     * @return JsonResponse
     */
    public function store(Request $request, Sector $sector)
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

        $sector->storeData($request->all());

        return response()->json(['success' => 'Sector added successfully']);
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
            return redirect()->route('sectors.index');
        }

        $sector = new Sector;
        $data = $sector->findData($id);

        return view('sectors.edit', ['sector' => $data]);
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

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $sector = new Sector;
        $sector->updateData($id, $request->all());

        if ($sector) {
            //redirect dengan pesan sukses
            return redirect()->route('sectors.index')->with(['success' => 'Data Berhasil Diupdate!']);
        } else {
            //redirect dengan pesan error
            return redirect()->route('sectors.index')->with(['error' => 'Data Gagal Diupdate!']);
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
            return redirect()->route('sectors.index');
        }

        $sector = new Sector;

        $stockExists = Sector::with('stocks')->where('id', $id)->first();

        if (!blank($stockExists->stocks)) {
            return response()->json(['errors' => 'Setor esta cadastrado em Ativos!']);
        };

        $sector->deleteData($id);

        return response()->json(['success' => 'Sector deleted successfully']);
    }

    /**
     * @return array
     */
    public function getListSector()
    {
        return Sector::all()->toArray();
    }

}
