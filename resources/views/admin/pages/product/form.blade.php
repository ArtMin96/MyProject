<div class="col-lg-8">
    <div class="card-wrapper">
        <!-- Input groups -->
        <div class="card">
            <!-- Card header -->
            <div class="card-header">
                <h3 class="mb-0">General</h3>
            </div>
            <!-- Card body -->
            @include('admin.pages.product.components.general')
        </div>
    </div>
    <div class="card-wrapper">
        <!-- Input groups -->
        <div class="card">
            <!-- Card header -->
            <div class="card-header">
                <h3 class="mb-0">Attributes</h3>
            </div>
            <!-- Card body -->
            <div class="card-body">
                @include('admin.pages.product.components.attribute',['attribute_group'=>$required_attr,'req' => 0])

                @include('admin.partials.attribute_group',['type' => 'is_product'])
                <div id="attribute_part">
                        @include('admin.pages.product.components.attribute',['attribute_group'=>$$item->attributes->unique('id')])
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-4">
    <div class="card-wrapper">
        <!-- Input groups -->
        <div class="card">
            <!-- Card header -->
            <div class="card-header">
                <h3 class="mb-0">Stock</h3>
            </div>
            <!-- Card body -->
            @include('admin.pages.product.components.stock')
        </div>
    </div>

    <div class="card-wrapper">
        <!-- Input groups -->
        <div class="card">
            <!-- Card header -->
            <div class="card-header">
                <h3 class="mb-0">Details</h3>
            </div>
            <!-- Card body -->
            @include('admin.pages.product.components.details')
        </div>
    </div>
    <div class="card-wrapper">
        <!-- Input groups -->
        <div class="card">
            <!-- Card header -->
            <div class="card-header">
                <h3 class="mb-0">
                    <span class="mr-3">Sale</span>
                    <label class="custom-toggle">
                        <input id="check_sale" type="checkbox" {{$$item->sale ? 'checked' : ''}} >
                        <span class="custom-toggle-slider rounded-circle" data-label-off="Off" data-label-on="ON"></span>
                    </label>
                </h3>
            </div>
            <!-- Card body -->
            @include('admin.pages.product.components.sale')
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('scripts')
    <script src="{{asset('assets/vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
    <script src="{{asset('assets/vendor/dropzone/dist/min/dropzone.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

@endpush
@php
 $var = isset($is_edit) ? $is_edit : false;
@endphp
@if($var == false)
    <script>
        $(document).ready(function() {
        $('.req_box').each(function (){
            $(this).click(function() { return false; });
            if($(this).is(":checked")){
                let content = $(this).parent().parent().next();
                let group_id =$(this).data('group-key');
                let attribute_id =$(this).data('attribute-key');
                if(this.checked) {
                    let type = $(this).val();
                    $.ajax({
                        url: "/admin/attribute-type",
                        type: "POST",
                        dataType: 'html',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {type:type,group_id:group_id,attribute_id:attribute_id},
                        success: function (response) {
                            console.log(response)
                            $(content).html(response)
                            $('.requ_box input').prop('required',true);
                            $(".tagsinp").tagsinput({
                                tagClass: 'badge badge-primary'
                            })
                        }
                    })
                }
                else{
                    $(content).html('')
                }
            }
        })

});
</script>
@endif

<script>

    $(document).on('select2:unselect select2:select', '#attribute_group', function (e) {
        let type = e.params.data.selected;
        let id = e.params.data.id;

        $.ajax({
            url: "{{route('attribute_group.type')}}",
            type: "POST",
            dataType: 'html',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {id:id,type:type},
            success: function (response) {
                if(!isNaN(response)){
                    $('.group_'+response).remove()
                }
                else{
                    $('#attribute_part').append(response);

                }


            }
        })
    })
</script>
