@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-8">
            <div class="card-wrapper">
                <!-- Input groups -->
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header">
                        <h3 class="mb-0">General</h3>
                    </div>

                    <!-- Card body -->
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
                                            <label class="form-control-label">Title </label>
                                            <div class="input-group input-group-merge">
                                                <input class="form-control"  value="{{isset($$item->title[$language->code])?$$item->title[$language->code]:''}}" disabled>
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><img class="input_img" src="/assets/img/language/{{$language->code}}.png"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-control-label" for="how_to_use">How To Use <span class="text-uppercase">({{$language->code}})</span></label>
                                            <div class="input-group input-group-merge">
                                                <textarea  class="form-control" disabled >{{isset($$item->how_to_use[$language->code]) ? $$item->how_to_use[$language->code] : ''}}</textarea>
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><img class="input_img" src="/assets/img/language/{{$language->code}}.png"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-control-label" for="description">Description <span class="text-uppercase">({{$language->code}})</span> @if($language->code == config('app.locale')) <span class="text-danger">*</span> @endif</label>
                                            <div class="input-group input-group-merge">
                                                <div  class="form-control disabled_block">{!!isset($$item->description[$language->code])? $$item->description[$language->code] :''!!}</div>
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><img class="input_img" src="/assets/img/language/{{$language->code}}.png"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-control-label" >Additional <span class="text-uppercase">({{$language->code}})</span></label>
                                            <div class="input-group input-group-merge">
                                                <div  class="form-control disabled_block" >{!!isset($$item->additional[$language->code]) ? $$item->additional[$language->code] : ''!!}</div>
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><img class="input_img" src="/assets/img/language/{{$language->code}}.png"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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
                        <h3 class="mb-0">Details</h3>
                    </div>
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="stock">Slug</label>
                                    <input  class="form-control" value="{{$$item->slug}}" disabled>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="stock">Stock</label>
                                    <input  class="form-control" value="{{$$item->stock}}" disabled>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="brand_id">Brand</label>
                                    @include('admin.partials.brand',['disabled' => 'disabled'])
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="price">Price <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" value="{{$$item->price}}" aria-label="Dram amount (with dot and two decimal places)" disabled>
                                        <div class="input-group-append">
                                            <span class="input-group-text">֏</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="SKU">SKU</label>
                                    <input type="text"  class="form-control"  value="{{$$item->SKU}}" disabled>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="parent_id">Cross Sale</label>
                                    @include('admin.partials.products',['disabled' => 'disabled'])
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="parent_id">Category</label>
                                    @include('admin.partials.category_dropdown',['name' => 'categories','type'=>'multiple','is_productPage'=>true,'disabled' => 'disabled'])
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="parent_id">Components</label>
                                    @include('admin.partials.components',['disabled' => 'disabled'])
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group  focused">
                                    <label class="form-control-label" for="image">Main Image </label>
                                    <div class="mb-2">
                                        <img id="image" src="@if($$item->main_image == null)/assets/img/icons/no-photo.png @else{{$$item->main_image}} @endif" class="img_cover" >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-wrapper">
                <!-- Input groups -->
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header">
                        <h3 class="mb-0">
                            <span class="mr-3">Sale</span>
                        </h3>
                    </div>
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="sale_type">Sale Type</label>
                                    <select  name="sale_type" class="form-control" id="sale_type" disabled>
                                        <option value="{{NULL}}">Not Selected</option>
                                        @foreach(config('app.sale_types') as $sale_key => $sale_type)
                                            <option value="{{$sale_key}}" @if ( $$item->sale and $sale_key == $$item->sale->type) selected @endif >{{$sale_type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label  for="sale" class="form-control-label">Sale</label>
                                    <input  class="form-control" value="@if($$item->sale) {{$$item->sale->value}} @endif"  disabled>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="sale_from">Start Date</label>
                                    <input class="form-control" type="text" placeholder="Start Date" value="@if($$item->sale){{$$item->sale->start_data}} @endif"  disabled >
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" >End Date</label>
                                    <input class="form-control" type="text" value="@if($$item->sale) {{$$item->sale->end_data}} @endif"  placeholder="End Date" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="set_block">
        <a class="btn btn-success" href="{{route('product.edit', $$item->id)}}">
            <span>
                <i class="ni ni-settings"></i>
            </span>
        </a>
        <a class="btn btn-danger" href="{{route('product.index')}}">
                <span>
                    <i class="fas fa-times"></i>
                </span>
        </a>
    </div>


    <script>
        var placeholder = "Select Categories";
        $("#categorySelect").select2({
            placeholder: placeholder,
            allowClear: false,
            minimumResultsForSearch: 5,
            templateSelection: format
        });
        $( document ).ready(function() {
            $('.select2-container .select2-search--inline').css('display','block');
            $('.select2-search__field').css({"margin-top": "inherit", "margin-right": "inherit"})
        });
        function format(item) {
            var val = item.text.replace('|--','').trim()
            return val;
        }

    </script>


@stop
