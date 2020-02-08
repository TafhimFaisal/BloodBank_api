<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
use App\Datatable\FatchData;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Http\Service\StoreUserDataValidation;

class AdminController extends Controller
{
    protected $datatable;

    public function __construct(JWTAuth $jwt)
    {
        $this->datatable = new FatchData;
    }

    public function index()
    {
        $users = $this->datatable->getData('users','id');
        return response()->json($users,200);
    }
    public function adminCheck(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;
        $active_status = $user->active_status;
        
        if($role == 'admin' && $active_status == 1){
            return response()->json('can log in',200);
        }else{
            return response()->json('admin',401);
        }
    }
    public function showUserData($id)
    {
        $user = User::findorFail($id);
        return $user!=null ? response()->json($user,200):response()->json(["message" => "user Not found"],204);
    }
    

    public function store(Request $request)
    {
        $data = $request->all();
        $dataValidate = new StoreUserDataValidation($data);
        $is_valide = $dataValidate->is_valide();

        if($is_valide){
            $data['password'] = Hash::make($data['password']);
            User::create($data);
            return response()->json(["message" => " successful"],200);
        }else{
            return response()->json($dataValidate->errorMassages(),203);
        }
    }
    
    public function update(Request $request,$id)
    {
        if($request->password == null){
            $user = User::findorFail($id);
            $user->update($request->all());
            return response()->json(["message" => "successfully updated"],200);
        }else{
            return response()->json(["message" => "you cannot change password from here"],403);
        }
    }

    public function delete($id)
    {
        $user = User::where('id',$id)->first();
        if($user!=null){
            $user->delete();
            return response()->json(["message" => "successful"],200);
        }else{
            return response()->json(["message" => "user not found"],204);
        }
    }

    public function changeUserPassword(Request $request,$id)
    {
        $user = User::findorFail($id);
        if($user!=null){
            $user->changePassword($request->password);
            return response()->json(["message" => "successfully changed the password"],200);
        }else{
            return response()->json(["message" => "user not found"],204);
        }
    }
}
