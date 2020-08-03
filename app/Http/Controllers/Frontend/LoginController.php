<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    public function userLogin()
    {
        return view('Login.login');
    }
    public function login_user(Request $request)
    {
//        dd($request->all());
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
//            if (Auth::loginUsingId($user->id)) {
            session()->flash('main_admin_success','Login Successfully');
                if (Auth::user()->role_id == 3) {
                    return redirect(RouteServiceProvider::HOME);
                }elseif (Auth::user()->role_id == 2){
                    return redirect(route('Trainer/dashboard'));
                }elseif (Auth::user()->role_id == 1){
                    return redirect(route('dashboard'));
                }
            } else {
            session()->flash('main_admin_errors','Invalid User, Email or Password');
                return redirect()->back();
            }
//        } else {
//            return redirect()->back()->with(['status' => 'error','message'=>'Invalid Email']);
//
//        }
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
