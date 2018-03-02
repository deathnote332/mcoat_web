<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class SecretaryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth','user']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {

        return view('secretary.dashboard');
    }


}
