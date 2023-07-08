<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifApprovePbjMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $data;
    public $pbjid;
    public $pbjnum;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $pbjid, $pbjnum)
    {
        $this->data  = $data;
        $this->pbjid = $pbjid;
        $this->pbjnum = $pbjnum;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Approve PBJ '. $this->pbjnum)
                    ->view('mail.approvepbj');
    }
}