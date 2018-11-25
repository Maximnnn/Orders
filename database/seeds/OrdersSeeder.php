<?php

use App\Order;
use App\OrderProduct;
use App\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 50; $i++) {
            $order = new Order([
                'country' => $faker->countryCode,
                'created_by' => \App\User::all()->random()->id
            ]);

            $products = new Collection();

            for ($y = 0; $y < rand(1,5); $y++) {
                $products->add(new OrderProduct([
                    'product_id' => Product::all()->random()->id,
                    'count' => rand(1,10)
                ]));
            }
            try {
                $order->store($products);
            } catch (Exception $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }
    }
}
