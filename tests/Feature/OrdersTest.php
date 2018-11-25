<?php

namespace Tests\Feature;

use App\Product;
use Tests\TestCase;

class OrdersTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testApiStoreOrder()
    {
        $token = $this->getToken();

        $response = $this->post('api/orders',[
            'api_token' => $token,
            'products' => [
                ['id' => Product::all()->random()->id, 'count' => rand(1,5)],
            ]
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            "success" => "Order Created"
        ]);
    }

    public function testApiGetOrder()
    {
        $token = $this->getToken();

        $response = $this->get('api/orders?' . http_build_query([
                'api_token' => $token
            ]));

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'orders' => []
        ]);
    }

    private function getToken() {
        $response_body = $this->post('api/login', [
            'email' => 'test@test.com',
            'password' => 'qwerty'
        ])->content();

        return json_decode($response_body)->api_token;
    }
}
