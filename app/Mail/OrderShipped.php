<?php

namespace App\Mail;

use App\User;
use App\OrdersAll;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;

    public $user;//$ordersAll;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)//$ordersAll)
    {
        $this->user = $user;//ordersAll = $ordersAll;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.shipped');//->attach('/path/to/file');
        //return $this->subject('Confirm your email!')//$this->campaign->name_compaign)
                     //->from('innadanylevska@gmail.com', config('app.name'))
                     //->view('emails.shipped');
                    // ->markdown('emails.campaigns.sent');
         
    }
}
