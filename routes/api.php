<?php

use App\Http\Controllers\FollowersController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OfferMasterController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\UserController;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserRecource;
use App\Models\Followers;
use App\Models\Like;
use App\Models\Post;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



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
use App\Models\User;
use Illuminate\Support\Facades\Auth;



Route::middleware(['auth:sanctum'])->group(function () {
  Route::get('/users', function (Request $request) {

    $itemsCount = $request->query('count');
    $paginate = User::paginate($itemsCount);
    $collection = new UserCollection($paginate);

    return $collection;
  });



  Route::delete('/users/{userId}', function ($userId) {
    return UserController::deleteUser($userId);
  });

  Route::post('/users/add', function (Request $request) {
    return UserController::addUser($request);
  });
});


//Users
Route::get('/user/auth', function () {
  return UserController::getAuthUser();
});



Route::get('garavatar/{userId}', function ($userId) {
  $user = User::find($userId)->first();
  return $user->getAvatarUrl();
});


///////////////OFFERS
Route::post('/offer', function (Request $request) {
  return OfferController::newOffer($request);
});

Route::get('/offers/{userId}', function ($userId) {
  return OfferController::getOffer($userId);
});

Route::delete('/offers/{offerId}', function ($offerId) {
  return OfferController::deleteOffer($offerId);
});

Route::post('/follow', function (Request $request) {


  return  OfferMasterController::follow($request);
});
Route::delete('/follow/{offerId}', function ($offerId) {


  return  OfferMasterController::unfollow($offerId);
});

Route::get('/link/{offerId}', function ($offerId) {


  return  LinkController::create($offerId);
});



///////////////FINANCE
Route::get('/finance', function () {


  return  UserController::getFinance();
});






//

Route::post('/sanctum/token', TokenController::class);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

Route::post('/tokens/create', function (Request $request) {
  $token = $request->user()->createToken($request->token_name);

  return ['token' => $token->plainTextToken];
});
