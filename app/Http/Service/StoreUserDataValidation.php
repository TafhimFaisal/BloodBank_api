<?php

namespace App\Http\Service;
use DB;

class StoreUserDataValidation {

    protected $users;
    protected $validatedData = [];
    protected $userData;
    protected $validation_array = [];
    protected $massage = [];

    public function __construct($request)
    {
        $this->users = DB::table('users');
        $this->userData = $request;
    }

    public function is_unique($key)
    {
        
        $value = $this->users->where($key,$this->userData[$key])->first();
        if($value != null){
            if($value->$key == $this->userData[$key]){
                $this->massage[$key]['unique'] = $key.' must be unique';
                $this->validation_array['unique'][$key] = false;
            }
        }else{
            $this->validatedData['unique'][$key] = true;
        };

    }

    public function is_email($email,$key)
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $this->validation_array['Isemail'][$key] = true;
        }else{
            $this->massage[$key]['Isemail'] = 'This fild must be valid '.$key;
            $this->validation_array['Isemail'][$key] = false;
        };
    }
    public function is_phone($phone,$key)
    {
        if(is_numeric($phone) && strlen($phone) == 11){
            $this->validation_array['Isphone'][$key] = true;
        }else{
            $this->massage[$key]['Isphone'] = 'This fild must be valid '.$key;
            $this->validation_array['Isphone'][$key] = false;
        };
    }
    public function require_check($key)
    {
        if(array_key_exists($key,$this->userData) && !empty($this->userData[$key])){
            $this->validation_array['required'][$key] = true;
        }else{
            $this->massage[$key]['required'] = $key.' fild is required';
            $this->validation_array['required'][$key] = false;
        }; 
    }

    public function name($key)
    {
        $this->require_check($key);
    }

    public function email($key)
    {
        $this->require_check($key);
        if($this->validation_array['required'][$key]){
            $this->is_email($this->userData[$key],$key);
            $this->is_unique($key); 
        }
    }

    public function phone($key)
    {
        $this->require_check($key);
        if($this->validation_array['required'][$key]){
            $this->is_unique($key);
            $this->is_phone($this->userData[$key],$key);
        }
    }

    public function blood_group($key)
    {
        $this->require_check($key);
    }

    public function address($key)
    {
        $this->require_check($key);
        
    }

    public function is_valide()
    {
        
        $this->blood_group('blood_group');
        $this->name('name');
        $this->phone('phone');
        $this->address('address');
        $this->email('email');
        $is_valide;
        foreach($this->validation_array as $key => $val){
            $is_valide[$key] = !in_array(false,$val);
        }
        return !in_array(false,$is_valide);

    }

    public function errorMassages()
    {
        return $this->massage;
    }
}
