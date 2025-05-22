<?php

namespace App\Models\Cart;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart_items extends Model
{
    use HasFactory,SoftDeletes;

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
        return self::whereHas('cart', function ($query) {
            $query->where('user_id', auth()->id());
        })
            ->join('products', 'cart_items.product_id', '=', 'products.id')
            ->select('cart_items.*')
            ->orderBy($column === 'product_name' ? 'products.name' : 'cart_items.'.$column, $direction)
            ->paginate($items);
    }

    public static function getAllDeletedCartItems($items, $column, $direction)
    {
        return self::onlyTrashed()
            ->whereHas('cart', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->join('products', 'cart_items.product_id', '=', 'products.id')
            ->select('cart_items.*')
            ->with('product')
            ->orderBy($column === 'product_name' ? 'products.name' : 'cart_items.'.$column, $direction)
            ->paginate($items);
    }
}
