<?php

namespace App\Jobs;

use App\Mail\OrderSuccess;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $row;
    public $type;
    public $mail_to;

    /**
     * SendMail constructor.
     * @param $row
     */
    public function __construct($row,$type,$mail_to)
    {
        $this->row = $row;
        $this->type = $type;
        $this->mail_to = $mail_to;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->type == 'order_details') {
            Mail::send(new OrderSuccess($this->row,$this->mail_to));
        }
    }
}
