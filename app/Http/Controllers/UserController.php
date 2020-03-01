<?php

namespace App\Http\Controllers;

    use App\User;
    use Illuminate\Http\Request;
    use App\Http\Requests;
    use App\Http\Controllers\Controller;
    use Tymon\JWTAuth\Contracts\JWTSubject;
    use JWTAuth;
    use Hash;

    
    class UserController extends Controller
    {
        //use AuthenticatesUsers;

/* 
           public function __construct()
    {
        $this->middleware('api')->except('logout');
    }
    public function username()
    {
        return config('ldap_auth.usernames.eloquent');
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string|regex:/^\w+$/',
            'password' => 'required|string',
        ]);
    } */

        public function authenticate(Request $request)
        {
            $credentials = $request->only('username', 'password');
            //var_dump($credentials);

            // Get user by email
            $user = User::where('username', $credentials['username'])->first();
           //var_dump($user);
      
            // Validate Company
            if(! $user) {
              return response()->json([
                'error' => 'Invalid credentials_users'
              ], 401);
            }

            
            $token = JWTAuth::fromUser($user);

            
              
            // Get expiration time
            $objectToken = JWTAuth::setToken($token);
            //$expiration = JWTAuth::decode($objectToken->getToken())->get('exp');
      
            return response()->json([
              'access_token' => $token,
              'token_type' => 'bearer',
              //'payload'  =>$payload,
              //'expires_in' => JWTAuth::decode()->get('exp')
            ]);
        }
    }    