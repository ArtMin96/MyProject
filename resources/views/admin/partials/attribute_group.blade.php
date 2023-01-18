@if($type == 'is_product')
    @php($rel = 'attributes')
@else
    @php($rel = 'groups')
@endif
<select id="attribute_group" name="attribute_group[]" class="mySelect for" multiple="multiple" style="width: 100%" {{isset($disabled) ? 'disabled': ''}}>
    @foreach($attribute_groups as $group)
        <option value="{{$group->id}}" {{in_array($group->id,$$item->$rel->pluck('id')->toArray()) ? 'selected' : ''}} >{{$group->title[config('app.locale')]}}</option>
  @endforeach
</select>


@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
@endpush

<script>
    var placeholder = "Select Group";
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
