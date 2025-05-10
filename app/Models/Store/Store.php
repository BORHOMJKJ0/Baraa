<?php

namespace App\Models\Store;

use App\Models\Category\Category;
use App\Models\Product\Product;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->HasMany(Product::class);
    }

    public static function getAllStores($items, $column, $direction)
    {
        return self::orderBy($column, $direction)->paginate($items);
    }
}
