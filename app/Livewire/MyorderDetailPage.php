<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Address;
use Livewire\Component;
use App\Models\OrderItem;

class MyorderDetailPage extends Component
{
    public $order_id;

    public function mount($order_id){
        $this->order_id = $order_id;
    }



    public function render()
    {
        $order_item = OrderItem::with('product')->where('order_id', $this->order_id)->get();
        $address = Address::where('order_id', $this->order_id)->first();
        $order = Order::where('id', $this->order_id)->first();
        return view('livewire.myorder-detail-page', compact('order_item', 'address', 'order'))->title('Order detail page - DStore');
    }
}
