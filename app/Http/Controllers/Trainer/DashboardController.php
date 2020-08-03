<?php

namespace App\Http\Controllers\Trainer;

use App\CourseSale;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Slider;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }
    public function dashboard(){
        return view('backend.trainer.dashboard.home');
    }
    public function profile(){
        return view('backend.trainer.dashboard.profile');
    }
    public function update_profile(Request $request){

//       dd($request->hasfile('image'));
        if($request->hasfile('image')){

            $postData = $request->only('image');

            $file = $postData['image'];

            $fileArray = array('image' => $file);

            // Tell the validator that this file should be an image
            $rules = array(
                'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000' // max 10000kb
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);


            // Check to see if validation fails or passes
            if ($validator->fails())
            {
                return redirect()->back()->with('alert','Upload Image only')->withInput();
            }

            $destinationpath=public_path("users/".Auth::user()->image);
            File::delete($destinationpath);
            $file=$request->file('image');
            $filename = str_replace(' ', '', $file->getClientOriginalName());
            $ext=$file->getClientOriginalExtension();
            $imgname=uniqid().$filename;
            $destinationpath=public_path('users');
            $file->move($destinationpath,$imgname);
        }else{
            $imgname=Auth::user()->image;

        }
        $check = User::where('id',Auth::user()->id)->update([
            'name'=>$request->name,
            'cnic'=>$request->cnic,
            'address'=>$request->address,
            'experience'=>$request->experience,
            'country'=>$request->country,
            'phone'=>$request->phone,
            'date_of_birth'=>$request->date_of_birth,
            'email'=>$request->email,
            'image'=>$imgname
        ]);
        if ($check){
            return redirect(route('Trainer/dashboard'));
        }
    }
    public function courses(){
        $data = Course::where('user_id',Auth::id())->get();
        $categories = Category::all();
        return view('backend.trainer.courses.home', compact('data','categories'));

    }
    public function sell_courses(){
        $data = CourseSale::where('sell_by',Auth::id())->get();
        return view('backend.trainer.courses.sales', compact('data'));

    }

    public function trainer_profile(){
        return view('trainer.index');
    }
}
