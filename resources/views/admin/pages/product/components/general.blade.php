<div class="card-body">
    <div class="nav-wrapper">
        <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
            @foreach($languages as $language)
                <li class="nav-item">
                    <a class="text-capitalize nav-link mb-sm-3 mb-md-0 @if($loop->first) active @endif" id="{{$language->code}}-tab" data-toggle="tab" href="#{{$language->code}}" role="tab" aria-controls="{{$language->code}}" aria-selected="true">{{$language->name}}</a>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="tab-content" id="myTabContent">
        @foreach($languages as $language)
            <div class="tab-pane fade @if($loop->first) show  active @endif" id="{{$language->code}}" role="tabpanel" aria-labelledby="{{$language->code}}-tab">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-control-label" for="example3cols1Input">Title @if($language->code == config('app.locale')) <span class="text-danger">*</span> @endif</label>
                        <div class="input-group input-group-merge">
                            <input name="title[{{$language->code}}]" class="form-control" value="{{isset($$item->title[$language->code]) ? $$item->title[$language->code] : old('title.'.$language->code)}}" placeholder="Title"  type="text" @if($language->code == config('app.locale'))  @endif>
                            <div class="input-group-append">
                                <span class="input-group-text"><img class="input_img" src="/assets/img/language/{{$language->code}}.png"></span>
                            </div>
                            @component('admin.components.error_box', ['name' => 'title.am'])@endcomponent
                        </div>
                    </div>
                </div>
                @include('admin.partials.meta_tags',['lang'=>$language->code])
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-control-label" >How To Use <span class="text-uppercase">({{$language->code}})</span></label>
                        <div class="input-group input-group-merge">
                            <textarea  name="how_to_use[{{$language->code}}]" class="form-control summernote" @if($language->code == config('app.locale')) @endif  >{{isset($$item->how_to_use[$language->code]) ? $$item->how_to_use[$language->code] : old('how_to_use.'.$language->code)}}</textarea>
                            <div class="input-group-append">
                               
                            </div>
                            @component('admin.components.error_box', ['name' => 'how_to_use.am'])@endcomponent
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-control-label" >Description <span class="text-uppercase">({{$language->code}})</span> @if($language->code == config('app.locale')) <span class="text-danger">*</span> @endif</label>
                        <div class="input-group input-group-merge">
                            <textarea  name="description[{{$language->code}}]" class="form-control summernote" @if($language->code == config('app.locale')) @endif  >{{isset($$item->description[$language->code]) ? $$item->description[$language->code] : old('description.'.$language->code)}}</textarea>
                            @component('admin.components.error_box', ['name' => 'description.am'])@endcomponent
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-control-label" >Additional <span class="text-uppercase">({{$language->code}})</span></label>
                        <div class="input-group input-group-merge">
                            <textarea name="additional[{{$language->code}}]" class="form-control" @if($language->code == config('app.locale')) @endif  >{{isset($$item->additional[$language->code]) ? $$item->additional[$language->code] : old('additional.'.$language->code)}}</textarea>
                            @component('admin.components.error_box', ['name' => 'additional.am'])@endcomponent
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <hr>
    @if(isset($type) and $type == 'edit')
       @include('admin.components.image_dropzone',['url'=>route('additional.images')])
    @endif
</div>

