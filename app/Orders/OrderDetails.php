<?php


namespace App\Orders;


use App\Billing\GatewayContract;
use App\Models\Cart;
use App\Models\Coupons;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class OrderDetails
{
    private $paymentGateway;

    private $price;

    private $products;

    private  $shipping;

    private  $coupon;
    private  $code;

    /**
     * OrderDetails constructor.
     * @param GatewayContract $gatewayContract
     */
    public function __construct(GatewayContract $gatewayContract)
    {
        $this->paymentGateway = $gatewayContract;
        $this->shipping = 0;
    }

    /**
     * @param $string
     * @return string|string[]
     */
    public function transList($string) {
        $roman = array("Sch","sch",'Yo','Zh','Kh','Ts','Ch','Sh','Yu','ya','yo','zh','kh','ts','ch','sh','yu','ya','A','B','V','G','D','E','Z','I','Y','K','L','M','N','O','P','R','S','T','U','F','','Y','','E','a','b','v','g','d','e','z','i','y','k','l','m','n','o','p','r','s','t','u','f','','y','','e');
        $cyrillic = array("Щ","щ",'Ё','Ж','Х','Ц','Ч','Ш','Ю','я','ё','ж','х','ц','ч','ш','ю','я','А','Б','В','Г','Д','Е','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Ь','Ы','Ъ','Э','а','б','в','г','д','е','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','ь','ы','ъ','э');
        $am = array ('Ա'  => 'A', 'Բ'  => 'B', 'Գ'  => 'G', 'Դ'  => 'D', 'Ե'  => 'E', 'Զ'  => 'Z', 'Է'  => 'E', 'Ը'  => 'Y', 'Թ'  => 'T', 'Ժ'  => 'ZH', 'Ի'  => 'I', 'Լ'  => 'L', 'Խ'  => 'X', 'Ծ'  => 'C', 'Կ'  => 'K', 'Հ'  => 'H', 'Ձ'  => 'J', 'Ղ'  => 'GH', 'Ճ'  => 'TCH', 'Մ'  => 'M', 'Յ'  => 'Y', 'Ն'  => 'N', 'Շ'  => 'SH', 'Ո'  => 'VO', 'Չ'  => 'CH', 'Պ'  => 'P', 'Ջ'  => 'J', 'Ռ'  => 'R', 'Ս'  => 'S', 'Վ'  => 'V', 'Տ'  => 'T', 'Ր'  => 'R', 'Ց'  => 'C', 'ՈՒ' => 'U', 'Ւ'  => 'W', 'Փ'  => 'P', 'Ք'  => 'K', 'և'  => 'EV', 'Օ'  => 'O', 'Ֆ'  => 'F', 'ա'  => 'a', 'բ'  => 'b', 'գ'  => 'g', 'դ'  => 'd', 'ե'  => 'e', 'զ'  => 'z', 'է'  => 'e', 'ը'  => 'y', 'թ'  => 't', 'ժ'  => 'zh', 'ի'  => 'i', 'լ'  => 'l', 'խ'  => 'x', 'ծ'  => 'c', 'կ'  => 'k', 'հ'  => 'h', 'ձ'  => 'j', 'ղ'  => 'gh', 'ճ'  => 'tch', 'մ'  => 'm', 'յ'  => 'y', 'ն'  => 'n', 'շ'  => 'sh', 'ո'  => 'o', 'չ'  => 'tch', 'պ'  => 'p', 'ջ'  => 'j', 'ռ'  => 'r', 'ս'  => 's', 'վ'  => 'v', 'տ'  => 't', 'ր'  => 'r', 'ց'  => 'c', 'ու' => 'u', 'ւ'  => 'u', 'փ'  => 'p', 'ք'  => 'k', 'օ'  => 'o', 'ֆ'  => 'f', );
        $return_ru = str_replace($cyrillic, $roman, $string);

        return str_replace(array_keys($am),$am,$return_ru);
    }


    /**
     * @param $address
     */
    public function shipping($address)
    {
       #if($address['state'] == 'Երևան' and $this->price < 10000){
          # $this->shipping = 500;
       #}
    }


    /**
     * @param $data
     * @return mixed
     */

    public function  order($data)
    {

        $cart_total = cart_total();
        $address = getShippingAddress();
        if(!$address){
            return  redirect()->back()->with('val_mess','Խնդրում ենք լրացնել հասցեի պարտադիր դաշտերը');
        }
        $this->shipping($address);
        $cart = Cart::where('session_id',Cookie::get('cart_id'))->get();

        $fullAddress = getCountryByID($address->country).', '.getStateByID($address->state).', '.$address->address;
        $priceWithShipping = $cart_total->total + $this->shipping;
        $paymentData = (object)[
            'price'=> $priceWithShipping,
            'description' =>$this->transList($fullAddress.' ,price -'.$priceWithShipping.' AMD'),
            'payment_id' => time(),
            'products' => $cart,
        ];
        if(request()->has('remember_card')) {
            if(request('remember_card') == 'yes') {
                Session::put('save_card_data','yes');
            }
        }
        $createData = [
            'user_id' => auth()->user()->id,
            'method' => request()->pay_type,
            'amount' =>  $priceWithShipping,
            'status' => 'uncompleted',
            'details' => $paymentData->description ,
            'payment_id' =>$paymentData->payment_id,
            'delivery_address' => $fullAddress,
            'is_gift' => isset($data->is_gift) ? $data->is_gift : NULL,
            'delivery_status' => 'On Hold',
//            'delivery_time' => $data->day .' - ('.$data->delivery_time.')',
            'shipping_cost' => $cart_total->shipping,
            'currency' =>$this->paymentGateway->currency,
            'total_count' =>$cart->sum('count'),
            'coupon_code' => $data->code,
            'order_note'=> isset($data->comment) ?$data->comment : NULL,
            'shipping_phone'=> isset($data->shipping_phone) ?$data->shipping_phone : NULL,
            'coupon_discount' => giftCard($cart_total->total)->sale_price,
        ];
      return $this->paymentGateway->startPayment($paymentData,$createData);

    }


    /**
     * @return mixed
     */
    public function  check()
    {
      return  $this->paymentGateway->checkPayment();
    }


    /**
     * @return mixed
     */
    public function  success()
    {
      return   $this->paymentGateway->success();
    }

    /**
     * @return mixed
     */
    public function  fail()
    {
        return  $this->paymentGateway->fail();
    }


}
