@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12">
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
                                    <div class="row">
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
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="parent_id">Group</label>
                                    @include('admin.partials.attribute_group',['disabled' => 'disabled'])
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="parent_id">Type</label>
                                    <input class="form-control" disabled value="{{$$item->type}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="set_block">
        <a class="btn btn-success" href="{{route('attribute.edit', $$item->id)}}">
            <span>
                <i class="ni ni-settings"></i>
            </span>
        </a>
        <a class="btn btn-danger" href="{{route('attribute.index')}}">
                <span>
                    <i class="fas fa-times"></i>
                </span>
        </a>
    </div>
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
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
@stop
