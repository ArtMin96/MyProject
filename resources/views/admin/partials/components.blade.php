<select name="components[]" class="mySelect for" multiple="multiple" style="width: 100%" {{isset($disabled) ? 'disabled': ''}}>
    @foreach($components as $component)
        <option value="{{$component->id}}" {{in_array($component->id,$$item->components->pluck('id')->toArray()) ? 'selected' : ''}} >{{$component->title[config('app.locale')]}}</option>
  @endforeach
</select>


@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
@endpush

<script>
    var placeholder = "Select Components";
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
