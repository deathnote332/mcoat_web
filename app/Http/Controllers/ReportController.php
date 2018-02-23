<?php

namespace App\Http\Controllers;

use App\Branches;
use App\Productin;
use App\Productout;
use Dompdf\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function invoice(Request $request){

        $invoice = Productout::where('product_out.receipt_no',$request->id)
            ->join('users','users.id','product_out.printed_by')
            ->join('branches','branches.id','product_out.branch')
            ->select('product_out.*','users.first_name','users.last_name','branches.name as branch_name','branches.address')
            ->first();

        $products = DB::table('product_out_items')->join('tblproducts','tblproducts.id','product_out_items.product_id')->select('tblproducts.*','product_out_items.quantity as product_qty')->where('receipt_no',$request->id)->get();
        $data =['warehouse'=>$request->warehouse,'total'=>$invoice->total,'updated_at'=>$invoice->updated_at,'status'=>$invoice->status,'receipt_no'=>$invoice->receipt_no,'name'=>$invoice->first_name.' '.$invoice->last_name,'address'=>$invoice->address,'branch_name'=>$invoice->branch_name,'created_at'=>date('M d,Y',strtotime($invoice->created_at)),'products'=>$products,'view'=>$request->view];


        $pdf = PDF::loadView('pdf.invoice',['invoice'=>$data])->setPaper('a4')->setWarnings(false);

        return @$pdf->stream();



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



    //receipts

    public function getReciepts(Request $request){



        if(Auth::user()->user_type ==1){
            if($request->_range == 'all'){
                $receipts = Productout::orderBy('product_out.id','desc')
                    ->join('branches','product_out.branch','branches.id')
                    ->join('users','product_out.printed_by','users.id')
                    ->select('product_out.*','users.first_name','users.last_name','branches.name')
                    ->where('product_out.status',1)
                    ->orwhere('product_out.status',2)
                    ->get();
            }elseif($request->_range == 'week'){
                $receipts = Productout::orderBy('product_out.id','desc')
                    ->where(DB::raw('WEEKOFYEAR(product_out.created_at)'),DB::raw('WEEKOFYEAR(NOW())'))
                    ->join('branches','product_out.branch','branches.id')
                    ->join('users','product_out.printed_by','users.id')
                    ->select('product_out.*','users.first_name','users.last_name','branches.name')
                    ->where('product_out.status',1)
                    ->orwhere('product_out.status',2)
                    ->get();

            }elseif($request->_range == 'today'){
                $receipts = Productout::orderBy('product_out.id','desc')
                    ->where(DB::raw('DATE(product_out.created_at)'),DB::raw('curdate()'))
                    ->join('branches','product_out.branch','branches.id')
                    ->join('users','product_out.printed_by','users.id')
                    ->select('product_out.*','users.first_name','users.last_name','branches.name')
                    ->where('product_out.status',1)
                    ->orwhere('product_out.status',2)
                    ->get();
            }elseif($request->_range == 'month'){
                $receipts = Productout::orderBy('product_out.id','desc')
                    ->where(DB::raw('YEAR(product_out.created_at)'),DB::raw('YEAR(NOW())'))
                    ->where(DB::raw('MONTH(product_out.created_at)'),DB::raw('MONTH(NOW())'))
                    ->join('branches','product_out.branch','branches.id')
                    ->join('users','product_out.printed_by','users.id')
                    ->select('product_out.*','users.first_name','users.last_name','branches.name')
                    ->where('product_out.status',1)
                    ->orwhere('product_out.status',2)
                    ->get();
            }
        }else{
            if(Auth::user()->warehouse == 1){
                $type = 1;
            }elseif(Auth::user()->warehouse == 2){
                $type=3;
            }

            if($request->_range == 'all'){
                $receipts = Productout::orderBy('product_out.id','desc')
                    ->where('product_out.type',$type)
                    ->where('product_out.printed_by',Auth::user()->id)
                    ->join('branches','product_out.branch','branches.id')
                    ->join('users','product_out.printed_by','users.id')
                    ->select('product_out.*','users.first_name','users.last_name','branches.name')
                    ->where('product_out.status',1)
                    ->orwhere('product_out.status',2)
                    ->get();
            }elseif($request->_range == 'week'){
                $receipts = Productout::orderBy('product_out.id','desc')
                    ->where('product_out.type',$type)
                    ->where('product_out.printed_by',Auth::user()->id)
                    ->where(DB::raw('WEEKOFYEAR(product_out.created_at)'),DB::raw('WEEKOFYEAR(NOW())'))
                    ->join('branches','product_out.branch','branches.id')
                    ->join('users','product_out.printed_by','users.id')
                    ->select('product_out.*','users.first_name','users.last_name','branches.name')
                    ->where('product_out.status',1)
                    ->orwhere('product_out.status',2)
                    ->get();

            }elseif($request->_range == 'today'){
                $receipts = Productout::orderBy('product_out.id','desc')
                    ->where('product_out.type',$type)
                    ->where('product_out.printed_by',Auth::user()->id)
                    ->where(DB::raw('DATE(product_out.created_at)'),DB::raw('curdate()'))
                    ->join('branches','product_out.branch','branches.id')
                    ->join('users','product_out.printed_by','users.id')
                    ->select('product_out.*','users.first_name','users.last_name','branches.name')
                    ->where('product_out.status',1)
                    ->orwhere('product_out.status',2)
                    ->get();
            }elseif($request->_range == 'month'){
                $receipts = Productout::orderBy('product_out.id','desc')
                    ->where('product_out.type',$type)
                    ->where('product_out.printed_by',Auth::user()->id)
                    ->where(DB::raw('YEAR(product_out.created_at)'),DB::raw('YEAR(NOW())'))
                    ->where(DB::raw('MONTH(product_out.created_at)'),DB::raw('MONTH(NOW())'))
                    ->join('branches','product_out.branch','branches.id')
                    ->join('users','product_out.printed_by','users.id')
                    ->select('product_out.*','users.first_name','users.last_name','branches.name')
                    ->where('product_out.status',1)
                    ->orwhere('product_out.status',2)
                    ->get();
            }

        }
        return ['data'=>$receipts];

    }

    public function getRecieptsIn(Request $request){

        if(Auth::user()->user_type ==1){


            if($request->_range == 'all'){
                $receipts = Productin::orderBy('product_in.id','desc')
                    ->leftjoin('suppliers','product_in.supplier_id','suppliers.id')
                    ->leftjoin('users','product_in.entered_by','users.id')
                    ->select('product_in.*','users.first_name','users.last_name','suppliers.name','product_in.warehouse as wr')
                    ->get();
            }elseif($request->_range == 'week'){
                $receipts = Productin::orderBy('product_in.id','desc')
                    ->where(DB::raw('WEEKOFYEAR(product_in.created_at)'),DB::raw('WEEKOFYEAR(NOW())'))
                    ->join('suppliers','product_in.supplier_id','suppliers.id')
                    ->join('users','product_in.entered_by','users.id')
                    ->select('product_in.id','product_in.receipt_no','product_in.created_at','users.first_name','users.last_name','suppliers.name','product_in.warehouse as wr')
                    ->get();

            }elseif($request->_range == 'today'){
                $receipts = Productin::orderBy('product_in.id','desc')
                    ->where(DB::raw('DATE(product_in.created_at)'),DB::raw('curdate()'))
                    ->join('suppliers','product_in.supplier_id','suppliers.id')
                    ->join('users','product_in.entered_by','users.id')
                    ->select('product_in.id','product_in.receipt_no','product_in.created_at','users.first_name','users.last_name','suppliers.name','product_in.warehouse as wr')
                    ->get();
            }elseif($request->_range == 'month'){
                $receipts = Productin::orderBy('product_in.id','desc')
                    ->where(DB::raw('YEAR(product_in.created_at)'),DB::raw('YEAR(NOW())'))
                    ->where(DB::raw('MONTH(product_in.created_at)'),DB::raw('MONTH(NOW())'))
                    ->join('suppliers','product_in.supplier_id','suppliers.id')
                    ->join('users','product_in.entered_by','users.id')
                    ->select('product_in.id','product_in.receipt_no','product_in.created_at','users.first_name','users.last_name','suppliers.name','product_in.warehouse as wr')
                    ->get();
            }


        }else{

            if($request->_range == 'all'){
                $receipts = Productin::orderBy('product_in.id','desc')
                    ->where('product_in.entered_by',Auth::user()->id)
                    ->join('suppliers','product_in.supplier_id','suppliers.id')
                    ->join('users','product_in.entered_by','users.id')
                    ->select('product_in.id','product_in.receipt_no','product_in.created_at','users.first_name','users.last_name','suppliers.name','product_in.warehouse as wr')
                    ->get();
            }elseif($request->_range == 'week'){
                $receipts = Productin::orderBy('product_in.id','desc')
                    ->where('product_in.entered_by',Auth::user()->id)
                    ->where(DB::raw('WEEKOFYEAR(product_in.created_at)'),DB::raw('WEEKOFYEAR(NOW())'))
                    ->join('suppliers','product_in.supplier_id','suppliers.id')
                    ->join('users','product_in.entered_by','users.id')
                    ->select('product_in.id','product_in.receipt_no','product_in.created_at','users.first_name','users.last_name','suppliers.name','product_in.warehouse as wr')
                    ->get();

            }elseif($request->_range == 'today'){
                $receipts = Productin::orderBy('product_in.id','desc')
                    ->where('product_in.entered_by',Auth::user()->id)
                    ->where(DB::raw('DATE(product_in.created_at)'),DB::raw('curdate()'))
                    ->join('suppliers','product_in.supplier_id','suppliers.id')
                    ->join('users','product_in.entered_by','users.id')
                    ->select('product_in.id','product_in.receipt_no','product_in.created_at','users.first_name','users.last_name','suppliers.name','product_in.warehouse as wr')
                    ->get();
            }elseif($request->_range == 'month'){
                $receipts = Productin::orderBy('product_in.id','desc')
                    ->where('product_in.entered_by',Auth::user()->id)
                    ->where(DB::raw('YEAR(product_in.created_at)'),DB::raw('YEAR(NOW())'))
                    ->where(DB::raw('MONTH(product_in.created_at)'),DB::raw('MONTH(NOW())'))
                    ->join('suppliers','product_in.supplier_id','suppliers.id')
                    ->join('users','product_in.entered_by','users.id')
                    ->select('product_in.id','product_in.receipt_no','product_in.created_at','users.first_name','users.last_name','suppliers.name','product_in.warehouse as wr')
                    ->get();
            }

        }

        return ['data'=>$receipts];
    }

}
