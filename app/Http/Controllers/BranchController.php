<?php

namespace App\Http\Controllers;

use App\Branches;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
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
    public function getBranch()
    {
        $data = Branches::where('status',1)->orderBy('name','asc')->get();
        return compact('data');
    }

    public function addBranch(Request $request){
        DB::table('branches')->insert(['name'=>$request->name,'address'=>$request->address,'status'=>1]);
    }

    public function updateBranch(Request $request){
        Branches::where('id',$request->id)->update(['name'=>$request->name,'address'=>$request->address]);
    }

    public function deleteBranch(Request $request){
        Branches::where('id',$request->id)->update(['status'=>0]);
    }
}
