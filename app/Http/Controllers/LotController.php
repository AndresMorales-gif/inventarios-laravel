<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Lot;
use Illuminate\Http\Request;

class LotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $lot = new Lot();
        $lot->lot_number = $request->lotNumber;
        $lot->id_product = $request->product;
        $lot->expiration_date = $request->expiration;
        $lot->price = $request->price;
        $lot->save();
        $inventory = new Inventory();
        $inventory->id_lot = $lot->id;
        $inventory->quantity = $request->quantity;
        $inventory->state = 'Disponible';
        $inventory->save();
        return response()->json(['success' => true, 'data' => "Producto insertado"], 200);
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

    public function available(Request $request)
    {
        $lot = Lot::where([['id_product', "=", $request->product], ['lot_number', '=', $request->lotNumber]])->get();
        if (sizeof($lot)==0)
        {
            $data = false;
        }
        else
        {
            $data = true;    
        }
        return response()->json(['success' => true, 'data' => $data], 200);
    }
}
