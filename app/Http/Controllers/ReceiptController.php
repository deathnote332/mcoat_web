<?php

namespace App\Http\Controllers;

use App\Branches;
use App\Product;
use App\Productin;
use App\Productout;
use App\Supplier;
use App\TempProductout;
use App\User;
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

        $products = DB::table('product_out_items')->join('tblproducts','tblproducts.id','product_out_items.product_id')->select('tblproducts.*','product_out_items.quantity as product_qty','product_out_items.unit_price as p_items_price')->where('receipt_no',$request->id)->get();
        $user_edited = "";
        $date_edited = "";
        if($invoice->status == 2){
            $getUsers = DB::table('edited_receipts')->where('receipt_no',$invoice->receipt_no)->first();
            $users = json_decode($getUsers->user_id,TRUE);
            $name = User::where('id',$users[count($users) - 1])->first();
            $user_edited = $name->first_name.' '.$name->last_name;
            $date_edited = $getUsers->updated_at;
        }

        $data =['warehouse'=>$request->warehouse,'total'=>$invoice->total,'updated_at'=>$invoice->updated_at,'status'=>$invoice->status,'receipt_no'=>$invoice->receipt_no,'name'=>$invoice->first_name.' '.$invoice->last_name,'address'=>$invoice->address,'branch_name'=>$invoice->branch_name,'created_at'=>date('M d,Y',strtotime($invoice->created_at)),'products'=>$products,'view'=>$request->view,'user_edited'=>$user_edited,'date_edited'=>date('M d,Y',strtotime($date_edited))];

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
                    ->select('product_in.*','users.first_name','users.last_name','suppliers.name')
                    ->get();

            }elseif($request->_range == 'week'){
                $data = Productin::orderBy('product_in.id','desc')
                    ->where(DB::raw('WEEKOFYEAR(product_in.created_at)'),DB::raw('WEEKOFYEAR(NOW())'))
                    ->join('suppliers','product_in.supplier_id','suppliers.id')
                    ->join('users','product_in.entered_by','users.id')
                    ->select('product_in.*','users.first_name','users.last_name','suppliers.name')
                    ->get();

            }elseif($request->_range == 'today'){
                $data = Productin::orderBy('product_in.id','desc')
                    ->where(DB::raw('DATE(product_in.created_at)'),date('Y-m-d'))
                    ->join('suppliers','product_in.supplier_id','suppliers.id')
                    ->join('users','product_in.entered_by','users.id')
                    ->select('product_in.id','product_in.receipt_no','product_in.created_at','users.first_name','users.last_name','suppliers.name')
                    ->get();
            }elseif($request->_range == 'month'){
                $data = Productin::orderBy('product_in.id','desc')
                    ->where(DB::raw('YEAR(product_in.created_at)'),DB::raw('YEAR(NOW())'))
                    ->where(DB::raw('MONTH(product_in.created_at)'),DB::raw('MONTH(NOW())'))
                    ->join('suppliers','product_in.supplier_id','suppliers.id')
                    ->join('users','product_in.entered_by','users.id')
                    ->select('product_in.*','users.first_name','users.last_name','suppliers.name')
                    ->get();
            }


        }else{

            if($request->_range == 'all'){
                $data = Productin::orderBy('product_in.id','desc')
                    ->where('product_in.entered_by',Auth::user()->id)
                    ->join('suppliers','product_in.supplier_id','suppliers.id')
                    ->join('users','product_in.entered_by','users.id')
                    ->select('product_in.*','users.first_name','users.last_name','suppliers.name')
                    ->get();

            }elseif($request->_range == 'week'){
                $data = Productin::orderBy('product_in.id','desc')
                    ->where('product_in.entered_by',Auth::user()->id)
                    ->where(DB::raw('WEEKOFYEAR(product_in.created_at)'),DB::raw('WEEKOFYEAR(NOW())'))
                    ->join('suppliers','product_in.supplier_id','suppliers.id')
                    ->join('users','product_in.entered_by','users.id')
                    ->select('product_in.*','users.first_name','users.last_name','suppliers.name')
                    ->get();

            }elseif($request->_range == 'today'){
                $data = Productin::orderBy('product_in.id','desc')
                    ->where('product_in.entered_by',Auth::user()->id)
                    ->where(DB::raw('DATE(product_in.created_at)'),date('Y-m-d'))
                    ->join('suppliers','product_in.supplier_id','suppliers.id')
                    ->join('users','product_in.entered_by','users.id')
                    ->select('product_in.*','users.first_name','users.last_name','suppliers.name')
                    ->get();
            }elseif($request->_range == 'month'){
                $data = Productin::orderBy('product_in.id','desc')
                    ->where('product_in.entered_by',Auth::user()->id)
                    ->where(DB::raw('YEAR(product_in.created_at)'),DB::raw('YEAR(NOW())'))
                    ->where(DB::raw('MONTH(product_in.created_at)'),DB::raw('MONTH(NOW())'))
                    ->join('suppliers','product_in.supplier_id','suppliers.id')
                    ->join('users','product_in.entered_by','users.id')
                    ->select('product_in.*','users.first_name','users.last_name','suppliers.name')
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


    public function getStockExchange(Request $request){

        if(Auth::user()->user_type ==1){

            if($request->_range == 'all'){
                $data = DB::table('stock_exchange')
                    ->join('branches as b1','stock_exchange.from_branch','b1.id')
                    ->join('branches as b2','stock_exchange.to_branch','b2.id')
                    ->join('users','stock_exchange.user_id','users.id')
                    ->select('b1.name as from','b2.name as to','users.first_name','users.last_name','stock_exchange.*')
                    ->orderBy('stock_exchange.id','desc')
                    ->get();

            }elseif($request->_range == 'week'){
                $data = DB::table('stock_exchange')
                    ->where(DB::raw('WEEKOFYEAR(stock_exchange.created_at)'),DB::raw('WEEKOFYEAR(NOW())'))
                    ->join('branches as b1','stock_exchange.from_branch','b1.id')
                    ->join('branches as b2','stock_exchange.to_branch','b2.id')
                    ->join('users','stock_exchange.user_id','users.id')
                    ->select('b1.name as from','b2.name as to','users.first_name','users.last_name','stock_exchange.*')
                    ->orderBy('stock_exchange.id','desc')
                    ->get();

            }elseif($request->_range == 'today'){
                $data = DB::table('stock_exchange')
                    ->where(DB::raw('DATE(stock_exchange.created_at)'),date('Y-m-d'))
                    ->join('branches as b1','stock_exchange.from_branch','b1.id')
                    ->join('branches as b2','stock_exchange.to_branch','b2.id')
                    ->join('users','stock_exchange.user_id','users.id')
                    ->select('b1.name as from','b2.name as to','users.first_name','users.last_name','stock_exchange.*')
                    ->orderBy('stock_exchange.id','desc')
                    ->get();
            }elseif($request->_range == 'month'){
                $data = DB::table('stock_exchange')
                    ->where(DB::raw('YEAR(stock_exchange.created_at)'),DB::raw('YEAR(NOW())'))
                    ->where(DB::raw('MONTH(stock_exchange.created_at)'),DB::raw('MONTH(NOW())'))
                    ->join('branches as b1','stock_exchange.from_branch','b1.id')
                    ->join('branches as b2','stock_exchange.to_branch','b2.id')
                    ->join('users','stock_exchange.user_id','users.id')
                    ->select('b1.name as from','b2.name as to','users.first_name','users.last_name','stock_exchange.*')
                    ->orderBy('stock_exchange.id','desc')
                    ->get();
            }


        }else{

            if($request->_range == 'all'){
                $data = DB::table('stock_exchange')
                    ->where('stock_exchange.user_id',Auth::user()->id)
                    ->join('branches as b1','stock_exchange.from_branch','b1.id')
                    ->join('branches as b2','stock_exchange.to_branch','b2.id')
                    ->join('users','stock_exchange.user_id','users.id')
                    ->select('b1.name as from','b2.name as to','users.first_name','users.last_name','stock_exchange.*')
                    ->orderBy('stock_exchange.id','desc')
                    ->get();

            }elseif($request->_range == 'week'){
                $data =  DB::table('stock_exchange')
                    ->where('stock_exchange.user_id',Auth::user()->id)
                    ->where(DB::raw('WEEKOFYEAR(stock_exchange.created_at)'),DB::raw('WEEKOFYEAR(NOW())'))
                    ->join('branches as b1','stock_exchange.from_branch','b1.id')
                    ->join('branches as b2','stock_exchange.to_branch','b2.id')
                    ->join('users','stock_exchange.user_id','users.id')
                    ->select('b1.name as from','b2.name as to','users.first_name','users.last_name','stock_exchange.*')
                    ->orderBy('stock_exchange.id','desc')
                    ->get();

            }elseif($request->_range == 'today'){
                $data = DB::table('stock_exchange')
                    ->where('stock_exchange.user_id',Auth::user()->id)
                    ->where(DB::raw('DATE(stock_exchange.created_at)'),date('Y-m-d'))
                    ->join('branches as b1','stock_exchange.from_branch','b1.id')
                    ->join('branches as b2','stock_exchange.to_branch','b2.id')
                    ->join('users','stock_exchange.user_id','users.id')
                    ->select('b1.name as from','b2.name as to','users.first_name','users.last_name','stock_exchange.*')
                    ->orderBy('stock_exchange.id','desc')
                    ->get();
            }elseif($request->_range == 'month'){
                $data = DB::table('stock_exchange')
                    ->where('stock_exchange.user_id',Auth::user()->id)
                    ->where(DB::raw('YEAR(stock_exchange.created_at)'),DB::raw('YEAR(NOW())'))
                    ->where(DB::raw('MONTH(stock_exchange.created_at)'),DB::raw('MONTH(NOW())'))
                    ->join('branches as b1','stock_exchange.from_branch','b1.id')
                    ->join('branches as b2','stock_exchange.to_branch','b2.id')
                    ->join('users','stock_exchange.user_id','users.id')
                    ->select('b1.name as from','b2.name as to','users.first_name','users.last_name','stock_exchange.*')
                    ->orderBy('stock_exchange.id','desc')
                    ->get();
            }

        }

        return compact('data');
    }

    public function invoiceStockExchange(Request $request){

        $invoice = DB::table('stock_exchange')->where('id',$request->id)->first();

        $data =['from_branch'=>$invoice->from_branch,'to_branch'=>$invoice->to_branch,'products'=>$invoice->data,'user_id'=>$invoice->user_id,'receipt_no'=>$invoice->receipt_no,'created_at'=>$invoice->created_at];



        $pdf = PDF::loadView('pdf.stock',['invoice'=>$data])->setPaper('a4')->setWarnings(false);
        return @$pdf->stream();



    }


    public function savePurchaseOrder(Request $request){
        $data = DB::table('temp_product_out')->where('type',7)->where('user_id',Auth::user()->id)->get();
        $purchase_order = DB::table('purchase_order')->insertGetId(['data'=>$data,'supplier'=>$request->supplier,'branch'=>$request->branch,'created_at'=>date('Y-m-d')]);
       $delete = DB::table('temp_product_out')->where('type',7)->where('user_id',Auth::user()->id)->delete();
        return $purchase_order;
    }





    public function invoicePurchaseOrder(Request $request){

        $getData = DB::table('purchase_order')->where('id',$request->id)->first();

        if($getData != null){
            $data = ['products'=>$getData->data,'supplier'=>$getData->supplier,'branch'=>$getData->branch,'date_printed'=>date('M d,Y',strtotime($getData->created_at))];
            $pdf = PDF::loadView('pdf.purchase-order',['invoice'=>$data])->setPaper('a4')->setWarnings(false);
            return @$pdf->stream();
        }else{
            abort(503);
        }


    }

    public function getPurchaseOrder(Request $request){

        if($request->_range == 'all'){
            $data = DB::table('purchase_order')
                ->join('branches','purchase_order.branch','branches.id')
                ->join('suppliers','purchase_order.supplier','suppliers.id')
                ->select('suppliers.name as supplier_name','branches.name as branch_name','purchase_order.*')
                ->orderBy('purchase_order.id','desc')
                ->get();

        }elseif($request->_range == 'week'){
            $data = DB::table('purchase_order')
                ->where(DB::raw('WEEKOFYEAR(purchase_order.created_at)'),DB::raw('WEEKOFYEAR(NOW())'))
                ->join('branches','purchase_order.branch','branches.id')
                ->join('suppliers','purchase_order.supplier','suppliers.id')
                ->select('suppliers.name as supplier_name','branches.name as branch_name','purchase_order.*')
                ->orderBy('purchase_order.id','desc')
                ->get();

        }elseif($request->_range == 'today'){
            $data = DB::table('purchase_order')
                ->where(DB::raw('DATE(purchase_order.created_at)'),date('Y-m-d'))
                ->join('branches','purchase_order.branch','branches.id')
                ->join('suppliers','purchase_order.supplier','suppliers.id')
                ->select('suppliers.name as supplier_name','branches.name as branch_name','purchase_order.*')
                ->orderBy('purchase_order.id','desc')
                ->get();
        }elseif($request->_range == 'month'){
            $data = DB::table('purchase_order')
                ->where(DB::raw('YEAR(purchase_order.created_at)'),DB::raw('YEAR(NOW())'))
                ->where(DB::raw('MONTH(purchase_order.created_at)'),DB::raw('MONTH(NOW())'))
                ->join('branches','purchase_order.branch','branches.id')
                ->join('suppliers','purchase_order.supplier','suppliers.id')
                ->select('suppliers.name as supplier_name','branches.name as branch_name','purchase_order.*')
                ->orderBy('purchase_order.id','desc')
                ->get();
        }

        return compact('data');
    }



    public function editStockReceipt(Request $request)
    {
        //move product_out items into temp_product_out


        //delete first if data existed
        $temp_product_out = DB::table('temp_product_out')->where('rec_no',$request->receipt_no)->delete();
        //get all receipt items
        $product_out_items = DB::table('stock_exchange')->where('receipt_no',$request->receipt_no)->first();
        //insert to temp but don't delete original data
        //type 5 for editing receipt
        foreach (json_decode($product_out_items->data,TRUE) as $key=>$val){
            DB::table('temp_product_out')->insert(['product_id'=>$val['product_id'],'qty'=>$val['qty'],'type'=>6,'user_id'=>Auth::user()->id,'rec_no'=>$product_out_items->receipt_no,'unit'=>$val['unit'],'price'=>$val['price']]);
        }
        return view('admin.edit-stock-exchange',['warehouse'=>$request->warehouse,'receipt_no'=>$request->receipt_no,'receipt_id'=>$product_out_items->id]);
    }


    public function saveEditStockReceipt(Request $request){

        $products = TempProductout::where('rec_no',$request->receipt_no)->get();

        $update = DB::table('stock_exchange')->where('id',$request->id)->update(['data'=>json_encode($products)]);

        $temp_product_out = DB::table('temp_product_out')->where('rec_no',$request->receipt_no)->delete();
        $cart = TempProductout::where('type',6)->where('rec_no',$request->receipt_no)->count();
        return ['id'=>$request->id,'count'=>$cart];
    }


    public function monthlySales(Request $request){
        $start_date = $request->year.'-'.$request->month.'-1';
        $end_date = date('t',strtotime($start_date));
        $month = $request->month;
        $year = $request->year;
        $branch = $request->branch_id;
        $pdf = PDF::loadView('pdf.monthly-sales',['start_date'=>$start_date,'end_date'=>$end_date,'month'=>$month,'year'=>$year,'branch'=>$branch])->setPaper('a4')->setWarnings(false);
        return @$pdf->stream();
    }


    public function editPurchaseReceipt(Request $request)
    {

        $po = DB::table('temp_product_out')->where('user_id',Auth::user()->id)->where('rec_no',$request->id)->delete();

        $po = DB::table('purchase_order')->where('id',$request->id)->first();
        foreach (json_decode($po->data,TRUE) as $key=>$val){
            DB::table('temp_product_out')->insert(['product_id'=>$val['product_id'],'qty'=>$val['qty'],'type'=>7,'user_id'=>Auth::user()->id,'rec_no'=>$po->id,'unit'=>$val['unit']]);
        }

         return view('admin.edit-purchase-order',['receipt_no'=>$po->id]);
    }


    public function saveEditPurchaseReceipt(Request $request){

        $products = TempProductout::where('rec_no',$request->id)->where('user_id',Auth::user()->id)->get();

        $update = DB::table('purchase_order')->where('id',$request->id)->update(['data'=>json_encode($products),'supplier'=>$request->supplier,'branch'=>$request->branch]);

        $temp_product_out = DB::table('temp_product_out')
            ->where('user_id',Auth::user()->id)
            ->where('rec_no',$request->id)->delete();

        $cart = TempProductout::where('type',7)->where('user_id',Auth::user()->id)->where('rec_no',$request->id)->count();
        return ['id'=>$request->id,'count'=>$cart];
    }
}
