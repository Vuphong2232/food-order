<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $code;
    public string $email;

    public function __construct(string $code, string $email)
    {
        $this->code = $code;
        $this->email = $email;
    }

    public function build()
    {
        return $this->subject('Mã xác nhận đặt lại mật khẩu')
            ->view('auth.forgot-password-otp');
    }
}