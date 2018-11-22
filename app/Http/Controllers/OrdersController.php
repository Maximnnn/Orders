<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrder;
use App\Order;
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
        $validator = Validator::make($request->all(), [
            'type' => 'string'
        ]);

        $validator->validate();

        $filter = [
            'type' => $request->get('type')
        ];

        return response()->json([
            'Orders' => $order->getAll($filter)
        ]);
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
     * @param Order $order
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrder $request, Order $order)
    {
        $request->validateResolved();

        $country = ''; //TODO

        return response()->json($order->store($request->validated(), $country));
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
