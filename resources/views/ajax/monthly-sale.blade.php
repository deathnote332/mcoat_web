@for($i=1;$i<=12;$i++)
    <?php
        $data = \App\Http\Controllers\SaleController::salePerMonth($branch,$i,$year);
        $total = json_decode($data,TRUE)
    ?>
    <div class="col-md-4">
        <a href="{{ url('/branch-sale/'.$branch.'/perMonth?year='.$year.'&month='.$i) }}">
        <div class="info-box">
            <span class="info-box-icon bg-aqua">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</span>

            <div class="info-box-content">
                <table width="100%">
                    <tbody>
                        <tr >
                            <td>TOTAL SALES</td>
                            <td></span>{{ 'P '.number_format($total['total_sales'],2) }}</td>
                        </tr>
                        <tr>
                            <td>CREDIT COLLECTION</td>
                            <td>{{ 'P '.number_format($total['credit_collection'],2) }}</td>
                        </tr>
                        <tr>
                            <td>FOR DEPOSIT</td>
                            <td>{{ 'P '.number_format($total['for_deposit'],2) }}</td>
                        </tr>
                        <tr>
                            <td>EXPENSES</td>
                            <td>{{ 'P '.number_format($total['expenses'],2) }}</td>
                        </tr>
                        <tr>
                            <td>PURCHASE ORDER</td>
                            <td>{{ 'P '.number_format(0,2) }}</td>
                        </tr>
                        <tr>
                            <td>STOCK IN</td>
                            <td>{{ 'P '.number_format(0,2) }}</td>
                        </tr>
                        <tr>
                            <td>STOCK OUT</td>
                            <td>{{ 'P '.number_format(0,2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
        </a>
    </div>
@endfor