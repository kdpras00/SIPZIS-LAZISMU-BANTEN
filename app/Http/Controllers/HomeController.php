<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Program;
use App\Models\News;
use App\Models\Artikel;

class HomeController extends Controller
{
    private function preventAdminAccess()
    {
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            return redirect()->route('dashboard')
                ->with('warning', 'Anda harus logout terlebih dahulu untuk mengakses halaman umum.');
        }
        return null;
    }

    public function index()
    {
        if ($redirect = $this->preventAdminAccess()) return $redirect;
        return view('pages.home');
    }

    public function tentang()
    {
        if ($redirect = $this->preventAdminAccess()) return $redirect;
        return view('pages.tentang');
    }

    public function berita()
    {
        if ($redirect = $this->preventAdminAccess()) return $redirect;

        
        $news = News::published()
            ->with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        return view('pages.berita', compact('news'));
    }

    public function artikel()
    {
        if ($redirect = $this->preventAdminAccess()) return $redirect;

        $artikels = Artikel::published()
            ->with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('artikel.index', compact('artikels'));
    }

    
    public function artikelAll()
    {
        if ($redirect = $this->preventAdminAccess()) return $redirect;

        $artikels = Artikel::published()
            ->with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('artikel.index', compact('artikels'));
    }

    
    public function artikelShow($slug)
    {
        if ($redirect = $this->preventAdminAccess()) return $redirect;

        $artikel = Artikel::where('slug', $slug)
            ->published()
            ->with('author')
            ->firstOrFail();

        return view('artikel.show', compact('artikel'));
    }

    public function program()
    {
        if ($redirect = $this->preventAdminAccess()) return $redirect;

        
        $allPrograms = cache()->remember('active_programs', 3600, function () {
            return Program::active()->get();
        });

        $zakatPrograms = $allPrograms->where('category', 'zakat');
        $infaqPrograms = $allPrograms->where('category', 'infaq');
        $shadaqahPrograms = $allPrograms->where('category', 'shadaqah');
        $pilarPrograms = $allPrograms->whereIn('category', ['pendidikan', 'kesehatan', 'ekonomi', 'sosial-dakwah', 'kemanusiaan', 'lingkungan']);

        return view('pages.program', compact('zakatPrograms', 'infaqPrograms', 'shadaqahPrograms', 'pilarPrograms'));
    }

    public function programByCategory($category)
    {
        if ($redirect = $this->preventAdminAccess()) return $redirect;

        
        $allPrograms = cache()->remember('active_programs', 3600, function () {
            return Program::active()->get();
        });

        $zakatPrograms = $allPrograms->where('category', 'zakat');
        $infaqPrograms = $allPrograms->where('category', 'infaq');
        $shadaqahPrograms = $allPrograms->where('category', 'shadaqah');
        $pilarPrograms = $allPrograms->whereIn('category', ['pendidikan', 'kesehatan', 'ekonomi', 'sosial-dakwah', 'kemanusiaan', 'lingkungan']);

        return view('pages.program', [
            'activeTab' => $category,
            'zakatPrograms' => $zakatPrograms,
            'infaqPrograms' => $infaqPrograms,
            'shadaqahPrograms' => $shadaqahPrograms,
            'pilarPrograms' => $pilarPrograms
        ]);
    }
}
