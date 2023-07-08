<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifApprovePrMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $data;
    public $pprid;
    public $prnum;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $pprid, $prnum)
    {
        $this->data  = $data;
        $this->pprid = $pprid;
        $this->prnum = $prnum;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Approve PR '. $this->prnum)
                    ->view('mail.approvepr');
    }
}
