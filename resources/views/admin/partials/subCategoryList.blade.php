
@foreach($subcategories as $subcategory)
    @if($catNumId != $subcategory->id)
    @php
            $selected = '';
                if( isset($catId) && ( (!is_array($catId) && $catId == $subcategory->id) OR (is_array($catId) && in_array($subcategory->id,$catId) ) ) ) {
                  $selected =  'selected';
                }

    @endphp
    <option value="{{$subcategory->id}}" {{$selected}}>
      {{str_repeat(' ',$level*2)}}|--  {{$subcategory->title[config('app.locale')]}}
    </option>
    @endif
        @if(count($subcategory->subCategories))
            @php
                $level++;
            @endphp
            @include('admin.partials.subCategoryList',['subcategories' => $subcategory->subCategories,'level'=> $level++,'catId'=>$catId,'catNumId'=>$catNumId])
        @endif

@endforeach
