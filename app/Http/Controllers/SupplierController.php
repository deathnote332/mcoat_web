<?php

namespace App\Http\Controllers;


use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
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
    public function getSupplier()
    {
        $data = Supplier::where('status',1)->orderBy('name','asc')->get();
        return compact('data');
    }

    public function addSupplier(Request $request){
        DB::table('suppliers')->insert(['name'=>$request->name,'address'=>$request->address,'status'=>1,'contact'=>$request->contact]);
    }

    public function updateSupplier(Request $request){
        Supplier::where('id',$request->id)->update(['name'=>$request->name,'address'=>$request->address,'contact'=>$request->contact]);
    }

    public function deleteSupplier(Request $request){
        Supplier::where('id',$request->id)->update(['status'=>0]);
    }

    public function getSupplierProducts(Request $request){
        $supplier = Supplier::where('id',$request->id)->first();
        return json_decode($supplier->products);
    }

    public function addSupplierProducts(Request $request){
        $supplier = Supplier::where('id',$request->id)->first();
        $products = json_decode($supplier->products);

        if($products == '' || $products=='null'){
            $products[] = $request->products;

            $update = Supplier::where('id',$request->id)->update(['products'=>json_encode($products)]);

        }else{
            if(!in_array($request->products,$products)){
                $products[] = $request->products;
                $update = Supplier::where('id',$request->id)->update(['products'=>json_encode($products)]);

            }
        }
        $suppliers = Supplier::where('id',$request->id)->first();
        return json_decode($suppliers->products);
    }

    public function removeSupplierBrand(Request $request){
        $supplier = Supplier::where('id',$request->id)->first();
        $products = json_decode($supplier->products);
        foreach ($products as  $key => $val){
            if($val == $request->brand){
                unset($products[$key]);
            }
        }
        $update = Supplier::where('id',$request->id)->update(['products'=>json_encode(array_values($products))]);
        $suppliers = Supplier::where('id',$request->id)->first();
        return json_decode($suppliers->products);
    }
}
