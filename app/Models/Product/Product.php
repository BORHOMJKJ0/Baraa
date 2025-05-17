<?php

namespace App\Models\Product;

use App\Models\Cart\Cart_items;
use App\Models\Store\Store;
use App\Models\User\User;
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
    public function cart_items()
    {
        return $this->HasMany(Cart_items::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorite_products', 'product_id', 'user_id');
    }

    public static function getAllProducts($items, $column, $direction)
    {
        return self::orderBy($column, $direction)->paginate($items);
    }
}
