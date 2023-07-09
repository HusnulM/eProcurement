<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifApprovePoMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $data;
    public $poid;
    public $ponum;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $poid, $ponum)
    {
        $this->data  = $data;
        $this->poid  = $poid;
        $this->ponum = $ponum;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Approve PO '. $this->ponum)
                    ->view('mail.approvepo');
    }
}
