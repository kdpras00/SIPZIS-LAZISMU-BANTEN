<?php

namespace App\Http\Controllers;

use App\Models\Muzakki;
use App\Models\Mustahik;
use App\Models\Payment;
use App\Models\Program;
use App\Models\Distribution;
use App\Services\DashboardStatsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $dashboardStatsService;

    public function __construct(DashboardStatsService $dashboardStatsService)
    {
        $this->dashboardStatsService = $dashboardStatsService;
    }
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

        $stats = $this->dashboardStatsService->getAdminStats($currentYear, $currentMonth);
        $availableYears = $this->dashboardStatsService->getAvailableYears();

        $recentPayments = Payment::with(['muzakki', 'program'])
            ->completed()
            ->latest('payment_date')
            ->take(5)
            ->get();

        $recentDistributions = Distribution::with(['mustahik'])
            ->whereHas('mustahik')
            ->latest('distribution_date')
            ->take(5)
            ->get();

        $chartData = $this->dashboardStatsService->getMonthlyChartData($currentYear);

        $programTypeStats = Payment::completed()
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

        $stats = $this->dashboardStatsService->getMuzakkiStats($muzakki, $currentYear);

        $recentPayments = $muzakki->payments()
            ->with('program')
            ->completed()
            ->latest('payment_date')
            ->take(5)
            ->get();

        $yearlyPayments = $muzakki->payments()
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

        $data = $this->dashboardStatsService->getApiStats($currentYear, $currentMonth);

        return response()->json($data);
    }

    public function transactions()
    {
        $user = Auth::user();
        $muzakki = $this->getMuzakkiProfile($user);

        $payments = $muzakki->payments()
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

        $payments = $muzakki->payments()
            ->with('program')
            ->completed()
            ->latest('payment_date')
            ->take(10)
            ->get();

        $stats = [
            'total_donated' => $muzakki->payments()->completed()->sum('paid_amount'),
            'total_count' => $muzakki->payments()->completed()->count(),
            'this_year' => $muzakki->payments()->completed()->byYear(date('Y'))->sum('paid_amount'),
        ];

        return view('muzakki.amalanku', compact('muzakki', 'payments', 'stats'));
    }
}
