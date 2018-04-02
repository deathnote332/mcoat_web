
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="charset=utf-8" />
    <meta charset="UTF-8">
    <title>RECEIPT</title>
    <style type="text/css">

        @page {
           margin-left: 30px;
            margin-right: 30px;
            margin-top: 20px;

        }

        body{
            font-family: "Century Gothic", CenturyGothic, AppleGothic, sans-serif;
            columns: 2;
           
        }

        .header h3{
            text-align: center;
            font-weight: 600;
        }
        table {
            border-collapse: collapse;
            text-align: center;
            border: 1px solid black;
            font-size: 12px;
            margin: 0px;
        }

        thead tr th {
            border: 1px solid black;

            text-transform: uppercase;

            color: #000;

        }

        table tbody tr td{
            padding: 5px 0px;
        }

        #total td{
            border-top: 1px solid black !important;
            font-weight:700;
        }


    </style>
</head>

<body>

<header>

    <div class="header">
        <h3>MONTHLY SALES</h3>
        <h4>BRANCH SALES REPORT OF BRANCH</h4>
        <h4>JANUARY 2018</h4>

    </div>


</header>

<div class="table-location">
    <table class="table" id="sample" width="100%">
        <thead>
        <tr>
            <th>Date</th>
            <th>Balance Number</th>
            <th>Receipt Number</th>


            <th>Total Sales</th>
            <th>Deposit</th>
            <th>Taken</th>
            <th>Credit Collection</th>
            <th>Expenses</th>
        </tr>
        </thead>
        <tbody>
                <?php $total_sales = 0;$deposit_total = 0;$taken_total = 0;$credit_total=0;$expense_total=0;?>
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
                    $expense_details = $total['expense_details'];

                    $_total = (($w_receipt + $wo_receipt) -$coh) - $expense ;

                    $total_sales  = $total_sales + ($w_receipt + $wo_receipt);
                    $deposit_total  = $deposit_total + $bank;
                    $taken_total  = $taken_total + $taken;
                    $credit_total  = $credit_total + $credit;
                    $expense_total  = $expense_total + $expense;

                    ?>
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ '-' }}</td>
                        <td>{{ '-' }}</td>
                        <td>{{ 'P '.number_format($w_receipt+ $wo_receipt,2) }}</td>
                        <td>{{ 'P '.number_format($bank,2) }}</td>
                        <td>{{ 'P '.number_format($taken,2) }}</td>
                        <td>{{ 'P '.number_format($credit,2) }}</td>
                        <td>{{ 'P '.number_format($expense,2).'-'.$expense_details }}</td>
                    </tr>
                @endfor
                <tr id="total">
                    <td>TOTAL </td>
                    <td></td>
                    <td></td>
                    <td>{{ 'P '.number_format($total_sales,2) }}</td>
                    <td>{{ 'P '.number_format($deposit_total,2) }}</td>
                    <td>{{ 'P '.number_format($taken_total,2) }}</td>
                    <td>{{ 'P '.number_format($credit_total,2) }}</td>
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
