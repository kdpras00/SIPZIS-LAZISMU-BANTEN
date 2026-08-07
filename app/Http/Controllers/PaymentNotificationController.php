<?php

namespace App\Http\Controllers;

use App\Models\ZakatPayment;
use App\Models\Notification;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentNotificationController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Handle incoming Midtrans webhook notification.
     */
    public function handleNotification(Request $request)
    {
        Log::info('Midtrans Webhook Received', $request->all());

        try {
            $payment = $this->midtransService->handleNotificationPayload($request->all());
            if ($payment) {
                return response()->json(['status' => 'success', 'message' => 'Notification processed successfully.']);
            }
            return response()->json(['status' => 'ignored', 'message' => 'Payment not found or status unhandled.'], 200);
        } catch (\Exception $e) {
            Log::error('Midtrans Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    /**
     * Handle generic Midtrans callback.
     */
    public function midtransCallback(Request $request)
    {
        return $this->handleNotification($request);
    }

    /**
     * View all user/admin notifications.
     */
    public function notifications(Request $request)
    {
        $query = Notification::query();

        if (Auth::check() && Auth::user()->role === 'muzakki') {
            $muzakki = Auth::user()->muzakki;
            if ($muzakki) {
                $query->where('muzakki_id', $muzakki->id);
            }
        }

        $notifications = $query->latest()->paginate(15);
        return view('muzakki.notifications', compact('notifications'));
    }

    /**
     * Mark notifications as read via AJAX.
     */
    public function markNotificationsAsRead()
    {
        if (Auth::check() && Auth::user()->role === 'muzakki') {
            $muzakki = Auth::user()->muzakki;
            if ($muzakki) {
                Notification::where('muzakki_id', $muzakki->id)
                    ->where('is_read', false)
                    ->update(['is_read' => true]);
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Ajax endpoint for real-time notification polling.
     */
    public function ajaxNotifications(Request $request)
    {
        $unreadCount = 0;
        $notifications = [];

        if (Auth::check() && Auth::user()->role === 'muzakki') {
            $muzakki = Auth::user()->muzakki;
            if ($muzakki) {
                $unreadCount = Notification::where('muzakki_id', $muzakki->id)->where('is_read', false)->count();
                $notifications = Notification::where('muzakki_id', $muzakki->id)->latest()->take(5)->get();
            }
        }

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $notifications
        ]);
    }
}
