<select name="blogs[]" class="mySelectBlog for" multiple="multiple" style="width: 100%" {{isset($disabled) ? 'disabled': ''}}>

    @foreach($blog_items as $blog_item)
        <option value="{{$blog_item->id}}" {{in_array($blog_item->id,$$item->blogs->pluck('id')->toArray())? 'selected' : ''}} >{{$blog_item->title[config('app.locale')]}}</option>
    @endforeach
</select>


@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
@endpush

<script>
    var placeholder = "Select Blogs";
    $(".mySelectBlog").select2({
        placeholder: placeholder,
        allowClear: false,
        minimumResultsForSearch: 5
    });
    $( document ).ready(function() {
        $('.select2-container .select2-search--inline').css('display','block');
        $('.select2-search__field').css({"margin-top": "inherit", "margin-right": "inherit"})
    });

</script>

