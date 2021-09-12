<?php

namespace App\Http\Rules;

use App\Models\Investment;
use Illuminate\Contracts\Validation\ImplicitRule;

class VerifyIfCanSellStock implements ImplicitRule
{
    protected string $stock_amount;

    public function __construct()
    {
    }

    public function passes($attribute, $value)
    {
        if($value['buy_r_sell'] == 'B'){
            return true;
        }

        $investmentData = Investment::query()
            ->where('stock_id', $value['stock_id'])
            ->where('user_id', '=', $value['user_id'])
            ->first();

        if(blank($investmentData)){
            return false;
        }

        $stock_amount = $investmentData->stock_amount;
        $this->stock_amount = $stock_amount;
        return (($stock_amount - $value['stock_amount']) >=0);
    }

    public function message()
    {
        return "Não é possível vender, você tem somente $this->stock_amount ações! ";
    }
}
