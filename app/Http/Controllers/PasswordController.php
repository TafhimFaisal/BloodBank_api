<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\Email;
use App\Models\User;
use Auth;

class PasswordController extends Controller {

    public function resetPassword(Request $request) 
    {
        return  Auth::user()->resetPassword($request);
    }

    public function forgetPasswordSendEmail(Request $request)
    {
       $user = User::where('email',$request->all()['email'])->first();
       if($user!=null){

           if($user->active_status != 0){
                $data = $user->userData();
                $data['url'] = $request->all()['url']; 
                $data['base_url'] = $request->all()['base_url']; 
                $purpose = 2; 
                Mail::to($request->all()['email'])->send(new Email($data,$purpose));
                return response()->json([
                    'message' => 'Check Your email for reset link'],200);
           }else{
                return response()->json([
                    'message' => 'Email has not been activated'],403);
           }

       }else{

            return response()->json([
                'message' => 'Unknown Email'],401);
       }
    }


    public function changePassword(Request $request)
    {
        $data = $request->all();
        $user = User::findorFail($data['id']);
        if( $data['token'] === $user->activation_token){
            $user->changePassword($request->all()['password']);
            return response()->json([
                'massage'=>'Password has successfully changed',
            ],200);
        }else{
            return response()->json([
                'massage'=>'Oops somthing went wrong',
            ],403);
        }
        
    }

}