<?php
namespace App\Http\Service;
use Carbon\Carbon;
use DB;

class SearchService {

    protected $user;
    protected $datetime;

    public function __construct()
    {
        $this->user = DB::table('users');
        $this->datetime = Carbon::now()->subDays(90)->toDateTimeString();
    }

    public function search($query,$urlBloodGroup,$datetime)
    {
        
        $bloodGroup = ['a','b','o','ab','a+','b+','o+','ab+','a-','b-','o-','ab-','+a','+b','+o','+ab','-a','-b','-o','-ab'];
        $address = ['branch','branch+office','head','head+office'];

        $query = strtolower(str_replace(" ","+",$query));
        
        //phone number check
        if(!is_numeric($query)){
            //address check
            if(in_array($query,$address)){
                $query = str_replace("+"," ",$query);
                return $this->searchAddress($query,$urlBloodGroup);
            }else{
                //bloodgroup check
                if(in_array($query,$bloodGroup)){
                    return $this->searchBloodGroup($query);
                }else{
                    //email check
                    if(strpos($query,"@")>0){
                        return $this->searchEmail($query);
                    }else{
                        return $this->searchName($query);
                    }
                }
            }

        }else{
            return $this->searchPhoneNumber($query);
        }

    }


    public function in_string($s,$q)
    {
        return gettype(strpos($s,$q)) == 'boolean' ? false : true ;
    }

    public function convertQuery($bloodGroup)
    {
        $sign = $this->findSigh($bloodGroup);
        if($sign != ''){
            if(strpos($bloodGroup,$sign)==0){
                return $bloodGroup = str_replace($sign,'',$bloodGroup).$sign;
            }else{
                return $bloodGroup;
            }
        }else{
            return $bloodGroup .= '+';
        }
    }

    public function findSigh($bloodGroup)
    {
        if($this->in_string($bloodGroup,'-')){
            return '-';
        }elseif($this->in_string($bloodGroup,'+')){
            return '+';
        }else{
            return '';
        };
    }

    public function searchBloodGroup($bloodGroup)
    {
        $bloodGroup = $this->convertQuery($bloodGroup);
        $result = $this->user->where('blood_group',$bloodGroup)->where('latest_donotion_date','<',$this->datetime);
        return $result;
    }

    public function searchName($name)
    {
        $result = $this->user->where('name','like',"%$name%");
        return $result;
    }

    public function searchPhoneNumber($phone)
    {
        $result = $this->user->where('phone',$phone);
        return $result;
    }

    public function searchEmail($email)
    {
        $result = $this->user->where('email',$email);
        return $result;
    }

    public function searchAddress($address,$bloodGroup)
    {
        if($bloodGroup!='all'){
            $result = $this->user
                        ->where('address','like',"$address%")
                        ->where('blood_group',$bloodGroup)
                        ->where('latest_donotion_date','<',$this->datetime);
        }else{
            $result = $this->user
                        ->where('address','like',"$address%")
                        ->where('latest_donotion_date','<',$this->datetime);
        }
        return $result;
    }

}