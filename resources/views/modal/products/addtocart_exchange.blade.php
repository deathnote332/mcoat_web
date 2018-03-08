
<!-- Modal -->
<div class="modal fade" id="addToCartModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Add to cart</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="product_id"  value="">
                <div class="row">
                    <div class="col-md-6 col-xs-6">
                        <div class="form-group">
                            <label>Brand</label>
                            <p class="form-control-static" id="brand">Test</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-xs-6">
                        <div class="form-group">
                            <label>Category</label>
                            <p class="form-control-static" id="category">Test</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-xs-6">
                        <div class="form-group">
                            <label>Code</label>
                            <p class="form-control-static" id="code">Test</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-xs-6">
                        <div class="form-group">
                            <label>Description</label>
                            <p class="form-control-static" id="description">Test</p>
                        </div>
                    </div>
                </div>
                <div class="row">



                    <div class="col-md-12">
                        <div class="form-group" id="unit-div">
                            <label>Unit</label>
                            <p class="form-control-static" id="unit">Unit</p>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-groupx">
                            <label>Enter Quantity</label>
                            <input class="form-control" placeholder="Enter quantity" id="add-qty" maxlength="5">
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-addCart">Add to cart</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->