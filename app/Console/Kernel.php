<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    
    protected function schedule(Schedule $schedule): void
    {
        
        $schedule->command('campaigns:update-statuses')
            ->dailyAt('01:00')
            ->withoutOverlapping()
            ->onSuccess(function () {
                
                Log::info('Campaign expiration job completed successfully');
            })
            ->onFailure(function () {
                Log::error('Campaign expiration job failed');
            });

        
        

        
        
    }

    
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
