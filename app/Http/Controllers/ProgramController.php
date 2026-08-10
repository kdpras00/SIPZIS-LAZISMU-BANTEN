<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Services\MediaService;

class ProgramController extends Controller
{
    protected $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }
    
    public function adminIndex()
    {
        $programs = Program::orderBy('category')
            ->orderBy('name')
            ->get();

        $groupedPrograms = $programs->groupBy('category');

        return view('admin.programs.index', compact('groupedPrograms'));
    }

    
    public function adminCreate()
    {
        $categories = $this->getAvailableCategories();

        return view('admin.programs.create', compact('categories'));
    }

    
    public function adminBulkCreate()
    {
        $categories = $this->getAvailableCategories();

        return view('admin.programs.bulk-create', compact('categories'));
    }

    
    public function adminStore(\App\Http\Requests\StoreProgramRequest $request)
    {
        $data = $request->validated();
        
        $data['slug'] = $this->generateUniqueSlug($data['name']);

        
        if (Program::where('name', $data['name'])->where('category', $data['category'])->exists()) {
            return redirect()->back()->withInput()
                ->withErrors(['name' => 'Program dengan nama dan kategori ini sudah ada.']);
        }

        
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->mediaService->uploadImage($request->file('photo'), 'programs');
        }

        Program::create($data);

        return redirect()->route('admin.programs.index')
            ->with('success', 'Program berhasil ditambahkan.');
    }

    
    public function adminStoreBulk(\App\Http\Requests\StoreBulkProgramRequest $request)
    {
        $validated = $request->validated();

        foreach ($validated['programs'] as $programData) {
            $data = [
                'name' => $programData['name'],
                'description' => $programData['description'] ?? '',
                'target_amount' => $programData['target_amount'] ?? 0,
                'status' => $programData['status'],
                'category' => $programData['category'], 
                'slug' => $this->generateUniqueSlug($programData['name']),
            ];

            
            if (Program::where('name', $data['name'])->where('category', $data['category'])->exists()) {
                return redirect()->back()->withInput()
                    ->withErrors([
                        'programs' => 'Program "' . $data['name'] . '" dengan kategori "' . $data['category'] . '" sudah ada.'
                    ]);
            }

            Program::create($data);
        }

        return redirect()->route('admin.programs.index')
            ->with('success', 'Semua program berhasil dibuat.');
    }

    
    public function adminEdit(Program $program)
    {
        $categories = $this->getAvailableCategories();

        return view('admin.programs.edit', compact('program', 'categories'));
    }

    
    public function adminUpdate(\App\Http\Requests\UpdateProgramRequest $request, Program $program)
    {
        $data = $request->validated();

        $data['slug'] = $this->generateUniqueSlug($data['name'], $program->id);

        
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->mediaService->uploadImage($request->file('photo'), 'programs', $program->photo);
        }

        $program->update($data);

        return redirect()->route('admin.programs.index')
            ->with('success', 'Program berhasil diperbarui.');
    }

    
    public function adminDestroy(Program $program)
    {
        $this->mediaService->deleteImage($program->photo);

        $program->delete();

        return redirect()->route('admin.programs.index')
            ->with('success', 'Program berhasil dihapus.');
    }

    
    public function show($slug)
    {
        $program = Program::where('slug', $slug)->active()->firstOrFail();

        $zakatTypes = Program::active()->where('category', 'like', 'zakat%')->get();
        $collectedAmount = $program->total_collected;
        $totalTarget = $program->total_target;
        $category = $program->category;

        
        $viewName = 'programs.show';

        return view($viewName, compact('program', 'zakatTypes', 'collectedAmount', 'totalTarget', 'category'));
    }

    
    public function completed($id)
    {
        $program = Program::findOrFail($id);
        
        
        $recommendedPrograms = Program::active()
            ->where('id', '!=', $id)
            ->where('category', $program->category)
            ->limit(3)
            ->get();
        
        
        if ($recommendedPrograms->count() < 3) {
            $additionalPrograms = Program::active()
                ->where('id', '!=', $id)
                ->whereNotIn('id', $recommendedPrograms->pluck('id'))
                ->limit(3 - $recommendedPrograms->count())
                ->get();
            
            $recommendedPrograms = $recommendedPrograms->merge($additionalPrograms);
        }
        
        return view('programs.completed', compact('program', 'recommendedPrograms'));
    }

    
    private function getAvailableCategories(): array
    {
        return [
            'zakat' => 'Zakat',
            'infaq' => 'Infaq',
            'shadaqah' => 'Shadaqah',
            'pendidikan' => 'Pendidikan',
            'kesehatan' => 'Kesehatan',
            'ekonomi' => 'Ekonomi',
            'sosial-dakwah' => 'Sosial & Dakwah',
            'kemanusiaan' => 'Kemanusiaan',
            'lingkungan' => 'Lingkungan',
        ];
    }

    
    private function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (true) {
            $query = Program::where('slug', $slug);
            
            
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            if (!$query->exists()) {
                return $slug;
            }

            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
    }
}
