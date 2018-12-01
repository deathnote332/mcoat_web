<!DOCTYPE html>
<html lang="en">
@for($ctr=1;$ctr<=$copy;$ctr++)
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ @$title }} Purchase Order</title>
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        table {
            border-collapse: collapse;
            border: 1px solid black;
            margin: 0px;
            text-align: center;
        }
        table tr th{
            border: 1px solid black;
           
        }
        table tr td{
            border: 1px solid black;
             padding:10px;
        }
        table tr:last-child td{
             padding:0px !important;
        }
        .center{
            text-align: center;
        }

        .right{
            text-align: right;
        }
        .header-details{
            position: relative;

        }
        .header-details div{
            display: inline-block;
            width: 33%;
            font-size: 18px;
            text-transform: uppercase;
            font-weight: 600;
        }
        .header-details div:nth-child(3){
            position:absolute;
            top:-40px; 
        }
        .daily-sale div{
            display:block; 
            width: 100%;
        }
         .daily-sale p{
            padding: 0;
            margin: 0;
            vertical-align:bottom;
        }
        .daily-sale p:first-child{
            font-size: 12px;
        }
        .daily-sale p:last-child{
            color:#3c8dbc;
            font-size: 18px;
        }
        .reminder{
            font-style:italic;
            font-size:14px;
            color:red;
        }
    </style>
</head>
<body>
    <h2 class="center">MONTHLY REPORT</h2>
    <div class="header-details">
       <div>{{$branch}}</div>
        <div class="center">{{$_date}}</div>
        <div class="right daily-sale">
            PURCHASE ORDER
            <div>
            <p>
                Control no.
            </p>
             <p>{{ $cr }}</p>
            </div>
        </div>
    </div>

    <div class="table-days">
        <table width="100%" >
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Receipt No.</th>
                    <th>Amount</th>
                    <th>Stocks From</th>
                </tr>
            </thead>
            <tbody>
                @for($i =1;$i<=40;$i++)
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endfor
                <tr>
                    <td></td>
                    <td>TOTAL</td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
@endfor
</html>