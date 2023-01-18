<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class OrderSuccess extends Mailable
{
    use Queueable, SerializesModels;


    public $row;
    public $mail_to;

    /**
     * OrderSuccess constructor.
     * @param $row
     */
    public function __construct($row,$mail_to){
        $this->row = $row;
        $this->mail_to = $mail_to;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // settings('mail_to')
        #return Mail::to(settings('mail_to'))->from(settings('mail_from'))->view('emails.orders.success')->with('row',$this->row);
      return $this->from(settings('mail_from'))->to($this->mail_to)->bcc([settings('mail_to')])->view('emails.orders.order_list_email')->with([
          'order'=>$this->row
      ]);
    }
}
