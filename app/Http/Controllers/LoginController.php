<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use App\Models\User;
use Auth;

class LoginController extends Controller
{
    protected $jwt;
    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function login(Request $request)
    {
        
        $this->validate($request, [
            'email'    => 'required|email|max:255',
            'password' => 'required',
        ]);

        if(User::where('email',$request->email)->first()->active_status != 0){
            try {
                if(!$token = $this->jwt->attempt($request->only('email', 'password'))){
                    return response()->json(['user_not_found'], 404);
                }
            }catch(TokenExpiredException $e){
                return response()->json(['token_expired'], 500);
            }catch(TokenInvalidException $e){
                return response()->json(['token_invalid'], 500);
            }catch(JWTException $e){
                return response()->json(['token_absent' => $e->getMessage()], 500);
            }
            
            return response()->json(compact('token'));
        }else{
            return response()->json(['message'=>'you need to activate your account'],403);
        }
        
    }

    public function logOut()
    {
        Auth::logout();
        return response()->json(['massage' => 'loged out successfully'],200);
    }
}