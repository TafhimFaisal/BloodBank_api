<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use App\Models\DonotionHistory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Hash;
use Carbon;
use DB;

class User extends Model implements AuthenticatableContract, AuthorizableContract,JWTSubject
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';
    protected $fillable = [
        'name', 
        'email', 
        'phone', 
        'blood_group', 
        'latest_donotion_date',
        'password',
        'address',
        'role'
    ];
    public $incrementing = false;

    public function history()
    {
        return $this->hasMany(DonotionHistory::class);
    }

    public function createHistory($date)
    {
        $this->history()->Create(['donotion_date'=>$date]);
    }

    public function updateDonotionDate($date)
    {
        $this->update(["latest_donotion_date"=>$date]);
        $this->createHistory($date);
    }

    public function resetPassword($request)
    {
        if(Hash::check($request->prev_password, $this->password)){
            $this->update(["password" => Hash::make($request->new_password)]);
            return response()->json([
                "massage" => "password has successfully changed"
            ],200);
        }else{
            return response()->json([
                "massage" => " Password not matched"
            ],203);
        }
    }

    public function token() { 
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
        $randomString = ''; 
        for ($i = 0; $i < 30; $i++) { 
            $index = rand(0, strlen($characters) - 1); 
            $randomString .= $characters[$index]; 
        } 
      
        return $randomString; 
    } 

    public function userData()
    {
        $this->activation_token = $this->token();
        $this->save();
        return $data = [
                'name' =>$this->name,
                'password' =>"secret",
                'token' =>$this->activation_token,
                'id' => $this->id,
            ];
    }

    public function activateUser()
    {
        $this->active_status = 1;
        $this->save();
    }

    public function changePassword($password)
    {
        $this->password = Hash::make($password);
        $this->activation_token = $this->token();
        $this->save();
    }

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    
}
