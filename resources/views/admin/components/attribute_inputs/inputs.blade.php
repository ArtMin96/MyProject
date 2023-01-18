@if($type == 'radio')
    <div class="custom-control custom-radio mb-3">
        <input name="{{$name}}" class="custom-control-input" id="yes" value="1" type="radio" @if($value == 1) checked  @endif>
        <label class="custom-control-label" for="yes">Yes</label>
    </div>
    <div class="custom-control custom-radio mb-3">
        <input name="{{$name}}" class="custom-control-input" id="no" value="0" type="radio" @if($value == 0) checked  @endif>
        <label class="custom-control-label" for="no">No</label>
    </div>
@else

    <div class="col-md-12">
        <div class="form-group">
            <input type="text" name="{{$name}}" class="form-control {{$type=='select'||$type=='checkbox' ?'tagsinp':'url'}}"
                   @if($type=='select'||$type=='checkbox')data-toggle="tags"  @endif  placeholder="{{$placeholder ?? 'Value'}}"
                     value="{{$value}}" >
        </div>
    </div>
@endif
