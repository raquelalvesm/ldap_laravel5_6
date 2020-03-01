<?php

use Illuminate\Http\Request;
use App\Http\Resources\UserCollection;
use App\User;
use App\Http\Resources\User as UserResource;
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

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
 */
Route::get('/ldap-search',function(Request $request){
    try{
        $credential = $request->only('username');
        $username = $credential['username'];
        $ldapuser = \Adldap::search()->where(env('LDAP_USER_ATTRIBUTE'), '=', $username . "")->first();
        return response()-> json($ldapuser);
    } catch(\Exception $e){
        return response()->json(["message" => $e->getMessage()], 200);
    }
});

Route::post('auth', 'UserController@authenticate');
Route::post('refresh', 'UserController@refresh');
Route::post('register', 'UserController@register');
Route::post('auth/logout', 'UserControllerApi\AuthController@logout');




Route::get('/users', function () {
    return new UserCollection(User::paginate());
    
});