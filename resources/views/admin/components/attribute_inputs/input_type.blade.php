@foreach(config('settings.attribute_type') as $key => $types)
    @switch($options->type)
        @case($key)
            @if($options->type =='range')
                @include('admin.components.attribute_inputs.inputs',
                    ['type'=>$options->type,'name'=>"attribute[$options->group_id][$options->attribute_id][value][from]",'placeholder'=>'From','value'=>null])
                @include('admin.components.attribute_inputs.inputs',
                        ['type'=>$options->type,'name'=>"attribute[$options->group_id][$options->attribute_id][value][to]",'placeholder'=>'To','value'=>null])
            @else
                @include('admin.components.attribute_inputs.inputs',
                        ['type'=>$options->type,'name'=>"attribute[$options->group_id][$options->attribute_id][value]",'value'=>null])
            @endif
        @break
    @endswitch
@endforeach


