@foreach($orderDetail->history as $order)

    <div class="order_table_data order_detail_table">
        <div class="row">
            <div class="col-12 col-md-3">
                <img src="{{$order->product->main_image}}">
            </div>
            <div class="col-12 col-md-6 vert_mid">
                {{jToS($order->description['title'])}}
            </div>

            <div class="col-12 col-md-3 vert_mid" style="justify-content: right;">
                <div class="text-right">
                    {{$order->price}} ֏
                    <span style="display:block;font-weight: 600;font-size: 15px;line-height: 18px;letter-spacing: -0.02em;color: #9D9D9D;">@lang('messages.quantity'): {{$order->count}}</span>
                </div>
            </div>
        </div>
    </div>
@endforeach

