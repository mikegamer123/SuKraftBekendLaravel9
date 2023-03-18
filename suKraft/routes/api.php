<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//API route for register new user
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
//API route for login user
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
//API route for email registration activation
Route::get('/emailReg/{emailToken}', [App\Http\Controllers\AuthController::class, 'setActiveUser']);
//API route for password remember
Route::post('/forgotPassword', [App\Http\Controllers\AuthController::class, 'forgotPassword']);

///////////PROTECTED ROUTES AUTH
Route::group(['middleware' => ['auth:sanctum']], function () {
// API route for logout user
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
});
///////////END OF PROTECTED ROUTES AUTH
///
////USERS
Route::prefix('users')->group(function () {
    //api route for returning all users or by ID
    Route::get('/get/{id?}', [App\Http\Controllers\UserController::class, 'getUsers']);

    //api route for updating user by ID
    Route::post('/put/{id}', [App\Http\Controllers\UserController::class, 'putUsers']);

    //api route for deleting users by ID
    Route::delete('/delete/{id}', [App\Http\Controllers\UserController::class, 'deleteUsers']);
});
////IMAGES
Route::prefix('media')->group(function () {
    //api route for creating images for various types
    Route::post('/{type}/{id}', [App\Http\Controllers\MediaController::class, 'mediaCreate']);
});
////CATEGORIES
Route::prefix('categories')->group(function () {
    //api route for returning all categories or by ID
    Route::get('/get/{id?}', [App\Http\Controllers\CategoryController::class, 'get']);
    //api route for updating categories by ID
    Route::post('/put/{id}', [App\Http\Controllers\CategoryController::class, 'put']);

    //api route for deleting categories by ID
    Route::delete('/delete/{id}', [App\Http\Controllers\CategoryController::class, 'delete']);

    //api route for creating categories
    Route::post('/create', [App\Http\Controllers\CategoryController::class, 'add']);
});

///FOLLOWERS
Route::prefix('followers')->group(function () {
    //api route for returning all followers or by ID
    Route::get('/get/{id?}', [App\Http\Controllers\FollowerController::class, 'get']);

    //api route for updating followers by ID
    Route::post('/put/{id}', [App\Http\Controllers\FollowerController::class, 'put']);

    //api route for deleting followers by ID
    Route::delete('/delete/{id}', [App\Http\Controllers\FollowerController::class, 'delete']);

    //api route for creating followers
    Route::post('/create', [App\Http\Controllers\FollowerController::class, 'add']);
});

////COMMENTS
Route::prefix('comments')->group(function () {
    //api route for returning all categories or by ID
    Route::get('/get/{id?}', [App\Http\Controllers\CommentController::class, 'get']);
    //api route for updating categories by ID
    Route::post('/put/{id}', [App\Http\Controllers\CommentController::class, 'put']);

    //api route for deleting categories by ID
    Route::delete('/delete/{id}', [App\Http\Controllers\CommentController::class, 'delete']);

    //api route for creating categories
    Route::post('/create', [App\Http\Controllers\CommentController::class, 'add']);
});
////POSTS
Route::prefix('posts')->group(function () {
    //api route for returning all categories or by ID
    Route::get('/get/{id?}', [App\Http\Controllers\PostController::class, 'get']);
    //api route for updating categories by ID
    Route::post('/put/{id}', [App\Http\Controllers\PostController::class, 'put']);

    //api route for deleting categories by ID
    Route::delete('/delete/{id}', [App\Http\Controllers\PostController::class, 'delete']);

    //api route for creating categories
    Route::post('/create', [App\Http\Controllers\PostController::class, 'add']);

});

///LIKES
Route::prefix('likes')->group(function () {
    //api route for returning all followers or by ID
    Route::get('/get/{id?}', [App\Http\Controllers\LikeController::class, 'get']);

    //api route for updating followers by ID
    Route::post('/put/{id}', [App\Http\Controllers\LikeController::class, 'put']);

    //api route for deleting followers by ID
    Route::delete('/delete/{id}', [App\Http\Controllers\LikeController::class, 'delete']);

    //api route for creating followers
    Route::post('/create', [App\Http\Controllers\LikeController::class, 'add']);
});
////PRODUCTS
Route::prefix('products')->group(function () {
    //api route for returning all products or by ID
    Route::get('/get/{id?}', [App\Http\Controllers\ProductController::class, 'get']);
    //api route for updating products by ID
    Route::post('/put/{id}', [App\Http\Controllers\ProductController::class, 'put']);

    //api route for deleting products by ID
    Route::delete('/delete/{id}', [App\Http\Controllers\ProductController::class, 'delete']);

    //api route for creating products
    Route::post('/create', [App\Http\Controllers\ProductController::class, 'add']);
    //api route for searching products
    Route::get('/search', [App\Http\Controllers\ProductController::class, 'search']);
});

////MESSAGES
Route::prefix('messages')->group(function () {
    //api route for returning all categories or by ID
    Route::get('/get/{id?}', [App\Http\Controllers\MessagesController::class, 'get']);
    //api route for updating categories by ID
    Route::post('/put/{id}', [App\Http\Controllers\MessagesController::class, 'put']);

    //api route for deleting categories by ID
    Route::delete('/delete/{id}', [App\Http\Controllers\MessagesController::class, 'delete']);

    //api route for creating categories
    Route::post('/create', [App\Http\Controllers\MessagesController::class, 'add']);
});
////REVIEWS
Route::prefix('reviews')->group(function () {
    //api route for returning all categories or by ID
    Route::get('/get/{id?}', [App\Http\Controllers\ReviewController::class, 'get']);
    //api route for updating categories by ID
    Route::post('/put/{id}', [App\Http\Controllers\ReviewController::class, 'put']);

    //api route for deleting categories by ID
    Route::delete('/delete/{id}', [App\Http\Controllers\ReviewController::class, 'delete']);

    //api route for creating categories
    Route::post('/create', [App\Http\Controllers\ReviewController::class, 'add']);
});
////SELLER
Route::prefix('sellers')->group(function () {
    //api route for returning all categories or by ID
    Route::get('/get/{id?}', [App\Http\Controllers\SellerController::class, 'get']);
    //api route for updating categories by ID
    Route::post('/put/{id}', [App\Http\Controllers\SellerController::class, 'put']);

    //api route for deleting categories by ID
    Route::delete('/delete/{id}', [App\Http\Controllers\SellerController::class, 'delete']);

    //api route for creating categories
    Route::post('/create', [App\Http\Controllers\SellerController::class, 'add']);
});

////ORDER
Route::prefix('orders')->group(function () {
    //api route for returning all categories or by ID
    Route::get('/get/{id?}', [App\Http\Controllers\OrderController::class, 'get']);
    //api route for updating categories by ID
    Route::post('/put/{id}', [App\Http\Controllers\OrderController::class, 'put']);

    //api route for deleting categories by ID
    Route::delete('/delete/{id}', [App\Http\Controllers\OrderController::class, 'delete']);

    //api route for creating categories
    Route::post('/create', [App\Http\Controllers\OrderController::class, 'add']);
});

///POSTPRODUCTS

Route::prefix('postproducts')->group(function () {
    //api route for returning all categories or by ID
    Route::get('/get/{id?}', [App\Http\Controllers\PostProductController::class, 'get']);
    //api route for updating categories by ID
    Route::post('/put/{id}', [App\Http\Controllers\PostProductController::class, 'put']);

    //api route for deleting categories by ID
    Route::delete('/delete/{id}', [App\Http\Controllers\PostProductController::class, 'delete']);

    //api route for creating categories
    Route::post('/create', [App\Http\Controllers\PostProductController::class, 'add']);
});

