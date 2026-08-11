<?php

namespace App\Http\Controllers;

use App\Models\Payment;
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

    
    public function handleNotification(Request $request)
    {
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

    
    public function midtransCallback(Request $request)
    {
        return $this->handleNotification($request);
    }

    
    public function notifications(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $query = Notification::query();

        if (Auth::check() && Auth::user()->role === 'muzakki') {
            $muzakki = Auth::user()->muzakki;
            if ($muzakki) {
                $query->where('muzakki_id', $muzakki->id);
            }
        }

        
        $typesQuery = clone $query;
        $notificationTypes = $typesQuery->select('type', \DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        if ($filter !== 'all') {
            $query->where('type', $filter);
        }

        $notifications = $query->latest()->paginate(15);
        return view('muzakki.notifications', compact('notifications', 'filter', 'notificationTypes'));
    }

    
    public function markNotificationsAsRead(Request $request)
    {
        if (Auth::check() && Auth::user()->role === 'muzakki') {
            $muzakki = Auth::user()->muzakki;
            if ($muzakki) {
                Notification::where('muzakki_id', $muzakki->id)
                    ->where('is_read', false)
                    ->update(['is_read' => true]);
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }

    
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

        $html = view('muzakki.partials.notifications-popup', compact('notifications'))->render();

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount,
            'html' => $html
        ]);
    }
}
