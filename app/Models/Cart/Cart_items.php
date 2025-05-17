<?php

namespace App\Models\Cart;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

class Cart_items extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function getAllCartItems($items, $column, $direction)
    {
        return self::orderBy($column, $direction)->paginate($items);
    }
}
