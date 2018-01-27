<?php

namespace App\Http\Controllers;

use App\Products;
use Illuminate\Http\Request;
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
    public function index()
    {
        $mcoat_total = Products::select(DB::raw('sum(quantity * unit_price) as total'))->first()->total;
        $allied_total = Products::select(DB::raw('sum(quantity_1 * unit_price) as total'))->first()->total;
        $data = [
            'mcoat_total' =>number_format($mcoat_total,2),
            'allied_total' =>number_format($mcoat_total,2),
        ];
        return view('admin.index',$data);
    }
}
