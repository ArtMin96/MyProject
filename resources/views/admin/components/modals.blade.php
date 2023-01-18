{{------------------------------------------------Sale modal --------------------------------------------}}

<div class="col-md-4">
    <div class="modal fade" id="modal-add_sale" tabindex="-1" role="dialog" aria-labelledby="modal-add_sale" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="modal-title-default">Select Sale Details</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form method="POST" action="">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="products" value="" id="product_ids">
                                <div class="form-group">
                                    <label class="form-control-label" for="sale_type">Sale Type <span class="text-danger">*</span></label>
                                    <select  name="sale_type" class="form-control" id="sale_type" required>
                                        <option value="{{NULL}}">Not Selected</option>
                                        @foreach(config('app.sale_types') as $sale_key => $sale_type)
                                            <option value="{{$sale_key}}">{{$sale_type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label  for="sale" class="form-control-label">Sale <span class="text-danger">*</span></label>
                                    <input  class="form-control" name="sale" type="number" min="1" id="sale"  required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="sale_from">Start Date</label>
                                    <input  type="text" name="sale_from" id="sale_from" placeholder="Start Date" >
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="sale_to">End Date</label>
                                    <input type="text" name="sale_to" id="sale_to" placeholder="End Date" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{------------------------------------------------ End --------------------------------------------}}
