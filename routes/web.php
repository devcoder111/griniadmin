<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShopifyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/test-order', [ShopifyController::class, 'test_order'])->name('test-order');
// Webhooks 
Route::any('webhook-order-update', [ShopifyController::class, 'webhook_handle_order_notification']);

Route::get('/set-webhook', [ShopifyController::class, 'set_webhook']);

Route::get('/show-webhook', [ShopifyController::class, 'show_list_webhook']);

Route::get('/update-webhook', [ShopifyController::class, 'update_webhook']);

Route::get('/delete-webhook', [ShopifyController::class, 'delete_webhook']);




// Get All Products
Route::get('/fetch-insert-new-product', [ShopifyController::class, 'fetch_insert_new_product'])->name('add-product');

// Get All Orders
Route::get('/fetch-insert-new-order', [ShopifyController::class, 'fetch_insert_new_order'])->name('add-order');

// Update All Products
Route::get('/fetch-update-product', [ShopifyController::class, 'fetch_update_product'])->name('fetch-update-product');

// Update All Products
Route::get('/fetch-update-order', [ShopifyController::class, 'fetch_update_order'])->name('fetch-update-order');

// get Order By databse id
Route::post('/get-order-record-by-id', [OrderController::class, 'get_order_record_by_id'])->name('get-order-record-by-id');

// Row Highlight Popup
Route::post('/order-highlight-popup', [OrderController::class, 'order_highlight_popup'])->name('order-highlight-popup');

// row highlight action
Route::post('/order-highlight-action', [OrderController::class, 'order_highlight_action'])->name('order-highlight-action');

// Update order into database
Route::post('/edit-order-action', [OrderController::class, 'edit_order_action'])->name('edit-order-action');


// Get New And Updated Product 
Route::get('/fetch-new-update-product', [ShopifyController::class, 'fetch_new_update_product'])->name('fetch-new-update-product');

// Get New And Updated Order 
Route::get('/fetch-new-update-order', [ShopifyController::class, 'fetch_new_update_order'])->name('fetch-new-update-order');


Route::get('/crud-list', 'App\Http\Controllers\OrderController@list')->middleware(['auth'])->name('list');
Route::get('/plan', 'App\Http\Controllers\OrderController@plan')->middleware(['auth'])->name('list');

Route::get('/get-crud-list', 'App\Http\Controllers\OrderController@getList');
Route::get('/get-plan-list', 'App\Http\Controllers\OrderController@getPlanList');








//Route::get('/product', 'Dashboard@index');
Route::get('/product', [DashboardController::class, 'index']);
                //->middleware('guest');

//Get all orders                
Route::get('/order', [OrderController::class, 'index']);
Route::get('/update-order-status', [OrderController::class, 'update_order_status']);
Route::get('/update-order-note', [OrderController::class, 'update_order_note']);






// Route::post('/delete-crud', 'App\Http\Controllers\CrudController@delete');
// Route::post('/add-crud-data', 'App\Http\Controllers\CrudController@add');
// Route::post('/get-crud-record', 'App\Http\Controllers\CrudController@getRecoredByID');
// Route::post('/update-crud-data', 'App\Http\Controllers\CrudController@update');


Route::get('/shopify', [ShopifyController::class, 'index']);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';