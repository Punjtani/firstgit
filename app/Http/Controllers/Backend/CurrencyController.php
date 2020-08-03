<?php

namespace App\Http\Controllers\Backend;

use App\Currency;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        // print_r("expression");exit();
        $data = Currency::all();
        return view('backend.admin.currency.home',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

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
            $destinationpath = public_path('currency');
            $file->move($destinationpath, $imgname);
        }

        $currency = Currency::create(['currency'=>$request->name, 'symbol'=>$request->symbol,'flag'=>$imgname]);

        if ($currency){
            return redirect()->back();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function show(Currency $currency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
         $currency = Currency::find($request->id);
        return response()->json(['status' => 'success', 'data'=>$currency ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Currency $currency)
    {
      $currency = Currency::find($request->id);
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

            $destinationpath=public_path("currency/".$currency->flag);
            File::delete($destinationpath);
            $file=$request->file('logo');
            $filename = str_replace(' ', '', $file->getClientOriginalName());
            $ext=$file->getClientOriginalExtension();
            $imgname=uniqid().$filename;
            $destinationpath=public_path('currency');
            $file->move($destinationpath,$imgname);
        }else{
            $imgname=$currency->flag;

        }
//        dd($imgname);

        $category = Currency::where('id',$request->id)->update(['currency'=>$request->name, 'symbol'=>$request->symbol, 'flag'=>$imgname]);

        if ($category){
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
      $currency = Currency::find($request->id);
        $destinationpath=public_path("currency/".$currency->flag);
            File::delete($destinationpath);
        Currency::destroy($request->id);
    }
}
