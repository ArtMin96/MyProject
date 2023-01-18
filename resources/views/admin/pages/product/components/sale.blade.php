<div class="card-body sale_block">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-control-label" for="sale_type">Sale Type <span class="text-danger">*</span></label>
                <select  name="sale_type" class="form-control" id="sale_type" required>
                    <option value="{{NULL}}">Not Selected</option>
                    @foreach(config('app.sale_types') as $sale_key => $sale_type)
                        <option value="{{$sale_key}}" @if ( $$item->sale and $sale_key == $$item->sale->type) selected @endif>{{$sale_type}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label  for="sale" class="form-control-label">Sale <span class="text-danger">*</span></label>
                <input  class="form-control" name="sale" type="number" min="1" id="sale" value="@if($$item->sale){{$$item->sale->value}}@else{{old('sale')}}@endif" required>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <label class="form-control-label" for="sale_from">Start Date</label>
                <input value="@if($$item->sale) {{$$item->sale->start_data}} @else{{old('sale_from')}} @endif" type="text" name="sale_from" id="sale_from" placeholder="Start Date" >
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <label class="form-control-label" for="sale_to">End Date</label>
                <input type="text" name="sale_to" id="sale_to" placeholder="End Date"  value="@if($$item->sale) {{$$item->sale->end_data}} @else{{old('sale_to')}} @endif">
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){


        if($('#check_sale').prop("checked") == true){
            $('.sale_block').css('display','block')
            $('.sale_block input').prop('disabled',false)
            $('#sale_type').attr('disabled',false)
        }
        else if($('#check_sale').prop("checked") == false){
            $('.sale_block').css('display','none')
            $('.sale_block input').prop('disabled',true)
            $('#sale_type').attr('disabled',true)
        }

        $('#check_sale').click(function(){
            if($(this).prop("checked") == true){
                $('.sale_block').css('display','block')
                $('.sale_block input').prop('disabled',false)
                $('#sale_type').attr('disabled',false)
            }
            else if($(this).prop("checked") == false){
                $('.sale_block').css('display','none')
                $('.sale_block input').prop('disabled',true)
                $('#sale_type').attr('disabled',true)
            }
        });
    });


    $("#sale_from, #sale_to").flatpickr({
        altInput: true,
        altFormat: "F j, Y H:i",
        enableTime: true,
        dateFormat: "Y-m-d H:i",
    });
</script>
