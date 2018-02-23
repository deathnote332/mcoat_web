<!-- Modal -->
<div class="modal fade" id="product-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Update product</h4>
            </div>
            <form id="form-products">
                <div class="modal-body">
                    <input type="hidden" id="product_id" name="product_id"  value="">
                    <div class="row">
                        <div class="col-md-6 col-xs-6">
                            <div class="form-group">
                                <label>Brand</label>
                                <input  type="text" class="form-control" id="brand" name="brand" required/>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-6">
                            <div class="form-group">
                                <label>Category</label>
                                <input  type="text" class="form-control" id="category" name="category" required/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-6">
                            <div class="form-group">
                                <label>Code</label>
                                <input  type="text" class="form-control" id="code" name="code" required/>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-6">
                            <div class="form-group">
                                <label>Description</label>
                                <input  type="text" class="form-control" id="description" name="description" required/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-6">
                            <div class="form-group">
                                <label>Unit</label>
                                <input  type="text" class="form-control" id="unit" name="unit" required/>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-6">
                            <div class="form-group">
                                <label>Unit price</label>
                                <input  type="text" class="form-control" id="unit_price" name="unit_price" required/>
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                                <label>Quantity</label>
                                <input  type="text" class="form-control" id="quantity" name="quantity" required/>
                            </div>
                        </div>
                    </div>



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-update">Update</button>
                </div>
            </form>
        </div>

        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- Modal Trigger -->