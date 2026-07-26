<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerEmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationUrl;
    public $customer;

    public function __construct($customer, $verificationUrl)
    {
        $this->customer = $customer;
        $this->verificationUrl = $verificationUrl;
    }

    public function build()
    {
        return $this->subject('Verifikasi Email Anda - Golden Hearth')
            ->markdown('emails.customer.verify-email')
            ->with([
                'name' => $this->customer->name,
                'url' => $this->verificationUrl,
            ]);
    }
}
