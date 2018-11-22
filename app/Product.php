<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['price', 'type', 'color', 'size'];

    public function orders() {
        return $this->hasManyThrough(Order::class, OrderProduct::class);
    }


    public function store(array $product):Product {
        return self::create($product);
    }
}
