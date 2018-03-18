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
        $this->middleware(['auth','admin']);
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
    public function stockReport(Request $request)
    {
        return view('admin.stockreport');
    }

    public function branches(Request $request)
    {
        return view('admin.branches');
    }

    public function suppliers(Request $request)
    {
        return view('admin.supplier');
    }



    public function reset(Request $request)
    {
        return view('admin.reset');
    }

    public function users(Request $request)
    {
        return view('admin.users');
    }

    public function stockExchange(Request $request)
    {
        return view('admin.stock-exchange');
    }

    public function activityLogs(Request $request)
    {
        return view('admin.activity-logs');
    }

    public function purchaseOrder(Request $request)
    {
        return view('admin.purchase-order');
    }

    public function receiptsExchange(Request $request)
    {
        return view('admin.stock-exchange-receipts');
    }
}
