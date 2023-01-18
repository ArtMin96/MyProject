<?php


namespace App\Billing;


use Illuminate\Support\Str;

class AmeriaGateway implements GatewayContract
{
    private $currency;
    private $discount;

    public function __construct($currency = null)
    {
        $this->currency = $currency;
        $this->discount = 0;
    }


    public function  setDiscount($amount){
        $this->discount = $amount;
    }

    public function charge($amount)
    {
        return [
            'amount'=>$amount - $this->discount,
            'currency' =>$this->currency,
            'discount' =>$this->discount,
            'fees' => $amount * 0.5,
        ];
    }

    public function  startPayment($data)
    {
        // TODO: Implement startPayment() method.
    }
}
