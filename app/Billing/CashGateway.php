<?php


namespace App\Billing;


use App\Jobs\SendMail;
use App\Models\OrderHistory;
use App\Models\Orders;
use Illuminate\Support\Facades\App;


class CashGateway implements GatewayContract
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

    public function props()
    {
        // TODO: Implement props() method.
    }

    public function initPayment()
    {
        // TODO: Implement initPayment() method.
    }

    /**
     * @param $paymentData
     * @param $createData
     * @return \Illuminate\Http\RedirectResponse
     */

    public function startPayment($paymentData,$createData)
    {
        try{
            $row = Orders::create($createData);

            $row->update(['order_id' =>rand(1111111,9999999),'status'=>'Pending']);


            foreach ($paymentData->products as $product){
                $data =[
                    'order_id' => $row->id,
                    'product_id' => $product->product_id,
                    'count' => $product->count,
                    'price' => $product->amount,
                    'description' => ['title' =>$product->products->title,'price' =>$product->products->price, 'description' =>$product->products->description,]
                ];
                OrderHistory::create($data);
            }

            couponResult();
            return redirect()->route('order.success',['order_id'=>$row->order_id]);


        }catch(\Exception $exception) {
            return redirect()->route('checkout.page');
        }
    }


    public function checkPayment()
    {
        // TODO: Implement checkPayment() method.
    }
}
