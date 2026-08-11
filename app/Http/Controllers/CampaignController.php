<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\Program;
use App\Models\Payment;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

use App\Services\MediaService;

class CampaignController extends Controller
{
    protected $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }
    
    public function all()
    {
        $campaigns = Campaign::active()
            ->withSum('payments', 'paid_amount')
            ->orderBy('created_at', 'desc')
            ->get();

        
        $totalCollected = 0;
        $totalTarget = 0;

        foreach ($campaigns as $campaign) {
            $collected = $campaign->collected_amount;
            $campaign->display_collected_amount = $collected;
            $totalCollected += $collected;
            $totalTarget += $campaign->target_amount;
        }

        return view('campaigns.all', compact('campaigns', 'totalCollected', 'totalTarget'));
    }

    
    public function index($category)
    {
        $campaigns = Campaign::active()
            ->byCategory($category)
            ->withSum('payments', 'paid_amount')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($campaigns as $campaign) {
            $campaign->display_collected_amount = $campaign->collected_amount;
        }

        
        $program = Program::byCategory($category)->first();
        $totalCollected = 0;
        $totalTarget = 0;

        if ($program) {
            $totalCollected = $program->total_collected;
            $totalTarget = $program->total_target;
        } else {
            $totalCollected = $campaigns->sum(fn($c) => $c->display_collected_amount);
            $totalTarget = $campaigns->sum('target_amount');
        }

        $categoryDetails = $this->getCategoryDetails($category);

        return view('campaigns.index', compact('campaigns', 'category', 'categoryDetails', 'totalCollected', 'totalTarget'));
    }

    
    public function show($category, Campaign $campaign)
    {
        
        $campaign->checkAndCompleteIfExpired();
        
        
        $campaign->refresh();
        
        
        if ($campaign->status === 'completed') {
            abort(404, 'Campaign sudah selesai.');
        }

        
        $campaign->load('payments.muzakki');
        $campaign->display_collected_amount = $campaign->collected_amount;

        
        $program = Program::byCategory($category)->first();
        $totalCollected = 0;

        if ($program) {
            $totalCollected = $program->total_collected;
        } else {
            
            $totalCollected = Campaign::published()
                ->byCategory($category)
                ->withSum('payments', 'paid_amount')
                ->get()
                ->sum('payments_sum_paid_amount');
        }

        $categoryDetails = $this->getCategoryDetails($category);

        return view('campaigns.show', compact('campaign', 'category', 'categoryDetails', 'totalCollected'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'program_category' => 'required|string|in:zakat,infaq,shadaqah,pendidikan,kesehatan,ekonomi,sosial-dakwah,kemanusiaan,lingkungan',
            'target_amount' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,completed,cancelled',
            'end_date' => 'nullable|date|after:today'
        ]);

        
        $targetAmount = $request->input('target_amount');
        if ($targetAmount && $targetAmount !== '') {
            $targetAmount = str_replace(['.', ','], '', $targetAmount);
            $targetAmount = is_numeric($targetAmount) ? (float)$targetAmount : 0;
        } else {
            $targetAmount = 0;
        }

        $data = $request->only([
            'title',
            'description',
            'program_category',
            'status',
            'end_date'
        ]);
        $data['target_amount'] = $targetAmount;

        
        $data['collected_amount'] = 0;
        $data['created_by'] = Auth::id();
        $data['is_published'] = ($data['status'] ?? 'draft') === 'published';

        
        $program = Program::byCategory($request->program_category)->first();
        if ($program) {
            $data['program_id'] = $program->id;
        }

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('campaigns', 'public');
        }

        $campaign = Campaign::create($data);

        return redirect()->back()->with('success', 'Campaign created successfully.');
    }

    

    
public function adminIndex()
{
    
    $campaigns = Campaign::orderBy('created_at', 'desc')->get();

    
    foreach ($campaigns as $campaign) {
        $campaign->display_collected_amount = $campaign->payments()->sum('paid_amount');
    }

    return view('admin.campaigns.index', compact('campaigns'));
}
    
    public function adminCreate()
    {
        return view('admin.campaigns.create');
    }

    
    public function adminStore(\App\Http\Requests\StoreCampaignRequest $request)
    {
        $data = $request->validated();
        
        $data['status'] = $data['status'] ?? 'draft';
        $data['is_published'] = $data['status'] === 'published';
        
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->mediaService->uploadImage($request->file('photo'), 'campaigns');
        }

        $campaign = Campaign::create($data);

        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign created successfully.');
    }

    
    public function adminEdit(Campaign $campaign)
    {
        
        

        return view('admin.campaigns.edit', compact('campaign'));
    }

    
    public function adminUpdate(\App\Http\Requests\UpdateCampaignRequest $request, Campaign $campaign)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $this->mediaService->uploadImage($request->file('photo'), 'campaigns', $campaign->photo);
        }

        $data['is_published'] = ($data['status'] ?? $campaign->status) === 'published';

        $campaign->update($data);

        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign updated successfully.');
    }

    
    public function adminDestroy(Campaign $campaign)
    {
        
        $this->mediaService->deleteImage($campaign->photo);

        $campaign->delete();

        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign deleted successfully.');
    }

    
    private function getCategoryDetails($category)
    {
        $categories = [
            'pendidikan' => [
                'title' => 'Pendidikan',
                'subtitle' => 'Meningkatkan kualitas pendidikan melalui berbagai inisiatif',
                'image' => asset('storage/program/pendidikan.jpg'),
                'text_color' => 'text-blue-800',
                'bg_color' => 'bg-blue-100',
                'border_color' => 'border-blue-200'
            ],
            'kesehatan' => [
                'title' => 'Kesehatan',
                'subtitle' => 'Memberikan akses layanan kesehatan yang terjangkau',
                'image' => asset('storage/program/kesehatan.jpg'),
                'text_color' => 'text-green-800',
                'bg_color' => 'bg-green-100',
                'border_color' => 'border-green-200'
            ],
            'ekonomi' => [
                'title' => 'Ekonomi',
                'subtitle' => 'Mendorong kemandirian ekonomi masyarakat',
                'image' => asset('storage/program/ekonomi.jpg'),
                'text_color' => 'text-amber-800',
                'bg_color' => 'bg-amber-100',
                'border_color' => 'border-amber-200'
            ],
            'sosial-dakwah' => [
                'title' => 'Sosial & Dakwah',
                'subtitle' => 'Mengembangkan kegiatan sosial dan dakwah',
                'image' => asset('storage/program/sosial-dakwah.jpg'),
                'text_color' => 'text-purple-800',
                'bg_color' => 'bg-purple-100',
                'border_color' => 'border-purple-200'
            ],
            'kemanusiaan' => [
                'title' => 'Kemanusiaan',
                'subtitle' => 'Menyejahterakan umat manusia tanpa diskriminasi',
                'image' => asset('storage/program/kemanusiaan.jpg'),
                'text_color' => 'text-purple-800',
                'bg_color' => 'bg-purple-100',
                'border_color' => 'border-purple-200'
            ],
            'lingkungan' => [
                'title' => 'Lingkungan',
                'subtitle' => 'Menjaga lingkungan untuk generasi mendatang',
                'image' => asset('storage/program/lingkungan.jpg'),
                'text_color' => 'text-cyan-800',
                'bg_color' => 'bg-cyan-100',
                'border_color' => 'border-cyan-200'
            ]
        ];

        return $categories[$category] ?? [
            'title' => ucfirst($category),
            'subtitle' => 'Program ' . ucfirst($category),
            'image' => asset('img/masjidbanten.webp'),
            'text_color' => 'text-emerald-800',
            'bg_color' => 'bg-emerald-100',
            'border_color' => 'border-emerald-200'
        ];
    }

    public function showPersonalCampaign($email)
    {
        
        $muzakki = \App\Models\Muzakki::where('email', $email)->first();

        if (!$muzakki) {
            abort(404, 'Campaigner tidak ditemukan');
        }

        $featureAvailable = Schema::hasColumn('campaigns', 'created_by');

        if ($featureAvailable) {
            $campaigns = Campaign::where('created_by', $muzakki->user_id)
                ->where('is_published', true)
                ->orderBy('created_at', 'desc')
                ->paginate(12);
        } else {
            $campaigns = new LengthAwarePaginator([], 0, 12, 1, [
                'path' => request()->url(),
                'pageName' => 'page',
            ]);
        }

        return view('campaigns.personal', [
            'muzakki' => $muzakki,
            'campaigns' => $campaigns,
            'campaignFeatureAvailable' => $featureAvailable,
        ]);
    }
}
