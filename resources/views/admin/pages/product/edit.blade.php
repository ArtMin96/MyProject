@extends('layouts.app')

@section('content')

    <form method="post" action="{{route('product.update',$$item->id)}}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="row">
            @include('admin.pages.product.form',['type' =>'edit'])
        </div>
        <div class="set_block">
            <button class="btn btn-success"><i class="fas fa-check"></i></button>
            <a class="btn btn-danger" href="{{url()->previous()}}">
                <span>
                    <i class="fas fa-times"></i>
                </span>
            </a>
        </div>
    </form>
@stop

