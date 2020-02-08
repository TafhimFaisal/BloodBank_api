<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Email extends Mailable {

    use Queueable,
        SerializesModels;
    protected $data;
    protected $purpose;
    public function __construct($data,$purpose)
    {
        $this->data = $data;
        $this->purpose = $purpose;
    }
    //build the message.
    public function build() {
        return $this->view('email')
                    ->with('data',$this->data)
                    ->with('purpose',$this->purpose);
    }
}