<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationUrl;

    /**
     * Create a new message instance.
     *
     * @param  string  $verificationUrl
     * @return void
     */
    public function __construct($verificationUrl)
    {
        $this->verificationUrl = $verificationUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Verify Your Email')
            ->markdown('emails.verification')  // Change back to 'markdown' to use components
            ->with([
                'verificationUrl' => $this->verificationUrl,
            ]);
    }
}
