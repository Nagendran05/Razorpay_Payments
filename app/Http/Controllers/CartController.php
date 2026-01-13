<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    public function add(Request $request)
{
    $product = Product::find($request->product_id);

    Cart::create([
        'product_id' => $product->id,
        'qty' => $request->qty,
        'price' => $product->price,
        'total' => $product->price * $request->qty
    ]);

    return response()->json(['message'=>'Added to cart']);
}

}
