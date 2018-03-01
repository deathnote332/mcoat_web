<?php

namespace App\Http\Controllers;

use App\Branches;
use App\Product;
use App\Productin;
use App\Productout;
use App\Supplier;
use Dompdf\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class ReceiptController extends Controller
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

        $warehouse = ($invoice->type == 1) ? 1 : 2 ;

        if($request->warehouse ==  $warehouse){
            $pdf = PDF::loadView('pdf.invoice',['invoice'=>$data])->setPaper('a4')->setWarnings(false);
            return @$pdf->stream();

        }else{
            abort(503);
        }


    }



    //receipts

    public function getReciepts(Request $request){



        if(Auth::user()->user_type == 1){
            if($request->_range == 'all'){
                $data = Productout::orderBy('product_out.id','desc')
                    ->join('branches','product_out.branch','branches.id')
                    ->join('users','product_out.printed_by','users.id')
                    ->select('product_out.*','users.first_name','users.last_name','branches.name')
                    ->whereIn('product_out.status',[1,2])
                    ->get();
            }elseif($request->_range == 'week'){
                $data = Productout::orderBy('product_out.id','desc')
                    ->where(DB::raw('YEARWEEK(product_out.created_at)'),DB::raw('YEARWEEK(NOW())'))
                    ->join('branches','product_out.branch','branches.id')
                    ->join('users','product_out.printed_by','users.id')
                    ->select('product_out.*','users.first_name','users.last_name','branches.name')
                    ->whereIn('product_out.status',[1,2])
                    ->get();

            }elseif($request->_range == 'today'){
                $data = Productout::orderBy('product_out.id','desc')
                    ->where(DB::raw('DATE(product_out.created_at)'),date('Y-m-d'))
                    ->join('branches','product_out.branch','branches.id')
                    ->join('users','product_out.printed_by','users.id')
                    ->select('product_out.*','users.first_name','users.last_name','branches.name')
                    ->whereIn('product_out.status',[1,2])
                    ->get();

            }elseif($request->_range == 'month'){
                $data = Productout::orderBy('product_out.id','desc')
                    ->where(DB::raw('YEAR(product_out.created_at)'),DB::raw('YEAR(NOW())'))
                    ->where(DB::raw('MONTH(product_out.created_at)'),DB::raw('MONTH(NOW())'))
                    ->join('branches','product_out.branch','branches.id')
                    ->join('users','product_out.printed_by','users.id')
                    ->select('product_out.*','users.first_name','users.last_name','branches.name')
                    ->whereIn('product_out.status',[1,2])
                    ->get();
            }
        }else{
            if(Auth::user()->warehouse == 1){
                $type = 1;
            }elseif(Auth::user()->warehouse == 2){
                $type=3;
            }

            if($request->_range == 'all'){
                $data = Productout::orderBy('product_out.id','desc')
                    ->where('product_out.type',$type)
                    ->where('product_out.printed_by',Auth::user()->id)
                    ->join('branches','product_out.branch','branches.id')
                    ->join('users','product_out.printed_by','users.id')
                    ->select('product_out.*','users.first_name','users.last_name','branches.name')
                    ->whereIn('product_out.status',[1,2])
                    ->get();
            }elseif($request->_range == 'week'){
                $data = Productout::orderBy('product_out.id','desc')
                    ->where('product_out.type',$type)
                    ->where('product_out.printed_by',Auth::user()->id)
                    ->where(DB::raw('WEEKOFYEAR(product_out.created_at)'),DB::raw('WEEKOFYEAR(NOW())'))
                    ->join('branches','product_out.branch','branches.id')
                    ->join('users','product_out.printed_by','users.id')
                    ->select('product_out.*','users.first_name','users.last_name','branches.name')
                    ->whereIn('product_out.status',[1,2])
                    ->get();

            }elseif($request->_range == 'today'){
                $data = Productout::orderBy('product_out.id','desc')
                    ->where('product_out.type',$type)
                    ->where('product_out.printed_by',Auth::user()->id)
                    ->where(DB::raw('DATE(product_out.created_at)'),date('Y-m-d'))
                    ->join('branches','product_out.branch','branches.id')
                    ->join('users','product_out.printed_by','users.id')
                    ->select('product_out.*','users.first_name','users.last_name','branches.name')
                    ->whereIn('product_out.status',[1,2])
                    ->get();
            }elseif($request->_range == 'month'){
                $data = Productout::orderBy('product_out.id','desc')
                    ->where('product_out.type',$type)
                    ->where('product_out.printed_by',Auth::user()->id)
                    ->where(DB::raw('YEAR(product_out.created_at)'),DB::raw('YEAR(NOW())'))
                    ->where(DB::raw('MONTH(product_out.created_at)'),DB::raw('MONTH(NOW())'))
                    ->join('branches','product_out.branch','branches.id')
                    ->join('users','product_out.printed_by','users.id')
                    ->select('product_out.*','users.first_name','users.last_name','branches.name')
                    ->whereIn('product_out.status',[1,2])
                    ->get();
            }

        }
        return compact('data');

    }

    public function getRecieptsIn(Request $request){

        if(Auth::user()->user_type ==1){


            if($request->_range == 'all'){
                $data = Productin::orderBy('product_in.id','desc')
                    ->leftjoin('suppliers','product_in.supplier_id','suppliers.id')
                    ->leftjoin('users','product_in.entered_by','users.id')
                    ->select('product_in.*','users.first_name','users.last_name','suppliers.name','product_in.warehouse as wr')
                    ->get();
            }elseif($request->_range == 'week'){
                $data = Productin::orderBy('product_in.id','desc')
                    ->where(DB::raw('WEEKOFYEAR(product_in.created_at)'),DB::raw('WEEKOFYEAR(NOW())'))
                    ->join('suppliers','product_in.supplier_id','suppliers.id')
                    ->join('users','product_in.entered_by','users.id')
                    ->select('product_in.id','product_in.receipt_no','product_in.created_at','users.first_name','users.last_name','suppliers.name','product_in.warehouse as wr')
                    ->get();

            }elseif($request->_range == 'today'){
                $data = Productin::orderBy('product_in.id','desc')
                    ->where(DB::raw('DATE(product_in.created_at)'),DB::raw('curdate()'))
                    ->join('suppliers','product_in.supplier_id','suppliers.id')
                    ->join('users','product_in.entered_by','users.id')
                    ->select('product_in.id','product_in.receipt_no','product_in.created_at','users.first_name','users.last_name','suppliers.name','product_in.warehouse as wr')
                    ->get();
            }elseif($request->_range == 'month'){
                $data = Productin::orderBy('product_in.id','desc')
                    ->where(DB::raw('YEAR(product_in.created_at)'),DB::raw('YEAR(NOW())'))
                    ->where(DB::raw('MONTH(product_in.created_at)'),DB::raw('MONTH(NOW())'))
                    ->join('suppliers','product_in.supplier_id','suppliers.id')
                    ->join('users','product_in.entered_by','users.id')
                    ->select('product_in.id','product_in.receipt_no','product_in.created_at','users.first_name','users.last_name','suppliers.name','product_in.warehouse as wr')
                    ->get();
            }


        }else{

            if($request->_range == 'all'){
                $data = Productin::orderBy('product_in.id','desc')
                    ->where('product_in.entered_by',Auth::user()->id)
                    ->join('suppliers','product_in.supplier_id','suppliers.id')
                    ->join('users','product_in.entered_by','users.id')
                    ->select('product_in.id','product_in.receipt_no','product_in.created_at','users.first_name','users.last_name','suppliers.name','product_in.warehouse as wr')
                    ->get();
            }elseif($request->_range == 'week'){
                $data = Productin::orderBy('product_in.id','desc')
                    ->where('product_in.entered_by',Auth::user()->id)
                    ->where(DB::raw('WEEKOFYEAR(product_in.created_at)'),DB::raw('WEEKOFYEAR(NOW())'))
                    ->join('suppliers','product_in.supplier_id','suppliers.id')
                    ->join('users','product_in.entered_by','users.id')
                    ->select('product_in.id','product_in.receipt_no','product_in.created_at','users.first_name','users.last_name','suppliers.name','product_in.warehouse as wr')
                    ->get();

            }elseif($request->_range == 'today'){
                $data = Productin::orderBy('product_in.id','desc')
                    ->where('product_in.entered_by',Auth::user()->id)
                    ->where(DB::raw('DATE(product_in.created_at)'),DB::raw('curdate()'))
                    ->join('suppliers','product_in.supplier_id','suppliers.id')
                    ->join('users','product_in.entered_by','users.id')
                    ->select('product_in.id','product_in.receipt_no','product_in.created_at','users.first_name','users.last_name','suppliers.name','product_in.warehouse as wr')
                    ->get();
            }elseif($request->_range == 'month'){
                $data = Productin::orderBy('product_in.id','desc')
                    ->where('product_in.entered_by',Auth::user()->id)
                    ->where(DB::raw('YEAR(product_in.created_at)'),DB::raw('YEAR(NOW())'))
                    ->where(DB::raw('MONTH(product_in.created_at)'),DB::raw('MONTH(NOW())'))
                    ->join('suppliers','product_in.supplier_id','suppliers.id')
                    ->join('users','product_in.entered_by','users.id')
                    ->select('product_in.id','product_in.receipt_no','product_in.created_at','users.first_name','users.last_name','suppliers.name','product_in.warehouse as wr')
                    ->get();
            }

        }

        return compact('data');
    }


    public function deleteReceipt(Request $request){

        $this->saveBackupReceipt($request->rec_no);
        //back all data first
        $product_items  = DB::table('product_out_items')->where('receipt_no',$request->rec_no)->get();

        foreach ($product_items as $key => $val){

           $old_qty =  Product::where('id',$val->product_id)->first();
           $newqty = 0;
           if($request->type ==1){
               $newqty = $old_qty->quantity + $val->quantity;
               Product::where('id',$val->product_id)->update(['quantity'=>$newqty]);
           }else{
               $newqty = $old_qty->quantity_1 + $val->quantity;
               Product::where('id',$val->product_id)->update(['quantity_1'=>$newqty]);
           }
        }

        $delete_items = DB::table('product_out_items')->where('receipt_no',$request->rec_no)->delete();
        Productout::where('receipt_no',$request->rec_no)->update(['status'=>0]);

    }

    private function saveBackupReceipt($receipt_no){

        $data = DB::table('product_out_items')->where('receipt_no',$receipt_no)->get();
        $ifExist = DB::table('edited_receipts')->where('receipt_no',$receipt_no)->where('type',2)->count();
        if($ifExist == 0){
            DB::table('edited_receipts')->insert(['receipt_no'=>$receipt_no,'data'=>json_encode($data),'user_id'=>Auth::user()->id,'type'=>2]);
        }
    }

    public function receiptInInovice(Request $request){

        $producin_items = DB::table('product_in_items')->join('tblproducts','tblproducts.id','product_in_items.product_id')->select('tblproducts.*','product_in_items.quantity as product_qty')->where('product_in_items.product_in_id',$request->id)->get();

        $invoice = Productin::where('id',$request->id)->first();

        $supplier = Supplier::find($invoice->supplier_id);

        $data =['receipt_no'=>$invoice->receipt_no,'name'=>$supplier->name,'address'=>$supplier->address,'warehouse'=>$invoice->warehouse,'entered_by'=>$invoice->entered_by,'created_at'=>date('M d,Y',strtotime($invoice->created_at)),'products'=>$producin_items];
        $pdf = PDF::loadView('pdf.receiptin',['invoice'=>$data])->setPaper('a4')->setWarnings(false);
        return @$pdf->stream();
    }

}
