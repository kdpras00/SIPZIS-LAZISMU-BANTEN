<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\ZakatPayment;
use App\Models\Muzakki;
use App\Observers\ZakatPaymentObserver;
use App\Observers\MuzakkiObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ZakatPayment::observe(ZakatPaymentObserver::class);
        Muzakki::observe(MuzakkiObserver::class);

        // ponytail: Native Laravel feature to find slow N+1 queries automatically
        \Illuminate\Database\Eloquent\Model::preventLazyLoading(! app()->isProduction());

        // ponytail: Native Laravel feature to log any query taking more than 100ms
        \Illuminate\Support\Facades\DB::whenQueryingForLongerThan(100, function (\Illuminate\Database\Connection $connection, \Illuminate\Database\Events\QueryExecuted $event) {
            \Illuminate\Support\Facades\Log::warning("Slow query ({$event->time}ms): {$event->sql}");
        });

        // Force HTTPS for assets when request is HTTPS (for ngrok/tunnel services)
        if (request()->isSecure() || request()->header('X-Forwarded-Proto') === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
