<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Muzakki;
use App\Models\Program;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    
    protected function authorizeAccess(Payment $payment): void
    {
        if (Auth::user()->role === 'admin') {
            return;
        }

        $muzakkiId = Auth::user()->muzakki?->id;
        abort_unless($muzakkiId && $payment->muzakki_id === $muzakkiId, 403, 'Anda tidak memiliki akses ke pembayaran ini.');
    }

    
    public function index(Request $request)
    {
        $query = Payment::with(['muzakki', 'program'])->latest();

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
                'total_amount' => $muzakki->payments()->completed()->sum('paid_amount'),
                'total_count' => $muzakki->payments()->completed()->count(),
                'this_month' => $muzakki->payments()->completed()->whereMonth('payment_date', date('m'))->sum('paid_amount'),
                'pending' => $muzakki->payments()->where('status', 'pending')->count(),
            ];
        } else {
            $stats = [
                'total_amount' => Payment::completed()->sum('paid_amount'),
                'total_count' => Payment::completed()->count(),
                'this_month' => Payment::completed()->whereMonth('payment_date', date('m'))->sum('paid_amount'),
                'pending' => Payment::where('status', 'pending')->count(),
            ];
        }

        return view('payments.index', compact('payments', 'stats'));
    }

    
    public function create(Request $request)
    {
        $allMuzakki = Muzakki::active()->orderBy('name')->get();
        $programs = Program::orderBy('name')->get();

        return view('payments.create', compact('allMuzakki', 'programs'));
    }

    
    public function store(\App\Http\Requests\StorePaymentRequest $request)
    {
        $validated = $request->validated();

        if (Auth::user()->role !== 'admin') {
            $muzakkiId = Auth::user()->muzakki?->id;
            abort_unless($muzakkiId && (int) $validated['muzakki_id'] === $muzakkiId, 403, 'Anda tidak dapat membuat pembayaran atas nama muzakki lain.');
            abort_if(in_array($validated['status'], ['completed', 'cancelled'], true), 403, 'Anda tidak dapat mengubah status pembayaran.');
            $validated['status'] = 'pending';
        }

        $paymentCode = 'ZKT-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        Payment::create([
            'payment_code' => $paymentCode,
            'midtrans_order_id' => $paymentCode,
            'muzakki_id' => $validated['muzakki_id'],
            'program_id' => $validated['program_id'],
            'payment_date' => $validated['payment_date'],
            'paid_amount' => $validated['paid_amount'],
            'payment_method' => $validated['payment_method'],
            'payment_reference' => $validated['payment_reference'],
            'status' => $validated['status'],
            'notes' => $validated['notes'],
            'received_by' => Auth::id(),
        ]);

        return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil ditambahkan.');
    }

    
    public function show(Payment $payment)
    {
        $this->authorizeAccess($payment);
        $payment->load(['muzakki', 'program', 'receivedBy']);
        return view('payments.show', compact('payment'));
    }

    
    public function edit(Payment $payment)
    {
        $this->authorizeAccess($payment);
        $allMuzakki = Muzakki::active()->orderBy('name')->get();
        $programs = Program::orderBy('name')->get();

        return view('payments.edit', compact('payment', 'allMuzakki', 'programs'));
    }

    
    public function update(\App\Http\Requests\StorePaymentRequest $request, Payment $payment)
    {
        $this->authorizeAccess($payment);
        $validated = $request->validated();

        if (Auth::user()->role !== 'admin') {
            abort_if(in_array($validated['status'], ['completed', 'cancelled'], true), 403, 'Anda tidak dapat mengubah status pembayaran.');
            $validated['status'] = 'pending';
        }

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

    
    public function destroy(Payment $payment)
    {
        $this->authorizeAccess($payment);
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Data pembayaran berhasil dihapus.');
    }

    
    public function receipt(Payment $payment)
    {
        $this->authorizeAccess($payment);
        $payment->load(['muzakki', 'program', 'receivedBy']);
        return view('payments.receipt', compact('payment'));
    }

    
    public function search(Request $request)
    {
        $query = $request->get('q');
        $payments = Payment::with('muzakki');

        if (Auth::user()->role !== 'admin') {
            $muzakkiId = Auth::user()->muzakki?->id;
            abort_unless($muzakkiId, 403);
            $payments->where('muzakki_id', $muzakkiId);
        }

        $payments->where(function ($q) use ($query) {
            $q->where('payment_code', 'like', "%{$query}%")
                ->orWhereHas('muzakki', function ($mq) use ($query) {
                    $mq->where('name', 'like', "%{$query}%");
                });
        });

        return response()->json($payments->take(10)->get());
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
