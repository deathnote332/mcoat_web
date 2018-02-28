
<style>

    @page {
        margin: 180px 50px;
    }



    h1,h3{
        margin: 0;
        padding: 0;
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
        padding: 10px 0px;
        text-transform: uppercase;

        color: white;

        background-color: black;
    }

    table tbody tr td{
        padding: 5px 0px;
    }

    .page-break {
        page-break-after: always;
    }


    .header{
        text-align: center;
        position: fixed;
        top: -150px;
        margin: 0;
        background-color: white;
    }
    .header h1{
        text-transform: uppercase;
        font-size: 16px;
    }
    .header .sub-header{
        margin-top: 5px;
    }
    .header .sub-header h3{
        font-weight: normal;
        text-transform: capitalize;
        font-size: 11px;
    }




    .branch-name{
        position: fixed;
        left: 0;
        top: -60px;
        font-size: 20px;
        text-transform: uppercase;
        padding: 8px 5px 0px 5px;
        background-color: white;
        font-weight: 700;
    }

    .inv-number{
        position: fixed;
        left: 0;
        top: -85px;
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


    .deliver-receipt{
        position: relative;
        left: 0;
        top: -125px;
        background-color: black;
        color: #fff;
        height: auto;
        width: 300px;
        font-size: 16px;
        text-align: center;
        padding: 8px 5px 10px 5px;
        display: block;
        font-weight: bold;
        border-radius: 5px;

    }

    .page-copy{
        position: fixed;
        text-align: left;
        bottom: -140px;
        background-color: white;
        font-size: 12px;
        font-style: italic;
    }

    .date{
        position: fixed;
        text-align: right;
        top: -50px;
        background-color: white;
        font-size: 14px;
    }
    .delivered_to{
        position: fixed;
        top: -50px;
        padding-left: 8px;


    }
    .address{
        position: fixed;
        top: -20px;
        padding-left: 8px;

    }
    .address span{
        margin-left: 20px;

    }
    .received-goods{
        text-align: right;
        font-style: italic;
    }
    .delivered_to,.address,.print-info,.received-goods{
        font-size: 14px;
    }
    .delivered_to span{

        text-transform: uppercase;
        font-weight: bold;

    }
    table tr th{border-right: 1px solid white !important}
    table tr th:last-child{border-right: 1px solid black !important;}
    table tr td{ border-right: 1px solid black !important; }
    table tbody tr td:nth-child(3){ text-align: left;padding-left: 10px }
    #total td{
        border-top: 1px solid black !important;

    }
    #total td:nth-child(1),#total td:nth-child(2),#total td:nth-child(3){
        border-right: 0 !important;
    }
    .print-info{
        padding-top: 20px;

    }
    .print-info div:nth-child(1) span{
        margin-left: 10px;
        text-transform: uppercase;
        font-weight: bold;

    }
    .print-info div:nth-child(2){
        padding-top: 10px;

    }
    .print-info div:nth-child(2) span{
        margin-left: 10px;
        border-bottom: 1px solid black;
    }
    .print-info div:nth-child(3){
        font-style: italic;
    }

    .received-by{
        position: relative;
        text-align: right;
        top:-25px;

    }
    .signature{
        margin-right: 50px;
    }
    .date-received{
        position: relative;
        top:-45px;
    }
    .prepared-by{
        position: relative;
        top:-15px;
    }
    .prepared-by span{
        margin-left: 10px;
        text-transform: capitalize;
        font-weight: bold;
    }
    .invoice-follow{
        position: relative;
        top:-15px;
        font-style: italic;
    }

    tbody tr.total td{
        border-top: 1px solid #000;
    }

</style>
<title>{!!  $invoice['name'].'- Receipt no: '.$invoice['receipt_no'] !!}</title>
    <div class="deliver-receipt">
        DELIVERY RECEIPT  NO. <span>{!! $invoice['receipt_no'] !!}</span>
    </div>

    <div class="date">
        Date entered: {!! $invoice['created_at'] !!}
    </div>
    <div class="delivered_to">

        Delivered From: <span>{{ $invoice['name'] }}</span>
    </div>
    <div class="address">
        Address: <span>{{ $invoice['address'] }}</span>
    </div>

    <div class="table-location">
        <table class="table" id="sample" width="100%">
            <thead>
            <tr>
                <th>Qty/Unit</th>
                <th>Code</th>
                <th>Description</th>
                <th>unit price</th>
                <th>Amount</th>
            </tr>
            </thead>
            <tbody>
            <?php $total=0; ?>
            @foreach($invoice['products'] as $key=>$val)
                <tr>
                    <td>{!! $val->product_qty !!}   {!!  $val->unit !!}</td>
                    <td>{!! $val->code !!} </td>
                    <td>{!! $val->brand.' '.$val->category.' '.$val->description  !!}</td>
                    <td>{!! 'P '.number_format($val->unit_price , 2) !!}</td>
                    <td>{!! 'P '.number_format($val->unit_price * $val->product_qty, 2) !!}</td>
                    <?php $total = $total + ($val->unit_price * $val->product_qty); ?>
                </tr>
            @endforeach
            <tr class="total">
                <td style="text-transform: capitalize; font-weight: bold;font-size: 16px">TOTAL</td>
                <td></td>
                <td></td>
                <td></td>
                <td class="total" style="font-weight: bold;font-size: 16px">{{ 'P '.number_format($total,2) }}</td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="print-info">
        <div class="">
            Entered by: <span>{!! \App\User::find($invoice['entered_by'])->first_name. ' '.\App\User::find($invoice['entered_by'])->last_name  !!}</span>
        </div>
        <div class="">
            Warehouse: <span>{!! ($invoice['warehouse'] == 2) ? 'MCOAT Pasig Warehouse' : 'Dagupan Warehouse' !!}</span>
        </div>

    </div>





