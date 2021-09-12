<?php

namespace App\Actions;

use App\Models\Investment;
use App\Models\Result;
use App\Models\Operation;
use Spatie\QueueableAction\QueueableAction;

class CreateResultsAction
{
    use QueueableAction;

    /**
     * Create a new action instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the action.
     *
     * @return mixed
     */
    public function execute(Operation $operation)
    {

        $investment = new Investment();

        $investmentData = $investment::query()
            ->where('stock_id', $operation->stock_id)
            ->where('user_id', $operation->user_id)
            ->first();


        if (blank($investmentData) || $operation->buy_r_sell == "B") {
            return null;
        }

        $resultValue = $investmentData->stock_amount * $investmentData->average_price;
        $sellValue = $operation->stock_amount * $operation->price;

        $data = [
            'user_id' => $operation->user_id,
            'stock_id' => $operation->stock_id,
            'broker_id' => $operation->broker_id,
            'value' => ($sellValue - $resultValue),
            'result_date' => $operation->operation_date,
        ];

        return (new Result())->storeData($data);
    }
}
