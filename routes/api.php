<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::group(['middleware' => 'auth:api'], function () {
    
});

Route::post('login', 'User\UserController@login');
Route::post('register', 'User\UserController@register');
Route::get('register/verify/{confirmationCode}', [
    'as' => 'confirmation_path',
    'uses' => 'User\UserController@confirm'
]);

Route::resource('users','User\UserController',['except' => ['create','edit']]);
Route::resource('profiles','Profile\ProfileController',['except' => ['create','edit']]);
Route::resource('modelos','Modelo\ModeloController',['except' => ['create','edit']]);
Route::resource('categories','Category\CategoryController',['except' => ['create','edit']]);
Route::resource('rates','Rate\RateController',['except' => ['create','edit']]);
Route::resource('roles','Role\RoleController',['except' => ['create','edit']]);
Route::resource('services','Service\ServiceController',['except' => ['create','edit']]);
Route::resource('videos','Video\VideoController',['except' => ['create','edit']]);
Route::resource('countries','Country\CountryController',['except' => ['create','edit']]);
Route::resource('cities','City\CityController',['except' => ['create','edit']]);


Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');
