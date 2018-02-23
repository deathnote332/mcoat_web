<?php

namespace App\Http\Controllers;

use App\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
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
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * Show the application Products.
     *
     * @return \Illuminate\Http\Response
     */
    public function products(Request $request)
    {
        return view('admin.products',['warehouse'=>$request->id]);
    }

    /**
     * Show the application Manage Products.
     *
     * @return \Illuminate\Http\Response
     */
    public function manageProducts(Request $request)
    {
        return view('admin.manageproduct',['warehouse'=>$request->id]);
    }

    /**
     * Show the application Product-Out.
     *
     * @return \Illuminate\Http\Response
     */
    public function productOut(Request $request)
    {
        if($request->id == 1 && $request->cart ==1 || $request->id == 2 && $request->cart ==3){
            return view('admin.productout',['warehouse'=>$request->id,'cart'=>$request->cart]);
        }else{
            abort(503);
        }

    }

    /**
     * Show the application Product-In.
     *
     * @return \Illuminate\Http\Response
     */
    public function productIn(Request $request)
    {
        return view('admin.productin',['warehouse'=>$request->id,'cart'=>$request->cart]);
    }

    /**
     * Show the application Receipts.
     *
     * @return \Illuminate\Http\Response
     */
    public function receipts(Request $request)
    {
        return view('admin.receipts');
    }

    /**
     * Show the application Receipts-In.
     *
     * @return \Illuminate\Http\Response
     */
    public function receiptsIn(Request $request)
    {
        return view('admin.receipts-in');
    }

    /**
     * Show the application Receipts-In.
     *
     * @return \Illuminate\Http\Response
     */
    public function editReceipt(Request $request)
    {
        //move product_out items into temp_product_out


        //delete first if data existed
        $temp_product_out = DB::table('temp_product_out')->where('rec_no',$request->receipt_no)->delete();
        //get all receipt items
        $product_out_items = DB::table('product_out_items')->where('receipt_no',$request->receipt_no)->get();
        //insert to temp but don't delete original data
        //type 5 for editing receipt
        foreach ($product_out_items as $key=>$val){
            DB::table('temp_product_out')->insert(['product_id'=>$val->product_id,'qty'=>$val->quantity,'type'=>5,'user_id'=>Auth::user()->id,'rec_no'=>$val->receipt_no]);
        }
        return view('admin.edit-receipt',['warehouse'=>$request->warehouse,'receipt_no'=>$request->receipt_no]);
    }
}
