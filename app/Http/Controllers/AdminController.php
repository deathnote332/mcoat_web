<?php

namespace App\Http\Controllers;

use App\Products;
use App\Branches;
use App\TempProductout;
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

    public function receiptsPurchase(Request $request)
    {
        return view('admin.purchase-receipts');
    }

    public function productTracking(Request $request)
    {
        return view('admin.product-tracking');
    }
    public function productTrackingIn(Request $request)
    {
        return view('admin.product-tracking-in');
    }
    public function productTrackingDelivery(Request $request)
    {
        return view('admin.product-tracking-delivery');
    }
    public function branchTotalInventory(Request $request)
    {
        $id = $request->id;
        $data['inventory_id'] = $id;
        $inventory = DB::table('total_inventory')->where('id',$id)->first();
       
        if($inventory != null || $inventory != ''){
           if($inventory->is_deleted == 0){
                $data['branch_name'] = Branches::find($inventory->branch_id)->name;
                $data['from_to'] = 'from: '.date('M d,Y',strtotime($inventory->from_date)).' '.'to: '.date('M d,Y',strtotime($inventory->to_date));
                //transfer data to temp_product_out
                $delete = TempProductout::where('type',8)->where('user_id',$inventory->entered_by)->delete();
                $insert =   DB::select("INSERT INTO temp_product_out (product_id, qty, price,unit,type,user_id,rec_no) SELECT product_id,quantity,price,unit,'8','$inventory->entered_by','$id' FROM total_inventory_items WHERE inventory_id = $id ");
           }else{
            $data['inventory_id'] = '';
           }
           
        }else{
            $data['inventory_id'] = '';
        }
       
        return view('admin.inventory',$data);
    }
    public function inventoryList(Request $request)
    {
       
       return view('admin.manage-inventory');
    }
}
