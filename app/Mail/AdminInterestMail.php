<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminInterestMail extends Mailable
{
    use Queueable, SerializesModels;
    public $admininterest;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($admininterest)
    {
        //
        $this->admininterest = $admininterest;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.admininterest')->
        from('hello@asknello.com')->
        subject('New Interest Received')->
        with('admininterest' , $this->admininterest);
    }
}
