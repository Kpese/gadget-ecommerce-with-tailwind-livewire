<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Address;
use Livewire\Component;
use App\Mail\OrderPlaced;
use App\Helpers\CartManagement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CheckoutPage extends Component
{
    public $first_name;
    public $last_name;
    public $phone;
    public $street_address;
    public $city;
    public $state;
    public $zip_code;
    public $payment_method;

    public function mount(){
        $cart_item = CartManagement::getCartItemsFromCookie();
        if(count($cart_item) === 0){
            return redirect('/products');
        }
    }

    public function placeOrder()
    {
        $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'street_address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'payment_method' => 'required',
        ]);

        $cart_item = CartManagement::getCartItemsFromCookie();

        $order = new Order;
        $order->user_id = Auth::user()->id;
        $order->grand_total = CartManagement::calculateGrandTotal($cart_item);
        $order->payment_method = $this->payment_method;
        $order->payment_status = 'pending';
        $order->status = 'new';
        $order->currency = 'ngn';
        $order->shipping_amount = 0;
        $order->shipping_method = 'none';
        $order->notes = 'Order placed by ' . Auth::user()->name;

        $order->save(); // Save the order first to get the order id

        $address = new Address;
        $address->order_id = $order->id;
        $address->first_name = $this->first_name;
        $address->last_name = $this->last_name;
        $address->phone = $this->phone;
        $address->street_address = $this->street_address;
        $address->city = $this->city;
        $address->state = $this->state;
        $address->zip_code = $this->zip_code;

        $address->save();

        $order->orderItem()->createMany($cart_item); // Ensure the relationship is named correctly
        CartManagement::clearCartItems();

        if ($this->payment_method === 'paystack') {
            Mail::to(request()->user())->send(new OrderPlaced($order));
         return redirect()->route('paystack.redirect', ['order_id' => $order->id]);
        } else if ($this->payment_method === 'cod') {
            Mail::to(request()->user())->send(new OrderPlaced($order));
            return redirect()->route('payment.success');
        }
    }

    public function render()
    {
        $cart_item = CartManagement::getCartItemsFromCookie();
        $grand_total = CartManagement::calculateGrandTotal($cart_item);

        return view('livewire.checkout-page', compact('cart_item', 'grand_total'))->title('Checkout - DStore');
    }
}
