<?php

namespace App\Models\Product;

use App\Models\Store\Store;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function store()
    {
        return $this->belongsTo(Store::class);

    }

    public static function getAllProducts($items, $column, $direction)
    {
        return self::orderBy($column, $direction)->paginate($items);
    }
}
