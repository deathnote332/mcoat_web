<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function products(Request  $request)
    {
        if(Auth::user()->warehouse != $request->id){
            abort(503);
        }else{
            return view('secretary.products',['warehouse'=>Auth::user()->warehouse]);
        }

    }

    public function manageProducts(Request $request)
    {
        if(Auth::user()->warehouse != $request->id){
            abort(503);
        }else{
            return view('secretary.manageproduct',['warehouse'=>Auth::user()->warehouse]);
        }

    }

    public function productOut(Request $request)
    {
        if(Auth::user()->warehouse != $request->id){
            abort(503);
        }else{

            return view('secretary.productout',['warehouse'=>Auth::user()->warehouse,'cart'=>(Auth::user()->warehouse == 1) ? 1 : 3]);
        }

    }

    public function productIn(Request $request)
    {
        if(Auth::user()->warehouse != $request->id){
            abort(503);
        }else{
            return view('secretary.productin',['warehouse'=>Auth::user()->warehouse,'cart'=>(Auth::user()->warehouse == 1) ? 2 : 4]);
        }
    }

    /**
     * Show the application Receipts.
     *
     * @return \Illuminate\Http\Response
     */
    public function receipts(Request $request)
    {
        return view('secretary.receipts');
    }

    /**
     * Show the application Receipts-In.
     *
     * @return \Illuminate\Http\Response
     */
    public function receiptsIn(Request $request)
    {
        return view('secretary.receipts-in');
    }

    public function stockReport(Request $request)
    {
        return view('secretary.stockreport');
    }

    public function branches(Request $request)
    {
        return view('admin.branches');
    }

    public function suppliers(Request $request)
    {
        return view('admin.supplier');
    }

    public function stockExchange(Request $request)
    {
        return view('admin.stock-exchange');
    }

}
