<?php


namespace App\Billing;


interface GatewayContract
{

         public function  props();

         public function  startPayment($paymentData,$createData);

         public function  initPayment();

         public function  checkPayment();
}
