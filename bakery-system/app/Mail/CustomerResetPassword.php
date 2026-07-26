<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $resetUrl;
    public $customer;

    public function __construct($customer, $resetUrl)
    {
        $this->customer = $customer;
        $this->resetUrl = $resetUrl;
    }

    public function build()
    {
        return $this->subject('Reset Kata Sandi - Golden Hearth')
            ->markdown('emails.customer.reset-password')
            ->with([
                'name' => $this->customer->name,
                'url' => $this->resetUrl,
            ]);
    }
}
