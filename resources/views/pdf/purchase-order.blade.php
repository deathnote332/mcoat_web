
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="charset=utf-8" />
    <meta charset="UTF-8">
    <title>RECEIPT</title>
    <style type="text/css">

        @page {
            margin-left: 20px;
            margin-right: 20px;
        }

        body{
            font-family: "Century Gothic", CenturyGothic, AppleGothic, sans-serif;
        }


        h1,h3{
            margin: 0;
            padding: 0;
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




        .delivery-receipt{
            position: absolute;
            right: 0;
            top:25px;
            float: right;
            background-color: black;
            color: #fff;
            height: 30px;
            font-size: 12px;
            width: 200px;
            text-align: center;
            padding: 8px 5px 0px 5px;
            border: 1px solid black;
            font-weight: bold;
            border-radius: 5px;
        }

        .invoice-number{
            position: absolute;
            right: 0;
            top:70px;
            border: 1px solid black;
            height: 30px;
            font-size: 16px;
            width: 200px;
            text-align: center;
            padding: 8px 5px 0px 5px;
            font-weight: bold;
            background-color: white;
            border-radius: 5px;
        }
        .invoice-number span{
            color: rgb(66, 103, 178);
        }

        .branch-name{
            font-size: 22px;
            text-transform: uppercase;
            padding: 5px 10px;
            background-color: white;
            font-weight: bolder;
        }
        .delivered_to,.address,.print-info,.received-goods{
            font-size: 14px;
            padding: 3px 10px;
            font-weight: 600;
        }
        .delivered_to span,.address span{
            text-transform: uppercase;
            border-bottom: 1px solid black;
            font-weight: normal;
        }
        .address span{
            margin-left: 25px;
        }

        .date,.date-reprinted{
            float: right;
            font-weight: bold;
            font-size: 13px;
        }
        table {
            border-collapse: collapse;
            text-align: center;
            border: 1px solid black;
            font-size: 13px;

        }

        thead tr th {
            border: 1px solid black;
            padding: 10px 0px;
            text-transform: uppercase;
            color: white;
            background-color: black;
        }

        table tbody tr td{
            padding: 3px 0px;
        }

        table tr th{border-right: 1px solid white !important}
        table tr th:last-child{border-right: 1px solid black !important;}
        table tr td{ border-right: 1px solid black !important; }
        table tbody tr td:nth-child(3),table tbody tr td:nth-child(2),table tbody tr td:nth-child(1),table tbody tr td:nth-child(4),table tbody tr td:nth-child(5){ text-align: left;padding-left: 15px; }
        table tbody tr:nth-of-type(5n) td {
            border-bottom: 1px dashed red;

        }
        #total td{
            border-top: 1px solid black !important;

        }
        #total td:nth-child(4),#total td:nth-child(1) span,#total td:nth-child(5){
            font-weight:700;
        }
        #total td:nth-child(2),#total td:nth-child(3){
            border-right: 0 !important;
        }


        footer {
            width: 100%;
            position: absolute;
            bottom: 0;

        }


        .page-copy,.warehouse{
            font-size: 12px;
            font-style: italic;
        }
        .page-copy{
            float: left;
        }
        .warehouse{
            float: right;
        }

        .print-info{
            position: relative;
            margin-top: 20px;
            padding: 0 20px;

            font-weight: normal;
        }
        .prepared-by{float: left; padding-top: 5px }
        .signature{
            padding-left: 100px;
        }

        .received-by{
            float: right;

        }
        .checked-by{
            margin-top: 10px;
            clear: both;
        }
        .checked-by div{
            font-style: italic;
        }

        .date-received{

            position: absolute;
            top:40px;
            right: 20px;
        }

        .edited{
            padding-top: 20px;
            font-weight: 600;
        }

        .edited .user-edited{
            color: red;
            font-style: italic;
            font-size: 12px;
        }
        .text-center{
            text-align: center;
        }
        .po-info-address{
            border-radius: 2px;
            border: 1px solid #000;
            padding: 10px;
            font-size: 14px;
        }
        .po-branch-sub-address span,.po-branch-address span,.po-supplier span{font-weight: 600;border-bottom: 1px solid #000000}
        .po-branch-sub-address{padding-left: 87px; margin-bottom: 10px}
        .po-supplier,.po-date{
            display: inline-block;
        }

        .po-date{
           float: right;
            padding-right: 10px;
            font-weight: 600

        }
        .authorize{
            text-align: right;
            font-size: 14px;
            font-weight: 600;
        }
        .authorize-name{
            padding-right: 80px;

        }
    </style>
</head>

<body>

<header>

    <div class="header">
        <h1>mcoat paint commercial & general merchandise</h1>
        <div class="sub-header">
            <h3>185 R. Jabson St. Bambang, Pasig City</h3>
            <h3>Clint D. De Jesus - Prop.</h3>
            <h3>Tel: 509-3387 Telefax: 570-5527</h3>
            <h3>Cel: 09423512001; 09178657629</h3>
            <h3>Vat. Reg. TIN: 146-286-502-001</h3>
        </div>
    </div>


    <div class="branch-name text-center">
        PURCHASE ORDER
    </div>
    <div class="date">
{{--        Date Printed: {!! date('M d,Y',strtotime($invoice['created_at'])) !!}--}}
    </div>

    <div class="delivered_to">
{{--        FROM: <span>{!! \App\Branches::find($invoice['from_branch'])->name !!}</span>--}}
    </div>
    {{--<div class="date-reprinted">--}}
    {{--Date Reprinted: {!! $invoice['created_at'] !!}--}}
    {{--</div>--}}
    <div class="po-info-address">
        <div class="po-branch-address">Delivered To:<span> {{ \App\Branches::find($invoice['branch'])->name }}</span></div>
        <div class="po-branch-sub-address"><span>{{ \App\Branches::find($invoice['branch'])->address }}</span></div>

{{--        TO: <span>{!! \App\Branches::find($invoice['to_branch'])->name !!}</span>--}}
        <div class="po-supplier">
            To: <span> {{ \App\Supplier::find($invoice['supplier'])->name }}</span>
        </div>
        <div class="po-date">
            {{ $invoice['date_printed'] }}
        </div>
    </div>

</header>

<div class="table-location">
    <table class="table" id="sample" width="100%">
        <thead>
        <tr>
            <th>Qty/Unit</th>
            <th>Code</th>
            <th>Description</th>

        </tr>
        </thead>
        <tbody>
        <?php $total = 0; ?>
        <?php $ctr = 0; ?>
        @foreach(json_decode($invoice['products'],TRUE) as $key=>$val)
            <?php $product = \App\Product::find($val['product_id']);
            $total = $total + $product->unit_price * $val['qty'];
            $ctr  = $ctr + 1;
            ?>
            <tr>
                <td width="15%">{!! $val['qty'] !!}   {!!   $val['unit']  !!}</td>
                <td width="15%">{!! $product->code !!} </td>
                <td>{!! $product->brand.' '.$product->category.' '.$product->description  !!}</td>

            </tr>
        @endforeach
        <tr id="total">
            <td>ITEMS #:<span>{{ $ctr }}</span> </td>

            <td></td>
            <td></td>
        </tr>
        </tbody>
    </table>
</div>


<footer>
    <div class="authorize">
        Authorize Signature: _____________________________
        <div class="authorize-name">Ludy De Jesus</div>
    </div>
</footer>
</body>

</html>
