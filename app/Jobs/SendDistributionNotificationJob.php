<?php

namespace App\Jobs;

use App\Models\Muzakki;
use App\Models\Notification;
use App\Models\Distribution;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDistributionNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public readonly Distribution $distribution
    ) {}

    public function handle(): void
    {
        Muzakki::whereNotNull('user_id')
            ->with('user')
            ->each(function (Muzakki $muzakki) {
                Notification::createDistributionNotification($muzakki, $this->distribution);
            });
    }
}
