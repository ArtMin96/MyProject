
<section>
    <div class="spacing-80"></div>
    <div class="container">

        <div class="d-flex flex-row justify-content-center product_content">
            <div class="p-2 best_buy ">
                <h2>
                    @lang('messages.best_buy')
                </h2>
            </div>
            <div class="p-2 show_many ">
                <a href="{{route('best.buy')}}" class="">
                     <span>
                  @lang('messages.view')
            </span>
                </a>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="main-gallery js-flickity" data-flickity='{ "groupCells": true }'>

        @foreach($products_best as $top_product)
            <div class="col-12 col-md-4 featured_container">
                <div style="flex: 1;" class="gallery-cell best_buy_slider ">
                    <a href="{{route('product.page',$top_product->slug)}}">
                        <img src="{{getImage($top_product->main_image)}}">
                    </a>
                        <div class="mt-2">
                            <div class="best-buy_title">
                                <h3><a class="text-dark" href="{{route('product.page',$top_product->slug)}}">{{jToS($top_product->title)}}</a></h3>
                            </div>
                            <div class="best_buy_price">
                                <span class="">
                                    ֏ @if($top_product->sale) {{newPrice($top_product->price,$top_product->sale)}} @else {{$top_product->price}} @endif
                                </span>

                                @if($top_product->sale)
                                <span class="sale_price">
                                    ֏{{$top_product->price}}
                                </span>
                                @endif
                                @if($top_product->options->where('color','main')->count() > 0 or $top_product->options->count()==0 )
                                <a href="javascript:void(0)" class=""  {{addToCartBtnCheckStockStatus($product)}}>
                                   <i class="far fa-shopping-cart"></i>
                                </a>
                                @endif
                            </div>
                            <div style="clear: both"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="spacing-80"></div>
    <script>
        $('.main-gallery').flickity({
            // options
            prevNextButtons: !!'{{$products->count() > 0}}',
            cellAlign: 'left',
            pauseAutoPlayOnHover: true,
            pageDots: false,
            freeScroll: true,
            contain: true,
            autoPlay: true,
            wrapAround: true,
        });

    </script>
</section>
