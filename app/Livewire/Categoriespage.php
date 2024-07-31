<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;

class Categoriespage extends Component
{
    public function render()
    {
        $category = Category::where('is_active', 1)->get();

        return view('livewire.categories-page', compact('category'))->title('Categories page - DStore');
    }
}
