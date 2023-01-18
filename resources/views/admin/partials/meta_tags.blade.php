<div class="col-md-12">
    <div class="form-group">
        <label class="form-control-label" >Meta Title <span class="text-uppercase">({{$language->code}})</span></label>
        <div class="input-group input-group-merge">
            <input  name="meta[title][{{$language->code}}]" class="form-control" @if($language->code == config('app.locale')) @endif value="{{isset($$item->metatags->where('type','title')->where('lang',$language->code)->first()->body) ? $$item->metatags->where('type','title')->where('lang',$language->code)->first()->body : old('meta.title.'.$language->code)}}" />
                
            <div class="input-group-append">
                <span class="input-group-text"><img class="input_img" src="/assets/img/language/{{$language->code}}.png"></span>
            </div>
            @component('admin.components.error_box', ['name' => 'how_to_use.am'])@endcomponent
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label class="form-control-label" >Meta Description <span class="text-uppercase">({{$language->code}})</span></label>
        <div class="input-group input-group-merge">
            <input  name="meta[description][{{$language->code}}]" value="{{isset($$item->metatags->where('type','description')->where('lang',$language->code)->first()->body) ? $$item->metatags->where('type','description')->where('lang',$language->code)->first()->body : old('meta.description.'.$language->code)}}" class="form-control" @if($language->code == config('app.locale')) @endif  >
            <div class="input-group-append">
                <span class="input-group-text"><img class="input_img" src="/assets/img/language/{{$language->code}}.png"></span>
            </div>
            @component('admin.components.error_box', ['name' => 'how_to_use.am'])@endcomponent
        </div>
    </div>
</div>