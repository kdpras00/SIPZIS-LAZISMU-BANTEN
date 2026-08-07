<?php

namespace App\Http\Controllers;

use App\Models\ZakatPayment;
use App\Models\Muzakki;
use App\Models\Program;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ZakatPaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Display a listing of zakat payments.
     */
    public function index(Request $request)
    {
        $query = ZakatPayment::with(['muzakki', 'program'])->latest();

        if (Auth::check() && Auth::user()->role === 'muzakki') {
            $muzakki = Auth::user()->muzakki;
            if (!$muzakki) {
                abort(404, 'Profil muzakki tidak ditemukan.');
            }
            $query->where('muzakki_id', $muzakki->id);
        }

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('payment_code', 'like', "%{$search}%")
                  ->orWhereHas('muzakki', function ($mq) use ($search) {
                      $mq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->paginate(15)->withQueryString();

        if (Auth::check() && Auth::user()->role === 'muzakki') {
            $muzakki = Auth::user()->muzakki;
            $stats = [
                'total_amount' => $muzakki->zakatPayments()->completed()->sum('paid_amount'),
                'total_count' => $muzakki->zakatPayments()->completed()->count(),
                'this_month' => $muzakki->zakatPayments()->completed()->whereMonth('payment_date', date('m'))->sum('paid_amount'),
                'pending' => $muzakki->zakatPayments()->where('status', 'pending')->count(),
            ];
        } else {
            $stats = [
                'total_amount' => ZakatPayment::completed()->sum('paid_amount'),
                'total_count' => ZakatPayment::completed()->count(),
                'this_month' => ZakatPayment::completed()->whereMonth('payment_date', date('m'))->sum('paid_amount'),
                'pending' => ZakatPayment::where('status', 'pending')->count(),
            ];
        }

        return view('payments.index', compact('payments', 'stats'));
    }

    /**
     * Show form for creating a new payment.
     */
    public function create(Request $request)
    {
        $allMuzakki = Muzakki::active()->orderBy('name')->get();
        $programs = Program::orderBy('name')->get();

        return view('payments.create', compact('allMuzakki', 'programs'));
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        if ($request->has('paid_amount')) {
            $cleanedAmount = str_replace(['.', ','], '', $request->input('paid_amount'));
            $request->merge(['paid_amount' => is_numeric($cleanedAmount) ? (float)$cleanedAmount : $request->input('paid_amount')]);
        }

        $request->validate([
            'muzakki_id' => 'required|exists:muzakki,id',
            'payment_date' => 'required|date',
            'paid_amount' => 'required|numeric|min:1000',
            'payment_method' => 'required|string',
            'program_id' => 'nullable|exists:programs,id',
            'payment_reference' => 'nullable|string|max:255',
            'status' => 'required|in:pending,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $paymentCode = 'ZKT-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        ZakatPayment::create([
            'payment_code' => $paymentCode,
            'midtrans_order_id' => $paymentCode,
            'muzakki_id' => $request->muzakki_id,
            'program_id' => $request->program_id,
            'payment_date' => $request->payment_date,
            'paid_amount' => $request->paid_amount,
            'payment_method' => $request->payment_method,
            'payment_reference' => $request->payment_reference,
            'status' => $request->status,
            'notes' => $request->notes,
            'received_by' => Auth::id(),
        ]);

        return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil ditambahkan.');
    }

    /**
     * Display the specified payment.
     */
    public function show(ZakatPayment $payment)
    {
        $payment->load(['muzakki', 'program', 'receivedBy']);
        return view('payments.show', compact('payment'));
    }

    /**
     * Show form for editing payment.
     */
    public function edit(ZakatPayment $payment)
    {
        $allMuzakki = Muzakki::active()->orderBy('name')->get();
        $programs = Program::orderBy('name')->get();

        return view('payments.edit', compact('payment', 'allMuzakki', 'programs'));
    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, ZakatPayment $payment)
    {
        if ($request->has('paid_amount')) {
            $cleanedAmount = str_replace(['.', ','], '', $request->input('paid_amount'));
            $request->merge(['paid_amount' => is_numeric($cleanedAmount) ? (float)$cleanedAmount : $request->input('paid_amount')]);
        }

        $request->validate([
            'muzakki_id' => 'required|exists:muzakki,id',
            'payment_date' => 'required|date',
            'paid_amount' => 'required|numeric|min:1000',
            'payment_method' => 'required|string',
            'program_id' => 'nullable|exists:programs,id',
            'payment_reference' => 'nullable|string|max:255',
            'status' => 'required|in:pending,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $payment->update($request->only([
            'muzakki_id',
            'program_id',
            'payment_date',
            'paid_amount',
            'payment_method',
            'payment_reference',
            'status',
            'notes',
        ]));

        return redirect()->route('payments.index')->with('success', 'Data pembayaran berhasil diperbarui.');
    }

    /**
     * Remove payment from storage.
     */
    public function destroy(ZakatPayment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Data pembayaran berhasil dihapus.');
    }

    /**
     * View payment receipt.
     */
    public function receipt(ZakatPayment $payment)
    {
        $payment->load(['muzakki', 'program', 'receivedBy']);
        return view('payments.receipt', compact('payment'));
    }

    /**
     * Search API endpoint.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        $payments = ZakatPayment::with('muzakki')
            ->where('payment_code', 'like', "%{$query}%")
            ->orWhereHas('muzakki', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->take(10)
            ->get();

        return response()->json($payments);
    }

    public function finish()
    {
        return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil diproses.');
    }

    public function unfinish()
    {
        return redirect()->route('payments.index')->with('warning', 'Pembayaran belum diselesaikan.');
    }

    public function error()
    {
        return redirect()->route('payments.index')->with('error', 'Pembayaran mengalami kendala.');
    }
}
