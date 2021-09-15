<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class ResultController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        return response()->json(Result::all()->where('user_id', $request->user('web')->id));
    }


    public function getListResults(Request $request)
    {
        $months = [
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro',
        ];

        $result = Result::query()
            ->where('user_id', $request->user('web')->id)
            ->selectRaw('sum(value) as total, result_date, extract(month FROM result_date) as month, extract(year FROM result_date) as year')
            ->where('result_date', '>=', Carbon::now()->subMonths(6))
            ->groupByRaw('extract(month FROM result_date), result_date, extract(year FROM result_date)')
            ->orderBy('result_date', 'asc')
            ->get();

        $filteredArray = Arr::where($months, function ($value, $key) {
            return ($key >= Carbon::now()->subMonths(5)->month) && ($key <= Carbon::now()->month);
        });

        $resultData = [];
        $monthFilled = Carbon::now()->subMonths(5)->month;

        $resultData = collect($filteredArray)->map(function ($value, $key) use ($resultData, $result, $monthFilled) {
            $resultfiltered = $result->where('month', $key)->first();

            if (!blank($resultfiltered)) {
                return [
                    "month" => $value,
                    "year" => $resultfiltered->year,
                    "total" => $resultfiltered->total
                ];
            }
            return [
                "month" => $value,
                "year" => (string)Carbon::now()->subMonths($monthFilled)->year,
                "total" => "0"
            ];
            $monthFilled ++;

        });
        return $resultData->toArray();
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
