<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['price', 'type', 'color', 'size'];

    public function orders() {
        return $this->belongsToMany(Order::class, 'order_products');
    }


    public function store() {

        if (Product
            ::where('type', $this->type)
            ->where('size', $this->size)
            ->where('color', $this->color)
            ->count() > 0) throw new \Exception('product already exists');

        return $this->save();
    }
}
