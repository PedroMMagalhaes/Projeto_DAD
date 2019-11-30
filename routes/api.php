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
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('users', 'UserControllerAPI@index');
Route::get('users/emailavailable', 'UserControllerAPI@emailAvailable');
Route::get('users/{id}', 'UserControllerAPI@show');
Route::post('users', 'UserControllerAPI@store');
//Route::put('users/me/{id}', 'UserControllerAPI@update');
Route::put('users/me', 'UserControllerAPI@save');
Route::delete('users/{id}', 'UserControllerAPI@destroy');

Route::post('users/me/photo', 'FileController@store');

//PASSPORT
Route::post('login', 'LoginControllerAPI@login')->name('login');
Route::middleware('auth:api')->post('logout','LoginControllerAPI@logout');
//Route::middleware('auth:api')->get('users/me', 'UserControllerAPI@myProfile'); //NOT VERY GOOD
Route::middleware('auth:api')->get('users/{id}', 'UserControllerAPI@myProfile');

//WALLETS
Route::get('wallets', 'WalletControllerAPI@index');
Route::get('numwallets', 'WalletControllerAPI@countWallets'); //num -> wallets

/*
Caso prefiram usar Resource Routes para o user, podem implementar antes as rotas:
NOTA: neste caso, o parâmetro a receber nos métodos do controlador é user e não id

Route::apiResource('users','UserControllerAPI');
Route::get('users/emailavailable', 'UserControllerAPI@emailAvailable');
*/
