<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ProductPage extends Component
{
    use WithPagination;
    use LivewireAlert;

    #[Url(as: 'category')]
    public $selected_category = [];

    #[Url(as: 'brands')]
    public $selected_brand = [];

    #[Url(as: 'features')]
    public $feature = [];

    #[Url(as: 'sale')]
    public $sale = [];


    #[Url(as: 'sorting')]
    public $sort = 'latest';

    // #[Url(as: 's')]
    public $price_range = 3000000;


    public function addToCart($product_id){
        $total_count = CartManagement::addItemsCart($product_id);

        $this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);

        $this->alert('success', 'Cart added succesfully!', [
            'position' => 'top-end',
            'timer' => 3000,
            'toast' => true,
           ]);
    }

    public function render()
    {
         $query = Product::where('is_active', 1);

         if (!empty($this->selected_category)) {
             $query->whereIn('category_id', $this->selected_category);
         }

         if (!empty($this->selected_brand)) {
             $query->whereIn('brand_id', $this->selected_brand);
         }

         if($this->feature){
            $query->whereIn('is_featured', $this->feature);
         }

         if($this->sale){
            $query->whereIn('on_sale', $this->sale);
         }

         if($this->price_range){
            $query->whereBetween('price', [0, $this->price_range]);
         }

         if($this->sort === 'latest'){
            $query->latest();
         }

         if($this->sort === 'high_price'){
            $query->orderByDesc('price');
         }

         if($this->sort === 'low_price'){
            $query->orderBy('price', 'ASC');
         }

         $product = $query->paginate(6);

        $brand = Brand::where('is_active', 1)->get();
        $category = Category::where('is_active', 1)->get();

        return view('livewire.product-page', compact('product', 'brand', 'category'))->title('Product Page - DStore');

    }
}
