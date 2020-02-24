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

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@authenticate');
Route::get('open', 'DataController@open');

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('user', 'UserController@getAuthenticatedUser');
    Route::get('closed', 'DataController@closed');
});


Route::get('/bd', function(Request $request){
   
    $comments =  DB::table('users')
    ->where($request->get('username'));
    return response()->json(['response' => 'success', 'comments' => $comments]);

});