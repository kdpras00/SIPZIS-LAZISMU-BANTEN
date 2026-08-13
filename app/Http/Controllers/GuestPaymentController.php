<?php

namespace App\Http\Controllers;

use App\Models\Payment;
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

        $displayTitle = '';
        $programCategory = 'umum';

        if ($campaign) {
            $displayTitle = $campaign->title;
            $programCategory = $campaign->program_category;
        } elseif ($program) {
            $displayTitle = $program->name;
            $programCategory = $program->slug;
        }

        $categoryMap = [
            'pendidikan'    => 'Mencerahkan Masa Depan dalam Membangun Negeri',
            'kesehatan'     => 'Mewujudkan Kehidupan yang Lebih Sehat untuk Semua',
            'ekonomi'       => 'Memberdayakan Masyarakat secara Ekonomi',
            'sosial-dakwah' => 'Membangun Masyarakat yang Berkualitas',
            'kemanusiaan'   => 'Menyejahterakan Umat Manusia Tanpa Diskriminasi',
            'lingkungan'    => 'Menjaga Lingkungan untuk Generasi Mendatang',
            'zakat'         => 'Menyalurkan Zakat dengan Amanah dan Transparan',
            'infaq'         => 'Bersedekah untuk Keberkahan Bersama',
            'shadaqah'      => 'Membuka Pintu Rezeki dengan Shadaqah',
            'umum'          => 'Bersama Kita Wujudkan Kebaikan',
        ];

        $displaySubtitle = $categoryMap[$programCategory] ?? 'Bersama Kita Wujudkan Kebaikan';

        $loggedInMuzakki = auth()->check() && auth()->user()->hasRole('muzakki') ? auth()->user() : null;

        $collectedAmount = $campaign ? $campaign->collected_amount : ($program ? $program->total_collected : 0);
        $targetAmount = $campaign ? $campaign->target_amount : 0;
        $percentage = $campaign ? $campaign->progress_percentage : ($program ? $program->progress_percentage : 0);

        $tickerPrayers = \App\Models\Payment::whereNotNull('notes')->latest()->take(5)->get();

        return view('payments.guest-create', compact(
            'program', 'campaign', 'displayTitle', 'displaySubtitle',
            'programCategory', 'loggedInMuzakki', 'collectedAmount',
            'targetAmount', 'percentage', 'tickerPrayers'
        ));
    }

    public function guestStore(\App\Http\Requests\StoreGuestPaymentRequest $request, \App\Services\DonationService $donationService)
    {
        try {
            $validated = $request->validated();

            $programCategory = 'umum';
            if (!empty($validated['campaign_id'])) {
                $campaign = \App\Models\Campaign::find($validated['campaign_id']);
                if ($campaign) $programCategory = $campaign->program_category;
            } elseif (!empty($validated['program_id'])) {
                $program = \App\Models\Program::find($validated['program_id']);
                if ($program) $programCategory = $program->slug;
            }
            $validated['program_category'] = $programCategory;

            $payment = $donationService->processGuestDonation($validated);

            $snapToken = $this->midtransService->createSnapToken($payment);

            if (!$snapToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mendapatkan token pembayaran dari Midtrans.'
                ], 500);
            }

            $payment->update(['snap_token' => $snapToken]);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'payment_code' => $payment->payment_code,
                'redirect_url' => route('guest.payment.summary', $payment->payment_code),
                'message' => 'Silakan selesaikan pembayaran Anda.'
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Guest Payment Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.'
            ], 500);
        }
    }

    public function guestSummary($paymentCode)
    {
        $payment = Payment::where('payment_code', $paymentCode)
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

    public function getSnapToken($paymentCode)
    {
        $payment = Payment::where('payment_code', $paymentCode)->firstOrFail();

        try {
            $snapToken = $this->midtransService->createSnapToken($payment, $payment->payment_method);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getTokenCustom(Request $request, $paymentCode)
    {
        $request->validate(['method' => 'required|string']);

        $payment = Payment::where('payment_code', $paymentCode)->firstOrFail();
        $payment->update(['payment_method' => $request->method]);

        try {
            $snapToken = $this->midtransService->createSnapToken($payment, $request->method);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function guestSuccess($paymentCode)
    {
        $payment = Payment::where('payment_code', $paymentCode)->firstOrFail();

        if (in_array($payment->status, ['pending', 'unpaid'])) {
            try {
                $statusResponse = \Midtrans\Transaction::status($paymentCode);
                $statusArray = json_decode(json_encode($statusResponse), true);
                app(\App\Services\MidtransService::class)->handleNotificationPayload($statusArray);
                $payment->refresh();
            } catch (\Exception $e) {
            }
        }

        return view('payments.guest-success', compact('payment'));
    }

    public function guestFailure($paymentCode)
    {
        $payment = Payment::where('payment_code', $paymentCode)->firstOrFail();
        return view('payments.guest-failure', compact('payment'));
    }

    public function guestReceiptByCode($paymentCode)
    {
        $payment = Payment::where('payment_code', $paymentCode)->firstOrFail();
        return view('payments.guest-receipt', compact('payment'));
    }

    public function downloadGuestReceipt($paymentCode)
    {
        $payment = Payment::where('payment_code', $paymentCode)->firstOrFail();
        $pdf = app('dompdf.wrapper')->loadView('payments.guest-receipt', compact('payment'));
        return $pdf->download("Kwitansi-Donasi-{$payment->payment_code}.pdf");
    }

    public function guestCheckStatus($paymentCode)
    {
        $payment = Payment::where('payment_code', $paymentCode)->first();
        if (!$payment) {
            return response()->json(['status' => 'not_found'], 404);
        }
        return response()->json(['status' => $payment->status]);
    }
}
