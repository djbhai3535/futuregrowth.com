<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Withdrawal;

class AdminWithdrawalNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $withdrawal;

    public function __construct(Withdrawal $withdrawal)
    {
        $this->withdrawal = $withdrawal;
    }

    public function build()
    {
        return $this->subject('New Withdrawal Request Notification - FutureGrowth')
                    ->view('emails.admin_withdrawal_notification');
    }
}
