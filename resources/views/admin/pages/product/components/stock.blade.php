<div class="card-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-control-label" for="price">Stock </label>
                <input name="stock" class="form-control" min="1" type="number" placeholder="Stock" id="stock" value="{{old('stock') ?? $$item->stock}}">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-control-label" for="price">Bonus Points </label>
                <input name="bonus_points" class="form-control" min="1" type="number" placeholder="Bonus Points" id="bonus_points" value="{{old('bonus_points') ?? $$item->bonus_points}}">
            </div>
        </div>

        <div class="col-md-12">
            <div class="custom-control custom-checkbox mb-3">
                <input name="out_stock" class="custom-control-input change_box" {{isset($$item->out_stock) && $$item->out_stock ? 'checked' : '' }} value="1" id="out_stock" type="checkbox">
                <label class="custom-control-label" for="out_stock">Out Of Stock</label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="custom-control custom-checkbox mb-3">
                <input name="is_commentable" class="custom-control-input change_box" value="yes" id="is_commentable" {{$$item->is_commentable == 'yes' ? 'checked':''}} type="checkbox">
                <label class="custom-control-label" for="is_commentable">Ask to Comment on Checkout</label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="custom-control custom-checkbox mb-3">
                <input name="is_new" class="custom-control-input change_box" value="yes" id="is_new" {{$$item->is_new == 'yes' ? 'checked':''}} type="checkbox">
                <label class="custom-control-label" for="is_new">Is This New Item?</label>
            </div>
        </div>
    </div>
</div>


































{{--    color - stock  version-----------------------------------------------------------------------}}



{{--<div class="card-body">--}}
{{--    @if($$item->options->where('color','main')->count()>0 or empty($$item->getAttributes()) or $$item->options->count()<1)--}}
{{--        <div class="general_stock">--}}
{{--            <div class="form-group">--}}
{{--                <input name="options[0][color]" class="form-control"  type="hidden" value="main">--}}
{{--                <input name="options[0][stock]" class="form-control" min="0" type="number"  value="@if($$item->options->count()>0){{($$item->options->first()->stock)}}@endif" required placeholder="Stock">--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    @endif--}}
{{--    @if(!empty($$item->getAttributes()))--}}

{{--        <div class="general_stock" style="display: none">--}}
{{--            <div class="form-group">--}}
{{--                <input name="options[0][color]" class="form-control" disabled  type="hidden" value="main">--}}
{{--                <input name="options[0][stock]" class="form-control" min="0" disabled type="number"  value="@if($$item->options->where('color','main')->count()>0){{($$item->options->first()->stock)}}@endif" required placeholder="Stock">--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    @endif--}}
{{--    <div id="newRow" >--}}
{{--        @if($$item->options->where('color','main')->count()==0 and !empty($$item->getAttributes()))--}}
{{--            @foreach($$item->options as $key_option => $option)--}}
{{--                <div id="inputFormRow" class="row">--}}
{{--                    <div class="col-md-6">--}}
{{--                        <div class="form-group">--}}
{{--                            <input name="options[{{$key_option}}][color]" class="form-control"  type="text" required placeholder="Color"  value="{{$option->color}}">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-4">--}}
{{--                        <div class="form-group">--}}
{{--                            <input name="options[{{$key_option}}][stock]" class="form-control" min="0" type="number" required placeholder="Stock"  value="{{$option->stock}}">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-2">--}}
{{--                        <div id="removeRow" ><button class="btn btn-danger">x</button></div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @endforeach--}}
{{--        @endif--}}
{{--    </div>--}}
{{--    <div id="addRow" class="add_address_edit  mb-4">--}}
{{--        <button type="button" class="btn btn-primary float-right">+Add Color</button>--}}
{{--    </div>--}}

{{--</div>--}}

{{--<script>--}}
{{--    var counter  ='{{$key_option ?? -1}}'--}}


{{--    $(document).on('click', '#addRow', function () {--}}
{{--        counter++--}}
{{--        let html = '';--}}
{{--        html += '<div id="inputFormRow" class="row">';--}}
{{--        html += '<div class="col-md-6">';--}}
{{--        html += '<div class="form-group">';--}}
{{--        html += '<input name="options['+counter+'][color]" class="form-control"  type="text" required placeholder="Color"  value="">';--}}
{{--        html += '</div>';--}}
{{--        html += '</div>'--}}
{{--        html += '<div class="col-md-4">';--}}
{{--        html += '<div class="form-group">';--}}
{{--        html += '<input name="options['+counter+'][stock]" class="form-control" min="0" type="number" required placeholder="Stock"  value="">';--}}
{{--        html += '</div>';--}}
{{--        html += '</div>';--}}
{{--        html += '<div class="col-md-2">';--}}
{{--        html += ' <div id="removeRow" ><button class="btn btn-danger">x</button></div>';--}}
{{--        html += '</div>';--}}
{{--        html += '</div>';--}}



{{--        $('#newRow').append(html);--}}
{{--        $('.general_stock').hide()--}}
{{--        $('.general_stock input').prop('disabled',true)--}}

{{--    });--}}

{{--    $(document).on('click', '#removeRow', function () {--}}

{{--        $(this).closest('#inputFormRow').remove();--}}
{{--        console.log($('.general_stock').length)--}}
{{--        if($('#newRow').html().search('inputFormRow')<0){--}}
{{--            $('.general_stock').show()--}}
{{--            $('.general_stock input').prop('disabled',false)--}}
{{--        }--}}
{{--        if($('.general_stock').length > 1){--}}
{{--            $('.general_stock')[0].remove()--}}
{{--        }--}}

{{--    });--}}


{{--</script>--}}



{{-------------------------------------------------------------- END--}}
