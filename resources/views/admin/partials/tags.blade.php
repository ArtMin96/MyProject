
@php
if(isset($name)){
    $row_name =$name;
}
else{
    $row_name = 'products';
}
@endphp


<select name="{{$row_name}}[]" class="mySelect for" multiple="multiple" style="width: 100%" {{isset($disabled) ? 'disabled': ''}}>
    @php
    $selector = 'crossSale';
       if(isset($type) && ($type == "blog" or $type == "test" )) {
           $selector = 'products';
       }
       if(isset($relation)){
           $selector = 'problem_products';
       }
    @endphp
    @foreach($products as $product_item)
        <option value="{{$product_item->id}}" {{in_array($product_item->id,$$item->{$selector}->pluck('id')->toArray())? 'selected' : ''}} >{{$product_item->title[config('app.locale')]}}</option>
    @endforeach
</select>


@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
@endpush

<script>
    var placeholder = "Select Products";
    $(".mySelect").select2({
        placeholder: placeholder,
        allowClear: false,
        minimumResultsForSearch: 5
    });
    $( document ).ready(function() {
        $('.select2-container .select2-search--inline').css('display','block');
        $('.select2-search__field').css({"margin-top": "inherit", "margin-right": "inherit"})
    });

</script>
