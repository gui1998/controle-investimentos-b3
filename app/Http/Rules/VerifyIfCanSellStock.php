<?php

namespace App\Http\Rules;

use App\Models\Investment;
use Illuminate\Contracts\Validation\ImplicitRule;

class VerifyIfCanSellStock implements ImplicitRule
{
  private string $message;

  public function __construct()
  {
  }

  public function passes($attribute, $value)
  {
    if ($value['buy_r_sell'] == 'B') {
      return true;
    }

    $investmentData = Investment::query()
      ->where('stock_id', $value['stock_id'])
      ->where('user_id', '=', $value['user_id'])
      ->first();

    $this->message = "Não é possível vender, você não possui ativos para venda!";

    if (blank($investmentData)) {
      return false;
    }

    $stock_amount = $investmentData->stock_amount;

    $this->message = "Não é possível vender, você possui somente $stock_amount ativos!";

    return (($stock_amount - $value['stock_amount']) >= 0);
  }

  public function message()
  {
    return $this->message;
  }
}
