<?php

use App\Models\Cart;
use App\Models\Coupons;
use App\Models\Wishlist;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if(!function_exists('jToS'))
    {
        function jToS ($json) {
            $locale = App::getLocale();
            if(key_exists($locale,$json)) {
                if(isset($json[$locale]) && !empty($json[$locale])) {
                    return $json[$locale];
                }
            }
            if(!isset($json[config('app.fallback_locale')])) {
                // return $json['en'];
                foreach($json as $lang => $content) {
                    if(!empty($content)) {
                        return $content;
                    }else {
                        return '';
                    }
                }
            }else {
                return $json[config('app.fallback_locale')];
            }

        }
    }

if(!function_exists('getNavUrl'))
{
    function getNavUrl ($data) {

        if(isset($data->link)){
            $locale = App::getLocale();
            if( $locale == config('app.fallback_locale')) {
                return $data->link;
            }
            if($data->link == '/') {
                return '/'.$locale;
            }
            return '/'.$locale.$data->link;
        }
        if(isset($data->category_id)){
            $slug = \App\Models\Category::findOrFail($data->category_id)->slug;
           return route('category.page',['slug'=>$slug]);
        }

    }
}
if(!function_exists('countRating')) {
    function countRating($rating,$maxRating=5) {
        $fullStar = "<li><i class='fas fa-star'></i></li>";
        $halfStar = "<li><i class = 'fas fa-star-half-alt'></i></li>";
        $emptyStar = "<li><i class = 'fas fa-star grey'></i></li>";
        $rating = $rating <= $maxRating?$rating:$maxRating;

        $fullStarCount = (int)$rating;
        $halfStarCount = ceil($rating)-$fullStarCount;
        $emptyStarCount = $maxRating -$fullStarCount-$halfStarCount;

        $html = str_repeat($fullStar,$fullStarCount);
        $html .= str_repeat($halfStar,$halfStarCount);
        $html .= str_repeat($emptyStar,$emptyStarCount);
        $html = '<ul>'.$html.'</ul>';
        return $html;
    }
}

if(!function_exists('settings'))
{
    function settings ($key){
        if(Schema::hasTable('settings')) {
        $row = \App\Models\Settings::where('key',$key);
        if ($row->count() > 0)
        {
            $value = $row->first()->value;

            return $value;
        }
        return  config('app.group_link');
        }
    }
}


if(!function_exists('payment'))
{
    function payment ($key,$value){

        $row = \App\Models\PaymentSetting::where('key',$key);
        if ($row->count() > 0)
        {
            $result = $row->first()->$value;

            return $result;
        }
        return  '';
    }
}


if(!function_exists('newPrice'))
{
    function newPrice ($product_price,$sale)
    {
        if ($sale) {
            switch ($sale->type) {
                case 'percent':
                    $newPrice = $product_price - (($product_price * $sale->value) / 100);
                    break;
                case 'price':
                    $newPrice = $sale->value;
                    break;
                case 'minus_price':
                    $newPrice = $product_price - $sale->value;
                    break;

                default:
                    $newPrice = $product_price;
            }
            return $newPrice;
        } else {
            return $product_price;
        }
    }
}

if(!function_exists('str_arm'))
{
    function str_arm($text) {

        $armenian = array (
            'Ա'  => 'A',
            'Բ'  => 'B',
            'Գ'  => 'G',
            'Դ'  => 'D',
            'Ե'  => 'E',
            'Զ'  => 'Z',
            'Է'  => 'Ē',
            'Ը'  => 'Y',
            'Թ'  => 'T',
            'Ժ'  => 'ZH',
            'Ի'  => 'I',
            'Լ'  => 'L',
            'Խ'  => 'X',
            'Ծ'  => 'C',
            'Կ'  => 'K',
            'Հ'  => 'H',
            'Ձ'  => 'J',
            'Ղ'  => 'GH',
            'Ճ'  => 'TCH',
            'Մ'  => 'M',
            'Յ'  => 'Y',
            'Ն'  => 'N',
            'Շ'  => 'SH',
            'Ո'  => 'O',
            'Չ'  => 'CHʿ',
            'Պ'  => 'P',
            'Ջ'  => 'J',
            'Ռ'  => 'R',
            'Ս'  => 'S',
            'Վ'  => 'V',
            'Տ'  => 'T',
            'Ր'  => 'R',
            'Ց'  => 'TS',
            'ՈՒ' => 'U',
            'Ւ'  => 'W',
            'Փ'  => 'P',
            'Ք'  => 'K',
            'և'  => 'EV',
            'Օ'  => 'O',
            'Ֆ'  => 'F',
            'ա'  => 'a',
            'բ'  => 'b',
            'գ'  => 'g',
            'դ'  => 'd',
            'ե'  => 'e',
            'զ'  => 'z',
            'է'  => 'e',
            'ը'  => 'y',
            'թ'  => 'tʿ',
            'ժ'  => 'zh',
            'ի'  => 'i',
            'լ'  => 'l',
            'խ'  => 'x',
            'ծ'  => 'c',
            'կ'  => 'k',
            'հ'  => 'h',
            'ձ'  => 'j',
            'ղ'  => 'gh',
            'ճ'  => 'tch',
            'մ'  => 'm',
            'յ'  => 'y',
            'ն'  => 'n',
            'շ'  => 'sh',
            'ո'  => 'o',
            'չ'  => 'ch',
            'պ'  => 'p',
            'ջ'  => 'j',
            'ռ'  => 'r',
            'ս'  => 's',
            'վ'  => 'v',
            'տ'  => 't',
            'ր'  => 'r',
            'ց'  => 'ts',
            'ու' => 'u',
            'ւ'  => 'u',
            'փ'  => 'pʿ',
            'ք'  => 'kʿ',
            'օ'  => 'o',
            'ֆ'  => 'f',
        );
        return str_replace(array_keys($armenian), array_values($armenian), $text);
    }
}

if(!function_exists('getImage'))
{
    function getImage ($image)
    {
        if(Storage::disk('public')->exists(str_replace('/storage/','',$image))) {
            return $image;
        }
        return '/assets/img/icons/no-photo.png';
    }
}
if(!function_exists('generateSlug'))
{
    function generateSlug ($slug,$title)
    {
        if(!isset($slug)) {
            $title = $title[config('app.locale')];
            $slug = Str::slug(str_arm($title));
        }else {
            $slug = Str::slug(str_arm($slug));
        }
        return $slug;
    }
}

if(!function_exists('meta_data'))
{
    function meta_data ($item,$type)
    {
        $locale = App::getLocale();
        $data = $item->metatags->where('type',$type)->where('lang',$locale)->first();
        if(isset($data) && !empty($data))
        {
            if(isset($data->body) && !empty($data->body)) {
                return $data->body;
            }
        }

        return '';
    }
}

if(!function_exists('changeLocal'))
{
    function changeLocal ($key,$arca = null,$idram = null,$cash = null, $ameria =null)
    {
        $methods = [
          'idram' => $idram,
          'arca' => $arca,
            'ameria' => $ameria,
            'cash' => $cash,
            'saved_card' => $arca,
        ];

        if($methods[$key]){
            return $methods[$key];
        }
        return $key;
    }
}

if(!function_exists('giftCard'))
{
    function giftCard ($cartTotal)
    {
        $lastPrice=-1;
        $sale_price =0;
        $coupon_code='-';
        if(Session::has('couponId'))
        {
            $coupon_code = Session::get('couponId')['code'];
            if(Session::get('couponId')['type'] == 'gift') {
                $getGift = \App\Models\Coupons::where('id',Session::get('couponId')['id'])->first();

                if($getGift->value >=  $cartTotal) {
                    $lastPrice = 0;
                    $sale_price = $cartTotal;
                }else {
                    $lastPrice = $cartTotal-$getGift->value;
                    $sale_price = $getGift->value;
                }

            }
            if(Session::get('couponId')['type'] == 'sale_card') {

                $getGift = \App\Models\SaleCoupon::where('id',Session::get('couponId')['id'])->first();
                if(isset($getGift)){
                    $cart = Cart::where('session_id',Cookie::get('cart_id'))->whereHas('products', function ($query){
                        $query->whereDoesntHave('sale');
                    })->get();
                    $cart_sale = $cartTotal-$cart->sum('amount');

                    switch ($getGift->type) {
                        case 'percent':
                            $sale_price =(($cart->sum('amount') * $getGift->value) / 100);
                            break;
                        case 'price':
                            $sale_price = ($getGift->value*$cart->count());
                            break;
                        case 'minus_price':
                            $sale_price =$getGift->value;
                            break;
                    }
                    $lastPrice = $cart->sum('amount') - $sale_price ;
                    $lastPrice+=$cart_sale;

                }
            }
        }
        return (object)['last_price' => $lastPrice,'sale_price'=>$sale_price,'coupon_code'=>$coupon_code];
    }
}

if(!function_exists('couponResult'))
{
    function couponResult ()
    {
        if(Session::has('couponId')) {
            $cart = Cart::where('session_id',Cookie::get('cart_id'))->get();
            $cartTotal = 0;
            foreach ($cart as $item){
                $cartTotal += $item->count * $item->amount;
            }
            if(Session::get('couponId')['type'] == 'gift') {
                $getGift = Coupons::where('id',Session::get('couponId')['id'])->first();
                if($getGift->value >=  $cartTotal) {
                    $getGift->update(['value'=>$getGift->value-$cartTotal,'use_value'=>$cartTotal]);
                }else {
                    $getGift->update(['value'=>0,'use_value'=>$getGift->value+$getGift->use_value]);
                }
            }
            Session::forget('couponId');
        }
        return true;
    }
}

if(!function_exists('cart_total'))
{
    function cart_total ()
    {
        $cart = Cart::where('session_id',Cookie::get('cart_id'))->get();
        $sale = false;
        $cartTotal = 0;
        $originPrice = 0;
        $lastPrice = 0;
        $weight = 0;
        foreach ($cart as $item){
            $originPrice += $item->products->price * $item->count;
            $weight += $item->products->weight * $item->count;
            $cartTotal += $item->count * $item->amount;
            $lastPrice += $item->count * $item->amount;
        }
        if(giftCard($cartTotal)->last_price>-1){
            $lastPrice =  giftCard($cartTotal)->last_price;
            $sale = giftCard($cartTotal)->sale_price;
        }
        if(auth()->check()) {
            $shippingCost = getShippingCost($weight);
        }else {
            $shippingCost =0;
        }
        return $price_settings = (object)[
            'price' => $originPrice,
            'sale' => $originPrice-$cartTotal,
            'total'=> $lastPrice+$shippingCost,
            'shipping'=>$shippingCost
        ];

    }
}
if(!function_exists('getShippingCost'))
{
    function getShippingCost($weight) {

        $shipping_address = getShippingAddress();
        if(isset($shipping_address->country) && $shipping_address->country == 11) {
            if($shipping_address->state == 243) {
                return 0;
            }else {
              return calculateShippingPrice($weight);
            }
        }
        return 0;
    }
}

if(!function_exists('calculateShippingPrice'))
{
    function calculateShippingPrice($weight) {
      $lowerWeight = floor($weight/1000);
      $upperWeight = ceil($weight/1000);
    //   if($weight > 0) {
    //     return 1100+($upperWeight-1)*200;
    //     }
    //     return 1100;

        if($weight <= 250)  {
            return 370;
        }else if($weight >= 251 && $weight <= 500){
            return 530;
        }else if($weight >= 501 && $weight <= 1000){
            return 760;
        }else if($weight >= 1001 && $weight < 2000){
            return 1010;
        }else if($weight == 2000){
            return 1300;
        }else if($weight >= 2001){
            return 1100+($upperWeight-1)*200;
        }
    }
}

if(!function_exists('getShippingAddress'))
{
    function getShippingAddress()
    {
        if(Session::has('deliveryAddress')) {
            $address = auth()->user()->address->where('id',Session::get('deliveryAddress'))->first();
        }else {
            $address = auth()->user()->address->where('is_default',1)->first();
        }
        if(isset($address)) {
            return $address;
        }else {
            return false;
        }

    }
}

if(!function_exists('getUserWishlistedProducts'))
{
    function getUserWishlistedProducts($product_id)
    {
        if(Cookie::get('cart_id') == NULL) {
            Cookie::queue('cart_id',session()->getId(),45000);
        }
        if(auth()->check()) {
            $user_id = auth()->user()->id;
           $items_count = Wishlist::where('user_id',$user_id)->where('product_id',$product_id)->count();
        }else {
            $session_id = Cookie::get('cart_id');
            $items_count = Wishlist::where('session_id',$session_id)->where('product_id',$product_id)->count();
        }
        if($items_count >= 1) {
            return '<i class="dn-heart"></i>';
        }

        return '<i class="dn-heart-o"></i>';
    }
}

if(!function_exists('getProductAttributes'))
{
    function getProductAttributes($product,$position)
    {
           $product_attributes = $product->attribute_values()->with('attribute_parent')->whereHas('attribute_parent',function($q) use($position){
                $q->where('show_in_product','yes');
                $q->where('position',$position);
            });
            $html = '';
            if($product_attributes->count() >= 1) {
                foreach($product_attributes->get() as $attr)  {
                    if($attr->type == 'range') {
                        $special_font = '';
                        return '<div class="product_top_tag" style="background:'.$attr->attribute_parent->color.'">'.$attr->value['from'].'+</div>';
                    }else {
                        foreach($attr->value as $attr_val) {
                            $html.= '<div class="product_tags" style="background:'.$attr->attribute_parent->color.'"><a href="'.route('shop',[$attr->attribute_product_filter->slug.'[]'=>$attr_val]).'"><i class="dn-boomark"></i> '.$attr_val.'</a></div>';
                        }
                    }
                }
            }
            return $html;

    }
}



if(!function_exists('getProductTags'))
{
    function getProductTags($product)
    {
           $product_attributes = $product->productByTag;
            $html = '';
            if($product_attributes->count() >= 1) {
                foreach($product_attributes as $attr)  {
                    $html.= '<div class="product_tags" style="background:#81C993"><a href="'.route('shop',['tags[]'=>$attr->id]).'"><i class="dn-boomark"></i> '.jToS($attr->title).'</a></div>';
                }
            }
            return $html;

    }
}


if(!function_exists('getThumbnailImagePath'))
{
    function getThumbnailImagePath($mainImagePath)
    {
        if(Storage::disk('public')->exists(str_replace('/storage/','',$mainImagePath))) {
            $thumbnail = explode('.',$mainImagePath);
            $thumbnailPath = $thumbnail[0].'_sm.'.$thumbnail[1];
            if(Storage::disk('public')->exists(str_replace('/storage/','',$thumbnailPath))) {
                return $thumbnailPath;
            }
            return $mainImagePath;
        }

        return '/assets/img/icons/no-photo.png';
    }
}

if(!function_exists('showPriceWithCurrency'))
{
    function showPriceWithCurrency($price)
    {
        if(Session::has('currency_selected')) {

        }

        return $price.' <span>֏</span>';
    }
}

if(!function_exists('addToCartBtnCheckStockStatus'))
{
    function addToCartBtnCheckStockStatus($product,$showCart=null,$withCount=null)
    {
        if($product->out_stock == 0) {
            $html = 'onclick=addCart('.$product->id.')';
           if(isset($showCart) && isset($withCount) ) {
            $html = 'onclick=addCart('.$product->id.','.$showCart.',\''.$withCount.'\')';
           }
        }else {
            $html ='disabled' ;
        }
        return $html;
    }
}
if(!function_exists('is_selected'))
{
    function is_selected($setVal,$getVal)
    {
      if($setVal == $getVal) {
          return 'selected';
      }
      return '';
    }
}
if(!function_exists('partnerCategories'))
{
    function partnerCategories()
    {
      return [
          'shops'=>'shops',
          'bookstores'=>'bookstores',
          'other'=>'other'
      ];
    }
}

if ( !function_exists('getCurrentLang') )
{
	function getCurrentLang(){
      return app()->getLocale();
	}
}

if ( !function_exists('getCountryByID') )
{
	function getCountryByID($id){
      $country = \App\Models\Countries::findOrFail($id);
      return jToS($country->country);
	}
}

if ( !function_exists('getStateByID') )
{
	function getStateByID($id){
      $state = \App\Models\States::findOrFail($id);
      return jToS($state->state);
	}
}

if ( !function_exists('getCityByID') )
{
	function getCityByID($id){
      $city = \App\Models\Cities::findOrFail($id);
      return jToS($city->city);
	}
}
