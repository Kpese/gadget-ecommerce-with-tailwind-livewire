<?php

use App\Livewire\CartPage;
use App\Livewire\HomePage;
use App\Livewire\Auth\Login;
use App\Livewire\CancelPage;
use App\Livewire\ProductPage;
use App\Livewire\SuccessPage;
use App\Livewire\CheckoutPage;
use App\Livewire\MyordersPage;
use App\Livewire\Auth\Register;
use App\Livewire\Categoriespage;
use App\Livewire\MyorderDetailPage;
use App\Livewire\ProductDetailPage;
use App\Livewire\Auth\ResetPassword;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Auth\ForgotPassword;
use Illuminate\Support\Facades\Route;
use Filament\Support\Exceptions\Cancel;
use App\Http\Controllers\PaymentController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', HomePage::class);
Route::get('/category', CategoriesPage::class);
Route::get('/products', ProductPage::class);
Route::get('/cart', CartPage::class);
Route::get('/products/{slug}', ProductDetailPage::class);


Route::middleware('guest')->group(function(){
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class);
    Route::get('/forgot', ForgotPassword::class)->name('password.forgot');
    Route::get('/reset/{token}', ResetPassword::class)->name('password.reset');
});

Route::middleware('auth')->group(function(){
    Route::get('/logout', function(){
        Auth::logout();
        return redirect('/');
    });
    Route::get('/checkout', CheckoutPage::class);
    Route::get('/myorders', MyordersPage::class);
    Route::get('/myorders/{order_id}', MyorderDetailPage::class)->name('myOrder');

    Route::get('/success', SuccessPage::class)->name('payment.success');
    Route::get('/cancel', CancelPage::class)->name('payment.cancel');



Route::get('/payment/paystack/redirect', [PaymentController::class, 'redirectToGateway'])->name('paystack.redirect');
Route::get('/payment/paystack/callback', [PaymentController::class, 'handleGatewayCallback'])->name('paystack.callback');

});
