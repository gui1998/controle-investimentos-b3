<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
  use HasFactory;

  protected $table = 'incomes';
  protected $guarded = array();
  protected $casts = [
    'payment_date' => 'date',
  ];

  public function getData()
  {
    return static::with(['users', 'stocks', 'incomeTypes'])->orderBy('created_at', 'desc')->get();
  }

  public function storeData($input)
  {
    return static::create($input);
  }

  public function findData($id)
  {
    return static::find($id);
  }

  public function updateData($id, $input)
  {
    return static::find($id)->update($input);
  }

  public function deleteData($id)
  {
    return static::find($id)->delete();
  }

  public function stocks()
  {
    return $this->belongsTo(Stock::class, 'stock_id', 'id');
  }

  public function incomeTypes()
  {
    return $this->belongsTo(IncomeType::class, 'income_type_id', 'id');
  }

  public function users()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

}
