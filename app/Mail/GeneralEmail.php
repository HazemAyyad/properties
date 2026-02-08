<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GeneralEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $phone;
    public $email;
    public $subject;
    public $text;
    public function __construct($name,$phone,$email,$subject, $text = null)
    {
        $this->name=$name;
        $this->phone=$phone;
        $this->email=$email;
        $this->subject=$subject;
        $this->text=$text;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.general_email');
    }
}
