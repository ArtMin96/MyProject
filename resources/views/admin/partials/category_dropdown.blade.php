@php
$catId = null;
$catNumId = null;
if(isset($is_catPage)) {
    $catId = $$item->$name;
    $catNumId = $$item->id;
}
if(isset($is_productPage)) {
    $catId = $$item->categories->pluck('id')->toArray();
}

@endphp
<select id="categorySelect" {{isset($type) ? 'multiple="multiple"':''}} name="{{isset($type) ? $name.'[]' : $name}}" class="form-control" id="{{$name}}" {{isset($disabled) ? 'disabled': ''}}>
    <option value="{{null}}">Not Selected</option>
@php
$level = 0;

@endphp
    @foreach($categories->whereNull('parent_id') as $category)
        @if($catNumId != $category->id)
    @php
        $selected = '';
            if((isset($is_catPage) && isset($category->id) && $catId == $category->id) OR ( isset($is_productPage) && is_array($catId) && in_array($category->id,$catId)) ) {
              $selected =  'selected';
            }
    @endphp

            <option value="{{$category->id}}" {{$selected}}>
                {{$category->title[config('app.locale')]}}
            </option>

    @php
    $level++;
    @endphp
        @endif
        @if(count($category->subCategories))
            @include('admin.partials.subCategoryList',['subcategories' => $category->subCategories,'level'=> $level,'catId'=>$catId,'catNumId'=>$catNumId])
        @endif

    @endforeach
</select>
