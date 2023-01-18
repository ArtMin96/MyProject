@php $att_row = collect() @endphp
@php if(!isset($req)) $req = 1;   @endphp
@foreach($attribute_group  as $group_key => $group)
    <div class="row py-4 @if($req == 1)group_{{$group->id}} @else requ_box @endif">
        <div class="col-md-12">
            <label class="form-control-label text-capitalize">{{jToS($group->title)}}</label>
            <hr class="mt-3">
            @foreach($group->attributes->where('is_required','!=',$req) as $key => $attribute)
                @if(isset($$item->exists))
                    @php $att_row = $$item->attribute_values->where('attribute_id',$attribute->id)->where('attribute_group_id',$group->id)@endphp
                @endif
                <div class="row">
                    <div class="col-md-4">
                        <div class="custom-control custom-checkbox mb-3">
                            <input hidden name="attribute[{{$group->id}}][{{$attribute->id}}][attribute_group_id]" value="{{$group->id}}">
                            <input hidden name="attribute[{{$group->id}}][{{$attribute->id}}][attribute_id]" value="{{$attribute->id}}">
                            <input data-group-key="{{$group->id}}" data-attribute-key="{{$attribute->id}}"  name="attribute[{{$group->id}}][{{$attribute->id}}][type]"
                                   class="custom-control-input change_box {{$req == 0 ? 'req_box':''}}" value="{{$attribute->type}}"
                                   id="att_{{$group->id}}_{{$key}}" type="checkbox"
                                   @if($att_row->count()>0 or $req == 0 ) checked @endif >
                            <label class="custom-control-label" for="att_{{$group->id}}_{{$key}}">{{jToS($attribute->title)}}</label>
                        </div>
                    </div>
                    <div class="col-md-8" id="content">
                        @if($att_row->count()>0 )
                            @php $name_g = $att_row->first()->attribute_group_id @endphp
                            @php $name_a = $att_row->first()->attribute_id;  @endphp
                            @if($att_row->first()->type =='range')
                                @include('admin.components.attribute_inputs.inputs',
                                    ['type'=>$att_row->first()->type,'name'=>"attribute[$name_g][$name_a][value][from]",'placeholder'=>'From','value'=>$att_row->first()->value["from"]])
                                @include('admin.components.attribute_inputs.inputs',
                                        ['type'=>$att_row->first()->type,'name'=>"attribute[$name_g][$name_a][value][to]",'placeholder'=>'To','value'=>$att_row->first()->value["to"]])
                            @else
                                @include('admin.components.attribute_inputs.inputs',
                                        ['type'=>$att_row->first()->type,'name'=>"attribute[$name_g][$name_a][value]",'value'=>implode(',',$att_row->first()->value)])
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <hr class="my-0">
@endforeach

<script>



    $(".change_box").change(function() {
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
                    $(content).html(response)
                    $(".tagsinp").tagsinput({
                        tagClass: 'badge badge-primary'
                    })

                }
            })
        }
        else{
            $(content).html('')
        }
    });
</script>

