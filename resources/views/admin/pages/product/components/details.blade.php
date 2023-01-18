<div class="card-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-control-label" for="slug">Slug</label>
                <input name="slug" class="form-control" type="text" placeholder="Slug" id="slug" value="{{old('slug') ?? $$item->slug}}">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-control-label" for="brand">Brand</label>
                @include('admin.partials.brand')
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-control-label" for="price">Price <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input name="price" class="form-control" min="1" type="number" placeholder="Price" id="price" value="{{old('price') ?? $$item->price}}" aria-label="Dram amount (with dot and two decimal places)" >
                    <div class="input-group-append">
                        <span class="input-group-text">֏</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-control-label" for="SKU">SKU</label>
                <input type="text" name="SKU" class="form-control" placeholder="SKU" id="SKU" value="{{old('SKU') ?? $$item->SKU}}">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-control-label" for="weight">Weight </label>
                <input name="weight" class="form-control" type="text" string placeholder="Weight in Gramms" id="weight" value="{{old('weight') ?? $$item->weight}}">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-control-label" for="parent_id">Cross Sale</label>
                @include('admin.partials.products')
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-control-label" for="parent_id">Category</label>
                @include('admin.partials.category_dropdown',['name' => 'categories','type'=>'multiple','is_productPage'=>true])
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-control-label" for="parent_id">Tags</label>
                @include('admin.partials.product_tags')
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-control-label" for="parent_id">Components</label>
                @include('admin.partials.components')
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-control-label" for="status">Status</label>
                <select  name="status" class="form-control" id=status">
                    <option value="1" @if($$item->status === 1) selected @endif>Show</option>
                    <option value="0" @if($$item->status === 0) selected @endif>Hide</option>
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group  focused">
                <label class="form-control-label" for="main_image">Main Image</label>
                <div class="mb-2">
                    <img id="image" src="@if($$item->main_image == null)/assets/img/icons/no-photo.png @else{{$$item->main_image}} @endif" class="img_cover" >
                </div>

                <input type="file" id="img" name="main_image" class="file-upload-default" style="display: none"  onchange="previewFile()">
                <div class="input-group col-xs-12">
                    <input type="text" class="form-control file-upload-info" placeholder="Choose Main Image"  disabled="" onchange="previewFile()">
                    <span class="input-group-append">
                                <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                             </span>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var placeholder = "Select Categories";
    $("#categorySelect").select2({
        placeholder: placeholder,
        allowClear: false,
        minimumResultsForSearch: 5,
        templateSelection: format
    });
    $( document ).ready(function() {
        $('.select2-container .select2-search--inline').css('display','block');
        $('.select2-search__field').css({"margin-top": "inherit", "margin-right": "inherit"})
    });
    function format(item) {
        var val = item.text.replace('|--','').trim()
        return val;
    }

</script>
