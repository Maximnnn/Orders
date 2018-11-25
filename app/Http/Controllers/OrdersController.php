<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrder;
use App\Order;
use App\OrderProduct;
use App\Services\IpLocation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Order $order)
    {
        Validator::make($request->all(), [
            'type' => 'string'
        ])->validate();

        $filter = array_filter([
            'type' => $request->get('type')
        ]);

        return response()->json([
            'orders' => $order->getAll($filter)
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreOrder $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(StoreOrder $request)
    {
        $request->validateResolved();

        $order = new Order([
            'country' => IpLocation::getInstanceFromIp($request->ip())->get(IpLocation::COUNTRY_CODE),
            'created_by' => $request->user()->id
        ]);

        $orderProducts = [];

        foreach ($request->validated()['products'] as $product) {
            $orderProducts[] = new OrderProduct([
                'product_id' => $product['id'],
                'count' => $product['count']
            ]);
        }

        $result = $order->store(new Collection($orderProducts));

        return response()->json([
            'success' => 'Order Created'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
