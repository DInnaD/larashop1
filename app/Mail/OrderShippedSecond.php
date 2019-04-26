<?php

namespace App\Mail;

use App\OrdersAll;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderShippedSecond extends Mailable
{
    use Queueable, SerializesModels;

    public $ordersAll;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ordersAll)
    {
        $this->ordersAll = $ordersAll;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.shippedSecond');
    }
}
