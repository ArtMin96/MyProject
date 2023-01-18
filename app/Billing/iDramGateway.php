<?php


namespace App\Billing;


use App\Jobs\SendMail;
use App\Models\OrderHistory;
use App\Models\Orders;
use App\Models\PaymentSetting;
use App\User;
use http\Client\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use App\Models\Cart;
use Omnipay\Omnipay;

class iDramGateway implements GatewayContract
{
    /**
     * @var
     */
    public $currency;

    private $lang;

    public function __construct($currency)
    {
        $this->currency = $currency;

        $this->lang = App::getLocale();


    }

    public  function  props() {

        return (object)PaymentSetting::where('key','idram')->first()->credentials;
    }

    /**
     * @param $props
     * @param $language
     * @return \Omnipay\Common\GatewayInterface
     */
    public function initPayment() {

        $gateway = Omnipay::create('Idram');

        $gateway->setAccountId($this->props()->account_id);
        $gateway->setSecretKey($this->props()->secret_key);
        $gateway->setParameter('language',strtoupper($this->lang));

        return $gateway;
    }

    /**
     * @param $paymentData
     * @param $createData
     * @return \Illuminate\Http\RedirectResponse
     */

    public function startPayment($paymentData,$createData)
    {
        try{

             $gateway = $this->initPayment();

            $gateway->setParameter('amount',$paymentData->price);

            $gateway->setParameter('transactionId',$paymentData->payment_id);

            $gateway->setParameter('email','info@xaxalove.am');


            $purchase = $gateway->purchase()->send();
            $row = Orders::create($createData);

            $row->update(['order_id' =>$purchase->getData()['EDP_BILL_NO']]);


            foreach ($paymentData->products as $key => $product){

                OrderHistory::create([
                    'order_id' => $row->id,
                    'product_id' => $product->product_id,
                    'count' => $product->count,
                    'price' => $product->amount,
                    'description' => [
                        'title' =>$product->products->title,
                        'price' => $product->products->price,
                        'description'=>$product->products->description,]
                ]);
            }

            $purchase->redirect();

        }catch(\Exception $exception) {
            return redirect()->route('checkout.page');
        }
    }


    /**
     * @return Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function checkPayment()
    {
        $payment = Orders::where('order_id', request('EDP_BILL_NO'))->first();
        if(request()->has('EDP_PRECHECK') && request()->has('EDP_BILL_NO') &&
            request()->has('EDP_REC_ACCOUNT') && request()->has('EDP_AMOUNT') )
        {

            if(request('EDP_PRECHECK') == "YES")
            {
                if(request('EDP_REC_ACCOUNT') == $this->props()->account_id)
                {
                    echo("OK");
                }
            }
        }

        if(request()->has('EDP_PAYER_ACCOUNT') && request()->has('EDP_BILL_NO') &&
                request()->has('EDP_REC_ACCOUNT') && request()->has('EDP_AMOUNT')
                        && request()->has('EDP_TRANS_ID') && request()->has('EDP_CHECKSUM'))
        {
            $txtToHash =
                $this->props()->account_id . ":" .
                request('EDP_AMOUNT') . ":" .
                $this->props()->secret_key . ":" .
                request('EDP_BILL_NO') . ":" .
                request('EDP_PAYER_ACCOUNT') . ":" .
                request('EDP_TRANS_ID') . ":" .
                request('EDP_TRANS_DATE');
                if(strtoupper(request('EDP_CHECKSUM')) != strtoupper(md5($txtToHash)))
                {
                    $payment->update(['status' => 'error']);
                }
                else
                {
                    $payment->update(['status' => 'Completed']);
                    $histories = Cart::where('session_id',Cookie::get('cart_id'))->get();
                    #dispatch(new SendMail($payment));
                    couponResult();
                    echo("OK");
                }
        }


    }


    public function success()
    {

        if(Cookie::has('cart_id')) {
           Cart::where('session_id',Cookie::get('cart_id'))->delete();
        }


        return redirect()->route('order.success',request()->EDP_BILL_NO);

    }

    public function fail()
    {
        return redirect('/checkout')->with('massages', 'Վճարումը չհաջողվեց');
    }
}
