<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserInterestMail extends Mailable
{
    use Queueable, SerializesModels;
    public $customer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($customer)
    {
        //
        $this->customer = $customer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.customerinterest')->
        from('hello@asknello.com')->
        subject('Nello Interest Received')->
        with('customer' , $this->customer);
    }
}
