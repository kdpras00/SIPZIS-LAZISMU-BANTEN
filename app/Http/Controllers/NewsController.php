<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    
    public function index()
    {
        
        $news = News::published()
            ->with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        return view('pages.berita', compact('news'));
    }

    
    public function adminIndex()
    {
        $news = News::with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.news.index', compact('news'));
    }

    
    public function adminShow(News $news)
    {
        $news->load('author');
        return view('admin.news.show', compact('news'));
    }

    
    public function create()
    {
        return view('admin.news.create');
    }

    
    public function store(\App\Http\Requests\StoreNewsRequest $request)
    {
        $validated = $request->validated();

        
        $validated['slug'] = News::generateSlug($validated['title']);
        $validated['author_id'] = Auth::id();
        $validated['is_published'] = $request->has('is_published');

        
        if ($request->hasFile('image')) {
            $validated['image'] = app(\App\Services\MediaService::class)->uploadImage(
                $request->file('image'), 
                'news'
            );
        }

        News::create($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil ditambahkan!');
    }

    
    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    
    public function update(\App\Http\Requests\UpdateNewsRequest $request, News $news)
    {
        $validated = $request->validated();

        
        if ($news->title !== $validated['title']) {
            $validated['slug'] = News::generateSlug($validated['title']);
        }

        $validated['is_published'] = $request->has('is_published');

        
        if ($request->hasFile('image')) {
            $validated['image'] = app(\App\Services\MediaService::class)->uploadImage(
                $request->file('image'), 
                'news',
                $news->image
            );
        }

        $news->update($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil diperbarui!');
    }

    
    public function destroy(News $news)
    {
        
        if ($news->image) {
            app(\App\Services\MediaService::class)->deleteImage($news->image);
        }

        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil dihapus!');
    }

    
    public function togglePublish(News $news)
    {
        $news->update([
            'is_published' => !$news->is_published
        ]);

        $status = $news->is_published ? 'dipublikasikan' : 'di-draft';

        return back()->with('success', "Berita berhasil {$status}!");
    }

    
    public function publicIndex()
    {
        $news = News::published()
            ->with('author')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('partials.berita', compact('news'));
    }

    
    public function all()
    {
        $news = News::published()
            ->with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('news.all', compact('news'));
    }

    
    public function show($slug)
    {
        $news = News::where('slug', $slug)
            ->published()
            ->with('author')
            ->firstOrFail();

        return view('news.show', compact('news'));
    }
}
