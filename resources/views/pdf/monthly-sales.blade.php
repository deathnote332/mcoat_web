
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
    <title>RECEIPT</title>
    <style type="text/css">

        @page {
           margin-left: 30px;
            margin-right: 30px;
            margin-top: 20px;
            margin-bottom: 0;

        }

        body{
            font-family: "Century Gothic", CenturyGothic, AppleGothic, sans-serif;
            columns: 2;
           
        }

        h1,h3,h2{
            margin: 0;
            padding: 0;
        }

        .delivery-receipt{
            position: absolute;
            left: 0;
            top:70px;
            float: right;
            background-color: black;
            color: #fff;
            height: 30px;
            font-size: 16px;
            width: 200px;
            text-align: center;
            padding: 8px 5px 0px 5px;
            border: 1px solid black;
            font-weight: bold;
            border-radius: 5px;
        }
        .header{
            text-align: center;
            background-color: white;
            position: relative;
        }
        .header h1{
            text-transform: uppercase;
            font-size: 16px;
        }
        .header .sub-header{
            margin-top: 5px;
            padding-bottom: 15px;
        }
        .header .sub-header h3{
            font-weight: normal;
            text-transform: capitalize;
            font-size: 11px;
        }

        .header h3{
            text-align: center;
            font-weight: 600;
        }
        table {
            border-collapse: collapse;
            text-align: center;
            border: 1px solid black;
            padding-right: 20px;
            font-size: 12px;

        }

        thead tr th {
            border: 1px solid black;

            text-transform: uppercase;

            color: #000;

        }

        table tbody tr td{
            padding: 5px 0px;
            border-left: 1px solid #000;
        }

        #total td{
            border-top: 1px solid black !important;
            font-weight:700;
        }

        .title{
            padding: 10px 0;
            text-align: center;
            text-transform: uppercase;
            font-weight: 600;
            font-size: 20px;
        }


    </style>
</head>

<body>

<header>

    {{--<div class="header">--}}
        {{--<h3>MONTHLY SALES</h3>--}}
        {{--<h4>BRANCH SALES REPORT OF {{ \App\Branches::find($branch)->name }}</h4>--}}
        {{--<h4>BRANCH SALES REPORT OF {{ \App\Branches::find($branch)->name }}</h4>--}}
        {{--<h4>{{date('F', mktime(0, 0, 0, $month, 1))}} {{ $year }}</h4>--}}

    {{--</div>--}}
    <div class="header">
        <h1>mcoat paint commercial & general merchandise</h1>
        <div class="sub-header">
            <h3>185 R. Jabson St. Bambang, Pasig City</h3>
            <h3>Clint D. De Jesus - Prop.</h3>
            <h3>Tel: 509-3387 Telefax: 570-5527</h3>
            <h3>Cel: 09423512001; 09178657629</h3>
            <h3>Vat. Reg. TIN: 146-286-502-001</h3>
        </div>
        <h2>MONTHLY REPORT</h2>

    </div>
    <div class="delivery-receipt">
        {{date('F', mktime(0, 0, 0, $month, 1))}} {{ $year }}
    </div>
    <h2 class="title">BRANCH SALES REPORT OF {{ \App\Branches::find($branch)->name }}</h2>





</header>

<div class="table-location">
    <table class="table" id="sample" width="100%">
        <thead>
        <tr>
            <th>Date</th>
            <th>Receipt Number</th>
            <th>Total Sales</th>
            <th>Daily Sales</th>
            <th>Credit Collection</th>
            <th>Taken</th>
            <th>Bank Deposit</th>
            <th>Expenses</th>
        </tr>
        </thead>
        <tbody>
                <?php $total_sales = 0;$deposit_total = 0;$taken_total = 0;$credit_total=0;$expense_total=0;$daily_sales_total=0;?>
                @for($i = 1; $i<=$end_date;$i++)
                    <?php $result = \App\Http\Controllers\SaleController::getSalePerDay($i,$month,$year,$branch);
                    $total = json_decode($result,TRUE);
                    $w_receipt = $total['with_receipt_total'];
                    $wo_receipt = $total['with_out_receipt_total'];
                    $credit = $total['credit_total'];
                    $expense = $total['expense_total'];
                    $cash = $total['amount_total'];
                    $taken = $total['taken_total'];
                    $bank = $total['deposit_total'];
                    $is_check = $total['is_check'];
                    $coh = $total['coh'];
                    $rec_no = $total['rec_no'];
                    $expense_details = $total['expense_details'];

                  
                    $money = $cash + $taken;
                    $_total = ($w_receipt + $wo_receipt + $credit) - $expense - $coh  ;
                   
    
                    if($_total > $money){
                        $loss = $money - $_total;
                        // $_total = $_total - $loss ;
                        if($is_check != 1){
                            $_totals = $_total + $loss;
                        }else{
                            $_totals = $_total;
                        }
                    }else{
                        $excess = $money - $_total ;
                    // $_total = $_total + $excess ;
                        if($is_check != 1){
                            $_totals = $_total + $excess;
                        }else{
                            $_totals = $_total;
                        }
                    }

                    $daily_sales = ($w_receipt + $wo_receipt);


                    $daily_sales_total =   $daily_sales_total + $daily_sales;
                    $total_sales = $total_sales + $_total;
                    $deposit_total  = $deposit_total + $bank;
                    $credit_total  = $credit_total + $credit;
                    $expense_total  = $expense_total + $expense;
                    $taken_total  = $taken_total + $taken;

                    

                    ?>
                    <tr>
                        <td>{{ $i }}</td>
                        <td></td>
                        <td>{{ 'P '.number_format($_totals,2) }}</td>
                        <td>{{ 'P '.number_format($daily_sales,2) }}</td>
                        <td>{{ 'P '.number_format($credit,2) }}</td>
                        <td>{{ 'P '.number_format($taken,2) }}</td>
                        <td>{{ 'P '.number_format($bank,2) }}</td>
                        <td>{{ 'P '.number_format($expense,2) }}</td>
                    </tr>
                @endfor
                <tr id="total">
                    <td>TOTAL </td>
                    <td></td>
                    <td>{{ 'P '.number_format($total_sales,2) }}</td>
                    <td>{{ 'P '.number_format($daily_sales_total,2) }}</td>
                    <td>{{ 'P '.number_format($credit_total,2) }}</td>
                    <td>{{ 'P '.number_format($taken_total,2) }}</td>
                    <td>{{ 'P '.number_format($deposit_total,2) }}</td>
                    <td>{{ 'P '.number_format($expense_total,2) }}</td>
                </tr>
        </tbody>
        </tbody>
    </table>
</div>
<div class="print-info">

</div>

<footer>

</footer>
</body>

</html>
