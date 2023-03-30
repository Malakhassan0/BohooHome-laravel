<?php


use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ContactusController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//register
Route::post("register", [AuthController::class, "handleRegister"]);
//login
Route::post('login', [AuthController::class, 'handleLogin']);
//logot
Route::post('logout', [AuthController::class, 'logout']);
Route::get('user', [AuthController::class, 'user']);


//crud product
// Route::get('createProduct',[ProductController::class,'create']);//getform

// crud category
Route::get('allCategories', [CategoryController::class, 'index']);
Route::get('singleCategory/{id}', [CategoryController::class, 'singleCategory']);


//cart
Route::get('cart/{id}', [CartController::class, 'AllCartItems']);
Route::post('storeCart', [CartController::class, 'storeitems']);
Route::post('deleteCart/{cart_id}/{id}', [CartController::class, 'deleteCart']);
Route::post('updatequantity/{cart_id}/{scope}/{id}', [CartController::class, 'updatequantity']);

//Favorite
Route::post('addtofavorites', [FavoriteController::class, 'addtofavorites']);
Route::get('favoriteList/{id}', [FavoriteController::class, 'getFavoriteList']);
Route::post('deleteFavorite/{id}', [FavoriteController::class, 'deleteFavorite']);


//contactus
Route::post('createContactus', [ContactusController::class, 'store']);


//product
Route::get("allProducts", [ProductController::class, 'index']);
Route::get('product/{id}', [ProductController::class, 'singleProduct']); //show single product
Route::get('searchProduct', [ProductController::class, 'search']);
Route::get('products/{catId}',[ProductController::class,'catProd']);
Route::get('productEdit/{id}', [ProductController::class, 'editProduct']);

//order
Route::post('placeOrder',[OrderController::class,'createOrder']);
Route::get('showOrders',[OrderController::class,'index']);
Route::post('deleteOrder/{id}', [OrderController::class, 'delete']);
Route::post('updateOrder/{id}', [OrderController::class, 'update']);


Route::group(['middleware' => 'isAdmin'], function ($router) {
    Route::post('productUpdate/{id}', [ProductController::class, 'update']);
    Route::post('productDelete/{id}', [ProductController::class, 'delete']);

    Route::post('storeProduct', [ProductController::class, 'store']); //store in db
    
    Route::post('createCategory', [CategoryController::class, 'createCategory']);
    Route::get('categoryEdit/{id}', [CategoryController::class, 'editCategory']);
    Route::post('categoryUpdate/{id}', [CategoryController::class, 'updateCategory']);
    Route::post('categoryDelete/{id}', [CategoryController::class, 'deleteCategory']);
});