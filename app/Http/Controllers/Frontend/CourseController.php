<?php

namespace App\Http\Controllers\Frontend;

use App\BookingModel;
use App\ClinicalPsychologistsModel;
use App\Http\Controllers\Controller;
use App\Lesson;
use App\Models\Category;
use App\Models\Course;
use App\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use DB;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }
    public function index()
    {
        //
    }
    public function onlineCourse(Request $request)
    {
       // dd($request->id);

        if ($request->id){
            $category = Category::where('id',$request->id)->first('name');
            if (!$category) {
                abort(404);
            }
        $data = Course::whereRaw("find_in_set($request->id,category_id)")->paginate(9);

        }else{
            $category = ['name'=>'All'];
            $data = Course::paginate(9);
        }
        return view('course.onlineCourse',compact('data','category'));
    }
    public function course_detail(Request $request)
    {
//        dd($request->id);

        if ($request->id) {
            $data = Course::find($request->id);
            if (!$data) {
                abort(404);
            }
//            dd($data->lessons);

            return view('course.coursedetail', compact('data'));
        }
    }
    public function offlineCourse()
    {

    }
    public function completeCourse()
    {
        return view('course.completeCourse');
    }
    /**
     * Show the form for creating a new resource.
     *add_psychiatrists
     * @return \Illuminate\Http\Response
     */



    public function add_clinical_psychologists(Request $request)
    {
        // dd('here');
        // dd($request->all());
        if ($request->hasfile('cv')) {
            $postData = $request->only('cv');

            $file = $postData['cv'];

            $fileArray = array('cv' => $file);

            // Tell the validator that this file should be an image
            $rules = array(
                'cv' => 'mimes:jpeg,jpg,png,pdf,doc,docx,gif|required|max:10000' // max 10000kb
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);


            // Check to see if validation fails or passes
            if ($validator->fails()) {
                return redirect()->back()->with('alert', 'Upload Image only')->withInput();
            }
            $file = $request->file('cv');
            $filename = str_replace(' ', '', $file->getClientOriginalName());
            $ext = $file->getClientOriginalExtension();
            $cvname = uniqid() . $filename;
            $destinationpath = public_path('course');
            $file->move($destinationpath, $cvname);
        }
        if ($request->hasfile('bank_slip')) {
            $postData = $request->only('bank_slip');

            $file = $postData['bank_slip'];

            $fileArray = array('bank_slip' => $file);

            // Tell the validator that this file should be an image
            $rules = array(
                'bank_slip' => 'mimes:jpeg,jpg,png,pdf,doc,docx,gif|required|max:10000' // max 10000kb
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);


            // Check to see if validation fails or passes
            if ($validator->fails()) {
                return redirect()->back()->with('alert', 'Upload Image only')->withInput();
            }
            $file = $request->file('bank_slip');
            $filename = str_replace(' ', '', $file->getClientOriginalName());
            $ext = $file->getClientOriginalExtension();
            $bankslipname = uniqid() . $filename;
            $destinationpath = public_path('course');
            $file->move($destinationpath, $bankslipname);
        }
        if ($request->hasfile('docs')) {
            $postData = $request->only('docs');

            $file = $postData['docs'];

            $fileArray = array('docs' => $file);

            // Tell the validator that this file should be an image
            $rules = array(
                'docs' => 'mimes:jpeg,jpg,png,pdf,doc,docx,gif|required|max:10000' // max 10000kb
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);


            // Check to see if validation fails or passes
            if ($validator->fails()) {
                return redirect()->back()->with('alert', 'Upload Image only')->withInput();
            }
            $file = $request->file('docs');
            $filename = str_replace(' ', '', $file->getClientOriginalName());
            $ext = $file->getClientOriginalExtension();
            $docsname = uniqid() . $filename;
            $destinationpath = public_path('course');
            $file->move($destinationpath, $docsname);
        }
        // dd('here');

        // $category_id = implode(',', $request->category_id);
        $category_id = 1;

        $category = ClinicalPsychologistsModel::create(
            [
                'category_id'=>$category_id,
                'name'=>$request->name,
                'email'=>$request->email,
                'phone'=>$request->phone,
                'age'=>$request->age,
                'cnic'=>$request->cnic,
                'qualification'=>$request->qualification,
                'experience'=>$request->experience,
                'specialty'=>$request->specialty,
                'bank_name'=>$request->bank_name,
                'account_number'=>$request->account_number,
                'bank_slip'=>$bankslipname,
                'cv'=>$cvname,
                'docs'=>$docsname

            ]
        );

        // if ($category){
            return redirect()->back();
        // }

    }
    public function add_psychiatrists(Request $request)
    {
        // dd('here');
        // dd($request->all());
        if ($request->hasfile('cv')) {
            $postData = $request->only('cv');

            $file = $postData['cv'];

            $fileArray = array('cv' => $file);

            // Tell the validator that this file should be an image
            $rules = array(
                'cv' => 'mimes:jpeg,jpg,png,pdf,doc,docx,gif|required|max:10000' // max 10000kb
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);


            // Check to see if validation fails or passes
            if ($validator->fails()) {
                return redirect()->back()->with('alert', 'Upload Image only')->withInput();
            }
            $file = $request->file('cv');
            $filename = str_replace(' ', '', $file->getClientOriginalName());
            $ext = $file->getClientOriginalExtension();
            $cvname = uniqid() . $filename;
            $destinationpath = public_path('course');
            $file->move($destinationpath, $cvname);
        }
        if ($request->hasfile('bank_slip')) {
            $postData = $request->only('bank_slip');

            $file = $postData['bank_slip'];

            $fileArray = array('bank_slip' => $file);

            // Tell the validator that this file should be an image
            $rules = array(
                'bank_slip' => 'mimes:jpeg,jpg,png,pdf,doc,docx,gif|required|max:10000' // max 10000kb
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);


            // Check to see if validation fails or passes
            if ($validator->fails()) {
                return redirect()->back()->with('alert', 'Upload Image only')->withInput();
            }
            $file = $request->file('bank_slip');
            $filename = str_replace(' ', '', $file->getClientOriginalName());
            $ext = $file->getClientOriginalExtension();
            $bankslipname = uniqid() . $filename;
            $destinationpath = public_path('course');
            $file->move($destinationpath, $bankslipname);
        }
        if ($request->hasfile('docs')) {
            $postData = $request->only('docs');

            $file = $postData['docs'];

            $fileArray = array('docs' => $file);

            // Tell the validator that this file should be an image
            $rules = array(
                'docs' => 'mimes:jpeg,jpg,png,pdf,doc,docx,gif|required|max:10000' // max 10000kb
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);


            // Check to see if validation fails or passes
            if ($validator->fails()) {
                return redirect()->back()->with('alert', 'Upload Image only')->withInput();
            }
            $file = $request->file('docs');
            $filename = str_replace(' ', '', $file->getClientOriginalName());
            $ext = $file->getClientOriginalExtension();
            $docsname = uniqid() . $filename;
            $destinationpath = public_path('course');
            $file->move($destinationpath, $docsname);
        }
        // dd('here');

        // $category_id = implode(',', $request->category_id);
        $category_id = 2;

        $category = ClinicalPsychologistsModel::create(
            [
                'category_id'=>$category_id,
                'name'=>$request->name,
                'email'=>$request->email,
                'phone'=>$request->phone,
                'age'=>$request->age,
                'cnic'=>$request->cnic,
                'qualification'=>$request->qualification,
                'experience'=>$request->experience,
                'specialty'=>$request->specialty,
                'bank_name'=>$request->bank_name,
                'account_number'=>$request->account_number,
                'bank_slip'=>$bankslipname,
                'cv'=>$cvname,
                'docs'=>$docsname

            ]
        );

        // if ($category){
            return redirect()->back();
        // }

    }
    public function add_mental_health_volunteers(Request $request)
    {
        // dd('here');
        // dd($request->all());
        if ($request->hasfile('cv')) {
            $postData = $request->only('cv');

            $file = $postData['cv'];

            $fileArray = array('cv' => $file);

            // Tell the validator that this file should be an image
            $rules = array(
                'cv' => 'mimes:jpeg,jpg,png,pdf,doc,docx,gif|required|max:10000' // max 10000kb
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);


            // Check to see if validation fails or passes
            if ($validator->fails()) {
                return redirect()->back()->with('alert', 'Upload Image only')->withInput();
            }
            $file = $request->file('cv');
            $filename = str_replace(' ', '', $file->getClientOriginalName());
            $ext = $file->getClientOriginalExtension();
            $cvname = uniqid() . $filename;
            $destinationpath = public_path('course');
            $file->move($destinationpath, $cvname);
        }
        if ($request->hasfile('bank_slip')) {
            $postData = $request->only('bank_slip');

            $file = $postData['bank_slip'];

            $fileArray = array('bank_slip' => $file);

            // Tell the validator that this file should be an image
            $rules = array(
                'bank_slip' => 'mimes:jpeg,jpg,png,pdf,doc,docx,gif|required|max:10000' // max 10000kb
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);


            // Check to see if validation fails or passes
            if ($validator->fails()) {
                return redirect()->back()->with('alert', 'Upload Image only')->withInput();
            }
            $file = $request->file('bank_slip');
            $filename = str_replace(' ', '', $file->getClientOriginalName());
            $ext = $file->getClientOriginalExtension();
            $bankslipname = uniqid() . $filename;
            $destinationpath = public_path('course');
            $file->move($destinationpath, $bankslipname);
        }
        if ($request->hasfile('docs')) {
            $postData = $request->only('docs');

            $file = $postData['docs'];

            $fileArray = array('docs' => $file);

            // Tell the validator that this file should be an image
            $rules = array(
                'docs' => 'mimes:jpeg,jpg,png,pdf,doc,docx,gif|required|max:10000' // max 10000kb
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);


            // Check to see if validation fails or passes
            if ($validator->fails()) {
                return redirect()->back()->with('alert', 'Upload Image only')->withInput();
            }
            $file = $request->file('docs');
            $filename = str_replace(' ', '', $file->getClientOriginalName());
            $ext = $file->getClientOriginalExtension();
            $docsname = uniqid() . $filename;
            $destinationpath = public_path('course');
            $file->move($destinationpath, $docsname);
        }
        // dd('here');

        // $category_id = implode(',', $request->category_id);
        $category_id = 3;

        $category = ClinicalPsychologistsModel::create(
            [
                'category_id'=>$category_id,
                'name'=>$request->name,
                'email'=>$request->email,
                'phone'=>$request->phone,
                'age'=>$request->age,
                'cnic'=>$request->cnic,
                'qualification'=>$request->qualification,
                'experience'=>$request->experience,
                'specialty'=>$request->specialty,
                'bank_name'=>$request->bank_name,
                'account_number'=>$request->account_number,
                'bank_slip'=>$bankslipname,
                'cv'=>$cvname,
                'docs'=>$docsname

            ]
        );

        // if ($category){
            return redirect()->back();
        // }

    }
    public function booking_session(Request $request)
    {
        // dd('here');
        // dd($request->all());date time age country gende r

        $category = BookingModel::create(
            [
                'category_id'=>1,
                'name'=>$request->name,
                'email'=>$request->email,
                'phone'=>$request->phone,
                'booking_date'=>$request->booking_date,
                'booking_time'=>$request->booking_time,
                'age'=>$request->age,
                'gender'=>$request->gender,
                'country'=>$request->country,
                'message'=>$request->message,


            ]
        );

        // if ($category){
            return redirect()->back();
        // }

    }
    public function create(Request $request)
    {
       // dd($request->all());
        if ($request->hasfile('image')) {
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
            if ($validator->fails()) {
                return redirect()->back()->with('alert', 'Upload Image only')->withInput();
            }
            $file = $request->file('image');
            $filename = str_replace(' ', '', $file->getClientOriginalName());
            $ext = $file->getClientOriginalExtension();
            $imgname = uniqid() . $filename;
            $destinationpath = public_path('course');
            $file->move($destinationpath, $imgname);
        }
//        dd($imgname);

        $category_id = implode(',', $request->category_id);

        $category = Course::create(['category_id'=>$category_id,'name'=>$request->name,'description'=>$request->description,'duration'=>$request->duration,'price'=>$request->price,'thumbnail'=>$imgname,'user_id'=>Auth::id()]);

        if ($category){
            return redirect()->back();
        }
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $categories = Category::all();
        return view('backend.trainer.courses.add', compact('categories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $course = Course::find($id);
        $cate_id = explode(',', $course->category_id);
        $categories = Category::all();
        $check = 0;
        return view('backend.trainer.courses.edit', compact('categories','course','cate_id','check'));
    }
    public function my_course()
    {
        $data = Course::where('user_id',Auth::id());
//        $categories = Category::all();
        return view('course.myCourse',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
       // dd($request->all());
        $slider = Course::find($request->id);
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

            $destinationpath=public_path("course/".$slider->image);
            File::delete($destinationpath);
            $file=$request->file('image');
            $filename = str_replace(' ', '', $file->getClientOriginalName());
            $ext=$file->getClientOriginalExtension();
            $imgname=uniqid().$filename;
            $destinationpath=public_path('course');
            $file->move($destinationpath,$imgname);
        }else{
            $imgname=$slider->thumbnail;

        }

        $category_id = implode(',', $request->category_id);


        $category = Course::where('id',$request->id)->update(['category_id'=>$category_id,'name'=>$request->name,'description'=>$request->description,'duration'=>$request->duration,'price'=>$request->price,'thumbnail'=>$imgname,'user_id'=>Auth::id()]);

        if ($category){
            return redirect(url('trainer/my-courses'));
        }

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
    public function delete(Request $request)
    {
        $id = $request->id;
        Lesson::where('course_id',$id)->delete();
        Course::where('id',$id)->delete();
        return redirect()->back();
    }
}
