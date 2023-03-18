<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $emailToken;

    public function __construct($nameTo,$emailToken)
    {
        $this->name = $nameTo;
        $this->emailToken = $emailToken;
    }

    public function build()
    {

        return $this->view('regMail')
            ->subject('Dobrodošao '.$this->name.'!')
            ->from(env('MAIL_FROM_ADDRESS'), 'SuKraft');
    }
}
