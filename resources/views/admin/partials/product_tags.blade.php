<select name="product_tags[]" class="TagsSelect for" multiple="multiple" style="width: 100%" {{isset($disabled) ? 'disabled': ''}}>
    @foreach($tags as $tag)
        <option value="{{$tag->id}}" {{in_array($tag->id,$$item->productByTag->pluck('id')->toArray()) ? 'selected' : ''}} >{{$tag->title[config('app.locale')]}}</option>
    @endforeach
</select>


@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
@endpush

<script>
    var placeholder = "Select Tags";
    $(".TagsSelect").select2({
        placeholder: placeholder,
        allowClear: false,
        minimumResultsForSearch: 5
    });
    $( document ).ready(function() {
        $('.select2-container .select2-search--inline').css('display','block');
        $('.select2-search__field').css({"margin-top": "inherit", "margin-right": "inherit"})
    });

</script>
