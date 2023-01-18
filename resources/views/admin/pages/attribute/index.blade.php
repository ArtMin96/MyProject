@extends('layouts.app')

@section('content')

    <div class="mt--6" >
        <!-- Table -->
        @component('admin.components.flash_massages')@endcomponent
        <div class="row">
            <div class="col">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header">
                        <h3 class="mb-0">Table
                            <a class="btn btn-info ml-3" href="{{route('attribute.create')}}">
                                <i class="fas fa-plus"></i>
                                Add More
                            </a>
                        </h3>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-buttons">
                            <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Settings</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Settings</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($all as $row)
                                <tr>
                                    <td>{{$row->id}}</td>
                                    <td>{{$row->title[config('app.locale')]}}</td>
                                    <td>{{$row->type}}</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(32px, 32px, 0px);">
                                                <a class="dropdown-item drop_show" style="color: orange" href="{{route('attribute.show', $row->id)}}">Show <span class="float-right"><i class="ni ni-tv-2"></i></span> </a>

                                                <form method="POST" action="{{route('attribute.destroy',$row->id)}}" id="form_delete_attribute_{{$row->id}}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" data-key="{{$row->id}}" data-type="{{$row->parent_id}}"  class="dropdown-item drop_delete remove_btn_attribute text-danger" >Delete <span class="float-right"><i class="fa fa-user-times"></i></span></button>
                                                </form>

                                                <a class="dropdown-item drop_edit text-success" href="{{route('attribute.edit', $row->id)}}">Edit  <span class="float-right"><i class="ni ni-settings"></i></span></a>
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
        </div>

        @include('layouts.assets.data_table_confirm')

        <script>
            $( document ).ready(function() {
                removeAlert('remove_btn_attribute','form_delete_attribute_')
            });

        </script>

@stop
