<?php


namespace App\Billing;


use App\Models\OrderHistory;
use App\Models\Orders;
use App\Models\CardBinding;
use App\Models\PaymentSetting;
use App\Models\Cart;
use Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Omnipay\Omnipay;
use Session;

class ArcaGateway implements GatewayContract
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
        if($this->lang == 'am') {
            $this->lang = 'hy';
        }

    }

    public  function  props() {

        return (object)PaymentSetting::where('key','arca')->first()->credentials;
    }

    /**
     * @param $props
     * @param $language
     * @return \Omnipay\Common\GatewayInterface
     */
    public function initPayment() {

        $gateway = Omnipay::create('Arca');
        $gateway->setUserName($this->props()->username);
        $gateway->setPassword($this->props()->password);
        $gateway->setParameter('language',$this->lang);
        $gateway->setParameter('returnUrl',route('arca.check'));
        $gateway->setTestMode(payment('arca','is_test'));
        $gateway->setParameter('jsonParams',json_encode(["FORCE_3DS2"=>"true"]));
        return $gateway;
    }

    /**
     * @param $paymentData
     * @param $createData
     * @return \Illuminate\Http\RedirectResponse
     */

    public function startPayment($paymentData,$createData)
    {
        if(Cart::where('session_id',Cookie::get('cart_id'))->count() <= 0) {
            return redirect()->back()->with('message',__('messages.cart_is_empty'));
        }
        try{
            $gateway = $this->initPayment();
            if($createData['method'] == 'saved_card') {
                $hash = Session::get('saved_card_hash');
               if(Session::has('saved_card') && auth()->user()->cards->where('secure_hash',$hash)->count() > 0) {
                    $card = auth()->user()->cards->where('secure_hash',$hash)->first();
                    Session::forget('save_card_data');
                } else{
                    $card = auth()->user()->cards->first();
               }
               if( auth()->user()->cards->count() > 0 ) {
                    $gateway->setParameter('clientId', auth()->user()->id);
                    $gateway->setParameter('bindingPayment',true);
                    $gateway->setParameter('bindingId',$card->bindingId);
                }
            }


                $gateway->setParameter('amount',$paymentData->price);
                $gateway->setParameter('paymentId',$paymentData->payment_id);
                $gateway->setParameter('transactionId',$paymentData->payment_id);
                $gateway->setParameter('description',$paymentData->description);
                $purchase = $gateway->purchase()->send();
                $orderId = $purchase->getData()['orderId'];

                if($createData['method'] == 'saved_card') {
                    $gateway->setParameter('mdOrder',$orderId);
                    $purchase = $gateway->makeBindingPayment()->send();
                }
                if ($purchase->isRedirect()) {
                    $purchase->redirect();
                }

            $row = Orders::create($createData);

            $row->update(['order_id' =>$orderId]);

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

        }catch(\Exception $exception) {
            return redirect()->route('home');
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function checkPayment()
    {
        $payment = Orders::where('order_id', request()->orderId)->first();
        $gateway = $this->initPayment();
        $purchase = $gateway->getOrderStatus(['transactionId' => request()->orderId])->send();
        if(isset($payment->status) && $payment->status != 'uncompleted') {
            abort(404);
        }
        if ($purchase->isSuccessful()) {
            if(Session::has('save_card_data') && isset($purchase->getData()['bindingInfo']) && isset($purchase->getData()['bindingInfo']['clientId'])) {
                if(auth()->user()->cards->where('pan',$purchase->getData()['cardAuthInfo']['pan'])->count() == 0) {
                    $purchaseData = [
                        'user_id'=>auth()->user()->id,
                        'expiration'=>$purchase->getData()['cardAuthInfo']['expiration'],
                        'cardholderName'=>$purchase->getData()['cardAuthInfo']['cardholderName'],
                        'approvalCode'=>$purchase->getData()['cardAuthInfo']['approvalCode'],
                        'pan'=>$purchase->getData()['cardAuthInfo']['pan'],
                        'clientId'=>$purchase->getData()['bindingInfo']['clientId'],
                        'bindingId'=>$purchase->getData()['bindingInfo']['bindingId'],
                        'secure_hash'=>md5($purchase->getData()['cardAuthInfo']['pan'])
                    ];
                    CardBinding::create($purchaseData);
                    Session::forget('save_card_data');
                }
            }

            $payment->update(['status' => 'Completed']);
            return redirect()->route('order.success',request()->orderId);
        } else {
            $error_message = 'ERROR';
            if(null !== $purchase->getData() && isset($purchase->getData()['ErrorMessage'])) {
                $error_message = $purchase->getData()['ErrorMessage'];
            }
            $payment->update(['status' => $error_message]);
            Session::forget('save_card_data');
            return redirect()->route('checkout.page')->with('messages', $error_message);

        }
    }
}
