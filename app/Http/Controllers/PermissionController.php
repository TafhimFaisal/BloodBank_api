<?php

namespace App\Http\Controllers;
use DB;
use App\Datatable\FatchData;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\Email;

class PermissionController extends Controller
{
    protected $datatable;
    protected $datetime;
    public function __construct()
    {
        $this->datatable = new FatchData;
        $this->datetime = Carbon::now()->subDays(90)->toDateTimeString();
    }
    

    public function is_email_exist($email)
    {
        $data = User::where('email',$email)->first();
        return $data != null ? true :false;
    }

    public function is_email_active($email)
    {
        $data = User::where('email',$email)->first()->active_status;
        return $data != 0 ? true :false;
    }

    public function can_user_login(Request $request)
    {
        $email = $request->all()['email'];
        
        if($this->is_email_exist($email) != true){
            return response()->json([
                    'message' => 'unknow email',
                    'permission' => false
            ],401);
        }else{

            if($this->is_email_active($email) != true){

                return response()->json([
                        'message' => 'you need your activate your account',
                        'permission' => false
                ],403);
            }else{
                return response()->json([
                        'message' => 'you can login',
                        'permission' => true
                ],200);
            }

        }
        
        
    }

    
}
