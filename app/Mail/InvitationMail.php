<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
     public $caller_user;
     public $called_user;
     public $data;
   public function __construct($caller_user,$called_user,$data)
   {
       $this->caller_user=$caller_user;
       $this->called_user=$called_user;
       $this->data=$data;
   }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      $data["caller_user"]=$this->caller_user;
      $data["called_user"]=$this->called_user;
      $data["data"]=$this->data;
      return $this->view('front.email.invite_email')
      ->subject("invite Email")
      ->with($data);
    }
}
