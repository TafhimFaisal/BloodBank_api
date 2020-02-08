<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use App\Mail\Email;
use App\Models\User;

class SendMailController extends Controller {

    protected $jwt;
    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function sendEmail(Request $request) {
        
        $user_email = $request->all()['email'];
        $url = $request->all()['url'];
        $user = User::where('email',$user_email)->first();
        $data = $user->userData();
        $data['base_url'] = $request->all()['base_url']; 
        $data['url'] = $url;
        
        Mail::to($user_email)->send(new Email($data,1));
        return response()->json([
            'massage'=>"Check you email for activation link"
        ],200);

    }

    public function varifyEmail(Request $request)
    {
       
        $data = $request->all();
        $id = $data['id'];
        $token = $data['token'];
        $url = $data['redirect_url'];
        
        $user = User::findorFail($id);
        if($user->activation_token == $token){
            $user->activateUser();
            $token = $this->jwt->attempt(['email'=>$user->email,'password'=>'secret']);
            $redirect_url = $url.'/views/user/profile.php?token='.$token;
            return redirect($redirect_url);
        }else{
            return response()->json([
                'massage'=>'Somthing went wrong'
            ],406);
        }
    }

}