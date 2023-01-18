<?php

use Illuminate\Database\Seeder;

class PaymentSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $payments = ['cash','arca','idram','ameria'];

       foreach ($payments as $payment){

           \App\Models\PaymentSetting::create([
               'key' => $payment
           ]);
       }
    }
}
