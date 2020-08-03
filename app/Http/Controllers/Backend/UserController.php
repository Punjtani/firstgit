<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function new_user_form()
    {
        return view('backend.admin.trainers.add');

    }

    public function new_profile(Request $request)
    {
//        dd($request->all());
        $user = User::where('email', $request->email)->first();
        if ($user) {
            return redirect()->back()->with('message', 'Email Already Exists');
        }
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
            $destinationpath = public_path('users');
            $file->move($destinationpath, $imgname);
        }

        User::create(['name' => $request->name, 'role_id' => $request->role_id, 'email' => $request->email, 'cnic' => $request->cnic, 'password' => Hash::make($request->password), 'address' => $request->address, 'experience' => $request->experience, 'country' => $request->country, 'phone' => $request->phone, 'date_of_birth' => $request->date_of_birth, 'image' => $imgname]);
        return redirect()->back();
    }

    public function update_profile(Request $request)
    {
//        dd($request->all());
        $user = User::find($request->id);
//        if ($user){
//            return redirect()->back()->with('message','Email Already Exists');
//        }
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

            $destinationpath = public_path("users/" . $user->image);
            File::delete($destinationpath);
            $file = $request->file('image');
            $filename = str_replace(' ', '', $file->getClientOriginalName());
            $ext = $file->getClientOriginalExtension();
            $imgname = uniqid() . $filename;
            $destinationpath = public_path('users');
            $file->move($destinationpath, $imgname);
        } else {
            $imgname = $user->image;

        }

        User::where('id', $request->id)->update(['name' => $request->name, 'role_id' => $request->role_id, 'email' => $request->email, 'cnic' => $request->cnic, 'password' => Hash::make($request->password), 'address' => $request->address, 'experience' => $request->experience, 'country' => $request->country, 'phone' => $request->phone, 'date_of_birth' => $request->date_of_birth, 'image' => $imgname]);
        if ($request->redirect == "trainer") {
            return redirect(url('admin/all-trainers'));
        } else {
            return redirect(url('admin/all-users'));
        }
    }

    public function delete_users(Request $request)
    {
        User::where('id', $request->id)->delete();


    }
    public function become_trainer()
    {
        User::where('id', Auth::id())->update(['is_trainer'=>1]);
        return redirect('/');


    }

    public function edit(Request $request)
    {
//        dd($request->all());
        $redirect = $request->redirect;
        $user = User::where('id', $request->id)->first();
        return view('backend.admin.trainers.edit', compact('user','redirect'));


    }
}
