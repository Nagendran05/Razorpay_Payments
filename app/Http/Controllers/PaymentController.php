<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Models\Payment;
use Razorpay\Api\Api;

use function Pest\Laravel\json;

class PaymentController extends Controller
{

    public function createorder(Request $request)
    {
        $api = new Api (
            env('RAZORPAY_KEY'),
            env('RAZORPAY_SECRET')
        );

        $order =   $api->order->create([
            'receipt'=>'rcpt_'.time(),
            'amount'=>'50000',
            'currency'=>'INR'
        ]);
    $amount = 50000;
        Payment::create([
            'order_id'=>$order['id'],
            'amount'=>$amount/100,
            'status'=>'Pending'
        ]);

        return response()->json([
            'order_id'=>$order['id'],
            'amount'=>$order['amount'],
            'key'=>env('RAZORPAY_KEY')
        ]);
    }

    public function verifypayments(Request $request){

        $api = new Api (
            env('RAZORPAY_KEY'),
            env('RAZORPAY_SECRET')
        );

        $attributes = [
            'razorpay_order_id'=>$request->order_id,
            'razorpay_payment_id'=>$request->payment_id,
            'razorpay_signature'=>$request->signature
        ];

        Payment::where('order_id', $request->order_id)->update([

            'payment_id'=>$request->payment_id,
            'signature'=>$request->signature,
            'status'=>'Success'
        ]);

        $api->utility->verifypaymentSignature($attributes);
        return response()->json([
            'status'=>'Payment Verified'
        ]);


    }
}
