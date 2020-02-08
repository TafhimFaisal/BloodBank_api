<?php

namespace App\Http\Controllers;
use App\Models\DonotionHistory;
use App\User;

class DonotionsHistoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index($id)
    {
        $data = DonotionHistory::where('user_id',$id)->pluck('donotion_date');
        if(!$data->isEmpty()){
            return response()->json(["data"=>$data],200);
        }else{
            return response()->json(["data"=>$data],204);
        }
    }
    

    
}
