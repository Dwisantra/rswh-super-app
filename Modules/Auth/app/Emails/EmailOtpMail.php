<?php

namespace Modules\Auth\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public string $otp,
        public string $name,
        public int $expiryMinutes
    ) {}

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject('Kode OTP Registrasi')
            ->view('auth::emails.register-otp');
    }
}
