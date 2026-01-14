<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    public function add(Request $request)
{


    $request->validate([
        'product_id' => 'required|integer',
        'qty' => 'required|integer|min:1'
    ]);

    
    $product = Product::find(1);

    if (!$product) {
        return response()->json([
            'message' => 'Product not found'
        ], 404);
    }

    Cart::create([
        'product_id' => 1, 
        'qty' => 1,
        'price' => $product->price,
        'total' => $product->price * $request->qty
    ]);

    return response()->json([
        'message' => 'Added to cart successfully'
    ]);
}

    public function list(){

        $cart = Cart::all();

        $grandTotal = $cart->sum('total');


        return response()->json([
            'items'=>$cart,
            'Total'=>$grandTotal
        ]);

    }

}
