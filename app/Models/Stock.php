<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stocks';
    protected $guarded = array();

    protected $casts = [
        'id' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'active' => 'boolean',
        'code' => 'string',
        'company_name' => 'string',
        'sector_id' => 'integer',
        'stock_type_id' => 'integer',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'company_name',
        'sector_id',
        'stock_type_id',
    ];

    public function getData()
    {
        return static::with(['stockTypes', 'sectors'])->orderBy('code', 'asc')->get();
    }

    public function storeData($input)
    {
        return static::create($input);
    }

    public function findData($id)
    {
        return static::with(['stockTypes', 'sectors'])->find($id);
    }

    public function deleteData($id)
    {
        return static::find($id)->delete();
    }

    public function stockTypes()
    {
        return $this->belongsTo(StockType::class, 'stock_type_id', 'id');
    }

    public function sectors()
    {
        return $this->belongsTo(Sector::class, 'sector_id', 'id');
    }

    public function operations()
    {
        return $this->hasMany(Operation::class);
    }

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }
}
