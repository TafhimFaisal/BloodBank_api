<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\DonotionHistory;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use Carbon\Carbon;
use DB;
use Auth;
use Illuminate\Support\Str;
use App\Http\Service\SearchService;

class UsersController extends Controller
{
    protected $user;
    protected $datetime;
    protected $bloodGroup;
    protected $searchService;

    public function __construct(JWTAuth $jwt)
    {
        $this->user = Auth::user();
        $this->datetime = Carbon::now()->subDays(90)->toDateTimeString();
        $this->searchService = new SearchService;
    }

    public function index(Request $request,$pagenumber,$blood_group)
    {

        $this->bloodGroup = $blood_group;
        $users = User::where('latest_donotion_date','<',$this->datetime);

        $record_per_page = 12;
        $totel_record = $users->count();
        $pagination_button_number = ceil($totel_record/$record_per_page);
        $pagination_buttons = '';
        $user_cards = '';
        $offset = ($pagenumber-1)*$record_per_page;
        $jason_users_data = [];
        $jason_pagination_data = [];
        
        if(!empty($request->all()['query'])){
            $users = $this->searchService->search($request->all()['query'],$blood_group,$this->datetime);
            $pagination_button_number = ceil($users->count()/$record_per_page);
            $users = $users->offset($offset)->limit($record_per_page)->get();

        }else{

            if($this->bloodGroup=='all'){
                $users = $users->offset($offset)->limit($record_per_page)->get();
            }else{
                $users = $users->where('blood_group','=',$this->bloodGroup);
                $pagination_button_number = ceil($users->count()/$record_per_page);
                $users = $users->offset($offset)->limit($record_per_page)->get();
            }
        }
        
        foreach ($users as $key => $user) {
            array_push($jason_users_data,[ 
                "name"=>$user->name,
                "blood_group"=>$user->blood_group,
                "address"=>$user->address,
                "phone"=>$user->phone,
                "email"=>$user->email,
            ]);
        }
        
        
        for($i = 1; $pagination_button_number>=$i;$i++){
            array_push($jason_pagination_data,[
                'page_no' => $i,
                'blood_group' => $this->bloodGroup
            ]);
        }
        
        return response()->json([
             'users_data' => $jason_users_data,
             'pagination_data' => $jason_pagination_data
        ],200);

    }

    

    public function userInfo()
    {
        $user = $this->user;
        $number_of_donotion = DonotionHistory::where('user_id',$user->id)->count();
        $format_date = date( 'j.m.Y', strtotime($user->latest_donotion_date));
        $create_date = date_create($format_date);
        $add_day = date_add($create_date,date_interval_create_from_date_string("92 days"));

        $next_date = date_format($add_day,"jS M Y");
        $latest_donotion_date = date( 'jS M Y', strtotime($user->latest_donotion_date));

        return response()->json([
            "data"=>$user,
            "number_of_donotion"=>$number_of_donotion,
            "next_donotion_date"=>$next_date,
            "latest_donotion_date"=>$latest_donotion_date,

        ],200);
    }
    public function update(Request $request)
    {
        if($request->password==null){
            $this->user->update($request->all());
            return response()->json([
                "massage" => " profile has successfully updated"
            ],200);
        }else{
            return response()->json([
                "massage" => "You can not change password from here"
            ],403);
        }
    }
    
    public function updateDonotionDate(Request $request)
    {
        
        if($request->password==null){
            empty($request->all()['time']) ? $date = $request->all()['date'].' 11:28:28':$date = $request->all()['date'].' '.$request->all()['time'];
            $this->user->updateDonotionDate($date);
            return response()->json([
                "massage" => "donotion date has been updated successfully"
            ],200);  
        }else{
            return response()->json([
                "massage" => "You can not change password from here"
            ],403);
        } 
    }

    public function authCheck()
    {
        if(Auth::user()->active_status == 1 ){
            return response()->json('can log in',200);
        }else{
            return response()->json('cannot log in',401);
        }
    }

    public function donotiontimeline()
    {

        $user = Auth::user();
        $histories = DonotionHistory::where('user_id',$user->id)->get();
        $data = '';
        if($histories!=null){
            foreach($histories as $key => $history){
    
                $format_date = date( 'j.m.Y', strtotime($history->donotion_date));
                $create_date = date_create($format_date);
                $add_day = date_add($create_date,date_interval_create_from_date_string("92 days"));
    
                $next_date = date_format($add_day,"jS M Y");
                $date = date( 'jS M Y', strtotime($history->donotion_date));
                $month = date( 'M', strtotime($history->donotion_date));
    
                $data .= '  <a class="timeline">
                                <div class="timeline-icon"><span class="timeline-icon-text">'.$month.'<span></div>
                                <div class="timeline-content">
                                    <h3 class="title">'.$date.'</h3>
                                    <p class="description">
                                        <span class="text-info">This was your '.$history->getOrdinal($key+1).' donotion </span>, 
                                        your next date should have been after <span class="text-danger">'.$next_date.'</span>.
                                        the number of donotion you make to this date is '.($key+1).'
                                    </p>
                                </div>
                            </a>
                        ';
            }

        }else{
            $data = 'Be a proud blood donors';
        }
        return response()->json([$data],200);
    }
}
