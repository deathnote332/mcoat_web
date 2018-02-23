<?php

namespace App\Http\Controllers;

use App\Product;
use App\Productin;
use App\Productout;
use App\TempProductout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
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
    public function getProducts(Request $request)
    {

        $data = Product::orderBy('brand', 'asc')->orderBy('description', 'asc')->orderBy('unit', 'asc')->where('status', 1)->get();
        return ['data'=>$data];

    }

    public function addProduct(Request $request){

        $exist = Product::where('brand',$request->brand)
            ->where('category',$request->category)
            ->where('code',$request->code)
            ->where('description',$request->description)
            ->where('unit',$request->unit)
            ->where('unit_price',(double) str_replace(',', '', $request->unit_price))
            ->count();

        if($exist == 1){
            $message = 'Product existed';
        }else{
            $product = new Product();
            $product->brand=$request->brand;
            $product->category=$request->category;
            $product->code=$request->code;
            $product->description=$request->description;
            $product->unit=$request->unit;
            if($request->type == 1){
                $product->quantity=$request->quantity;
            }else{
                $product->quantity_1=$request->quantity;
            }
            $product->unit_price=(double) str_replace(',', '', $request->unit_price);

            $product->save();

            $message = 'Product successfully added';
        }

        return $message;
    }

    public function updateProduct(Request $request){
        $quantity = ($request->type == 1) ? 'quantity' : 'quantity_1';
        Product::where('id',$request->product_id)->update(['brand'=>$request->brand,'category'=>$request->category,
            'code'=>$request->code,'description'=>$request->description,'unit'=>$request->unit,$quantity=>$request->quantity,'unit_price'=>(double) str_replace(',', '', $request->unit_price)]);
        $message = 'Product successfully updated';

        return $message;
    }

    public function deleteProduct(Request $request){
        Product::where('id',$request->id)->update(['status'=>0]);
        $message = 'Product successfully deleted';
        return $message;
    }


    public function productCart(Request $request){

        if ($request->has('receipt_no')) {
            $getCart= DB::table('temp_product_out')->join('tblproducts','temp_product_out.product_id','tblproducts.id')
                ->select('tblproducts.*','temp_product_out.qty as temp_qty','temp_product_out.id as temp_id')
                ->where('temp_product_out.rec_no',$request->receipt_no)
                ->get();
        }else{
            $getCart = TempProductout::join('tblproducts','product_id','tblproducts.id')
                ->select('tblproducts.*','temp_product_out.qty as temp_qty','temp_product_out.id as temp_id')
                ->where('temp_product_out.type',$request->id)
                ->where('temp_product_out.user_id',Auth::user()->id)
                ->get();
        }

      


        return ['data'=>$getCart];
    }


    public function addToCart(Request $request){
        $type = $request->type;
        //product out
        $product_id = $request->id;
        $product_qty = $request->qty;
        $newQty  = $request->current_qty - $product_qty;

        //add to cart
        if($request->has('receipt_no')){

            $this->saveBackupReceipt($request->receipt_no);
            $temp = TempProductout::where('product_id',$product_id)->where('rec_no',$request->receipt_no)->where('user_id',Auth::user()->id)->first();

            if(empty($temp)){
                TempProductout::insert(['product_id'=>$product_id,'user_id'=>Auth::user()->id,'qty'=>$product_qty,'type'=>$type,'rec_no'=>$request->receipt_no]);
                DB::table('product_out_items')->insert(['product_id'=>$product_id,'quantity'=>$product_qty,'receipt_no'=>$request->receipt_no]);
            }else{
                TempProductout::where('product_id',$product_id)->where('rec_no',$request->receipt_no)->where('user_id',Auth::user()->id)->update(['qty'=>$temp->qty + $product_qty]);
                DB::table('product_out_items')->where('product_id',$product_id)->where('receipt_no',$request->receipt_no)->update(['quantity'=>$temp->qty + $product_qty]);
            }



            $total = TempProductout::join('tblproducts','temp_product_out.product_id','tblproducts.id')->select(DB::raw('sum(temp_product_out.qty * tblproducts.unit_price) as total'))->where('rec_no',$request->receipt_no)->first()->total;
            $count = TempProductout::where('rec_no',$request->receipt_no)->count();
            //update receipt
            Productout::where('receipt_no',$request->receipt_no)->update(['total'=>$total]);

        }else{

            $temp = TempProductout::where('product_id',$product_id)->where('type',$type)->where('user_id',Auth::user()->id)->first();

            if(empty($temp)){
                TempProductout::insert(['product_id'=>$product_id,'user_id'=>Auth::user()->id,'qty'=>$product_qty,'type'=>$type]);
            }else{
                TempProductout::where('product_id',$product_id)->where('type',$type)->where('user_id',Auth::user()->id)->update(['qty'=>$temp->qty + $product_qty]);
            }
            $total = number_format(TempProductout::join('tblproducts','temp_product_out.product_id','tblproducts.id')->where('type',$type)->select(DB::raw('sum(temp_product_out.qty * tblproducts.unit_price) as total'))->where('user_id',Auth::user()->id)->first()->total, 2);
            $count = TempProductout::where('type',$type)->where('user_id',Auth::user()->id)->count();
        }


        if($type == 1){
            //minus to the current stock
            Product::where('id',$product_id)->update(['quantity'=>$newQty]);
        }elseif($type == 3){
            Product::where('id',$product_id)->update(['quantity_1'=>$newQty]);
        }
        $message = 'Product successfully added to cart';
        return ["message"=>$message,'count'=>$count,'total'=>number_format($total,2)];
    }

    public function removeToCart(Request $request){
        $type = $request->type;

        $temp_id= $request->temp_id ;
        $product_id= $request->product_id ;
        $qty= $request->qty ;


        if($type == 1){
            //update this product
            $oldqty = Product::find($product_id)->quantity;
            $newQty = $oldqty + $qty;
            Product::where('id',$product_id)->update(['quantity'=>$newQty]);
        }elseif($type == 3){
            $oldqty = Product::find($product_id)->quantity_1;
            $newQty = $oldqty + $qty;
            Product::where('id',$product_id)->update(['quantity_1'=>$newQty]);
        }

        //delete temp
        TempProductout::where('id',$temp_id)->delete();

        if($request->has('receipt_no')){

            $this->saveBackupReceipt($request->receipt_no);

            $check = Productout::where('receipt_no',$request->receipt_no)->count();

            if($check == 0){

                Productout::where('receipt_no',$request->receipt_no)->delete();
            }

            DB::table('product_out_items')->where('product_id',$product_id)->where('receipt_no',$request->receipt_no)->delete();
            $total = number_format(TempProductout::join('tblproducts','temp_product_out.product_id','tblproducts.id')->select(DB::raw('sum(temp_product_out.qty * tblproducts.unit_price) as total'))->where('rec_no',$request->receipt_no)->first()->total, 2);

            $count = TempProductout::where('rec_no',$request->receipt_no)->count();

            Productout::where('receipt_no',$request->receipt_no)->update(['total'=>$total]);


        }else{
            $total = number_format(TempProductout::join('tblproducts','temp_product_out.product_id','tblproducts.id')->where('type',3)->select(DB::raw('sum(temp_product_out.qty * tblproducts.unit_price) as total'))->where('user_id',Auth::user()->id)->first()->total, 2);
            $count = TempProductout::where('type',$type)->where('user_id',Auth::user()->id)->count();

        }


        return ['total'=>$total,'count'=>$count];
    }



    public function printCart(Request $request){

        $type = $request->type;
        $branch_id = $request->branch_id;
        $products = Product::join('temp_product_out','temp_product_out.product_id','tblproducts.id')
            ->select('temp_product_out.qty as temp_qty','tblproducts.*','temp_product_out.id as temp_id')
            ->where('type',$type)
            ->where('temp_product_out.user_id',Auth::user()->id)
            ->get()->chunk(25);

        foreach($products as $key=> $product){
            $id = Productout::orderBy('id','desc')->first()->id + 1;

            if($type == 1){
                $receipt_title = 'MC-';
            }elseif($type == 3){
                $receipt_title = 'AP-';
            }

            $receipt =$receipt_title.date('Y').'-'.str_pad($id, 6, '0', STR_PAD_LEFT);

            // $total = 0;
            foreach ($product as $key=>$val){
                //  $total = $total + $val->temp_qty *  $val->unit_price;
                $temp_id[]=$val->temp_id;
                //insert to product_out_items
                $insertProductoutITems = DB::table('product_out_items')->insert(['product_id'=>$val->id,'quantity'=>$val->temp_qty,'receipt_no'=>$receipt]);
            }
            //delete temp_product_out
            $deleteTempProductout = DB::table('temp_product_out')->wherein('id',$temp_id)->delete();
            $total = DB::table('product_out_items')->join('tblproducts','product_out_items.product_id','tblproducts.id')->where('product_out_items.receipt_no',$receipt)->groupBy('product_out_items.receipt_no')->select(DB::raw('sum(product_out_items.quantity * tblproducts.unit_price) as total'))->first()->total;
            Productout::insert(['receipt_no'=>$receipt,'total'=>$total,'branch'=>$branch_id,'printed_by'=>1,'type'=>$type,'status'=>1,'created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s')]);
            $rec_no[]=$receipt;
        }


        return ["rec_no"=>$rec_no,'count'=>TempProductout::where('type',$type)->where('user_id',Auth::user()->id)->count()];

    }


    public function saveProducts(Request$request){

        $receipt_no = $request->receipt_no;
        $supplier_id = $request->supplier_id;
        $id = Productin::insertGetId(['receipt_no'=>$receipt_no,'supplier_id'=>$supplier_id,'entered_by'=>1,'warehouse'=>$request->type]);

        $getAllinTemp = TempProductout::where('type',$request->type)->where('user_id',Auth::user()->id)->get();

        foreach ($getAllinTemp as $key=>$val) {
            DB::table('product_in_items')->insert(['product_id'=>$val->product_id,'quantity'=>$val->qty,'receipt_no'=>$receipt_no,'product_in_id'=>$id]);

            //getoldqty of product

            if($request->type == 2){
                $oldqty = Product::find($val->product_id)->quantity;
            }else{
                $oldqty = Product::find($val->product_id)->quantity_1;
            }

            $newqty = $oldqty + $val->qty;

            if($request->type == 2){
                Product::where('id',$val->product_id)->update(['quantity'=>$newqty]);
            }else{
                Product::where('id',$val->product_id)->update(['quantity_1'=>$newqty]);
            }

        }
        //delete
        TempProductout::where('type',$request->type)->where('user_id',Auth::user()->id)->delete();

        $count = TempProductout::where('type',$request->type)->where('user_id',Auth::user()->id)->count();
        return ['count'=>$count];

      }


      private function saveBackupReceipt($receipt_no){

          $data = DB::table('product_out_items')->where('receipt_no',$receipt_no)->get();
          $ifExist = DB::table('edited_receipts')->where('receipt_no',$receipt_no)->count();
          if($ifExist == 0){
              Productout::where('receipt_no',$receipt_no)->update(['status'=>2]);
              DB::table('edited_receipts')->insert(['receipt_no'=>$receipt_no,'data'=>json_encode($data),'user_id'=>Auth::user()->id]);

          }
      }
}