<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     *
     *the word honor mean the answer mean khatab
     * ha   ha ha the one and only danish taimoor
     * the birds fly in the assssiuirughuhgu\
     * e
     * jehfjhenjfhejhdhjdwnjdwjdhwhswu uhwsjwns  m
     * motu patla dm,vnm,dvnm,m,vdlvvm,n,cm,vbnm,dvnm,cnbm,vnm,vcbbm,b nvnnbmfbghjfebhjsdhjfhsbvjbfjdvbejbvhjfbvhjfebvhjbevjebjfbbhfbjfbfbfbhjf
     * Display a listing of the resource.vbfhvhfhbvhfevjqevbjbfhvbfhvbhjfebvjfevhfbvhjfbvhuhuguhjhdjhudifdgvfhdfhbdvfdvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
     *vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
//        dd($request->all());
//        $imgname = "";

        if ($request->hasfile('logo')) {
            $postData = $request->only('logo');

            $file = $postData['logo'];

            $fileArray = array('logo' => $file);

            // Tell the validator that this file should be an image
            $rules = array(
                'logo' => 'mimes:jpeg,jpg,png,gif|required|max:10000' // max 10000kb
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);


            // Check to see if validation fails or passes
            if ($validator->fails()) {
                return redirect()->back()->with('alert', 'Upload Image only')->withInput();
            }
            $file = $request->file('logo');
            $filename = str_replace(' ', '', $file->getClientOriginalName());
            $ext = $file->getClientOriginalExtension();
            $imgname = uniqid() . $filename;
            $destinationpath = public_path('category');
            $file->move($destinationpath, $imgname);
        }
//        dd($imgname);

        $category = Category::create(['name'=>$request->name,'logo'=>$imgname]);

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
    public function edit(Request $request)
    {
//        dd($request->all());
        $slider = Category::find($request->id);
        return response()->json(['status' => 'success', 'data'=>$slider ]);
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
        $slider = Category::find($request->id);
        if($request->hasfile('logo')){

            $postData = $request->only('logo');

            $file = $postData['logo'];

            $fileArray = array('logo' => $file);

            // Tell the validator that this file should be an image
            $rules = array(
                'logo' => 'mimes:jpeg,jpg,png,gif|required|max:10000' // max 10000kb
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);


            // Check to see if validation fails or passes
            if ($validator->fails())
            {
                return redirect()->back()->with('alert','Upload Image only')->withInput();
            }

            $destinationpath=public_path("category/".$slider->image);
            File::delete($destinationpath);
            $file=$request->file('logo');
            $filename = str_replace(' ', '', $file->getClientOriginalName());
            $ext=$file->getClientOriginalExtension();
            $imgname=uniqid().$filename;
            $destinationpath=public_path('category');
            $file->move($destinationpath,$imgname);
        }else{
            $imgname=$slider->image;

        }
//        dd($imgname);

        $category = Category::where('id',$request->id)->update(['name'=>$request->name,'logo'=>$imgname]);

        if ($category){
            return redirect()->back();
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

    public function delete_category(Request $request){
        Category::where('id',$request->id)->delete();


    }
}
