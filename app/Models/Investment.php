<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
  use HasFactory;

  protected $table = 'investments';
  protected $guarded = array();

  public function getData()
  {
    return static::with(['users', 'stocks', 'brokers'])->orderBy('created_at', 'desc')->get();
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

  public function brokers()
  {
    return $this->belongsTo(Broker::class, 'broker_id', 'id');
  }

  public function users()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

}
