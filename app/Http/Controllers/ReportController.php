<?php

namespace App\Http\Controllers;

use App\Branches;
use App\Product;
use App\Productin;
use App\Productout;
use Dompdf\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use DataTables;
class ReportController extends Controller
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
    public function getSalesReport()
    {
        $graph = Productout::groupBy('branch')->where(DB::raw('MONTH(created_at)'),DB::raw('MONTH(NOW())'))->select('branch',DB::raw('COUNT(receipt_no) as total_receipt'))->where('status',1)->get();
        $totalReceipt = Productout::count();
        $data = array();
        foreach ($graph as $key=>$val){
            $branch = Branches::find($val->branch);
            $percentage = ($val->total_receipt / $totalReceipt) * 100;
            $data[]=['label'=>$branch->name,'value'=>number_format($percentage,1)];
        }
        return $data;
    }


    //price list

    public function priceList(Request $request){
        ini_set("memory_limit", "999M");
        ini_set("max_execution_time", "999");
        if(empty($request->category)){
            $products = Product::where('brand',$request->brand)
                ->orderBy('brand')
                ->orderBy('category')
                ->orderBy('description')
                ->orderBy('unit')
                ->get();
            $title = $request->brand;
        }else{
            $products = Product::where('brand',$request->brand)->where('category',$request->category)->orderBy('brand')
                ->orderBy('category')
                ->orderBy('description')
                ->orderBy('unit')
                ->get();
            $title = $request->brand.' - '.$request->category;
        }

        $data = ['data'=>json_encode($products),'title'=>$title];

        $pdf = PDF::loadView('pdf.pricelist',$data)->setPaper('a4')->setWarnings(false);
        return $pdf->stream();
    }

    public function stockList(Request $request){

        ini_set("memory_limit", "999M");
        ini_set("max_execution_time", "999");


        $queryBrand = $request->brand;
        $queryCategory =  $request->category;
        $queryStock = $request->stock;


        if($request->warehouse == 1){
            $queryString = 'quantity';
        }else{
            $queryString = 'quantity_1';
        }

        //not empty brand
        if($queryCategory == '' && $queryBrand != '') {

            if($queryStock == 0){
                $products = Product::orderBy('brand')
                    ->orderBy('category')
                    ->orderBy('description')
                    ->orderBy('unit')
                    ->where($queryString, 0)
                    ->where('brand', $queryBrand)
                    ->get();

            }elseif($queryStock == 1){
                $stock = [1,2,3];
                $products = Product::orderBy('brand')
                    ->orderBy('category')
                    ->orderBy('description')
                    ->orderBy('unit')
                    ->whereIn($queryString, $stock)
                    ->where('brand', $queryBrand)
                    ->get();
            }else{
                $products = Product::orderBy('brand')
                    ->orderBy('category')
                    ->orderBy('description')
                    ->orderBy('unit')
                    ->where('brand', $queryBrand)
                    ->get();
            }
            $title = $queryBrand;
            //not empty category
        }elseif($queryBrand == '' && $queryCategory != ''){

            if($queryStock == 0){
                $products = Product::orderBy('brand')
                    ->orderBy('category')
                    ->orderBy('description')
                    ->orderBy('unit')
                    ->where($queryString, 0)
                    ->where('category',$queryCategory)
                    ->get();

            }elseif($queryStock == 1){
                $stock = [1,2,3];
                $products = Product::orderBy('brand')
                    ->orderBy('category')
                    ->orderBy('description')
                    ->orderBy('unit')
                    ->whereIn($queryString, $stock)
                    ->where('category',$queryCategory)
                    ->get();
            }else{
                $products = Product::orderBy('brand')
                    ->orderBy('category')
                    ->orderBy('description')
                    ->orderBy('unit')
                    ->where('category',$queryCategory)
                    ->get();
            }



            $title = $queryCategory;
            //not empty brand and category
        }elseif($queryBrand != '' && $queryCategory != ''){

            if($queryStock == 0){
                $products = Product::orderBy('brand')
                    ->orderBy('category')
                    ->orderBy('description')
                    ->orderBy('unit')
                    ->where($queryString, 0)
                    ->where('brand',$queryBrand)
                    ->where('category',$queryCategory)
                    ->get();

            }elseif($queryStock == 1){
                $stock = [1,2,3];
                $products = Product::orderBy('brand')
                    ->orderBy('category')
                    ->orderBy('description')
                    ->orderBy('unit')
                    ->whereIn($queryString, $stock)
                    ->where('brand',$queryBrand)
                    ->where('category',$queryCategory)
                    ->get();
            }else{
                $products = Product::orderBy('brand')
                    ->orderBy('category')
                    ->orderBy('description')
                    ->orderBy('unit')
                    ->where('brand',$queryBrand)
                    ->where('category',$queryCategory)
                    ->get();
            }

            $title = $queryBrand.'-'.$queryCategory;
        }

        $data = ['data'=>json_encode($products),'title'=>$title,'warehouse'=>$request->warehouse];

        $pdf = PDF::loadView('pdf.stocklist',$data)->setPaper('a4');
        return $pdf->stream();
    }


    public function editDailySale(Request $request){

        $date = date('Y-m-d', strtotime($request->_date));
        
        $branch= $request->branch;
        $check = DB::table('month_sales')->where('branch_id',$request->branch_id)->where('_date',$date)->first();
        if($check != null ||  $check != '' ){
            $data = $request->all();
            unset($data['_token']);

            $_data = json_encode($data);
            DB::table('month_sales')->where('_date',$date)->update(['data'=>$_data]);
            $message = 'Successfully updated sale today.';

        }else{
            $data = $request->all();
            unset($data['_token']);
            $_data = json_encode($data);
            DB::table('month_sales')->insert(['_date'=>$date,'data'=>$_data,'branch_id'=>$request->branch_id]);
            $message = 'Successfully saved sale today.';
        }

        return $message;

    }


    public function getUserLogs(Request $request){
        if($request->type != null){
            $data = DB::table('notifications')->where(DB::raw('DATE(created_at)'),date('Y-m-d'))->orderBy('id','desc')->limit(10);
        }else{
            $data = DB::table('notifications')->orderBy('id','desc');
        }

      return Datatables::of($data)->make(true);
    }



}
