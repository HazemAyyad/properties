<?php

namespace App\Mail;

use App\Models\PlanUpgradeRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PlanUpgradeRequestNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PlanUpgradeRequest $upgradeRequest,
        public string $requestUrl
    ) {}

    public function build()
    {
        return $this->subject(__('New plan upgrade request'))
            ->view('emails.plan_upgrade_request');
    }
}
