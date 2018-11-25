<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $fillable = ['country', 'created_by'];

    public function orderProducts() {
        return $this->hasMany(OrderProduct::class);
    }

    public function products() {
        return $this->belongsToMany(Product::class,'order_products')->withPivot('order_id', 'product_id', 'count');
    }

    /**
     * @param $orderProducts Collection
     * @return float
     * */

    private function getPrice($orderProducts) {
        return $orderProducts->reduce(function($acc, OrderProduct $product){
            $acc += Product::find($product->product_id)->first()->price * $product->count;
            return $acc;
        });
    }
    /**
     * @param $orderProducts Collection
     * @return bool
     */
    private function canStore($orderProducts) {
        $total_price = $this->getPrice($orderProducts);

        if ($total_price < $this->minPriceToStore()) return false;

        return true;
    }

    private function minPriceToStore() {
        return config('MINIMUM_PRICE_TO_STORE');
    }

    /**
     * @param  $orderProducts Collection
     * @return Order
     * @throws \Exception
     */

    public function store($orderProducts) {

        if ($this->canStore($orderProducts)) {
            return DB::transaction(function () use ($orderProducts) {
                $this->save();
                $this->products()->attach($orderProducts->toArray());
                return $this;
            });
        }

        throw new \Exception('can`t store less than ' . $this->minPriceToStore() );
    }

    /**
     * @param $product_filter array
     * @return Collection
     */
    public function getAll(array $product_filter = []) {

        $order_ids = $this->getOrdersByProduct($product_filter);

        return self::where(function($query) use ($order_ids) {
            if (!is_null($order_ids))
                $query->whereIn('id', $order_ids);
        })->with(['orderProducts' => function($query){
            $query->with('product');
        }])->get();
    }

    private function getOrdersByProduct(array $product_filter = []) {
        $order_ids = null;

        if (!empty($product_filter)) {

            $sql = 'select distinct o.id from orders o left join order_products op on op.order_id=o.id left join products p on p.id=op.product_id where 1=1';

            if (!empty($product_filter)) {
                foreach ($product_filter as $key => $value)
                    $sql .= ' and p.`' . $key . '` = "' . $value . '" ';
            }

            $order_ids = array_map(function($row) {
                return $row->id;
            }, DB::select(DB::raw($sql)));
        }
        return $order_ids;
    }

}
