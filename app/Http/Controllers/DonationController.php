<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller
{
    
    public function show($slug, Request $request)
    {
        
        $campaign = Campaign::whereRaw('LOWER(REPLACE(title, " ", "-")) = ?', [$slug])->first();

        if (!$campaign) {
            abort(404, 'Program donasi tidak ditemukan.');
        }

        return redirect()->route('guest.payment.create', ['campaign_id' => $campaign->id]);
    }
}
