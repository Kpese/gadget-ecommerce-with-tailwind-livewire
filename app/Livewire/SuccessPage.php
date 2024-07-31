<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class SuccessPage extends Component
{
    public function render()
    {
        $order = Order::with('address')->where('user_id', Auth::user()->id)->latest()->first();
        return view('livewire.success-page', compact('order'))->title('Success Page - DStore');
    }
}
