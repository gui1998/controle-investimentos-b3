<?php

namespace App\Actions;

use App\Models\Investment;
use App\Models\Operation;
use Spatie\QueueableAction\QueueableAction;

class CreateOrUpdateInvestmentAction
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

        $stock_amount = $operation->stock_amount;
        $average_price = $operation->price;

        if (!blank($investmentData)) {
            if ($operation->buy_r_sell == "B") {
                $average_price = round((
                        ($average_price * $stock_amount) +
                        ($investmentData->average_price * $investmentData->stock_amount)
                    ) / ($stock_amount + $investmentData->stock_amount), 2);

                $stock_amount += $investmentData->stock_amount;
            } else {
                $stock_amount = $investmentData->stock_amount - $stock_amount;
                if($stock_amount <= 0){
                    return $investment->deleteData($investmentData->id);
                }
                $average_price = $investmentData->average_price;
            }
        }

        $data = [
            'user_id' => $operation->user_id,
            'stock_id' => $operation->stock_id,
            'broker_id' => $operation->broker_id,
            'stock_amount' => $stock_amount,
            'average_price' => $average_price,
        ];

        if (!blank($investmentData)) {
            $investmentData->update($data);
            return $investmentData;
        }

        return $investment->storeData($data);
    }
}
