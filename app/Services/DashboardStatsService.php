<?php

namespace App\Services;

use App\Models\Muzakki;
use App\Models\Mustahik;
use App\Models\Payment;
use App\Models\Distribution;

class DashboardStatsService
{
    
    public function getAdminStats(string $year, string $month): array
    {
        return cache()->remember('admin_dashboard_stats_' . $year . '_' . $month, 300, function () use ($year, $month) {
            return [
                'total_muzakki' => Muzakki::active()->count(),
                'total_mustahik' => Mustahik::active()->count(),
                'total_payments_this_year' => Payment::completed()->byYear($year)->sum('paid_amount'),
                'total_distributions_this_year' => Distribution::byYear($year)->sum('amount'),
                'total_payments_this_month' => Payment::completed()->byMonth($month)->sum('paid_amount'),
                'total_distributions_this_month' => Distribution::byYear($year)->whereMonth('distribution_date', $month)->sum('amount'),
            ];
        });
    }

    
    public function getAvailableYears(): array
    {
        return cache()->remember('available_payment_years', 600, function () {
            $years = Payment::selectRaw('YEAR(payment_date) as year')
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year')
                ->toArray();
            if (!in_array(date('Y'), $years)) {
                array_unshift($years, date('Y'));
            }
            return $years;
        });
    }

    
    public function getMonthlyChartData(string $year): array
    {
        $monthlyPayments = Payment::completed()
            ->byYear($year)
            ->selectRaw('MONTH(payment_date) as month, SUM(paid_amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyPayments[$i] ?? 0;
        }

        return $chartData;
    }

    
    public function getMuzakkiStats(Muzakki $muzakki, string $year): array
    {
        $aggregateStats = clone $muzakki->payments()->completed();
        $aggregateResult = $aggregateStats->selectRaw("
            SUM(paid_amount) as total_zakat,
            SUM(CASE WHEN YEAR(payment_date) = ? THEN paid_amount ELSE 0 END) as zakat_year,
            COUNT(id) as payment_count
        ", [$year])->first();

        return [
            'total_zakat_paid' => $aggregateResult->total_zakat ?? 0,
            'total_donated' => $aggregateResult->total_zakat ?? 0,
            'zakat_this_year' => $aggregateResult->zakat_year ?? 0,
            'this_year' => $aggregateResult->zakat_year ?? 0,
            'payment_count' => $aggregateResult->payment_count ?? 0,
            'total_count' => $aggregateResult->payment_count ?? 0,
            'last_payment' => $muzakki->payments()->completed()->latest('payment_date')->first(),
        ];
    }
    
    
    public function getApiStats(string $year, string $month): array
    {
        return cache()->remember('api_dashboard_stats_' . $year . '_' . $month, 300, function () use ($year, $month) {
            return [
                'payments' => [
                    'total_this_year' => Payment::completed()->byYear($year)->sum('paid_amount'),
                    'total_this_month' => Payment::completed()->byMonth($month)->sum('paid_amount'),
                    'count_this_year' => Payment::completed()->byYear($year)->count(),
                ],
                'distributions' => [
                    'total_this_year' => Distribution::byYear($year)->sum('amount'),
                    'total_this_month' => Distribution::byYear($year)->whereMonth('distribution_date', $month)->sum('amount'),
                    'count_this_year' => Distribution::byYear($year)->count(),
                ],
                'balance' => Payment::completed()->sum('paid_amount') - Distribution::sum('amount'),
            ];
        });
    }
}
