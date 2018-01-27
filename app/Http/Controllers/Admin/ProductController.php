<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Products;
class ProductController extends Controller
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
    public function mcoatStocksPage()
    {
        return view('admin.mcoat-products');
    }
    public  function getProducts(){
        $products = Products::orderBy('brand','asc')->orderBy('category','asc')->orderBy('description','asc')->orderBy('code','asc')->orderBy('unit','asc')->where('status',1)->get();
        return json_encode(['data'=>$products]);
    }
}
