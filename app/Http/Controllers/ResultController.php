<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ResultController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        return response()->json(Result::all()->where('user_id',  $request->user('web')->id));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = new Result;
        $result->deleteData($id);

        return response(['success' => 'Result deleted successfully'], 200);
    }
}
