<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Payment;
use App\Models\Muzakki;
use App\Observers\PaymentObserver;
use App\Observers\MuzakkiObserver;

class AppServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {
        
    }

    
    public function boot(): void
    {
        Payment::observe(PaymentObserver::class);
        Muzakki::observe(MuzakkiObserver::class);

        
        \Illuminate\Database\Eloquent\Model::preventLazyLoading(! app()->isProduction());

        
        \Illuminate\Support\Facades\DB::whenQueryingForLongerThan(100, function (\Illuminate\Database\Connection $connection, \Illuminate\Database\Events\QueryExecuted $event) {
            \Illuminate\Support\Facades\Log::warning("Slow query ({$event->time}ms): {$event->sql}");
        });

        
        if (request()->isSecure() || request()->header('X-Forwarded-Proto') === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
