<?php

namespace App\Http\Controllers;


use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
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
    public function getSupplier()
    {
        $data = Supplier::where('status',1)->orderBy('name','asc')->get();
        return compact('data');
    }

    public function addSupplier(Request $request){
        DB::table('suppliers')->insert(['name'=>$request->name,'address'=>$request->address,'status'=>1]);
    }

    public function updateSupplier(Request $request){
        Supplier::where('id',$request->id)->update(['name'=>$request->name,'address'=>$request->address]);
    }

    public function deleteSupplier(Request $request){
        Supplier::where('id',$request->id)->update(['status'=>0]);
    }
}
