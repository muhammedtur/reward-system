<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $msg;
    public $subject;
    public $invitation_code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $msg, $invitation_code)
    {
        $this->subject = $subject;
        $this->msg = $msg;
        $this->invitation_code = $invitation_code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = 'noreply@'.env('APP_DOMAIN_EXT');
        $name = __('site.application');

        return $this->view('emails.invitation', [
            'title' => $this->subject,
            'msg' => $this->msg,
            'invitation_code' => $this->invitation_code
            ])
        ->from($address, $name)
        ->replyTo($address, $name)
        ->subject($this->subject);
    }
}
