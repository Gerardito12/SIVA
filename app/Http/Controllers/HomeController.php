<?php

namespace App\Http\Controllers;
use DB;
use App\userlevels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
        $levelUSR = Auth::user()->profile;
        $lv = userlevels::find($levelUSR);
        return view('home',[
            'level' =>  $lv
        ]);
    }
}
