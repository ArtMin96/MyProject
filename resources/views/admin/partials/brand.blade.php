<select name="brand_id" class="form-control" data-toggle="select" {{isset($disabled) ? 'disabled': ''}}>
    <option value="{{null}}" disabled selected>Choose Brand</option>
    @foreach($brands as $brand)
        <option value="{{$brand->id}}" @if($$item->brand_id == $brand->id) selected @endif>{{$brand->title[config('app.locale')]}}</option>
    @endforeach
</select>
