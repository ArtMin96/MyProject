<select  name="parent_id" class="form-control">
    <option value="0">Choose Parent</option>
    @if($parents->where('position','Navigation Menu')->count() > 0)
        <optgroup label="Navigation Menu">
            @foreach($parents->where('position','Navigation Menu') as $parent)
                <option value="{{$parent->id}}"  @if($$item->parent_id == $parent->id))  selected @endif >
                    {{$parent->title[config('app.locale')]}}
                </option>
            @endforeach
        </optgroup>
    @endif
    @if($parents->where('position','Footer Menu')->count()> 0)
        <optgroup label="Footer Menu">
            @foreach($parents->where('position','Footer Menu') as $parent)
                <option value="{{$parent->id}}" @if($$item->parent_id == $parent->id))  selected @endif >
                    {{$parent->title[config('app.locale')]}}
                </option>
            @endforeach
        </optgroup>
     @endif
</select>

