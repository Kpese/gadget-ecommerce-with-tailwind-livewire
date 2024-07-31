<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class MyordersPage extends Component
{
    use WithPagination;

    public function render()
    {
        $order = Order::where('user_id', Auth::user()->id)->latest()->paginate(5);
        return view('livewire.myorders-page', compact('order'))->title('Order - DStore');
    }
}
