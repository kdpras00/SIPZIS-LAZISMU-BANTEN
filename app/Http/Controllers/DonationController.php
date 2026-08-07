<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller
{
    /**
     * Tampilkan halaman donasi berdasarkan slug campaign.
     */
    public function show($slug, Request $request)
    {
        // Ambil campaign berdasarkan slug (slug otomatis dari title)
        $campaign = Campaign::whereRaw('LOWER(REPLACE(title, " ", "-")) = ?', [$slug])->first();

        if (!$campaign) {
            abort(404, 'Program donasi tidak ditemukan.');
        }

        // Mapping kategori → subtitle default
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

        $subtitle = $categoryMap[$campaign->program_category] ?? 'Bersama Kita Wujudkan Kebaikan';
        $textColor = match ($campaign->program_category) {
            'pendidikan' => 'text-blue-800',
            'kesehatan' => 'text-red-800',
            'ekonomi' => 'text-amber-800',
            'sosial-dakwah' => 'text-green-800',
            'kemanusiaan' => 'text-purple-800',
            'lingkungan' => 'text-cyan-800',
            'zakat' => 'text-orange-800',
            'infaq' => 'text-blue-800',
            'shadaqah' => 'text-green-800',
            default => 'text-emerald-800',
        };

        // Ambil user yang login (jika muzakki)
        $loggedInMuzakki = Auth::check() && Auth::user()->role === 'muzakki'
            ? Auth::user()
            : null;

        return view('pages.donasi', [
            'campaign' => $campaign,
            'displayTitle' => $campaign->title,
            'displaySubtitle' => $subtitle,
            'programCategory' => $campaign->program_category,
            'textColor' => $textColor,
            'loggedInMuzakki' => $loggedInMuzakki,
        ]);
    }
}
