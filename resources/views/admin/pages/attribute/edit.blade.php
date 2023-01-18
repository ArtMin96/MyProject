@extends('layouts.app')

@section('content')

    <form method="post" action="{{route('attribute.update',$$item->id)}}" >
        @method('PUT')
        @csrf
        <div class="row">
            @include('admin.pages.attribute.form')
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
