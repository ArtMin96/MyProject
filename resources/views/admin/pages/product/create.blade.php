@extends('layouts.app')

@section('content')
@php
    if(!isset($$item)) {
        $$item = $product;
    }
@endphp

    <form method="post" action="{{route('product.store')}}" enctype="multipart/form-data" id="product_form">
        @csrf
        <div class="row">
            @include('admin.pages.product.form')
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

