<!-- Modal -->
<div class="modal fade" id="edit-day-modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <div class="col-md-12">
                    <h4 class="modal-title" id="myModalLabel"><strong>DAILY SALES REPORT</strong> </h4>

                </div>
                <div class="col-md-12">
                    <h5 class="modal-title" id="myModalLabel">BRANCH: <strong id="_branch"> CDJ PASIG</strong></h5>

                </div>
                <div class="col-md-8">
                    <h5 class="modal-title" id="myModalLabel">ADDRESS: <strong id="_address"> 108 R. Jabson St. Bambang, Pasig City</strong></h5>

                </div>
                <div class="col-md-4">
                    <h5 class="modal-title" id="myModalLabel">DATE: <strong id="_date"> Jan 1, 2018</strong></h5>

                </div>

            </div>

            <form id="daily-edit-sale">
                <div class="modal-body">
                    <div id="steps">
                        <div class="step-app clearfix" >
                            <ul class="step-steps clearfix">
                                <li><a href="#step1">1. With Receipt</a></li>
                                <li><a href="#step2">2. Without Receipt</a></li>
                                <li><a href="#step3">3. Credit Collection</a></li>
                                <li><a href="#step4">4. Expenses</a></li>
                                <li><a href="#step5">5. Item Returns</a></li>
                                <li><a href="#step6">6. Cash Breakdown</a></li>
                                <li><a href="#step7">7. Taken</a></li>
                                <li><a href="#step8">8. Bank Depost</a></li>
                            </ul>
                            <div class="step-content clearfix">
                                <div class="step-tab-panel" id="step1">
                                    <div class="col-md-12 margin_bottom"><h4>Total: <span class="total">0</span></h4></div>

                                    <div class="row margin_top">

                                        <div class="col-md-1 ">
                                            <div class="number-ctr">1.</div>
                                        </div>
                                        <div class="col-md-6">

                                            <input type="text" class="form-control" name="with_receipt[0][rec_no]" placeholder="Receipt no.">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="with_receipt[0][rec_amount]" placeholder="Amount">
                                        </div>

                                    </div>
                                </div>
                                <div class="step-tab-panel" id="step2">
                                    <div class="col-md-12 margin_bottom"><h4>Total: <span class="total">0</span></h4></div>

                                    <div class="row margin_top">
                                        <div class="col-md-1">
                                            <div class="number-ctr">1.</div>
                                        </div>
                                        <div class="col-md-11">
                                            <input type="text" class="form-control" name="without_receipt[0][amount]" placeholder="Amount">
                                        </div>
                                    </div>
                                </div>
                                <div class="step-tab-panel" id="step3">
                                    <div class="col-md-12 margin_bottom"><h4>Total: <span class="total">0</span></h4></div>
                                    <div class="row margin_top">
                                        <div class="col-md-1">
                                            <div class="number-ctr">1.</div>
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="credit[0][company]" placeholder="Company">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="credit[0][bank_name]" placeholder="Bank Name">
                                        </div>
                                        <div class="col-md-5 margin_top">
                                            <input type="text" class="form-control" name="credit[0][bank]" placeholder="Bank Number">
                                        </div>
                                        <div class="col-md-6 margin_top">
                                            <input type="text" class="form-control" name="credit[0][amount]" placeholder="Amount">
                                        </div>
                                    </div>
                                </div>
                                <div class="step-tab-panel" id="step4">
                                    <div class="col-md-12 margin_bottom"><h4>Total: <span class="total">0</span></h4></div>
                                    <div class="row margin_top">
                                        <div class="col-md-1">
                                            <div class="number-ctr">1.</div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="expense[0][details]" placeholder="Details">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="expense[0][amount]" placeholder="Amount">
                                        </div>
                                    </div>
                                </div>
                                <div class="step-tab-panel" id="step5">
                                    <div class="col-md-12 margin_bottom"><h4>Total: <span class="total">0</span></h4></div>
                                    <div class="row margin_top">
                                        <div class="col-md-1">
                                            <div class="number-ctr">1.</div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="return[0][name]" placeholder="Name">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="return[0][amount]" placeholder="Amount">
                                        </div>
                                    </div>
                                </div>
                                <div class="step-tab-panel" id="step6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>Total: <span class="total">0</span></h4></div>
                                    </div>
                                    <div class="row margin_top">
                                        <div class="col-md-2">
                                            <div class="number-ctr">1000*</div>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="amount_1000" placeholder="Pieces">
                                        </div>
                                    </div>
                                    <div class="row margin_top">
                                        <div class="col-md-2">
                                            <div class="number-ctr">500*</div>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="amount_500" placeholder="Pieces">
                                        </div>
                                    </div>
                                    <div class="row margin_top">
                                        <div class="col-md-2">
                                            <div class="number-ctr">100*</div>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="amount_100" placeholder="Pieces">
                                        </div>
                                    </div>
                                    <div class="row margin_top">
                                        <div class="col-md-2">
                                            <div class="number-ctr">50*</div>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="amount_50" placeholder="Pieces">
                                        </div>
                                    </div>
                                    <div class="row margin_top">
                                        <div class="col-md-2">
                                            <div class="number-ctr">20*</div>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="amount_20" placeholder="Pieces">
                                        </div>
                                    </div>
                                    <div class="row margin_top">
                                        <div class="col-md-2">
                                            <div class=" number-ctr">Coins* </div>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="amount_coins" placeholder="Amount">
                                        </div>
                                    </div>
                                    <div class="row margin_top">
                                        <div class="col-md-2">
                                            <div class=" number-ctr">Cash Funds </div>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="coh" placeholder="Amount">
                                        </div>
                                    </div>
                                    <div class="row margin_top">

                                        <div class="col-md-6 col-md-offset-6 text-right form-check">
                                            <input class="form-check-input" type="checkbox" value="2" id="is_check" >
                                            <label class="form-check-label" for="is_check">
                                                No Cash Breakdown
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="step-tab-panel" id="step7">
                                    <div class="col-md-12 margin_bottom"><h4>Total: <span class="total">0</span></h4></div>

                                    <div class="row margin_top">
                                        <div class="col-md-1 ">
                                            <div class="number-ctr">1.</div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="taken[0][name]" placeholder="Name">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="taken[0][amount]" placeholder="Amount">
                                        </div>
                                    </div>
                                </div>

                                <div class="step-tab-panel" id="step8">
                                    <div class="col-md-12 margin_bottom"><h4>Total: <span class="total">0</span></h4></div>
                                    <div class="row margin_top">
                                        <div class="col-md-1">
                                            <div class="number-ctr">1.</div>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="deposit[0][bank_name]" placeholder="Bank Name">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="deposit[0][bank_number]" placeholder="Bank Number">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="deposit[0][amount]" placeholder="Amount">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="step-footer clearfix">
                                <button data-direction="prev" class="btn btn-primary">Previous</button>
                                <button data-direction="next" class="btn btn-primary">Next</button>
                                <button data-direction="finish" class="btn btn-primary">Finish</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
