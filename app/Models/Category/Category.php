<?php

namespace App\Models\Category;

use App\Models\Store\Store;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function stores()
    {
        return $this->hasMany(Store::class);
    }

    public static function getAllCategories($items, $column, $direction)
    {
        return self::orderBy($column, $direction)->paginate($items);
    }
}
