<?php

namespace App\Jobs;

use App\Models\Muzakki;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Fan-out program/campaign publish notifications to all registered muzakki.
 *
 * Dispatched from Campaign::boot() and Program::boot() so the N-insert loop
 * runs in a queue worker instead of the web request holding the DB connection.
 *
 * ponytail: ceiling = synchronous N INSERTs; upgrade = batch-insert with Notification::insert([])
 */
class SendProgramNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public readonly mixed $notifiable, // Campaign or Program model
        public readonly string $eventType = 'program',
    ) {}

    public function handle(): void
    {
        Muzakki::whereNotNull('user_id')
            ->with('user')
            ->each(function (Muzakki $muzakki) {
                if ($muzakki->user) {
                    Notification::createProgramNotification($muzakki->user, $this->notifiable, $this->eventType);
                }
            });
    }
}
