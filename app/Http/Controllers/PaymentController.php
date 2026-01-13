<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Models\Payment;
use Razorpay\Api\Api;
use App\Models\Cart;

use function Pest\Laravel\json;

class PaymentController extends Controller
{

    public function createorder(Request $request)
    {
        $cartTotal = Cart::sum('total');
        $amount = $cartTotal;
        $api = new Api (
            env('RAZORPAY_KEY'),
            env('RAZORPAY_SECRET')
        );

        $order =   $api->order->create([
            'receipt'=>'rcpt_'.time(),
            'amount'=>$amount,
            'currency'=>'INR'
        ]);
        Payment::create([
            'order_id'=>$order['id'],
            'amount'=>$amount,
            'status'=>'Pending'
        ]);

        return response()->json([
            'order_id'=>$order['id'],
            'amount'=>$amount,
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
        Cart::truncate();
        return response()->json([
            'status'=>'Payment Verified'
        ]);


    }

    public function webhooks(Request $request){
        $payload = $request->getContent();
        $razorpaySignature = $request->header('X-Razorpay-Signature');

        $secret = env('RAZORPAY_WEBHOOK_SECRET');

        $expectedSignature = hash_hmac(
        'sha256',
        $payload,
        $secret
    );

    if ($razorpaySignature !== $expectedSignature) {
        return response()->json(['error' => 'Invalid signature'], 400);
    }

    $data = json_decode($payload, true);

    if ($data['event'] === 'payment.captured') {

        $paymentId = $data['payload']['payment']['entity']['id'];
        $orderId   = $data['payload']['payment']['entity']['order_id'];

        Payment::where('order_id', $orderId)->update([
            'payment_id' => $paymentId,
            'status' => 'success'
        ]);
    }

    return response()->json(['status' => 'Webhook processed']);
    }
}
