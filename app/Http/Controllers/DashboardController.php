<?php

namespace App\Http\Controllers;

use App\Models\Muzakki;
use App\Models\Mustahik;
use App\Models\ZakatPayment;
use App\Models\Program;
use App\Models\ZakatDistribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return $this->adminDashboard($request);
        }

        if ($user->role === 'muzakki') {
            return $this->muzakkiDashboard();
        }

        Auth::logout();
        return redirect('/');
    }

    /**
     * Helper privat untuk memastikan profil muzakki selalu ada tanpa forced redirect.
     */
    private function getMuzakkiProfile($user)
    {
        $muzakki = $user->muzakki;
        if (!$muzakki) {
            $muzakki = Muzakki::create([
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'user_id' => $user->id,
                'is_active' => true,
                'campaign_url' => url('/campaigner/' . $user->email),
            ]);
        }
        return $muzakki;
    }

    private function adminDashboard(Request $request)
    {
        $currentYear = $request->input('year', date('Y'));
        $currentMonth = date('m');

        $stats = cache()->remember('admin_dashboard_stats_' . $currentYear . '_' . $currentMonth, 300, function () use ($currentYear, $currentMonth) {
            return [
                'total_muzakki' => Muzakki::active()->count(),
                'total_mustahik' => Mustahik::active()->count(),
                'total_payments_this_year' => ZakatPayment::completed()->byYear($currentYear)->sum('paid_amount'),
                'total_distributions_this_year' => ZakatDistribution::byYear($currentYear)->sum('amount'),
                'total_payments_this_month' => ZakatPayment::completed()->byMonth($currentMonth)->sum('paid_amount'),
                'total_distributions_this_month' => ZakatDistribution::byYear($currentYear)->whereMonth('distribution_date', $currentMonth)->sum('amount'),
            ];
        });

        $availableYears = cache()->remember('available_payment_years', 600, function () {
            $years = ZakatPayment::selectRaw('YEAR(payment_date) as year')
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year')
                ->toArray();
            if (!in_array(date('Y'), $years)) {
                array_unshift($years, date('Y'));
            }
            return $years;
        });

        $recentPayments = ZakatPayment::with(['muzakki', 'program'])
            ->completed()
            ->latest('payment_date')
            ->take(5)
            ->get();

        $recentDistributions = ZakatDistribution::with(['mustahik'])
            ->whereHas('mustahik')
            ->latest('distribution_date')
            ->take(5)
            ->get();

        $monthlyPayments = ZakatPayment::completed()
            ->byYear($currentYear)
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

        $programTypeStats = ZakatPayment::completed()
            ->byYear($currentYear)
            ->selectRaw('program_id, SUM(paid_amount) as total')
            ->groupBy('program_id')
            ->with('program')
            ->get();

        $mustahikCategoryStats = Mustahik::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->get();

        return view('dashboard.admin', compact(
            'stats',
            'recentPayments',
            'recentDistributions',
            'chartData',
            'programTypeStats',
            'mustahikCategoryStats',
            'availableYears',
            'currentYear'
        ));
    }

    private function muzakkiDashboard()
    {
        $user = Auth::user();
        $muzakki = $this->getMuzakkiProfile($user);

        $currentYear = date('Y');

        $aggregateStats = clone $muzakki->zakatPayments()->completed();
        $aggregateResult = $aggregateStats->selectRaw("
            SUM(paid_amount) as total_zakat,
            SUM(CASE WHEN YEAR(payment_date) = ? THEN paid_amount ELSE 0 END) as zakat_year,
            COUNT(id) as payment_count
        ", [$currentYear])->first();

        $stats = [
            'total_zakat_paid' => $aggregateResult->total_zakat ?? 0,
            'total_donated' => $aggregateResult->total_zakat ?? 0,
            'zakat_this_year' => $aggregateResult->zakat_year ?? 0,
            'this_year' => $aggregateResult->zakat_year ?? 0,
            'payment_count' => $aggregateResult->payment_count ?? 0,
            'total_count' => $aggregateResult->payment_count ?? 0,
            'last_payment' => $muzakki->zakatPayments()->completed()->latest('payment_date')->first(),
        ];

        $recentPayments = $muzakki->zakatPayments()
            ->with('program')
            ->completed()
            ->latest('payment_date')
            ->take(5)
            ->get();

        $yearlyPayments = $muzakki->zakatPayments()
            ->completed()
            ->selectRaw('YEAR(payment_date) as year, SUM(paid_amount) as total')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->take(5)
            ->get();

        $profileCompleteness = $muzakki->profile_completeness;

        return view('dashboard.muzakki', compact(
            'muzakki',
            'stats',
            'recentPayments',
            'yearlyPayments',
            'profileCompleteness'
        ));
    }

    public function stats()
    {
        $currentYear = date('Y');
        $currentMonth = date('m');

        $data = cache()->remember('api_dashboard_stats_' . $currentYear . '_' . $currentMonth, 300, function () use ($currentYear, $currentMonth) {
            return [
                'payments' => [
                    'total_this_year' => ZakatPayment::completed()->byYear($currentYear)->sum('paid_amount'),
                    'total_this_month' => ZakatPayment::completed()->byMonth($currentMonth)->sum('paid_amount'),
                    'count_this_year' => ZakatPayment::completed()->byYear($currentYear)->count(),
                ],
                'distributions' => [
                    'total_this_year' => ZakatDistribution::byYear($currentYear)->sum('amount'),
                    'total_this_month' => ZakatDistribution::byYear($currentYear)->whereMonth('distribution_date', $currentMonth)->sum('amount'),
                    'count_this_year' => ZakatDistribution::byYear($currentYear)->count(),
                ],
                'balance' => ZakatPayment::completed()->sum('paid_amount') - ZakatDistribution::sum('amount'),
            ];
        });

        return response()->json($data);
    }

    public function transactions()
    {
        $user = Auth::user();
        $muzakki = $this->getMuzakkiProfile($user);

        $payments = $muzakki->zakatPayments()
            ->with(['program'])
            ->latest('payment_date')
            ->paginate(10);

        return view('muzakki.dashboard.transactions', compact('muzakki', 'payments'));
    }

    public function recurringDonations()
    {
        $user = Auth::user();
        $muzakki = $this->getMuzakkiProfile($user);

        $recurringDonations = $muzakki->recurringDonations()
            ->with('program')
            ->latest()
            ->get();

        $programs = cache()->remember('active_programs', 3600, function () {
            return Program::active()->get();
        })->sortBy('name')->values();

        return view('muzakki.dashboard.recurring-donations', compact('muzakki', 'recurringDonations', 'programs'));
    }

    public function bankAccounts()
    {
        $user = Auth::user();
        $muzakki = $this->getMuzakkiProfile($user);

        $bankAccounts = $muzakki->bankAccounts()->orderByDesc('is_primary')->latest()->get();

        return view('muzakki.dashboard.bank-accounts', compact('muzakki', 'bankAccounts'));
    }

    public function accountManagement()
    {
        $user = Auth::user();
        $muzakki = $this->getMuzakkiProfile($user);

        return view('muzakki.dashboard.account-management', compact('muzakki'));
    }

    public function transferAccount()
    {
        $user = Auth::user();
        $muzakki = $this->getMuzakkiProfile($user);

        return view('muzakki.dashboard.account-transfer', compact('muzakki'));
    }

    public function deleteAccount()
    {
        $user = Auth::user();
        $muzakki = $this->getMuzakkiProfile($user);

        return view('muzakki.dashboard.account-delete', compact('muzakki'));
    }

    public function donation()
    {
        $user = Auth::user();
        $muzakki = $this->getMuzakkiProfile($user);

        $programs = cache()->remember('active_programs', 3600, function () {
            return Program::active()->get();
        })->sortByDesc('created_at')->values();

        return view('muzakki.donation', compact('muzakki', 'programs'));
    }

    public function fundraising()
    {
        $user = Auth::user();
        $muzakki = $this->getMuzakkiProfile($user);

        $campaigns = collect([]);
        return view('muzakki.fundraising', compact('muzakki', 'campaigns'));
    }

    public function amalanku()
    {
        $user = Auth::user();
        $muzakki = $this->getMuzakkiProfile($user);

        $payments = $muzakki->zakatPayments()
            ->with('program')
            ->completed()
            ->latest('payment_date')
            ->take(10)
            ->get();

        $stats = [
            'total_donated' => $muzakki->zakatPayments()->completed()->sum('paid_amount'),
            'total_count' => $muzakki->zakatPayments()->completed()->count(),
            'this_year' => $muzakki->zakatPayments()->completed()->byYear(date('Y'))->sum('paid_amount'),
        ];

        return view('muzakki.amalanku', compact('muzakki', 'payments', 'stats'));
    }
}
