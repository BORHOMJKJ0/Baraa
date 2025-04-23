<?php

namespace App\Notifications;

use Ichtrojan\Otp\Otp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OTPNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $message;

    public $subject;

    public $fromEmail;

    public $mailer;

    private $otp;

    public function __construct(string $subject)
    {
        $this->fromEmail = env('MAIL_FROM_ADDRESS');
        $this->mailer = env('MAIL_MAILER');
        $this->otp = new Otp;
        $this->subject = $subject;
        if ($subject == 'emailVerify') {
            $this->message = 'Use this code to verify your email within 2 minutes';
        } else {
            $this->message = 'Use this code to reset password within 2 minutes';
        }
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $otp = $this->otp->generate($notifiable->email, 'numeric', 6, 2);

        return (new MailMessage)
            ->mailer($this->mailer)
            ->subject($this->subject)
            ->greeting('Hello '.$notifiable->first_name.' '.$notifiable->last_name.'!')
            ->line($this->message)
            ->line($otp->token);
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
