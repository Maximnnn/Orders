<?php

namespace Tests\Feature;

use App\Product;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testApiStoreProduct()
    {
        $token = $this->getToken();

        $type = str_random(10);
        $price = rand(1,10);
        $color = str_random(10);
        $size = rand(1,10);

        $response = $this->post('api/products', [
            'api_token' => $token,
            'type'      => $type,
            'price'     => $price,
            'color'     => $color,
            'size'      => $size
        ]);

        $response->assertStatus(201);

        $product = Product::where('type', $type)
            ->where('price', $price)
            ->where('color', $color)
            ->where('size', $size)
            ->first();

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($product->price, $price);
        $this->assertEquals($product->color, $color);
        $this->assertEquals($product->size, $size);
        $this->assertEquals($product->type, $type);
    }

    private function getToken() {
        $response_body = $this->post('api/login', [
            'email' => 'test@test.com',
            'password' => 'qwerty'
        ])->content();

        return json_decode($response_body)->api_token;
    }
}
