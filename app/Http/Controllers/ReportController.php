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
        return view('pdf.pricelist',$data);
        //$pdf = PDF::loadView('pdf.pricelist',$data)->setPaper('a4')->setWarnings(false);
        //return $pdf->stream();
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
        // if($queryCategory == '' && $queryBrand != '') {

        //     if($queryStock == 0){
        //         $products = Product::orderBy('brand')
        //             ->orderBy('category')
        //             ->orderBy('description')
        //             ->orderBy('unit')
        //             ->where($queryString, 0)
        //             ->where('brand', $queryBrand)
        //             ->where('status',1)
        //             ->get();

        //     }elseif($queryStock == 1){
        //         $stock = [1,2,3];
        //         $products = Product::orderBy('brand')
        //             ->orderBy('category')
        //             ->orderBy('description')
        //             ->orderBy('unit')
        //             ->whereIn($queryString, $stock)
        //             ->where('brand', $queryBrand)
        //             ->where('status',1)
        //             ->get();
        //     }else{
        //         $products = Product::orderBy('brand')
        //             ->orderBy('category')
        //             ->orderBy('description')
        //             ->orderBy('unit')
        //             ->where('brand', $queryBrand)
        //             ->where('status',1)
        //             ->get();
        //     }
        //     $title = $queryBrand;
        //     //not empty category
        // }elseif($queryBrand == '' && $queryCategory != ''){

        //     if($queryStock == 0){
        //         $products = Product::orderBy('brand')
        //             ->orderBy('category')
        //             ->orderBy('description')
        //             ->orderBy('unit')
        //             ->where($queryString, 0)
        //             ->where('category',$queryCategory)
        //             ->where('status',1)
        //             ->get();

        //     }elseif($queryStock == 1){
        //         $stock = [1,2,3];
        //         $products = Product::orderBy('brand')
        //             ->orderBy('category')
        //             ->orderBy('description')
        //             ->orderBy('unit')
        //             ->where($queryString,'>',1)
        //             ->where('category',$queryCategory)
        //             ->where('status',1)
        //             ->get();
        //     }else{
        //         $products = Product::orderBy('brand')
        //             ->orderBy('category')
        //             ->orderBy('description')
        //             ->orderBy('unit')
        //             ->where('category',$queryCategory)
        //             ->where('status',1)
        //             ->get();
        //     }



        //     $title = $queryCategory;
        //     //not empty brand and category
        // }elseif($queryBrand != '' && $queryCategory != ''){

        //     if($queryStock == 0){
        //         $products = Product::orderBy('brand')
        //             ->orderBy('category')
        //             ->orderBy('description')
        //             ->orderBy('unit')
        //             ->where($queryString, 0)
        //             ->where('brand',$queryBrand)
        //             ->where('category',$queryCategory)
        //             ->where('status',1)
        //             ->get();

        //     }elseif($queryStock == 1){
        //         $stock = [1,2,3];
        //         $products = Product::orderBy('brand')
        //             ->orderBy('category')
        //             ->orderBy('description')
        //             ->orderBy('unit')
        //             ->where($queryString,'>',1)
        //             ->where('brand',$queryBrand)
        //             ->where('category',$queryCategory)
        //             ->where('status',1)
        //             ->get();
        //     }else{
        //         $products = Product::orderBy('brand')
        //             ->orderBy('category')
        //             ->orderBy('description')
        //             ->orderBy('unit')
        //             ->where('brand',$queryBrand)
        //             ->where('category',$queryCategory)
        //             ->where('status',1)
        //             ->get();
        //     }
        //     $title = $queryBrand.'-'.$queryCategory;
        // }elseif(isset($request->brand) && isset($request->category)){

        $products = Product::orderBy('brand')
                ->orderBy('category')
                ->orderBy('description')
                ->orderBy('unit')
                ->where($queryString,'>',1)
                ->where('status',1)
                ->get();
        $title = 'All stocks';
        // }

        $data = ['data'=>json_encode($products),'title'=>$title,'warehouse'=>$request->warehouse];
        
       // $pdf = PDF::loadView('pdf.stocklist',$data)->setPaper('a4');
        return view('pdf.stocklist',$data);
       // return $pdf->stream();
    }


    public function editDailySale(Request $request){

      //  dd($request->all());

        $date = date('Y-m-d', strtotime($request->_date));

        $branch= $request->branch;
        $check = DB::table('month_sales')->where('branch_id',$request->branch_id)->where('_date',$date)->first();

        if($check != null ||  $check != '' ){
            $data = $request->all();
            unset($data['_token']);

            $_data = json_encode($data);
            DB::table('month_sales')
                ->where('branch_id',$request->branch_id)
                ->where('_date',$date)
                ->update(['data'=>$_data]);
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

    public function getProductTracking(Request $request){
        $data = DB::table('product_out_items')
        ->select('tblproducts.brand','tblproducts.category','tblproducts.code','tblproducts.description','tblproducts.unit','product_out_items.quantity','product_out_items.receipt_no','b.name as b_name','po.created_at as p_date')
        ->join('tblproducts','tblproducts.id','product_out_items.product_id')
         ->join('product_out as po','po.receipt_no','product_out_items.receipt_no')
         ->join('branches as b','b.id','po.branch')
         ->where('po.type',$request->warehouse_id)
          ->orderBy('po.created_at','desc');
        return Datatables::of($data)->make(true);
    }

    public function getProductTrackingIn(Request $request){
        $data = DB::table('product_in_items as pii')
        ->select('p.*','pii.quantity as p_quantity','pi.receipt_no','pi.created_at as p_date','s.name as company')
        ->join('tblproducts as p','p.id','pii.product_id')
        ->join('product_in as pi','pi.id','pii.product_in_id')
        ->join('suppliers as s','s.id','pi.supplier_id')
        ->where('pi.warehouse',$request->warehouse_id)
        ->orderBy('pi.created_at','desc');
        return Datatables::of($data)->make(true);
    }
    public function getProductDelivery(Request $request){
        $data = DB::table('product_out as po')
        ->select('po.*','b.name as branch_name')
        ->join('branches as b','b.id','po.branch')
        ->where('po.type',$request->warehouse_id)
        ->orderBy('po.created_at','desc');
        return Datatables::of($data)->make(true);
    }
    public function getInventory(Request $request){
        $data = DB::table('total_inventory')
                ->select('total_inventory.*','branches.name','users.first_name','users.last_name')
                ->join('branches','total_inventory.branch_id','branches.id')
                ->join('users','total_inventory.entered_by','users.id')
                ->where('is_deleted',0)
                ->orderBy('id', 'desc')
                ->get();
        return Datatables::of($data)->make(true);
    }

    public function printInventory(Request $request){
        $products = DB::table('total_inventory_items')
        ->join('tblproducts','total_inventory_items.product_id','tblproducts.id')
        ->where('total_inventory_items.inventory_id',$request->id)
        ->select('tblproducts.brand','tblproducts.category','tblproducts.description','tblproducts.code','total_inventory_items.quantity','total_inventory_items.price','total_inventory_items.unit')
        ->get();

        $title = DB::table('total_inventory')
                ->join('branches','total_inventory.branch_id','branches.id')
                ->where('total_inventory.id',$request->id)
                ->first();
        $data_title = '';
        if(!empty($title)){
            $data_title = 'from :'.date('M d,Y',strtotime($title->from_date)).' to:'.date('M d,Y',strtotime($title->to_date)).' - '.$title->name;
        }

        $data = ['data'=>json_encode($products),'title'=>$data_title];
        return view('pdf.inventory',$data);
    }

}
