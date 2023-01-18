@extends('front.layouts.app',['mid_body'=>true])

@section('content')

    <div class="spacing-80"></div>
    <div class="footerGrow">
        <div class="container">
            <div class="category_title text-center  mb-5">
                <h2 class="titles">
                    @lang('messages.best_buy')
                </h2>
            </div>
            <div class="row">
                @foreach($best_products as $best_product)
                    <div class="col-lg-3 col-6  category_buy_slider">
                        <a href="{{route('product.page',$best_product->slug)}}"><img src="{{getImage($best_product->main_image)}}"></a>
                        <div style="text-align: initial">
                            <div class="best-buy_title   ">
                                <h3>{{jToS($best_product->title)}}</h3>
                            </div>
                            <div class="best_buy_price">
                                <span class="">
                                    ֏ @if($best_product->sale) {{newPrice($best_product->price,$best_product->sale)}} @else {{$best_product->price}} @endif
                                </span>

                                @if($best_product->sale)
                                    <span class="sale_price">
                                    ֏ {{$best_product->price}}
                                </span>
                                @endif
                                <span class="">
                                @if($best_product->options->where('color','main')->count() > 0 or $best_product->options->count()==0 )
                                      <a href="javascript:void(0)" class="" {{addToCartBtnCheckStockStatus($product)}}>
                                            <i class="far fa-shopping-cart"></i>
                                      </a>
                                @endif
                            </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@stop
