<?php

namespace App\listeners;

use App\Events\UserEmailverified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailVerified
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
     * @param  \App\Events\UserEmailverified  $event
     * @return void
     */
    public function handle(UserEmailverified $event)
    {
        //dd($event->user);
        $user = $event->user->toArray();
        Mail::send('verification.emailVerified', ['user' => $user],function($message,) use($user){
            $message->to($user['email']);
            $message->subject('Email Verified');
            //$message->to($user['email']);            
        });
    }
}
