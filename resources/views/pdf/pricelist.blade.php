
<style>

    @page {
        margin: 180px 50px 40px 50px;
    }



    h1,h3{
        margin: 0;
        padding: 0;
    }

    table {
        border-collapse: collapse;
        text-align: center;
        border: 1px solid black;
        font-size: 8px;
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
    table th, table td {
        border: 1px solid #000;
    }


    table tr th{border-right: 1px solid white !important}
    table tr th:last-child{border-right: 1px solid black !important;}
    table tr td{ border-right: 1px solid black !important; font-size: 12px }
    table tbody tr td:nth-child(3) span{margin-left: 2em}
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

    .title{
        text-align: center;
        position: fixed;
        top: -30px;
        font-size: 16px;
        font-weight: bold;
        text-transform: uppercase;
    }

</style>
<title>MCOAT Pricelist - {{$title}}</title>
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

<div class="title">
    PRICELIST OF {{ $title }}
</div>



<div class="table-location">
    <table class="table" id="sample" width="100%" >
        <thead>
        <tr>
           <th>Code</th>
            <th>Brand</th>
            <th>Unit</th>
            <th>Category</th>
            <th>Description</th>
            <th>unit price</th>
        </tr>
        </thead>
        <tbody>
        @foreach(json_decode($data,TRUE) as $key=>$val)
            <tr>
               <td>{!! $val['code'] !!} </td>
               <td>{!! $val['brand'] !!} </td>
                <td>{!! $val['unit'] !!} </td>
                <td>{!! $val['category'] !!} </td>
                <td>{!! $val['category'] !!} </td>
                <td>{!! 'P '.number_format($val['unit_price'] , 2) !!}</td>
           </tr>
        @endforeach
        </tbody>
    </table>
</div>





