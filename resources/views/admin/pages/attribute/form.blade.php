<div class="col-lg-12">
    <div class="card-wrapper">
        <!-- Input groups -->
        <div class="card">
            <!-- Card header -->
            <div class="card-header">
                <h3 class="mb-0">Attribute</h3>
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
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="example3cols1Input">Title <span class="text-uppercase">({{$language->code}})</span> @if($language->code == config('app.locale')) <span class="text-danger">*</span> @endif</label>
                                        <div class="input-group input-group-merge">
                                            <input name="title[{{$language->code}}]" class="form-control" value="{{isset($$item->title[$language->code]) ? $$item->title[$language->code] : old('title.'.$language->code)}}" placeholder="Age"  type="text" @if($language->code == config('app.locale')) required @endif>
                                            <div class="input-group-append">
                                                <span class="input-group-text"><img class="input_img" src="/assets/img/language/{{$language->code}}.png"></span>
                                            </div>
                                            @component('admin.components.error_box', ['name' => 'title.am'])@endcomponent
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <hr>
                <div class="row">


                    <div class="col-md-3">
                        <div class="custom-control custom-checkbox mb-3">
                            <input name="show_in_filters" class="custom-control-input change_box" value="no" type="hidden">
                            <input name="show_in_filters" class="custom-control-input change_box" value="yes" id="show_in_filters" {{$$item->show_in_filters == 'yes' ? 'checked':''}} type="checkbox">
                            <label class="custom-control-label" for="show_in_filters">Show in Filters</label>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="custom-control custom-checkbox mb-3">
                            <input name="show_in_product" class="custom-control-input change_box" value="no" type="hidden">
                            <input name="show_in_product" class="custom-control-input change_box" value="yes" id="show_in_product" {{$$item->show_in_product == 'yes' ? 'checked':''}} type="checkbox">
                            <label class="custom-control-label" for="show_in_product">Show in Product</label>
                        </div>
                    </div>

                    
                    <div class="col-md-3">
                        <div class="custom-control custom-checkbox mb-3">
                            <input name="is_required" class="custom-control-input change_box" value="0" type="hidden">
                            <input name="is_required" class="custom-control-input change_box" value="1" id="is_required" {{$$item->is_required == 1 ? 'checked':''}} type="checkbox">
                            <label class="custom-control-label" for="is_required">Is Required?</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols1Input">Group <span class="text-danger">*</span></label>
                            @include('admin.partials.attribute_group',['type'=> false])
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols1Input">Type <span class="text-danger">*</span></label>
                            <select  name="type" class="form-control" id="type">
                                <option selected disabled>Select Type</option>
                                @foreach(config('settings.attribute_type') as $key => $type)
                                    <option value="{{$key}}" class="text-capitalize" @if($key ==$$item->type ) selected @endif>{{$key}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                           
                            <label class="form-control-label" for="position">Position In Products</label>
                            <select  name="position" class="form-control" id="position">
                                <option selected disabled>Select Position</option>
                                    <option value="top" class="text-capitalize" @if($$item->position=='top' ) selected @endif>Top</option>
                                    <option value="bottom" class="text-capitalize" @if($$item->position=='bottom' ) selected @endif>Bottom</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="custom-control  mb-3">
                            <label class="form-control-label" for="color">Color Code</label>
                            <input name="color" class="form-control" value="{{$$item->color}}" id="color" type="input">
                        </div>
                    </div>
                    <div class="col-md-3">
                        @php
                        $last_id =  \App\Models\Attribute::max('order');
                        $last_id = $last_id+1;
                      @endphp
                        <div class="mb-3">
                            <label class="form-control-label" for="order">Order By</label>
                            <input name="order" class="form-control" value="{{(isset($$item->order) && $$item->order > 0 ) ? $$item->order : $last_id}}" id="order" type="input">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-control-label" for="is_collapse">Is Collapse?</label>
                            <select  name="is_collapse" class="form-control" id="is_collapse">
                                <option selected disabled></option>
                                    <option value="1" class="text-capitalize" @if($$item->is_collapse==1 ) selected @endif>Yes</option>
                                    <option value="0" class="text-capitalize" @if($$item->is_collapse==0 ) selected @endif>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-control-label" for="custom_class">Custom CSS Class</label>
                            <input name="custom_class" class="form-control" value="{{$$item->custom_class}}" id="custom_class" type="input">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-control-label" for="custom_styles">Custom CSS Styles</label>
                            <input name="custom_styles" class="form-control" value="{{$$item->custom_styles}}" id="custom_styles" type="input">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
    <script src="../../assets/vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
@endpush

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
