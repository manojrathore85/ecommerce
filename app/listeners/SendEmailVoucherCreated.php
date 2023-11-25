<?php

namespace App\listeners;

use App\Events\VoucherCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailVoucherCreated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\VoucherCreated  $event
     * @return void
     */
    public function handle(VoucherCreated $event)
    {
          //dd($event->user);
          $emails = $event->emails;
          Mail::send('email.voucherCreated', $emails,function($message,) use($emails){
              $message->to($emails[0]);
              $message->subject('Voucher Created');
              //$message->to($user['email']);            
          });
    }
}
