@extends('layouts.app')

@section('content')
    <div class="mt--6" >
        <!-- Table -->
        @component('admin.components.flash_massages')@endcomponent
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header">
                        <h3 class="mb-0">Table
                            <a class="btn btn-info ml-3" href="{{route('product.create')}}">
                                <i class="fas fa-plus"></i>
                               Add More
                            </a>
                        </h3>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-buttons">
                            <thead class="thead-light">
                            <tr>
                                <th>
                                    ID
                                    <div class="custom-control custom-checkbox d-inline-block ml-2">
                                        <input class="custom-control-input" id="customCheckAll" type="checkbox">
                                        <label class="custom-control-label" for="customCheckAll"></label>
                                    </div>
                                </th>
                                <th>Title</th>
                                <th>SKU</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Main Image</th>
                                <th>Settings</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>SKU</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Main Image</th>
                                <th>Settings</th>

                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($all as $row)
                                <tr class="list_row">
                                    <td>
                                        {{$row->id}}
                                        <div class="custom-control custom-checkbox d-inline-block ml-2">
                                            <input class="custom-control-input" id="customCheck{{$row->id}}" type="checkbox">
                                            <label class="custom-control-label" for="customCheck{{$row->id}}"></label>
                                        </div>
                                    </td>
                                    <td>{{$row->title[config('app.locale')]}}</td>
                                    <td>{{$row->SKU}}</td>
                                    <td>{{$row->price}}</td>
                                    <td>{!!$row->out_stock == 1 ? '<span class="text-danger">Out of Stock</span>' : '<span class="text-success">In Stock</span>'!!}</td>
                                    <td style="font-size: 18px">
                                        @if($row->status == 1)
                                            <i class="fas fa-eye text-success"></i>
                                        @else
                                            <i class="fas fa-eye-slash text-danger"></i>
                                        @endif

                                    </td>
                                    <td class="text-center"><img src="@if($row->main_image){{$row->main_image}} @else /assets/img/icons/no-photo.png  @endif"  class="list_img"></td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(32px, 32px, 0px);">
                                                <a class="dropdown-item drop_show" style="color: orange" href="{{route('product.show', $row->id)}}">Show <span class="float-right"><i class="ni ni-tv-2"></i></span> </a>

                                                <form method="POST" action="{{route('product.destroy',$row->id)}}" id="form_delete_product_{{$row->id}}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" data-key="{{$row->id}}" data-type="{{$row->parent_id}}"  class="dropdown-item drop_delete remove_btn_product text-danger" >Delete <span class="float-right"><i class="fa fa-user-times"></i></span></button>
                                                </form>
                                                <a class="dropdown-item drop_edit text-info" href="{{route('product.duplicate', $row->id)}}">Duplicate  <span class="float-right"><i class="ni ni-settings"></i></span></a>
                                                <a class="dropdown-item drop_edit text-success" href="{{route('product.edit', $row->id)}}">Edit  <span class="float-right"><i class="ni ni-settings"></i></span></a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="set_block">
                <button type="button" id="sal_add" class="btn btn-block btn-success mb-3" data-toggle="modal" data-target="#modal-add_sale"><i class="fas fa-plus"></i> Add Sale</button>
            </div>

            @include('admin.components.modals')
        </div>
        @include('layouts.assets.data_table_confirm')

        @push('styles')
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        @endpush

        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        @endpush

        <script>
            $( document ).ready(function() {

                $("#customCheckAll").click(function(){
                    $('input:checkbox').not(this).prop('checked', this.checked);
                });

                removeAlert('remove_btn_product','form_delete_product_')

                $("#sale_from, #sale_to").flatpickr({
                    altInput: true,
                    altFormat: "F j, Y H:i",
                    enableTime: true,
                    dateFormat: "Y-m-d H:i",
                });
            });
                $('#sal_add').click(function () {
                    if($('input[type=checkbox]').prop('checked') == true)
                    {
                       alert(1);
                    }
                    else{
                        alert(2)
                    }
                })

        </script>

@stop
