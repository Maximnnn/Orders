<?php

use App\Product;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
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
            $product = new Product([
                'price' => $faker->randomFloat(2,1,30),
                'size' => $faker->randomFloat(1,10,50),
                'type' => $faker->text('10'),
                'color' => $faker->colorName
            ]);
            try {
                $product->store();
            } catch (Exception $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }
    }
}
