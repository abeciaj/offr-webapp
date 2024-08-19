<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\ShoppingListController;



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

Route::get('/', function () {
    return view('login');
})->middleware('auth');;

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('user');

// Route::get('/home/customer', [App\Http\Controllers\HomeController::class, 'customer'])->middleware('user','fireauth');

Route::get('/email/verify', [App\Http\Controllers\Auth\ResetController::class, 'verify_email'])->name('verify')->middleware('fireauth');

Route::post('login/{provider}/callback', 'Auth\LoginController@handleCallback');

Route::resource('/home/profile', App\Http\Controllers\Auth\ProfileController::class)->middleware('user','fireauth');

Route::resource('/password/reset', App\Http\Controllers\Auth\ResetController::class);

Route::resource('/img', App\Http\Controllers\ImageController::class);

// Route::get('/compare-prices/{productName}', [CompareController::class, 'comparePrices'])->name('comparePrices');


// Route::post('/shopping-list/add/{productName}', [ShoppingListController::class, 'add'])->name('shopping-list.add');

// Route::delete('/shopping-list/remove/{productName}', [ShoppingController::class, 'remove'])->name('shopping-list.remove');
// Route::get('/shopping-list/clear', [ShoppingListController::class, 'clear'])->name('shopping-list.clear');
// Route::post('/shopping-list/add/{productName}', [ShoppingListController::class, 'add'])->name('shopping-list.add');

//New
// // Route to show the shopping list
// Route::get('/shopping-list', [ShoppingListController::class, 'show'])->name('shoppingList.show');


// // Route to add an item to the shopping list
// Route::post('/shopping-list/add', [App\Http\Controllers\ShoppingListController::class, 'addToShoppingList'])->name('addToShoppingList');


// // Route to remove an item from the shopping list
// Route::post('/remove-from-shopping-list/{productName}', [ShoppingListController::class, 'remove'])->name('removeFromShoppingList');




Route::get('/shopping-list', [ShoppingListController::class, 'show'])->name('shoppingList.show');
Route::post('/add-to-shopping-list', [ShoppingListController::class, 'addToShoppingList'])->name('addToShoppingList');
Route::post('/remove-from-shopping-list/{productName}', [ShoppingListController::class, 'remove'])->name('removeFromShoppingList');


// \Log::info('Product data:', ['product' => $product]);
