<select id="categorySelect" name="blog_category_id" class="form-control" id="blog_category_id">
    <option value="{{null}}">Not Selected</option>

    @foreach($blog_categories as $category)
    @php
        $selected = '';
            if($category->id == $$item->blog_category_id ) {
              $selected =  'selected';
            }
    @endphp
            <option value="{{$category->id}}" {{$selected}}>
                {{$category->title[config('app.locale')]}}
            </option>
    @endforeach
</select>


