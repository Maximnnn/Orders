<?php

namespace App;

use http\Exception\InvalidArgumentException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Order extends Model
{
    protected $fillable = ['country'];

    public function products() {
        return $this->hasManyThrough(Product::class, OrderProduct::class);
    }

    /**
     * @param $products Collection
     * @return bool
     */
    private function canStore($products) {
        $total_price = $products->reduce(function($acc, Product $product){
            $acc += $product->price;
            return $acc;
        });

        if ($total_price < env('MINIMUM_PRICE_TO_STORE', 10)) return false;

        return true;
    }

    /**
     * @param $product_list array
     * @param $country string
     * @return Order
     */
    public function store(array $product_list, $country):Order {

        $products = Product::query()->whereIn('id', array_map(function($row){
            return $row['id'];
        }, $product_list))->get();

        if (!$this->canStore($products)) throw new InvalidArgumentException('Minimum required price is much then selected product`s price');

        return DB::transaction(function () use ($product_list, $country){
            $order = self::create([
                'county' => $country
            ]);

            foreach ($product_list as $product) {
                OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $product['id'],
                    'count' => $product['count']
                ]);
            }
            return $order;
        });
    }

    /**
     * @param $filter array
     * @return Collection
     */
    public function getAll(array $filter = []) {
        return self::with('products', function ($query) use ($filter) {
            $query->where($filter);
        })->get();
    }

}
