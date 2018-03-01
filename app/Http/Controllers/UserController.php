<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
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
    public function getUsers()
    {
        $data = User::orderBy('first_name','asc')->get();
        return compact('data');
    }
    public function updateUser(Request $request){
        if($request->type == 1){
            //approve
            User::where('id',$request->id)->update(['status'=>1]);
        }elseif($request->type == 2){
            //disapprove
            User::where('id',$request->id)->update(['status'=>0]);
        }elseif($request->type == 3){
            //change user_type
            User::where('id',$request->id)->update(['user_type'=>$request->user_type]);
        }elseif($request->type == 4){
            //remove
            User::where('id',$request->id)->update(['is_remove'=>0]);
        }elseif($request->type == 5){
            //undo remove
            User::where('id',$request->id)->update(['is_remove'=>1]);
        }elseif($request->type == 6){
            //undo remove
            User::where('id',$request->id)->update(['warehouse'=>$request->warehouse]);
        }
        return 'User updated successfully';
    }


}
