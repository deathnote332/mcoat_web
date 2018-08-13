<?php

namespace App\Http\Controllers;

use App\Notifications;
use App\Product;
use App\Productin;
use App\Productout;
use App\Supplier;
use App\TempProductout;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use DataTables;

class ProductController extends Controller
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
    public function getProducts()
    {
        $data = Product::orderBy('brand')->orderBy('category')->orderBy('description')->orderBy('unit')->where('status',1);
        return Datatables::of($data)->make(true);


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
            $warehouse = ($request->type == 1) ? 'MCOAT WAREHOUSE' : 'ALLIED WAREHOUSE';
            $product = 'Brand:'.$request->brand.' Category:'.$request->category.' Code:'.$request->code.' Description:'.$request->description.' Unit:'.$request->unit.' Quantity: '.$request->quantity.' Unit Price:'.$request->unit_price ;
            $noti_message = Auth::user()->first_name.' '.Auth::user()->last_name.' added new product ('.$product.') in '.$warehouse;
            Notifications::insert(['message'=>$noti_message,'created_at'=>date('Y-m-d')]);
        }

        return $message;
    }

    public function updateProduct(Request $request){
        $quantity = ($request->type == 1) ? 'quantity' : 'quantity_1';
        Product::where('id',$request->product_id)->update(['brand'=>$request->brand,'category'=>$request->category,
            'code'=>$request->code,'description'=>$request->description,'unit'=>$request->unit,$quantity=>$request->quantity,'unit_price'=>(double) str_replace(',', '', $request->unit_price)]);
        $message = 'Product successfully updated';

        //notification / logs
        $warehouse = ($request->type == 1) ? 'MCOAT WAREHOUSE' : 'ALLIED WAREHOUSE';
        $product = 'Brand:'.$request->brand.' Category:'.$request->category.' Code:'.$request->code.' Description:'.$request->description.' Unit:'.$request->unit.' Quantity: '.$request->quantity.' Unit Price:'.$request->unit_price ;
        $noti_message = Auth::user()->first_name.' '.Auth::user()->last_name.' updated product ('.$product.') in '.$warehouse;
        Notifications::insert(['message'=>$noti_message,'created_at'=>date('Y-m-d')]);

        return $message;
    }

    public function deleteProduct(Request $request){
        Product::where('id',$request->id)->update(['status'=>0]);
        $message = 'Product successfully deleted';

        $product = Product::find($request->id);
        $products = 'Brand:'.$product->brand.' Category:'.$product->category.' Code:'.$product->code.' Description:'.$product->description.' Unit:'.$product->unit.' Quantity: '.$product->quantity.' Unit Price:'.$product->unit_price ;
        $noti_message = Auth::user()->first_name.' '.Auth::user()->last_name.' deleted product ('.$products.')';
        Notifications::insert(['message'=>$noti_message,'created_at'=>date('Y-m-d')]);

        return $message;
    }


    public function productCart(Request $request){
      
        if ($request->has('receipt_no')) {
                if ($request->id == 8) {
                    $inventory = DB::table('total_inventory')->where('id',$request->receipt_no)->first();

                    $data= DB::table('temp_product_out')->join('tblproducts','temp_product_out.product_id','tblproducts.id')
                    ->select('tblproducts.*','temp_product_out.qty as temp_qty','temp_product_out.id as temp_id','temp_product_out.unit as temp_unit','temp_product_out.price as temp_price')
                    ->where('temp_product_out.rec_no',$request->receipt_no)
                    ->where('temp_product_out.user_id',$inventory->entered_by)
                    ->get();
                }else{
                    $data= DB::table('temp_product_out')->join('tblproducts','temp_product_out.product_id','tblproducts.id')
                    ->select('tblproducts.*','temp_product_out.qty as temp_qty','temp_product_out.id as temp_id','temp_product_out.unit as temp_unit','temp_product_out.price as temp_price')
                    ->where('temp_product_out.rec_no',$request->receipt_no)
                    ->where('temp_product_out.user_id',Auth::user()->id)
                    ->get();
                }

        }else{
           
            $data = TempProductout::join('tblproducts','product_id','tblproducts.id')
                ->select('tblproducts.*','temp_product_out.qty as temp_qty','temp_product_out.id as temp_id','temp_product_out.price as temp_price','temp_product_out.unit as temp_unit')
                ->where('temp_product_out.type',$request->id)
                ->where('temp_product_out.rec_no',0)
                ->where('temp_product_out.user_id',Auth::user()->id)
                ->get();
        }
       
        
        return compact('data');
    }


    public function addToCart(Request $request){
        $type = $request->type;


        //product out
        $product_id = $request->id;
        $product_qty = $request->qty;
        $newQty  = $request->current_qty - $product_qty;

        //add to cart
        if($request->has('receipt_no')){
          
            if($type == 8){
                $temp = TempProductout::where('product_id',$product_id)->where('unit',$request->unit)->where('rec_no',$request->receipt_no)->first();
                $inventory = DB::table('total_inventory')->where('id',$request->receipt_no)->first();
                if(empty($temp)){
                    TempProductout::insert(['product_id'=>$product_id,'user_id'=>$inventory->entered_by,'qty'=>$product_qty,'type'=>$type,'rec_no'=>$request->receipt_no,'unit'=>$request->unit,'price'=>$request->price]);
                }else{
                    TempProductout::where('product_id',$product_id)->where('rec_no',$request->receipt_no)->update(['qty'=>$temp->qty + $product_qty]);
                }
                $total = TempProductout::select(DB::raw('sum(temp_product_out.qty * temp_product_out.price) as total'))->where('user_id',$inventory->entered_by)->where('rec_no',$request->receipt_no)->first()->total;
                $count = TempProductout::where('rec_no',$request->receipt_no)->where('user_id',$inventory->entered_by)->count();
                
            }else{

                if($type == 6 || $type == 7 ) {
                    $this->saveBackupReceipt($request->receipt_no);
               
                    $temp = TempProductout::where('product_id',$product_id)->where('unit',$request->unit)->where('rec_no',$request->receipt_no)->where('user_id',Auth::user()->id)->first();
                  
                    if(empty($temp)){
                        TempProductout::insert(['product_id'=>$product_id,'user_id'=>Auth::user()->id,'qty'=>$product_qty,'type'=>$type,'rec_no'=>$request->receipt_no,'unit'=>$request->unit,'price'=>$request->price]);
                   }else{
                        TempProductout::where('product_id',$product_id)->where('rec_no',$request->receipt_no)->where('user_id',Auth::user()->id)->update(['qty'=>$temp->qty + $product_qty]);
                    }
                }else{
                    $temp = TempProductout::where('product_id',$product_id)->where('rec_no',$request->receipt_no)->where('user_id',Auth::user()->id)->first();
                  
                    if(empty($temp)){
                        TempProductout::insert(['product_id'=>$product_id,'user_id'=>Auth::user()->id,'qty'=>$product_qty,'type'=>$type,'rec_no'=>$request->receipt_no]);
                        DB::table('product_out_items')->insert(['product_id'=>$product_id,'quantity'=>$product_qty,'receipt_no'=>$request->receipt_no]);
                    }else{
                        TempProductout::where('product_id',$product_id)->where('rec_no',$request->receipt_no)->where('user_id',Auth::user()->id)->update(['qty'=>$temp->qty + $product_qty]);
                        DB::table('product_out_items')->where('product_id',$product_id)->where('receipt_no',$request->receipt_no)->update(['quantity'=>$temp->qty + $product_qty]);
                    }
    
                }
    
    
                $total = TempProductout::join('tblproducts','temp_product_out.product_id','tblproducts.id')->select(DB::raw('sum(temp_product_out.qty * tblproducts.unit_price) as total'))->where('rec_no',$request->receipt_no)->first()->total;
                $count = TempProductout::where('rec_no',$request->receipt_no)->count();
                //update receipt
                Productout::where('receipt_no',$request->receipt_no)->update(['total'=>$total]);
            }
           
            

        }else{
            if($type == 8 ){
                $temp = TempProductout::where('product_id',$product_id)
                ->where('unit',$request->unit)
                ->where('rec_no',0)
                ->where('type',$type)->where('user_id',Auth::user()->id)->first();

                if(empty($temp)){
                    TempProductout::insert(['product_id'=>$product_id,'user_id'=>Auth::user()->id,'qty'=>$product_qty,'type'=>$type,'unit'=>$request->unit,'price'=>$request->price]);
                }else{
                    TempProductout::where('product_id',$product_id)->where('rec_no',0)->where('unit',$request->unit)->where('type',$type)->where('user_id',Auth::user()->id)->update(['qty'=>$temp->qty + $product_qty]);
                }
                $total = TempProductout::select(DB::raw('sum(qty * price) as total'))->where('type',$type)->where('rec_no',0)->where('user_id',Auth::user()->id)->first()->total;
                $count = TempProductout::where('type',$type)->where('user_id',Auth::user()->id)->where('rec_no',0)->count();


            }else{

                if($type == 6 || $type == 7 ){

                    $temp = TempProductout::where('product_id',$product_id)
                        ->where('unit',$request->unit)
                        ->where('type',$type)->where('user_id',Auth::user()->id)->first();
    
                    if(empty($temp)){
                        TempProductout::insert(['product_id'=>$product_id,'user_id'=>Auth::user()->id,'qty'=>$product_qty,'type'=>$type,'unit'=>$request->unit,'price'=>$request->price]);
                    }else{
                        TempProductout::where('product_id',$product_id)->where('unit',$request->unit)->where('type',$type)->where('user_id',Auth::user()->id)->update(['qty'=>$temp->qty + $product_qty]);
                    }
                    $total = TempProductout::select(DB::raw('sum(qty * price) as total'))->where('user_id',Auth::user()->id)->where('rec_no',0)->first()->total;
                    $count = TempProductout::where('type',$type)->where('user_id',Auth::user()->id)->where('rec_no',0)->count();
    
    
                }else{
    
                    $temp = TempProductout::where('product_id',$product_id)->where('type',$type)->where('user_id',Auth::user()->id)->first();
    
                    if(empty($temp)){
                        TempProductout::insert(['product_id'=>$product_id,'user_id'=>Auth::user()->id,'qty'=>$product_qty,'type'=>$type]);
                    }else{
                        TempProductout::where('product_id',$product_id)->where('type',$type)->where('user_id',Auth::user()->id)->update(['qty'=>$temp->qty + $product_qty]);
                    }
                    $total = TempProductout::join('tblproducts','temp_product_out.product_id','tblproducts.id')->where('type',$type)->select(DB::raw('sum(temp_product_out.qty * tblproducts.unit_price) as total'))->where('user_id',Auth::user()->id)->first()->total;
                    $count = TempProductout::where('type',$type)->where('user_id',Auth::user()->id)->count();
    
                }
            }

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
            if($request->receipt_no != ''){
                if($type == 8){

                    $total = TempProductout::select(DB::raw('sum(temp_product_out.qty * temp_product_out.price) as total'))->where('rec_no',$request->receipt_no)->first()->total;
                    $count = TempProductout::where('rec_no',$request->receipt_no)->count();
                   
                }else{
                    $this->saveBackupReceipt($request->receipt_no);
    
                    $check = Productout::where('receipt_no',$request->receipt_no)->count();
        
                    if($check == 0){
        
                        Productout::where('receipt_no',$request->receipt_no)->delete();
                    }
        
                    DB::table('product_out_items')->where('product_id',$product_id)->where('receipt_no',$request->receipt_no)->delete();
                    $total = TempProductout::join('tblproducts','temp_product_out.product_id','tblproducts.id')->select(DB::raw('sum(temp_product_out.qty * tblproducts.unit_price) as total'))->where('rec_no',$request->receipt_no)->first()->total;
        
                    $count = TempProductout::where('rec_no',$request->receipt_no)->count();
        
                    Productout::where('receipt_no',$request->receipt_no)->update(['total'=>$total]);
                }
            }else{
                $total = number_format(TempProductout::where('type',8)->where('rec_no',0)->select(DB::raw('sum(temp_product_out.qty * temp_product_out.price) as total'))->where('user_id',Auth::user()->id)->first()->total, 2);
                $count = TempProductout::where('type',$type)->where('rec_no',0)->where('user_id',Auth::user()->id)->count();
            }
            
        }else{
            if($type == 8){
                $total = number_format(TempProductout::where('type',8)->where('rec_no',0)->select(DB::raw('sum(temp_product_out.qty * temp_product_out.price) as total'))->where('user_id',Auth::user()->id)->first()->total, 2);
                $count = TempProductout::where('type',$type)->where('rec_no',0)->where('user_id',Auth::user()->id)->count();
    
            }else{
                $total = number_format(TempProductout::join('tblproducts','temp_product_out.product_id','tblproducts.id')->where('type',3)->select(DB::raw('sum(temp_product_out.qty * tblproducts.unit_price) as total'))->where('user_id',Auth::user()->id)->first()->total, 2);
                $count = TempProductout::where('type',$type)->where('user_id',Auth::user()->id)->count();
    
            }
          
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

            foreach ($product as $key=>$val){
                $temp_id[]=$val->temp_id;
                //insert to product_out_items
                $insertProductoutITems = DB::table('product_out_items')->insert(['product_id'=>$val->id,'quantity'=>$val->temp_qty,'receipt_no'=>$receipt,'unit_price'=>$val->unit_price]);
            }
            $deleteTempProductout = DB::table('temp_product_out')->wherein('id',$temp_id)->delete();
            $total = DB::table('product_out_items')->join('tblproducts','product_out_items.product_id','tblproducts.id')->where('product_out_items.receipt_no',$receipt)->groupBy('product_out_items.receipt_no')->select(DB::raw('sum(product_out_items.quantity * tblproducts.unit_price) as total'))->first()->total;
            Productout::insert(['receipt_no'=>$receipt,'total'=>$total,'branch'=>$branch_id,'printed_by'=>Auth::user()->id,'type'=>$type,'status'=>1,'created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s')]);
            $rec_no[]=$receipt;
        }
        //notification
        $user = Auth::user()->first_name.' '.Auth::user()->last_name;
        Notifications::insert(['message'=>$user.' printed delivery receipt/s '.implode(",",$rec_no).'','created_at'=>date('Y-m-d')]);

        return ["rec_no"=>$rec_no,'count'=>TempProductout::where('type',$type)->where('user_id',Auth::user()->id)->count()];

    }


    public function saveProducts(Request$request){

        $receipt_no = $request->receipt_no;
        $supplier_id = $request->supplier_id;
        $id = Productin::insertGetId(['receipt_no'=>$receipt_no,'supplier_id'=>$supplier_id,'entered_by'=>Auth::user()->id,'warehouse'=>$request->type,'created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s')]);

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

        //notification
        $warehouse = ($request->type == 2) ? 'MCOAT WAREHOUSE' : 'ALLIED WAREHOUSE';

        $user = Auth::user()->first_name.' '.Auth::user()->last_name;
        Notifications::insert(['message'=>$user.' entered delivery receipt/s '.$receipt_no.' from '.Supplier::find($supplier_id)->name.' to '.$warehouse,'created_at'=>date('Y-m-d')]);


        $count = TempProductout::where('type',$request->type)->where('user_id',Auth::user()->id)->count();
        return ['count'=>$count];

      }


      private function saveBackupReceipt($receipt_no){

          $data = DB::table('product_out_items')->where('receipt_no',$receipt_no)->get();
          $ifExist = DB::table('edited_receipts')->where('receipt_no',$receipt_no)->count();
          if($ifExist == 0){
              $users[] = Auth::user()->id;
              Productout::where('receipt_no',$receipt_no)->update(['status'=>2]);
              DB::table('edited_receipts')->insert(['receipt_no'=>$receipt_no,'data'=>json_encode($data),'user_id'=>json_encode($users)]);

          }else{

               $getUsers = DB::table('edited_receipts')->where('receipt_no',$receipt_no)->first();
               if(!in_array(Auth::user()->id,json_decode($getUsers->user_id,TRUE))){
                   $users  = json_decode($getUsers->user_id,TRUE);
                   $users[] = Auth::user()->id;
                   DB::table('edited_receipts')->where('receipt_no',$receipt_no)->update(['user_id'=>json_encode($users)]);
               }
          }
      }



    public function getCategory(Request $request){

        $products = Product::where('brand', $request->brand)
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->get();

        return view('ajax.category', ['data'=>$products]);
    }

    public function deleteTempEditCart(Request $request){
        Productout::where('receipt_no',$request->rec_no)->update(['branch'=>$request->branch]);
        TempProductout::where('rec_no',$request->rec_no)->delete();
    }

    public function resetProduct(Request $request){
        $warehouse = $request->warehouse == 1 ? 'PASIG WAREHOUSE' : 'ALLIED WAREHOUSE';
        if($request->brand != 'Choose Brand' && $request->category == 'Choose Category'){
            $data = json_encode(Product::where('brand',$request->brand)->get());
            $message = Auth::user()->first_name.' '.Auth::user()->last_name.' reset "'.$request->brand.'" quantity to zero from '. $warehouse .'';
            $reset_db = DB::table('reset_products')->insert(['data'=>$data,'reset_by'=>Auth::user()->id,'message'=>$message,'warehouse'=>$request->warehouse]);
            Product::where('brand',$request->brand)->update([$request->quantity=>0]);
            $message = 'Product successfully reset';
        }elseif($request->brand == 'Choose Brand' && $request->category != 'Choose Category'){
            $data = json_encode(Product::where('category',$request->category)->get());
            $message = Auth::user()->first_name.' '.Auth::user()->last_name.' reset "'.$request->category.'" quantity to zero from '. $warehouse;
            $reset_db = DB::table('reset_products')->insert(['data'=>$data,'reset_by'=>Auth::user()->id,'message'=>$message,'warehouse'=>$request->warehouse]);
            Product::where('category',$request->category)->update([$request->quantity=>0]);
            $message = 'Product successfully reset';
        }elseif($request->brand != 'Choose Brand' && $request->category != 'Choose Category'){
            $data = json_encode(Product::where('category',$request->category)->where('brand',$request->brand)->get());
            $message = Auth::user()->first_name.' '.Auth::user()->last_name.' reset "'.$request->brand.'-'.$request->category.'" quantity to zero  from '. $warehouse;
            $reset_db = DB::table('reset_products')->insert(['data'=>$data,'reset_by'=>Auth::user()->id,'message'=>$message,'warehouse'=>$request->warehouse]);
            Product::where('brand',$request->brand)
                ->where('category',$request->category)
                ->update([$request->quantity=>0]);
            $message = 'Product successfully reset';
        }elseif($request->brand == 'Choose Brand' && $request->category == 'Choose Category'){
            $data = json_encode(Product::where($request->quantity,'!=',0)->get());

            $message = Auth::user()->first_name.' '.Auth::user()->last_name.' reset all products to zero from '. $warehouse;
            $reset_db = DB::table('reset_products')->insert(['data'=>$data,'reset_by'=>Auth::user()->id,'message'=>$message,'warehouse'=>$request->warehouse]);
            Product::where($request->quantity,'!=',0)->update([$request->quantity=>0]);
            $message = 'Product successfully reset';
        }
        return $message;
    }


    public function getReset(){
        $data = DB::table('reset_products')->select('reset_products.*','users.first_name','users.last_name')->join('users','users.id','reset_products.reset_by')->orderBy('reset_products.id','desc')->get();
        return compact('data');

    }

    public function undoReset(Request $request){

        $data = DB::table('reset_products')->where('id',$request->id)->first();
        foreach (json_decode($data->data,TRUE) as $key=>$val){
            if($data->warehouse == 2){
                Product::where('id',$val['id'])->update(['quantity'=>$val['quantity']]);
            }else{
                Product::where('id',$val['id'])->update(['quantity'=>$val['quantity_1']]);
            }
        }
        DB::table('reset_products')->where('id',$request->id)->update(['_undo'=>1]);
        return 'Success';

    }

    public function printStockExhange(Request $request){


        $from = $request->from;
        $to = $request->to;
        $products = TempProductout::where('type',6)->where('user_id',Auth::user()->id)->get()->chunk(25);

        foreach ($products as $key=>$product){
            $id = DB::table('stock_exchange')->orderBy('id','desc')->first();
            $rec_id = 0;
            if($id != null){
                $rec_id = $id->id + 1;
            }else{
                $rec_id = 1;
            }

            $receipt ='ST-'.date('Y').'-'.str_pad($rec_id, 6, '0', STR_PAD_LEFT);
            $reciept_id = DB::table('stock_exchange')->insertGetId(['from_branch'=>$from,'to_branch'=>$to,'data'=>json_encode($product),'receipt_no'=>$receipt,'user_id'=>Auth::user()->id,'created_at'=>date('Y-m-d')]);
            $reciept_ids[] = $reciept_id;
        }


        $products_delete = TempProductout::where('type',6)->where('user_id',Auth::user()->id)->delete();
        $count = TempProductout::where('type',6)->where('user_id',Auth::user()->id)->count();

        return ['rec_no'=>$reciept_ids,'count'=>$count];


    }


    public function ajaxExchange(Request $request){
        $basis_array = ['Gal','Ltr','Pail','Tin'];

        $computer_unit_pail =array(

            'Pail' => $request->unit_price,
            '1/2 Pail' => $request->unit_price / 2,
            '1/4 Pail' => $request->unit_price/ 4,
            '1/8 Pail' => $request->unit_price / 8,


            'Gal' => $request->unit_price /4 ,
            '1/2 Gal' => $request->unit_price / 8,
            '1/4 Gal' => $request->unit_price/ 16,
            '1/8 Gal' => $request->unit_price / 32,
            //Ltr
            'Ltr' => $request->unit_price / 16,
            '1/2 Ltr' => $request->unit_price / 32,
            '1/4 Ltr' => $request->unit_price / 64,
            '1/8 Ltr' => $request->unit_price / 128,
            //pint
            'Pint' => $request->unit_price / 32,
            '1/2 Pint' => $request->unit_price / 64,
            '1/4 Pint' => $request->unit_price / 128,
            '1/8 Pint' => $request->unit_price / 256,



        );

        $computer_unit_gal =array(
            'Gal' => $request->unit_price,
            '1/2 Gal' => $request->unit_price / 2,
            '1/4 Gal' => $request->unit_price/ 4,
            '1/8 Gal' => $request->unit_price / 8,
            //Ltr
            'Ltr' => $request->unit_price / 4,
            '1/2 Ltr' => $request->unit_price / 8,
            '1/4 Ltr' => $request->unit_price / 16,
            '1/8 Ltr' => $request->unit_price / 32,
            //pint
            'Pint' => $request->unit_price / 8,
            '1/2 Pint' => $request->unit_price / 16,
            '1/4 Pint' => $request->unit_price / 32,
            '1/8 Pint' => $request->unit_price / 64,



        );

        $computer_unit_ltr = array(
            //Ltr
            'Ltr' => $request->unit_price,
            '1/2 Ltr' => $request->unit_price / 2,
            '1/4 Ltr' => $request->unit_price / 4,
            '1/8 Ltr' => $request->unit_price / 8,
            //pint
            'Pint' => $request->unit_price / 2,
            '1/2 Pint' => $request->unit_price / 4,
            '1/4 Pint' => $request->unit_price / 8,
            '1/8 Pint' => $request->unit_price / 16,
        );

        $computer_unit_roll = array(
            //Ltr
            'Roll(s)' => $request->unit_price,
            'Ft.' => $request->unit_price / 50,
        );
       
        $data = [];
        if(in_array($request->unit,$basis_array)){
            if($request->unit == 'Gal'){
                $data = $computer_unit_gal;
            }else if($request->unit == 'Ltr'){
                $data = $computer_unit_ltr;
            }else if($request->unit == 'Pail' || $request->unit == 'Tin'){
                $data = $computer_unit_pail;
            }
        }elseif(strstr(strtolower($request->unit),'roll')){
            $data = $computer_unit_roll;
        }else{
            $data = [$request->unit => $request->unit_price];
        }
        return view('ajax.exchange-unit',['data'=>$data]);
    }

    public function saveInventory(Request $request){
       
        if($request->inventory_id != ''){
            $user_id = Auth::user()->id;
            $delete  =   DB::table('total_inventory_items')->where('inventory_id',$request->inventory_id)->delete();
            $insert =   DB::select("INSERT INTO total_inventory_items (inventory_id, product_id, price,unit,quantity) SELECT '$request->inventory_id',product_id,price,unit,qty FROM temp_product_out WHERE type = 8 and temp_product_out.user_id = '$user_id' ");
            $delete = TempProductout::where('type',8)->where('rec_no',$request->inventory_id)->delete();
        }else{
            $user_id = Auth::user()->id;
            $total_id = DB::table('total_inventory')->insertGetID(['branch_id'=>$request->branch,'from_date'=>date('Y-m-d',strtotime($request->from)),'to_date'=>date('Y-m-d',strtotime($request->to)),'entered_by'=>Auth::user()->id]);
            $insert =   DB::select("INSERT INTO total_inventory_items (inventory_id, product_id, price,unit,quantity) SELECT '$total_id',temp_product_out.product_id,temp_product_out.price,temp_product_out.unit,temp_product_out.qty FROM temp_product_out WHERE temp_product_out.type = 8 and temp_product_out.user_id = '$user_id'");
            $delete = TempProductout::where('type',8)->where('rec_no',0)->where('user_id',Auth::user()->id)->delete();
        }
       
    }

    public function deleteInventory(Request $request){
        $delete  =  DB::table('total_inventory')->where('id',$request->id)->update(['is_deleted'=>1]);
    }

}
