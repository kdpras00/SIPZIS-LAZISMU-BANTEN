<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArtikelController extends Controller
{
    
    public function index()
    {
        $artikels = Artikel::with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.artikel.index', compact('artikels'));
    }

    
    public function create()
    {
        return view('admin.artikel.create');
    }

    
    public function store(\App\Http\Requests\StoreArtikelRequest $request)
    {
        $validated = $request->validated();

        
        $validated['slug'] = Artikel::generateSlug($validated['title']);
        $validated['author_id'] = Auth::id();
        $validated['is_published'] = $request->has('is_published');

        
        if ($request->hasFile('image')) {
            $validated['image'] = app(\App\Services\MediaService::class)->uploadImage(
                $request->file('image'), 
                'artikel'
            );
        }

        Artikel::create($validated);

        return redirect()->route('admin.artikel.index')
            ->with('success', 'Artikel berhasil ditambahkan!');
    }

    
    public function show(Artikel $artikel)
    {
        $artikel->load('author');
        return view('admin.artikel.show', compact('artikel'));
    }

    
    public function edit(Artikel $artikel)
    {
        return view('admin.artikel.edit', compact('artikel'));
    }

    
    public function update(\App\Http\Requests\UpdateArtikelRequest $request, Artikel $artikel)
    {
        $validated = $request->validated();

        
        if ($artikel->title !== $validated['title']) {
            $validated['slug'] = Artikel::generateSlug($validated['title']);
        }

        $validated['is_published'] = $request->has('is_published');

        
        if ($request->hasFile('image')) {
            $validated['image'] = app(\App\Services\MediaService::class)->uploadImage(
                $request->file('image'), 
                'artikel',
                $artikel->image
            );
        }

        $artikel->update($validated);

        return redirect()->route('admin.artikel.index')
            ->with('success', 'Artikel berhasil diperbarui!');
    }

    
    public function destroy(Artikel $artikel)
    {
        
        if ($artikel->image) {
            app(\App\Services\MediaService::class)->deleteImage($artikel->image);
        }

        $artikel->delete();

        return redirect()->route('admin.artikel.index')
            ->with('success', 'Artikel berhasil dihapus!');
    }

    
    public function togglePublish(Artikel $artikel)
    {
        $artikel->update([
            'is_published' => !$artikel->is_published
        ]);

        $status = $artikel->is_published ? 'dipublikasikan' : 'di-draft';
        
        return back()->with('success', "Artikel berhasil {$status}!");
    }

    
    public function publicIndex()
    {
        $artikels = Artikel::published()
            ->with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('artikel.index', compact('artikels'));
    }

    
    public function publicShow($slug)
    {
        $artikel = Artikel::where('slug', $slug)
            ->published()
            ->with('author')
            ->firstOrFail();

        return view('artikel.show', compact('artikel'));
    }
}