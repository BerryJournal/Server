<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmRegister extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

     public $invitation;
     public $userName;
    public function __construct($invitation, $userName)
    {
        $this->invitation = $invitation;
        $this->userName = $userName;
    }

    public function build() {
        return $this->subject('Подтверждение электронной почты')->view('emails.confirm');
    }

}
