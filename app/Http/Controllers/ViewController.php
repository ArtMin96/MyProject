<?php

namespace App\Http\Controllers;
use App\Mail\OrderSuccess;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Coupons;
use App\Models\Faq;
use App\Models\Blog;
use App\Models\Orders;
use App\Models\Sale;
use App\Models\SaleCoupon;
use App\Models\Partner;
use App\Models\Cities;
use App\Models\Test;
use App\Models\Language;
use App\Models\TestResult;
use App\Models\Work;
use App\Models\Wishlist;
use App\Models\PaymentSetting;
use App\Models\Product;
use App\Models\UserAddress;
use App\Models\Info;
use App\Models\ProductReview;
use App\Models\RegisterSettings;
use App\Orders\OrderDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\ProductFilter;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\ProductAttribute;
use App\Jobs\SendContactEmail;
use App\Models\Promo;
use App\Models\Countries;
use App\Models\States;
use DB;

class ViewController extends Controller
{

    /**
     * @param $slug
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function  product($slug)
    {
        $product = Product::where('slug',$slug)->where('status',1)->with('crossSale')->first();
        $seo = (object) [
            'image' => url(getImage($product->main_image)),
            'meta_description' => meta_data($product,'title'),
            'meta_title' => meta_data($product,'description')
        ];
        if($product){

            return view('front.pages.product.index', compact('product','seo'));
        }
        else{
            abort(404);
        }

    }

   /**
     * @param $slug
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function  page($slug)
    {
        if(isset($slug)) {
        $page = Info::where('slug',$slug)->first();
            if(isset($page)) {
                $seo = (object) [
                    'image' => url(getImage($page->main_image)),
                    'meta_description' => Str::words(strip_tags(jToS($page->description)),10),
                ];
                    return view('front.pages.static.'.$page->type)->with('page',$page);
            }

        }

        abort(404);

    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function  faq()
    {
        $questions = Faq::orderBy('order_by','desc')->where('status',1)->get();

        return view('front.pages.faq.index',compact('questions'));
    }


    public function  policy()
    {
        return view('front.pages.policy.index');
    }


    public function  search()
    {
        if(request()->has('search')){
            $products = Product::whereRaw(
                'LOWER(`title`) LIKE ? ',['%'.trim(strtolower(session('search_value') )).'%'])->where('status',1)->paginate(16);

            return view('front.pages.search.index',compact('products'));
        }
        return redirect()->back();

    }


    /**
     * @param $slug
     * @param $sort
     * @param $type
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function shop($slug=NULL)
    {
        $filtersToShow = ProductFilter::join('attributes', 'product_filters.attribute_id', '=', 'attributes.id')->orderBy('attributes.order', 'asc')->select('product_filters.*')->paginate(10);

        return view('front.pages.category.index')->with('filtersToShow',$filtersToShow);

    }

    public function getAjaxProducts() {
        $currlang = app()->getLocale();
        if(request()->ajax()) {
            $products =  Product::where('status',1);
            $result = $products;
            if(request()->has('category')) {
                $cats = request('category');
                $result = $result->with('categories')->whereHas('categories',function($query) use ($cats) {
                        $query->where(function ($query) use ($cats) {
                            foreach($cats as $cat) {
                                $query->orWhere('category_id', $cat);
                            }
                        });
                });

            }
            if(request()->has('tags') && !empty(request('tags'))) {
                $tags = request('tags');
                $result = $result->with('tags')->whereHas('tags',function($query) use ($tags) {
                        $query->where(function ($query) use ($tags) {
                            foreach($tags as $tag) {
                                $query->orWhere('tag_id', $tag);
                            }
                        });
                });
            }


            if(request()->has('search') && !empty(request('search'))) {
                $keyword = strip_tags(request('search'));

                $result = $result->whereRaw(
                    'LOWER(`title`) LIKE ? ',['%'.trim(mb_strtolower($keyword)).'%']);
            }
            if(request()->has('range') && !empty(request('range'))) {
                if(isset(request('range')['from']) && isset(request('range')['to'])) {
                    $result = $result->whereBetween('price',[request('range')['from'],request('range')['to']]);
                }
           }

           if(request()->has('sort_by')) {
            switch (request('sort_by')) {
                case 'recent':
                   $result = $result->orderBy('is_new','desc')->orderBy('created_at','desc');
                    break;
                case 'rating':
                    $result = $result->orderBy('rating','desc');
                break;
                case 'price_high_to_low':
                    $result = $result->orderBy('price','asc');
                break;
                case 'alphabetical':
                    $result = $result->orderBy('title->'.$currlang,'asc');
                break;
                case 'price_low_to_high':
                    $result = $result->orderBy('price','desc');
                break;

                case 'top_sales':
                    $result = $result->with('orders')->withCount('orders')->orderBy('orders_count', 'DESC');
                break;
            }
        }else {
            $result = $result->orderBy('created_at','desc')->orderBy('is_new','desc');
        }

        $filteredItems = ProductFilter::get();
        foreach($filteredItems as $attributes) {
           $attrSlug = $attributes->slug;
                if(request()->has($attrSlug)) {
                    $result = $result->with('attribute_values')->whereHas('attribute_values',function($query) use ($attrSlug,$attributes) {
                        $needles = request()->$attrSlug;
                            if($attributes->attribute->type != 'range'){
                                $query->where(function ($query) use ($needles) {
                                    $query->where(function ($query) use ($needles){
                                        foreach($needles as $needle) {
                                            $query->orWhereJsonContains('value', [$needle]);
                                        }
                                    });
                                });
                            }else {
                                $query->where(function ($query) use ($needles) {
                                    $query->where(function ($query) use ($needles) {
                                        $query->where(function ($query) use ($needles) {
                                            if((isset($needles['from']) && isset($needles['to'])) && (!empty($needles['from']) && !empty($needles['from']))) {
                                                $query->orWhere('value->from','>=',intval($needles['from']));
                                                $query->orWhere('value->from','<=',intval($needles['to']));
                                            }
                                        });
                                        $query->where(function ($query) use ($needles) {
                                            if((isset($needles['from']) && isset($needles['to'])) && (!empty($needles['from']) && !empty($needles['from']))) {
                                                $query->orWhere('value->to','>=',intval($needles['from']));
                                                $query->orWhere('value->to','<=',intval($needles['to']));
                                            }
                                        });
                                    });
                                });
                                // $query->where(function ($query) use ($needles) {
                                //     $query->where(function ($query) use ($needles) {
                                //         $query->where(function ($query) use ($needles) {
                                //             if((isset($needles['from']) && isset($needles['to'])) && (!empty($needles['from']) && !empty($needles['from']))) {

                                //                 $query->where('value->from','>=',intval($needles['from']));
                                //                 $query->where('value->from','<=',intval($needles['to']));
                                //             }
                                //         });
                                //         $query->orWhere(function ($query) use ($needles) {
                                //             if((isset($needles['from']) && isset($needles['to'])) && (!empty($needles['from']) && !empty($needles['from']))) {
                                //                 // $query->where('value->from','>=',-1);
                                //                 // $query->where('value->to','>=',$needles['to']);
                                //                 // $query->where('value->to','<=',$needles['to']);
                                //                 $query->where('value->to','>=',$needles['to']);
                                //                 $query->where('value->from','<=',$needles['to']);
                                //             }
                                //         });
                                //     });
                                // });
                            }
                    });
                }

        }
        $style=null;
        if(request()->has('view_style') && request('view_style') == 'list') {
            $style = 'list';
        }

            $result = $result->where('out_stock',0)->paginate(16)->appends(request()->except('page'));
            return view('front.pages.product.product_items_widget')->with('products',$result)->with('columns',3)->with('pagination',true)->with('style', $style)->render();
        }

        return redirect()->route('shop');
    }


    public function ajaxLiveSearch(Request $request) {
        $products =  Product::where('status',1);
        $result = $products;
        if(request()->has('search') && !empty(request('search'))) {
            $keyword = strip_tags(request('search'));

            $result = $result->whereRaw(
                'LOWER(`title`) LIKE ? ',['%'.trim(mb_strtolower($keyword)).'%']);
        }
        return view('front.layouts.includes.live_search_results')->with('products',$result->limit(5)->get())->render();
    }
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function  bestBuy()
    {
        $best_products = Product::orderBy('orders','desc')->where('status',1)->take(20)->get();

        return view('front.pages.best_buy.index',compact('best_products'));
    }


    public function  cartPage()
    {
        if(Cookie::get('cart_id') != Null) {
        $cart = Cart::where('session_id',Cookie::get('cart_id'))->get();
        }else {
            $cart = collect();
            Cookie::queue('cart_id',session()->getId(),45000);
        }
        $cart_total = cart_total();
        $product_ids = $cart->pluck('product_id')->toArray();
        if(count($product_ids) > 0) {
            $getCross = DB::table('cross_sales')->whereIn('product_id',$product_ids)->pluck('related_id')->toArray();
            $similar = Product::whereIn('id',$getCross)->inRandomOrder()->whereNotIn('id',$product_ids)->where('out_stock',0)->limit(8)->get();
        }else {
            $similar = Product::inRandomOrder()->where('out_stock',0)->limit(8)->get();
        }

        return view('front.pages.cart.index',compact('cart','similar','cart_total'));
    }



    public  function checkout()
    {
        $cart = Cart::where('session_id',Cookie::get('cart_id'))->get();
        if($cart->count() == 0){
            return  redirect()->back()->with('massage','Զամբյուղը դատարկ է');
        }
        $is_commentable = 0;
        foreach ($cart as $item) {

             if(isset($item->products->is_commentable) && $item->products->is_commentable == 'yes') {
                    $is_commentable++;
                }
        }
        $cart_total = cart_total();
        $address = getShippingAddress();
        if(!$address) {
            return redirect()->route('profile.page')->with('massage',__('messages.please_add_delivery_address'));
        }

        $payment_part = PaymentSetting::where('status',1)->get();
        return view('front.pages.checkout.index',compact('cart_total','payment_part','is_commentable','address'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function coupon(){
        $row_gift = Coupons::where('code',request('code'));
        $row_sale = SaleCoupon::where('code',request('code'));

        if($row_gift->count()>0 and $row_sale->count() < 1 and $row_gift->first()->value >0){
            $session = Session::put('couponId',['id'=>$row_gift->first()->id,'type'=>'gift','code'=>request('code')]);
        }
        elseif($row_sale->count()>0 and $row_gift->count()<1){
            $session = Session::put('couponId',['id'=>$row_sale->first()->id,'type'=>'sale_card','code'=>request('code')]);
        }
        else{

           # return redirect()->back()->with('sale_error','Կոդը սխալ է կամ այլևս չի գործում:');
            Session::put('sale_error','Կոդը սխալ է կամ այլևս չի գործում:');
            return response()
            ->json(['success'=>true , 'data' => '' ]);
           #return false;
        }
        #return redirect()->back()->with('sale_suc','Կոդը կիրառված է:');
        Session::put('sale_suc','Կոդը կիրառված է');
        return response()
        ->json(['success'=>true , 'data' => '' ]);

    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public  function profile()
    {
        $additional_settings = RegisterSettings::orderBy('order_by','desc')->where('status',1)->get();

        $orders = Orders::where('user_id',auth()->user()->id)->paginate(4);

        return view('front.pages.profile.index',compact('additional_settings','orders'));
    }




//   -------------------------------------------- Ajax methods---------------------------------


    public function saleRemove(){
        Session::forget('couponId');
        return true;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function cartSend()
    {
        $product = Product::findOrFail(request()->id);
        $price = newPrice($product->price,$product->sale);

        if($product->out_stock == 0) {
            if(request()->has('quantity') && request('quantity') != null) {
                if(request('quantity') >= 1) {
                    Cart::updateOrCreate([ 'session_id'=>Cookie::get('cart_id'), 'product_id'=>request()->id, 'amount' => $price],['count'=>request('quantity')]);
                }
            }else {
                Cart::updateOrCreate([ 'session_id'=>Cookie::get('cart_id'), 'product_id'=>request()->id, 'amount' => $price])->increment('count');
            }
        $cart_count = Cart::where('session_id',Cookie::get('cart_id'))->count();

        return   response()
            ->json(['success'=>true , 'data' => $cart_count ]);

    }
        $cart_count = Cart::where('session_id',Cookie::get('cart_id'))->count();
        return   response()
        ->json(['success'=>false , 'data' => $cart_count ]);

    }


    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function cartManipulate()
    {
        switch (request()->type) {
            case 'increment':
                Cart::find(request()->id)->increment('count');
                break;
            case 'decrement':
                $check = Cart::find(request()->id);

                if($check->count <= 1) {
                    $check->delete();
                }else {
                    $check->decrement('count');
                }
                break;
            case 'delete':
                Cart::find(request()->id)->delete();
                break;
            default:
                'No Actions were made';
        }
        $cart_count =Cart::where('session_id',Cookie::get('cart_id'))->count();
        return response()
            ->json(['cart'=>$this->refreshCart(),'cart_count' => $cart_count]);
    }


    /**
     * @return array|string
     * @throws \Throwable
     */
    public function refreshCart() {
        $cart = Cart::where('session_id',Cookie::get('cart_id'))->get();
        $cart_total = cart_total();
        return view('front.pages.cart.includes.cart_form_items')->with('cart',$cart)->with('cart_total',$cart_total)->render();
    }


    public function worksLike()
    {
        $work = Work::find(request('id'));
        if (!in_array(auth()->user()->id,$work->users()->get()->pluck('id')->toArray()))
        {
            if (request('type') == 'add') {
                $work->users()->attach(auth()->user()->id);
                return Response::json(['type' => true, 'count' => $work->users()->count()], 200);
            }

        } else {
            $work->users()->detach(auth()->user()->id);
            return Response::json(['type' => false, 'count' => $work->users()->count()], 200);
        }

    }


    //    --------------------------------End------------------------------


    /**
     * @return array|string
     * @throws \Throwable
     */
    public function test() {

        $questions = Test::where('status',1)->get();

        return view('front.pages.test.index',compact('questions'));
    }

    public function testResult($coin) {

       $coin_type= explode('_',$coin);

        if(isset($coin_type)){
            $result = TestResult::where('coin_from',$coin_type[0])->where('coin_to','>=',$coin_type[1])->first();
        }

        if($result == null){
           return abort(404);
        }
        return view('front.pages.test.result',compact('result'));
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function works() {

        $works = Work::get();

        return view('front.pages.work.index',compact('works'));
    }



    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function worksStart() {

        return view('front.pages.work.start');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function worksRegister() {

        return view('front.pages.work.register');
    }


    public function workSend() {

        if(Work::where('user_id',auth()->user()->id)->count() > 0){
            return  redirect()->back()->with('massage','Error message');
        }

      request()->validate([
            'user_interest' => 'required',
            'name' => 'required',
            'description' => 'required',
            'image' => 'required',
        ]);

        $row = Work::create([
            'name' =>request('name'),
            'user_id' =>auth()->user()->id,
            'description' =>request('description'),
        ]);

        if (request()->has('image') and request()->image != null) {
            $row->update([
                'image' =>'/storage/'.request()->image->store('/works','public')
            ]);
        }

        return redirect()->route('works');
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function blogs(){
        $newPosts = Blog::paginate(16);
        if(request()->has('search') && !empty(request('search'))) {
            $newPosts = Blog::where('title','LIKE','%'.request('search').'%')->orWhere('content','LIKE','%'.request('search').'%')->paginate(16)->appends(request()->except('page'));

        }
        return view('front.pages.blog.listing')
        ->with('newPosts',$newPosts);
    }


    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    function fetchBlog(Request $request)
    {
     if($request->ajax())
     {
        switch ($request->type) {
            case 'cat':
                $data = Blog::where('blog_category_id',1)->paginate(7);
              break;
            case 'top':
                $data = Blog::orderBy('hits','DESC')->paginate(7);
              break;
            default:
                $data = Blog::paginate(7);
          }

      return view('front.pages.blog.includes.load_more')->with('blogPosts',$data)->render();
     }
    }

    /**
     * @param $slug
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function blogPost($slug){
        $blogPost = Blog::where('slug',$slug)->first();
        $otherPosts = Blog::inRandomOrder()->limit(3)->get();
        $seo = (object) [
            'image' => url(getImage($blogPost->main_image)),
            'meta_description' => meta_data($blogPost,'title'),
            'meta_title' => meta_data($blogPost,'description')
        ];
        if($blogPost){
            if(!Session::get($slug)) {
                Session::put($slug, 1);
                $blogPost->increment('hits');
            }
            return view('front.pages.blog.detail')->with('post',$blogPost)->with('other',$otherPosts)->with('seo',$seo);
        }
        else{
            abort(404);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function productReview(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'required',
            'rating' => 'required',
        ]);
        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        if(Auth::check()) {
            if(ProductReview::where('user_id',Auth::user()->id)->where('product_id',$request->product_id)->count() >= 1) {
                return back()->with('massage','Դուք արդեն թողել եք Ձեր կարծիքը');
            }

            $data = [
                'title' => $request->title,
                'product_id' => $request->product_id,
                'description' => $request->description,
                'rating' => $request->rating,
                'user_id' => Auth::user()->id,
                'status' => 1
            ];
            ProductReview::create($data);
            $product = Product::find($request->product_id);
            $count = $product->reviews->count();
            $total = $product->reviews->sum('rating');
            $last = round(($total/$count) * 2) / 2;
            $product->update(['rating'=>$last]);


        }
        return back()->with('success','Շնորհակալություն կարծիքի համար');
    }


//    --------------------------------Pyment------------------------------

    /**
     * @param OrderDetails $orderDetails
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */

    public  function paymentStore ( OrderDetails $orderDetails){
        $cart = Cart::where('session_id',Cookie::get('cart_id'));
        if($cart->count() == 0) {
            return redirect('/');
        }

        $validator = Validator::make(request()->all(), [
            'shipping_phone' => ['nullable','regex:/\d|\s|\+|\-/m'],
        ],[
            'shipping_phone.regex'=>'Հեռախոսահամարը սխալ է'
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

       return $orderDetails->order((object) request()->all());

    }

    /**
     * @param OrderDetails $orderDetails
     */
    public  function checkPayment (OrderDetails $orderDetails){
      return   $orderDetails->check();

    }


    public  function successPayment (OrderDetails $orderDetails){

      return   $orderDetails->success();

    }

    public  function failPayment (OrderDetails $orderDetails){

     return    $orderDetails->fail();

    }


    /**
     * @param $order_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public  function orderSuccess ( $order_id){
        if(auth()->check()) {
            $order = Orders::where('order_id',$order_id)->where('user_id',auth()->user()->id)->firstOrFail();
                if(isset($order)) {
                    Cart::where('session_id',Cookie::get('cart_id'))->delete();
                    if(null !== config('mail.from.address') && $order->mail_sent_at === null) {
                        try {
                            dispatch(new \App\Jobs\SendMail($order,'order_details',$order->user->email));
                            $order->update([
                                'mail_sent_at'=> Carbon::now()
                            ]);
                        }catch(\Exception $e) {

                        }
                    }
                    return view('front.pages.order.success')->with('order',$order);
                }

        }
        abort(404);
    }

    public function  mail(){
        $row  = Orders::where('id',14)->first();
        return view('mail',compact('row'));
    }
    public function ajaxProducts() {
        //dd(request()->all());
         if(null === (request('filters'))) {
            $products = Product::paginate(16);
             if (request()->ajax()) {
                return view('front.pages.product.product_items_widget', ['products'=>$products,'columns'=>3])->render();
             }
         }

     }

     public function partners(){
         $partners = Partner::orderBy('order_by','asc')->get();
         if(request()->has('city')) {
            $partnerList = Partner::orderBy('order_by','asc')->whereIn('city_id',request('city'))->get();
         }else{
            $partnerList = $partners;
        }
         return view('front.pages.partners.index')
         ->with('partners',$partners)
         ->with('partnerList',$partnerList);
     }

     public function findStore() {
        if(request()->has('city') && !empty(request('city'))) {
            $city = Cities::where('city','LIKE','%'.strip_tags(request('city')).'%')->first();
            if(isset($city)){
                return redirect()->route('partners',['city[]'=>$city->id]);
            }
            return redirect()->route('partners');
        }
     }
     public function ajaxCartWidget() {
        $cart = Cart::where('session_id',Cookie::get('cart_id'))->get();
        return response()->json(['cart'=>view('front.layouts.partials.cart_widget')->with('cart',$cart)->render(),'count' => $cart->count()]);
     }

     public function orders() {

        if(Auth::check()) {
            if(request()->has('date') && !empty(request('date'))) {
                $dates = [
                    Carbon::createFromFormat('d/m/Y', request('date.from')),
                    Carbon::createFromFormat('d/m/Y', request('date.to')),
                ];
                $orders = Orders::where('user_id',auth()->user()->id)->whereBetween('created_at',$dates)->orderBy('created_at','desc')->paginate(10);
            }else{
                $orders = Orders::where('user_id',auth()->user()->id)->orderBy('created_at','desc')->paginate(10);
            }
            return view('front.pages.profile.orders')->with('orders',$orders);
        }

        return  redirect()->route('login');
     }

     public function orderDetails($id) {
        if(Auth::check()) {
            $orderDetail = Orders::findOrFail($id);
            if($orderDetail->user_id == auth()->user()->id) {
                return view('front.pages.profile.order_details')->with('orderDetail',$orderDetail);
            }
        }
        abort(404);
     }

     public function ajaxAddAddress() {
        return view('front.pages.profile.modules.address_box')->with('type','new')->render();
     }
     public function ajaxRemoveAddress() {
         $id = request('id');
         $user = auth()->user()->id;
         $addr = UserAddress::where('user_id',$user)->where('id',$id);
         if($addr->first()->is_default != 1) {
            $addr->delete();
            return response()
         ->json(['success'=>true , 'id' => $id ]);
         }
         return response()
         ->json(['success'=>false , 'id' => $id ]);
     }

     public function ajaxEditAddress() {
         $id = request('id');
         $user = auth()->user()->id;
         $addr = UserAddress::where('user_id',$user)->where('id',$id)->first();
         return view('front.pages.profile.modules.address_box')->with('type','edit')->with('address',$addr)->render();
     }

     public function ajaxChooseAddress() {
        $id = request('id');
        $user = auth()->user()->id;
        Session::put('deliveryAddress', $id);
        return true;
    }

     public function ajaxUpdateAddress() {
         if(!request()->has('is_default')) {
            request()->merge(['is_default' =>0]);

         }

         $id = request('id');
         $user = auth()->user()->id;

         if( auth()->user()->address->where('id',$id)->where('is_default',1)->count() == 1 ) {
            request()->merge(['is_default' =>1]);
        }
        if(request()->has('is_default') && request('is_default') == 1) {
            UserAddress::where('user_id',$user)->update(['is_default'=>0]);
         }
        $addr = UserAddress::where('user_id',$user)->where('id',$id)->update(request()->except('_token'));
        return back();
    }
     public function ajaxStoreAddress() {
         if(!request()->has('is_default')) {
            request()->merge(['is_default' =>0]);
         }
        $user = auth()->user()->id;
        if(request()->has('is_default') && request('is_default') == 1) {
            UserAddress::where('user_id',$user)->update(['is_default'=>0]);
         }
         request()->merge(['user_id' =>$user]);
         if(auth()->user()->address->count() == 0 ) {
            request()->merge(['is_default' =>1]);
         }

        $addr = UserAddress::create(request()->except('_token','new_modal_address'));
        if(request()->has('new_modal_address')) {
            return view('front.pages.profile.modules.address_box')->with('type','show_in_modal')->with('address',$addr)->render();
        }
        return back();
    }

    public function contact() {
        return view('front.pages.contacts.index');
    }
    public function contactMessage() {
        dispatch(new SendContactEmail(request()->all()));
        return back()->with('success',__('messages.successfuly_sent'));
    }

    public function setLocale(Request $request)
    {
        $locale = $request->input('locale');
        $return_url = $request->input('return_url');
        $languages = Language::get()->pluck('code')->toArray();

        $url = explode('/',$return_url);
        if(isset($url[3]) && in_array($url[3],$languages)) {
            $return_url = str_replace($url[3],$locale,$return_url);
        }
        session(['locale' => $locale]);
        return redirect($return_url);
    }

    public function addWishlistAjax() {
        if(request()->has('id')) {
            if(Cookie::get('cart_id') == Null) {
                Cookie::queue('cart_id',session()->getId(),45000);
            }
            $id = request('id');
            $product = Product::findOrFail($id);

            if(auth()->check()) {
                $user_id = auth()->user()->id;
                Wishlist::updateOrCreate(['product_id'=>$product->id,'user_id'=>$user_id]);
            }else {
                $session_id = Cookie::get('cart_id');
                Wishlist::updateOrCreate(['product_id'=>$product->id,'session_id'=>$session_id]);
            }
            return   response()
            ->json(['success'=>true , 'data' => '22' ]);
        }
    }

    public function removeWishlistAjax() {
        if(request()->has('id')) {
            if(Cookie::get('cart_id') == Null) {
                Cookie::queue('cart_id',session()->getId(),45000);
            }
            $id = request('id');
            $product = Product::findOrFail($id);

            if(auth()->check()) {
                $user_id = auth()->user()->id;
                Wishlist::where('product_id',$product->id)->where('user_id',$user_id)->delete();
            }else {
                $session_id = Cookie::get('cart_id');
                Wishlist::where('product_id',$product->id)->where('session_id',$session_id)->delete();
            }
            return   response()
            ->json(['success'=>true , 'data' => '22' ]);
        }
    }

    public function getUserWishlist() {
            if(Cookie::get('cart_id') == NULL) {
                Cookie::queue('cart_id',session()->getId(),45000);
            }
            if(auth()->check()) {
                $user_id = auth()->user()->id;
               $items = Wishlist::where('user_id',$user_id)->pluck('product_id');
            }else {
                $session_id = Cookie::get('cart_id');
                $items = Wishlist::where('session_id',$session_id)->pluck('product_id');
            }
            $products = Product::whereIn('id',$items)->paginate(16);
            return view('front.pages.wishlist.index')->with('items',$items)->with('products',$products);
    }

    public function ajaxRemoveCard() {
        $id = request('id');
        $user = auth()->user()->cards->where('id',$id);
        if($user->count() == 1) {
            $user->first()->delete();
            return response()
            ->json(['success'=>true , 'id' => $id ]);
        }
     }

     public function getPromotions() {

        $promos = (new Promo)->activePromos();
        if($promos->count() > 0) {
            return view('front.pages.cart.includes.promo_items')->with('promos',$promos->get())->render();
        }
     }

     public function getCountriesAjax(Request $request) {
        if($request->has('search') && !empty($request->search) ) {
            $countries_list = Countries::select('id','country')->whereRaw('UPPER(country) LIKE ?', ['%' . strtoupper($request->search) . '%'])->where('id',11)->get();
            //whereRaw('UPPER('{country}') LIKE ?', ['%' . $request->search . '%'])
        }else {
            $countries_list = Countries::select('id','country')->where('id',11)->get();
        }
        return response()->json($countries_list);
     }
     public function getStatesByCountryAjax(Request $request) {
        $states_list = null;
        if($request->has('country') && !empty($request->country)) {
            $states_list = States::select('id','state')->where('country_id',$request->country)->get();
        }
        if($request->has('search') && !empty($request->search) && $request->has('country') && !empty($request->country)) {
            $states_list = States::select('id','state')->where('country_id',$request->country)->whereRaw('UPPER(state) LIKE ?', ['%' . strtoupper($request->search) . '%'])->get();
        }
        return response()->json($states_list);
     }
     public function getCitiesByStateAjax(Request $request) {
        $cities_list = null;
        if($request->has('state_id') && !empty($request->state_id)) {
            $cities_list = Cities::select('id','city')->where('state_id',$request->state_id)->get();
        }
        if($request->has('search') && !empty($request->search) && $request->has('country') && !empty($request->country)) {
            $cities_list = States::select('id','city')->where('state_id',$request->state_id)->whereRaw('UPPER(city) LIKE ?', ['%' . strtoupper($request->search) . '%'])->get();
        }
        return response()->json($cities_list);
     }
    //-----------------------------------------END------------- ----------------
}


