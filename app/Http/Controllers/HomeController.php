<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        return view('home');
    }
    public function home()
    {
        $categories = Category::all();
        $slider = Slider::all();
        return view('home.frontend.index',compact('slider','categories'));
    }
}
