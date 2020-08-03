<?php

namespace App\Http\Controllers;
use App\Dashboard;
use App\NewsRoom;
use App\NewsStatus;
use MaddHatter\LaravelFullcalendar\Facades\Calendar;
use Illuminate\Http\Request;
use App\Conversation;
use App\Employee;
use App\Slider;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $events=Dashboard::all();
        $event_arr=[];

        foreach($events as $event)
        {


            //$enddate=$event->end_date."24:00:00";
            $event_arr[]=\Calendar::event(


                $event->name,
                false,
                new \DateTime($event->interview_date),
                new \DateTime($event->interview_time),

                [
                    'color' => '#f05050',
                ]


            );
        }
//        $user_id=Auth::id();
//        dd($user_id);
//        $user_id= auth()->user()->id;
        $calendar=\Calendar::addEvents($event_arr);
        $id=90;
        $news=NewsRoom::where('status','active')->first();
//        dd($news);
        if(isset($news)){
            $news_status=NewsStatus::where([
                ['user_id','=',$id],
                ['news_rooms_id','=',$news->id]
            ])->first();
        }

        if(isset($news_status)){
            $show_news = $news;
            $insert_news_status=array(
                'news_rooms_id' => $news->id,
                'user_id' => $id,
                'status' => 'seen',
            );
            NewsStatus::insert($insert_news_status);
        }else{
            $show_news = [];
        }

        $id=90;
//        $conversations=Conversation::all();
        $conversation_notifications=Conversation::where([
            ['receiver_id','=',$id],
            ['status','=','unseen'],
        ])->orderby('id','DESC')->get();

        $conversation_count = $conversation_notifications->count();

        $contacts=Employee::where('id','<>',$id)->get();
        $conversations=Conversation::all();

        $conversation_level=DB::table('users_chat_status')
            ->join('employees','employees.id','=','users_chat_status.child_user_id')
            ->select('employees.id','employees.name','employees.avatar_pic_url','users_chat_status.child_user_id','users_chat_status.updated_at')
            ->orderby('users_chat_status.updated_at','DESC')
            ->where('parent_user_id',$id)
            ->get();
//        dd($conversation_level[0]->child_user_id);
        if(count($conversation_level) > 0) {
//            $conversations=DB::table('conversations')
//                ->where('conversations.sender_id',$conversation_level[0]->child_user_id)
//                ->orWhere('conversations.receiver_id',$conversation_level[0]->child_user_id)
//                ->get();

            $conversations=Conversation::where([
                ['sender_id','=',$id],
                ['receiver_id','=',$conversation_level[0]->child_user_id],
            ])->orWhere([
                ['sender_id','=',$conversation_level[0]->child_user_id],
                ['receiver_id','=',$id],
            ])->get();
            $sender_name=Employee::where('id',$id)->select('name','avatar_pic_url')->first();
            $receiver_name=Employee::where('id',$conversation_level[0]->child_user_id)->select('name','avatar_pic_url')->first();

//dd($sender_name);
            $opened_chat_user=DB::table('employees')
                ->select('id','name','avatar_pic_url','status','online_timing')
                ->where('id',$conversation_level[0]->child_user_id)
                ->first();
//                    dd($open_chat);
//            $conversations = Conversation::where('sender_id',$conversation_level[0]->child_user_id)->orWhere('receiver_id',$conversation_level[0]->child_user_id)->get();
        }else{
            $conversations = null;
            $opened_chat_user = null;
            $sender_name = null;
            $receiver_name = null;
        }

        $employees = get_employees();
//        dd($employees);
        $attendances = [];
        foreach ($employees as $employee){
            $total_working_days = get_working_days();
//            dd($total_working_days);
            $absents = get_current_month_absents($employee->id);
            $presents = $total_working_days - $absents;

            $attendances[] =[
                'id' => $employee->id ,
                'name' =>$employee->name ,
                'avatar_pic_url' =>$employee->avatar_pic_url ,
                'working_days' =>$total_working_days ,
                'presents' =>$presents
            ];

//            dd($absents);
        }
//        dd($attendances);

        $data = [];
        $data['conversations'] = $conversations;
        $data['opened_chat_user'] = $opened_chat_user;
        $data['conversation_notification'] = $conversation_notifications;
        $data['conversation_count'] = $conversation_count;
        $data['contacts'] = $contacts;
        $data['conversation_level'] = $conversation_level;
        $data['current_user'] = $id;
        $data['sender_name'] = $sender_name;
        $data['receiver_name'] = $receiver_name;
        $data['events'] = $events;
        $data['calendar'] = $calendar;
        $data['show_news'] = $show_news;
        $data['attendances'] = $attendances;

//        $data=[
//            'events'=>$events,
//            'calendar'=>$calendar,
//            'show_news'=>$show_news
//        ];

        return view('dashboard',$data);
    }

    public function book_session()
    {
        return view('site.book_session');

    }public function blogs()
    {
        return view('site.blogs');

    }
    public function about_us()
    {
        return view('site.about_us');
    }
    public function join_us()
    {
        return view('site.join_us');
    }
    public function Clinical_Psychologists()
    {
        return view('site.Clinical_Psychologists');
    }
    public function Psychiatrists()
    {
        return view('site.Psychiatrists');
    }
    public function mental_health_volunteers()
    {
        return view('site.mental_health_volunteers');
    }
   
        
    public function site()
    {
        // dd('here');
        $slides = Slider::all();
        $intro = Slider::where('area',2)->first();
        $chose = Slider::where('area',3)->first();
        $misison = Slider::where('area',4)->first();
        $path  =  __DIR__;
        $path  = substr($path ,0,26); 
        $path =  $path .'panel/public/';

        // str_replace("\","Peter",$path);
        $path = str_replace('\\', '/', $path);
        // $slides = \public_path();
        // dd($slides);
        $slid_array = array();
        foreach($slides as $key=>$slide){
            // dd();
            $slid_array[$key] = $slide['image'];
        }
       $data=[
           'path'=>$path,
           'slides'=>$slides,
           'intro'=>$intro,
           'chose'=>$chose,
           'misison'=>$misison,
           'slid_array'=>$slid_array,
       ];
// dd($slid_array);
        return view('site.main', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
//        dd($request['name']);
        $user = Aquisition::create($request->all());
        return redirect()->route('aquisition');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
