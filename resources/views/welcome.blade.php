@extends('front.layouts.app')

@section('content')

{{--    <a href="{{getNavUrl($a)}}">{{jToS($a->title)}}</a>--}}
    @include('front.layouts.partials.slider')

    @include('front.layouts.partials.flickers')
    
    @include('front.layouts.partials.intro')

    @include('front.layouts.partials.products')

    @include('front.layouts.partials.partners')
    {{-- @include('front.layouts.partials.best_buy')

    <div class="spacing-80"></div>

    @include('front.layouts.partials.products')

    @include('front.layouts.partials.blog')

    <div class="spacing-80"></div>

    @include('front.layouts.partials.test')

    @include('front.layouts.partials.other')

    <div class="spacing-80"></div>

    @include('front.layouts.partials.how_to_buy') --}}

    <!--include('front.layouts.partials.instagram') -->


@stop
