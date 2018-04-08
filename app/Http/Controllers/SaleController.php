<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SaleController extends Controller
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

    public function branchSale(Request $request)
    {
        return view('sale.branch-sales');
    }


    public function salePerBranch(Request $request)
    {
        return view('sale.branch-sale-month',['branch_id'=>$request->branch_id]);
    }

    public function ajaxMonth(Request $request){
        if($request->year == date('Y')){
            $month_count = date('m');
        }else{
            if($request->year > date('Y')){
                $month_count = 0;
                abort(503);
            }elseif($request->year < date('Y')){
                $month_count = 12;
            }
        }

        $data_total = [
            'branch'=>$request->branch,
            'year'=>$request->year,
            'month_count'=>$month_count,
        ];
        return view('ajax.monthly-sale',$data_total);
    }

    public function perMonth(Request $request){
        $start_date = $request->year.'-'.$request->month.'-1';
        $end_date = date('t',strtotime($start_date));
        $month = $request->month;
        $year = $request->year;
        $branch = $request->branch_id;

        return view('sale.branch-sale-permonth',['start_date'=>$start_date,'end_date'=>$end_date,'month'=>$month,'year'=>$year,'branch'=>$branch]);

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     * @paramms i,month,year and branch
     */
    public static function getSalePerDay($i,$month,$year,$branch)
    {
        $str = $i. '-' . $month . '-' . $year;
        $date = date('Y-m-d', strtotime($str));
        $data = DB::table('month_sales')->where('branch_id', $branch)->where('_date', $date)->first();

        $with_receipt_total = 0;
        $with_out_receipt_total = 0;
        $credit_total = 0;
        $expense_total = 0;
        $return_total = 0;
        $total_amount = 0;
        $taken_total = 0;
        $deposit_total = 0;
        $is_check = 2;
        $coh = 0;


        $expense_string = '';
        $firstRec ='' ;
        $lastRec ='' ;

        $number_of_check = 0;

        if($data != null){
            $json_data =  json_decode($data->data,TRUE);


            $rec_no = [];
            foreach ($json_data['with_receipt'] as $key => $val){
                $total =0;
                if($val['rec_amount']  == 'null'){
                    $total= 0;
                }else{
                    $total = $val['rec_amount'];
                }
                $with_receipt_total = $with_receipt_total + $total ;
                array_push($rec_no,$val['rec_no']);

                $number_of_check = $number_of_check + (isset($val['is_check']) ? 1 : 0 );
            }

            $firstRec = $rec_no[0];
            $lastRec = $rec_no[count($rec_no) - 1];

            foreach ($json_data['without_receipt'] as $key => $val){
                $total =0;
                if($val['amount']  == 'null'){
                    $total= 0;
                }else{
                    $total = $val['amount'];
                }
                $with_out_receipt_total = $with_out_receipt_total + $total ;
            }
            foreach ($json_data['credit'] as $key => $val){
                $total =0;
                if($val['amount']  == 'null'){
                    $total= 0;
                }else{
                    $total = $val['amount'];
                }
                $credit_total = $credit_total + $total ;
            }
            foreach ($json_data['expense'] as $key => $val){
                $total =0;
                if($val['amount']  == 'null'){
                    $total= 0;
                }else{
                    $total = $val['amount'];
                }
                $expense_total = $expense_total + $total ;

                $expense_string .= $val['details'].' ';

            }
            foreach ($json_data['return'] as $key => $val){
                $total =0;
                if($val['amount']  == 'null'){
                    $total= 0;
                }else{
                    $total = $val['amount'];
                }
                $return_total = $return_total + $total ;
            }


            $amount_1000 = ($json_data['amount_1000']  != null) ? json_decode($data->data,TRUE)['amount_1000'] * 1000 : 0;
            $amount_500 = ($json_data['amount_500']  != null) ? json_decode($data->data,TRUE)['amount_500'] * 500 : 0;
            $amount_100 = ($json_data['amount_100']  != null) ? json_decode($data->data,TRUE)['amount_100'] * 100 : 0;
            $amount_50 = ($json_data['amount_50']  != null) ? json_decode($data->data,TRUE)['amount_50'] * 50 : 0;
            $amount_20 = ($json_data['amount_20']  != null || json_decode($data->data,TRUE)['amount_20']  != '') ? json_decode($data->data,TRUE)['amount_20'] * 20 : 0;
            $amount_coins = ($json_data['amount_coins']  != null) ? json_decode($data->data,TRUE)['amount_coins']  : 0;
            $total_amount = $amount_1000 + $amount_500 + $amount_100 + $amount_50 +$amount_20+ $amount_coins;
            $is_check = (isset($json_data['is_check'])) ? json_decode($data->data,TRUE)['is_check']  : $is_check;
            $coh = (isset($json_data['coh'])) ? json_decode($data->data,TRUE)['coh']  : $coh;

            foreach (json_decode($data->data,TRUE)['taken'] as $key => $val){
                $total =0;
                if($val['amount']  == 'null'){
                    $total= 0;
                }else{
                    $total = $val['amount'];
                }
                $taken_total = $taken_total + $total ;
            }
            foreach (json_decode($data->data,TRUE)['deposit'] as $key => $val){
                $total =0;
                if($val['amount']  == 'null'){
                    $total= 0;
                }else{
                    $total = $val['amount'];
                }
                $deposit_total = $deposit_total + $total ;
            }

        }


        $_data =[
            'with_receipt_total' =>$with_receipt_total,
            'with_out_receipt_total' =>$with_out_receipt_total,
            'credit_total' =>$credit_total,
            'expense_total' =>$expense_total,
            'return_total' =>$return_total,
            'amount_total' =>$total_amount,
            'taken_total' =>$taken_total,
            'deposit_total' =>$deposit_total,
            'data' =>($data != null) ? $data->data : '',
            'date' =>$date,
            'is_check'=>$is_check,
            'coh'=>$coh,
            'expense_details'=>$expense_string,
            'rec_no'=>$firstRec.'-'.$lastRec,
            'number_of_check' =>$number_of_check


        ];


        return json_encode($_data);

    }


    public static function salePerMonth($branch,$month,$year){
        $data = DB::table('month_sales')->where('branch_id',$branch)->where(DB::raw('YEAR(_date)'),$year)->where(DB::raw('MONTH(_date)'),$month)->get();

        $with_receipt_total = 0;
        $with_out_receipt_total = 0;
        $credit_total = 0;
        $expense_total = 0;
        $return_total = 0;
        $total_amount = 0;
        $taken_total = 0;
        $deposit_total = 0;

        if($data != null){
            foreach($data as $key => $vals){
                $_data = json_decode($vals->data,TRUE);

                foreach ($_data['with_receipt'] as $key => $val){
                    $total =0;
                    if($val['rec_amount']  == 'null'){
                        $total= 0;
                    }else{
                        $total = $val['rec_amount'];
                    }
                    $with_receipt_total = $with_receipt_total + $total ;
                }

                foreach ($_data['without_receipt'] as $key => $val){
                    $total =0;
                    if($val['amount']  == 'null'){
                        $total= 0;
                    }else{
                        $total = $val['amount'];
                    }
                    $with_out_receipt_total = $with_out_receipt_total + $total ;
                }

                foreach ($_data['credit'] as $key => $val){
                    $total =0;
                    if($val['amount']  == 'null'){
                        $total= 0;
                    }else{
                        $total = $val['amount'];
                    }
                    $credit_total = $credit_total + $total ;
                }
                foreach ($_data['expense'] as $key => $val){
                    $total =0;
                    if($val['amount']  == 'null'){
                        $total= 0;
                    }else{
                        $total = $val['amount'];
                    }
                    $expense_total = $expense_total + $total ;
                }
                foreach ($_data['return'] as $key => $val){
                    $total =0;
                    if($val['amount']  == 'null'){
                        $total= 0;
                    }else{
                        $total = $val['amount'];
                    }
                    $return_total = $return_total + $total ;
                }
                $amount_1000 = ($_data['amount_1000']  != null) ? $_data['amount_1000'] * 1000 : 0;
                $amount_500 = ($_data['amount_500']  != null) ? $_data['amount_500'] * 500 : 0;
                $amount_100 = ($_data['amount_100']  != null) ? $_data['amount_100'] * 100 : 0;
                $amount_50 = ($_data['amount_50']  != null) ? $_data['amount_50'] * 50 : 0;
                $amount_20 = ($_data['amount_20']  != null ) ? $_data['amount_20'] * 20 : 0;
                $amount_coins = ($_data['amount_coins']  != null) ? $_data['amount_coins']  : 0;
                $total_amount = $amount_1000 + $amount_500 + $amount_100 + $amount_50 +$amount_20+ $amount_coins;

                foreach ($_data['taken'] as $key => $val){
                    $total =0;
                    if($val['amount']  == 'null'){
                        $total= 0;
                    }else{
                        $total = $val['amount'];
                    }
                    $taken_total = $taken_total + $total ;
                }
                foreach ($_data['deposit'] as $key => $val){
                    $total =0;
                    if($val['amount']  == 'null'){
                        $total= 0;
                    }else{
                        $total = $val['amount'];
                    }
                    $deposit_total = $deposit_total + $total ;
                }
            }
        }

        $return_data =[
            'total_sales' =>$with_receipt_total + $with_out_receipt_total,
            'credit_collection' =>$credit_total,
            'for_deposit' =>$deposit_total,
            'expenses' =>$expense_total,
            'purchase_order' =>0,
            'stock_in' =>$taken_total,
            'stock_out' =>$deposit_total,

        ];

        return json_encode($return_data);

    }
}


