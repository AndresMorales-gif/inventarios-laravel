<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Sale;
use App\Models\Sale_detail;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SaleController extends Controller
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
        if ($request->type == 'sale') {
            $sale = new Sale();
            $sale->id_user = Auth::user()->id;
            $sale->number_invoice = Auth::user()->id . '-' . Str::random(5);
            $sale->state = 'Cotizacion';
            $sale->price = 0;
            $sale->save();
            return response()->json(['success' => true, 'data' => $sale->id], 200);
        }
        else{            
            $saleDetail = new Sale_detail();
            $saleDetail->id_sale = $request->sale;
            $saleDetail->id_lot = $request->lot;
            $saleDetail->quantity = $request->quantity;
            $saleDetail->price = $request->price * $request->quantity;
            $saleDetail->state = 1;
            $saleDetail->save();
            return response()->json(['success' => true, 'data' => "Producto insertado"], 200);
        }
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
        $lot = Sale_detail::where([['id_sale', "=", $request->sale], ['id_lot', '=', $request->lot]])->get();
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
    public function availableQuantity(Request $request)
    {
        $lot = Inventory::where('id_lot', "=", $request->lot)->first();
        if ($lot->quantity < $request->quantity)
        {
            $data = true;
        }
        else
        {
            $data = false;    
        }
        return response()->json(['success' => true, 'data' => $data], 200);
    }
    public function saleDetail(Request $request)
    {
        $sales = Sale_detail::select('sale_details.id', 'sale_details.quantity', 'sale_details.price', 'products.name', 'products.description', 'lots.lot_number', 'lots.expiration_date')
        ->leftjoin( 'lots','sale_details.id_lot', "=", 'lots.id')
        ->leftjoin( 'products', 'lots.id_product', "=", 'products.id')
        ->where('id_sale', "=", $request->sale)->get();
        return $sales;
    }
    public function viewInventory(Request $request)
    {
        $inventories = Inventory::select('inventories.id', 'inventories.quantity as quantityI', 'products.name', 'lots.lot_number')
        ->leftjoin( 'lots', 'lots.id', "=", 'inventories.id_lot')        
        ->leftjoin( 'products', 'lots.id_product', "=", 'products.id')        
        ->get();

        $inventories2 = Inventory::select('inventories.id', 'sale_details.quantity as quantityS', 'inventories.quantity as quantityI', 'products.name', 'lots.lot_number')
        ->leftjoin( 'lots', 'lots.id', "=", 'inventories.id_lot')
        ->leftjoin( 'sale_details', 'lots.id', "=", 'sale_details.id_lot' )
        ->leftjoin( 'products', 'lots.id_product', "=", 'products.id')        
        ->where('id_sale', "=", $request->sale)->get();
        return $inventories->merge($inventories2);
    }

    public function saleEdit(Request $request) 
    {
        $saleDetail = Sale_detail::find($request->idSale);
        $saleDetail->id_sale = $request->sale;
        $saleDetail->id_lot = $request->lot;
        $saleDetail->quantity = $request->quantity;
        $saleDetail->price = $request->price * $request->quantity;
        $saleDetail->state = 1;
        $saleDetail->save();
        return response()->json(['success' => true, 'data' => "Producto insertado"], 200);
    }

    public function saleDelete(Request $request) 
    {
        $saleDetail = Sale_detail::find($request->idSale);
        $saleDetail->delete();
        return response()->json(['success' => true, 'data' => "Producto eliminado"], 200);
    }

    public function cancel(Request $request)
    {
        $sale = Sale::find($request->sale);
        $sale->state = 'Cancelada';
        $sale->save();
        return response()->json(['success' => true, 'data' => "Venta cancelada"], 200);
    }

    public function invoice(Request $request)
    {
        $sale = Sale::find($request->sale);
        $sale->state = 'Facturada';
        $saleDetails = Sale_detail::where('id_sale', '=', $sale->id)->get();
        $priceT = 0;
        foreach ($saleDetails as $saleDetail) {
           $priceT = $priceT + $saleDetail->price;
           $inventory = Inventory::where('id_lot', '=', $saleDetail->id_lot)->first();
           $inventory->quantity = $inventory->quantity - $saleDetail->quantity;
           $inventory->save(); 
        }
        $sale->price = $priceT;
        $sale->save();
        
        return response()->json(['success' => true, 'data' => "Venta cancelada"], 200);
    }

    public function pdfInvoice($id)
    {
        $sale = Sale::find($id);
        $numberInvoice = $sale->number_invoice;
        $saleDetails = Sale_detail::select('sale_details.id', 'sale_details.quantity', 'sale_details.price as priceT', 'products.name', 'products.description', 'lots.lot_number', 'lots.expiration_date', 'lots.price as priceU')
        ->leftjoin( 'lots','sale_details.id_lot', "=", 'lots.id')
        ->leftjoin( 'products', 'lots.id_product', "=", 'products.id')
        ->where('id_sale', '=', $id)->get();
        $pdf = \PDF::loadView('pdfs/invoice', compact('saleDetails', 'numberInvoice'))
                ->setPaper('a4', 'landscape');
        return $pdf->download('Factura_'.$numberInvoice.'.pdf');
    }

}
