<?php

namespace App\Http\Controllers;

use App\Models\ZakatPayment;
use App\Models\Program;
use App\Models\Campaign;
use App\Models\Muzakki;
use App\Models\User;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GuestPaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Display guest payment create form.
     */
    public function guestCreate(Request $request)
    {
        $program = null;
        $campaign = null;

        if ($request->has('program_id')) {
            $program = Program::find($request->program_id);
        }

        if ($request->has('campaign_id')) {
            $campaign = Campaign::find($request->campaign_id);
            if ($campaign && !$program) {
                $program = $campaign->program;
            }
        }

        return view('guest.payment.create', compact('program', 'campaign'));
    }

    /**
     * Store a guest payment transaction.
     */
    public function guestStore(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.\'\`-]+$/'],
            'phone' => ['required', 'string', 'regex:/^[0-9]{10,15}$/'],
            'email' => 'nullable|email',
            'amount' => 'required|numeric|min:10000',
            'payment_method' => 'required|string',
            'program_id' => 'nullable|exists:programs,id',
            'campaign_id' => 'nullable|exists:campaigns,id',
            'notes' => 'nullable|string|max:500',
            'is_anonymous' => 'nullable|boolean',
        ], [
            'name.regex' => 'Nama hanya boleh berisi huruf dan tanda petik.',
            'phone.regex' => 'Nomor telepon harus berupa 10 hingga 15 digit angka.',
            'amount.min' => 'Nominal donasi minimal Rp 10.000.',
        ]);

        DB::beginTransaction();
        try {
            // Find or create muzakki
            $muzakki = null;
            if ($request->filled('email')) {
                $muzakki = Muzakki::where('email', $request->email)->first();
            }

            if (!$muzakki && $request->filled('phone')) {
                $muzakki = Muzakki::where('phone', $request->phone)->first();
            }

            if (!$muzakki) {
                $user = null;
                if ($request->filled('email')) {
                    $user = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => Hash::make(Str::random(16)),
                        'role' => 'muzakki',
                        'is_active' => true,
                    ]);
                }

                $muzakki = Muzakki::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'gender' => 'male',
                    'user_id' => $user?->id,
                    'is_active' => true,
                ]);
            }

            $paymentCode = 'ZKT-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            $payment = ZakatPayment::create([
                'payment_code' => $paymentCode,
                'midtrans_order_id' => $paymentCode,
                'muzakki_id' => $muzakki->id,
                'program_id' => $request->program_id,
                'campaign_id' => $request->campaign_id,
                'paid_amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_date' => now(),
                'status' => 'pending',
                'is_guest_payment' => true,
                'notes' => $request->notes,
                'is_anonymous' => $request->boolean('is_anonymous'),
            ]);

            DB::commit();

            return redirect()->route('guest.payment.summary', ['paymentCode' => $paymentCode]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Guest Payment Store Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses transaksi. Silakan coba lagi.');
        }
    }

    /**
     * Show guest payment summary page.
     */
    public function guestSummary($paymentCode)
    {
        $payment = ZakatPayment::where('payment_code', $paymentCode)
            ->where('is_guest_payment', true)
            ->firstOrFail();

        $snapToken = null;
        try {
            $snapToken = $this->midtransService->createSnapToken($payment, $payment->payment_method);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Token Error: ' . $e->getMessage());
        }

        return view('payments.guest-summary', compact('payment', 'snapToken'));
    }

    /**
     * Get Snap token via AJAX.
     */
    public function getSnapToken($paymentCode)
    {
        $payment = ZakatPayment::where('payment_code', $paymentCode)->firstOrFail();

        try {
            $snapToken = $this->midtransService->createSnapToken($payment, $payment->payment_method);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Custom method to get Snap token with payment method override.
     */
    public function getTokenCustom(Request $request, $paymentCode)
    {
        $request->validate(['method' => 'required|string']);

        $payment = ZakatPayment::where('payment_code', $paymentCode)->firstOrFail();
        $payment->update(['payment_method' => $request->method]);

        try {
            $snapToken = $this->midtransService->createSnapToken($payment, $request->method);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Guest success page.
     */
    public function guestSuccess($paymentCode)
    {
        $payment = ZakatPayment::where('payment_code', $paymentCode)->firstOrFail();
        return view('payments.guest-success', compact('payment'));
    }

    /**
     * Guest failure page.
     */
    public function guestFailure($paymentCode)
    {
        $payment = ZakatPayment::where('payment_code', $paymentCode)->firstOrFail();
        return view('payments.guest-failure', compact('payment'));
    }

    /**
     * Guest receipt by code.
     */
    public function guestReceiptByCode($paymentCode)
    {
        $payment = ZakatPayment::where('payment_code', $paymentCode)->firstOrFail();
        return view('payments.guest-receipt', compact('payment'));
    }

    /**
     * Download guest receipt PDF/print.
     */
    public function downloadGuestReceipt($paymentCode)
    {
        $payment = ZakatPayment::where('payment_code', $paymentCode)->firstOrFail();
        return view('payments.guest-receipt', compact('payment'));
    }

    /**
     * Check payment status via AJAX polling.
     */
    public function guestCheckStatus($paymentCode)
    {
        $payment = ZakatPayment::where('payment_code', $paymentCode)->first();
        if (!$payment) {
            return response()->json(['status' => 'not_found'], 404);
        }
        return response()->json(['status' => $payment->status]);
    }

    /**
     * Handle guest leave page.
     */
    public function guestLeavePage(Request $request, $paymentCode)
    {
        return response()->json(['status' => 'acknowledged']);
    }
}
