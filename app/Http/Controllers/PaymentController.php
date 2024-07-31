<?php

namespace App\Http\Controllers;

use App\Models\Order;
// use Unicodeveloper\Paystack\Paystack;
use Illuminate\Http\Request;
use Unicodeveloper\Paystack\Facades\Paystack;

class PaymentController extends Controller
{
    public function redirectToGateway(Request $request)
    {
        // Retrieve the order using the order_id from the request
        $order = Order::findOrFail($request->order_id);

        // Generate a unique reference for the transaction
        // $reference = Paystack::genTranxRef();

        // Set the data to be sent to Paystack
        $data = [
            'amount' => $order->grand_total * 100,
            'email' => auth()->user()->email,
            'orderID' => $order->id,
            'callback_url' => route('paystack.callback'),
            'metadata' => json_encode([
                'order_id' => $order->id,
            ]),
        ];

        try {
                return Paystack::getAuthorizationUrl($data)->redirectNow();
                } catch (\Exception $e) {
                    return back()->with('error', 'The Paystack token has expired. Please refresh the page and try again.');
                }
            }

    public function handleGatewayCallback()
    {
        $paymentDetails = Paystack::getPaymentData();

       $amunt =  $paymentDetails->data->amount;

        // Now you have the payment details,
        // you can store the information in the database and perform further actions

        return view('payment.success', compact('paymentDetails'));
    }
}
