<?php

namespace App\Livewire;

use Livewire\Component;
use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class CartPage extends Component
{
    use LivewireAlert;
    public $cart_item = [];
    public $grand_total;

    public function reduceQty($product_id){
        $this->cart_item = CartManagement::decrementQuantityToCartItems($product_id);
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_item);
    }

    public function increaseQty($product_id){
        $this->cart_item = CartManagement::increasementQuantityToCartItems($product_id);
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_item);
    }

    public function mount(){
        $this->cart_item = CartManagement::getCartItemsFromCookie();
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_item);
    }

    public function removeItem($product_id){
        $this->cart_item = CartManagement::removeCartItems($product_id);
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_item);


        $this->dispatch('update-cart-count', total_count: count($this->cart_item))->to(Navbar::class);

        $this->alert('success', 'Cart is removed succesfully!', [
            'position' => 'top-end',
            'timer' => 3000,
            'toast' => true,
           ]);
    }

    public function render()
    {
        return view('livewire.cart-page')->title('Cart Page - DStore');
    }
}
