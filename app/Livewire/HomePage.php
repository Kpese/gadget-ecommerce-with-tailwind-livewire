<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use Livewire\Component;


class HomePage extends Component
{
    public function render()
    {
        $brand = Brand::where('is_active', 1)->get();
        $category = Category::where('is_active', 1)->get();

        return view('livewire.home-page', compact('brand', 'category'))->title('Home Page - DStore');
    }
}
