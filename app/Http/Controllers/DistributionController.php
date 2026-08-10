<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use App\Models\Mustahik;
use App\Models\Payment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DistributionController extends Controller
{
    
    public static function availableBalance(): float
    {
        $paid = Payment::completed()->sum('paid_amount');
        $distributed = Distribution::where('distribution_type', 'cash')->sum('amount');
        $balance = $paid - $distributed;
        return $balance > 0 ? $balance : 0;
    }

    
    public function index(Request $request)
    {
        $query = Distribution::with(['mustahik', 'distributedBy']);

        
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('distribution_code', 'like', "%{$search}%")
                    ->orWhere('program_name', 'like', "%{$search}%")
                    ->orWhereHas('mustahik', fn($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        
        if ($category = $request->get('category')) {
            $query->whereHas('mustahik', fn($q) => $q->where('category', $category));
        }

        
        if ($type = $request->get('distribution_type')) {
            $query->where('distribution_type', $type);
        }

        
        if ($program = $request->get('program')) {
            $query->where('program_name', 'like', "%{$program}%");
        }

        
        if ($request->filled('received_status')) {
            $query->where('is_received', $request->received_status === 'received');
        }

        
        if ($from = $request->get('date_from')) {
            $query->whereDate('distribution_date', '>=', $from);
        }
        if ($to = $request->get('date_to')) {
            $query->whereDate('distribution_date', '<=', $to);
        }

        $distributions = $query->latest('distribution_date')->paginate(15)->withQueryString();

        
        $categories = array_keys(Mustahik::CATEGORIES);
        $programs = Distribution::select('program_name')
            ->distinct()
            ->whereNotNull('program_name')
            ->pluck('program_name');

        
        $stats = [
            'total_amount' => Distribution::sum('amount'),
            'total_count' => Distribution::count(),
            'this_month' => Distribution::whereMonth('distribution_date', date('m'))->sum('amount'),
            'pending_receipt' => Distribution::where('is_received', false)->count(),
            'available_balance' => self::availableBalance(),
        ];

        return view('distributions.index', compact('distributions', 'categories', 'programs', 'stats'));
    }

    
    public function create(Request $request)
    {
        $mustahikId = $request->get('mustahik_id');
        $mustahik = $mustahikId ? Mustahik::findOrFail($mustahikId) : null;

        $allMustahik = cache()->remember('active_mustahik', 300, function() { return Mustahik::active()->orderBy('name')->get(); });
        $categories = array_keys(Mustahik::CATEGORIES);
        $availableBalance = self::availableBalance();

        return view('distributions.create', compact('mustahik', 'allMustahik', 'categories', 'availableBalance'));
    }

    
    public function store(\App\Http\Requests\StoreDistributionRequest $request)
    {
        $validated = $request->validated();
        $amount = (float) $validated['amount'];

        $mustahik = Mustahik::active()->findOrFail($request->mustahik_id);

        $distribution = DB::transaction(function () use ($request, $mustahik, $amount) {

            
            $paid = Payment::completed()->lockForUpdate()->sum('paid_amount');
            $distributed = Distribution::where('distribution_type', 'cash')
                ->lockForUpdate()
                ->sum('amount');
            $available = max(0, $paid - $distributed);

            if ($request->distribution_type === 'cash' && $amount > $available) {
                return null;
            }

            $distributionCode = Distribution::generateDistributionCode();

            $programId = null;
            if ($request->filled('program_id')) {
                $programId = $request->program_id;
            } elseif ($request->filled('program_slug')) {
                $program = \App\Models\Program::where('slug', $request->program_slug)->first();
                if ($program) {
                    $programId = $program->id;
                }
            }

            return Distribution::create([
                'distribution_code' => $distributionCode,
                'mustahik_id' => $mustahik->id,
                'amount' => $amount,
                'distribution_type' => $request->distribution_type,
                'goods_description' => $request->goods_description,
                'distribution_date' => $request->distribution_date,
                'notes' => $request->notes,
                'program_id' => $programId,
                'program_name' => $request->program_name,
                'distributed_by' => Auth::id(),
                'location' => $request->location,
                'is_received' => false,
            ]);
        });

        if (!$distribution) {
            return back()->withInput()->with('error', 'Saldo zakat tidak mencukupi.');
        }

        return redirect()->route('distributions.index')->with('success', 'Distribusi zakat berhasil dicatat.');
    }

    
    public function show(Distribution $distribution)
    {
        $distribution->load(['mustahik', 'distributedBy', 'program']);

        return view('distributions.show', compact('distribution'));
    }

    
    public function edit(Distribution $distribution)
    {
        $allMustahik = cache()->remember('active_mustahik', 300, function() { return Mustahik::active()->orderBy('name')->get(); });
        $categories = array_keys(Mustahik::CATEGORIES);
        $availableBalance = self::availableBalance();

        return view('distributions.edit', compact('distribution', 'allMustahik', 'categories', 'availableBalance'));
    }

    
    public function update(\App\Http\Requests\UpdateDistributionRequest $request, Distribution $distribution)
    {
        $validated = $request->validated();
        $amount = (float) $validated['amount'];

        return DB::transaction(function () use ($request, $distribution, $amount) {

            
            $paid = Payment::completed()->lockForUpdate()->sum('paid_amount');
            $distributed = Distribution::where('id', '!=', $distribution->id)
                ->where('distribution_type', 'cash')
                ->lockForUpdate()
                ->sum('amount');

            $available = max(0, $paid - $distributed);

            if ($request->distribution_type === 'cash' && $amount > $available) {
                return back()->withInput()->with('error', 'Saldo zakat tidak mencukupi.');
            }

            
            $programId = null;
            if ($request->filled('program_id')) {
                $programId = $request->program_id;
            } elseif ($request->filled('program_slug')) {
                $program = \App\Models\Program::where('slug', $request->program_slug)->first();
                if ($program) {
                    $programId = $program->id;
                }
            }

            $data = $request->all();
            $data['amount'] = $amount;
            $data['program_id'] = $programId;
            $distribution->update($data);
            return redirect()->route('distributions.index')->with('success', 'Distribusi zakat berhasil diperbarui.');
        });
    }

    
    public function markAsReceived(Request $request, Distribution $distribution)
    {
        $request->validate([
            'received_by_name' => 'nullable|string|max:255',
            'received_notes' => 'nullable|string',
        ]);

        if ($distribution->is_received) {
            return back()->with('error', 'Distribusi ini sudah ditandai sebagai diterima.');
        }

        $distribution->update([
            'is_received' => true,
            'received_date' => now(),
            'received_by_name' => $request->received_by_name,
            'received_notes' => $request->received_notes,
        ]);

        return redirect()->route('distributions.show', $distribution)
            ->with('success', 'Distribusi berhasil ditandai sebagai sudah diterima.');
    }

    
    public function destroy(Distribution $distribution)
    {
        if ($distribution->is_received) {
            return back()->with('error', 'Distribusi yang sudah diterima tidak dapat dihapus.');
        }

        $distribution->delete();
        return redirect()->route('distributions.index')->with('success', 'Data distribusi berhasil dihapus.');
    }

    
    public function receipt(Distribution $distribution)
    {
        $distribution->load(['mustahik', 'distributedBy']);

        return view('distributions.receipt', compact('distribution'));
    }

    
    public function search(Request $request)
    {
        $query = Distribution::with(['mustahik', 'distributedBy']);

        
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('distribution_code', 'like', "%{$search}%")
                    ->orWhere('program_name', 'like', "%{$search}%")
                    ->orWhereHas('mustahik', fn($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        
        if ($category = $request->get('category')) {
            $query->whereHas('mustahik', fn($q) => $q->where('category', $category));
        }

        
        if ($type = $request->get('distribution_type')) {
            $query->where('distribution_type', $type);
        }

        
        if ($program = $request->get('program')) {
            $query->where('program_name', 'like', "%{$program}%");
        }

        
        if ($request->has('received_status') && $request->received_status !== '') {
            $query->where('is_received', $request->received_status === 'received');
        }

        
        if ($from = $request->get('date_from')) {
            $query->whereDate('distribution_date', '>=', $from);
        }
        if ($to = $request->get('date_to')) {
            $query->whereDate('distribution_date', '<=', $to);
        }

        $distributions = $query->latest('distribution_date')->paginate(15);

        
        $statsQuery = Distribution::query();
        
        
        if ($search = $request->get('search')) {
            $statsQuery->where(function ($q) use ($search) {
                $q->where('distribution_code', 'like', "%{$search}%")
                    ->orWhere('program_name', 'like', "%{$search}%")
                    ->orWhereHas('mustahik', fn($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }
        if ($category = $request->get('category')) {
            $statsQuery->whereHas('mustahik', fn($q) => $q->where('category', $category));
        }
        if ($type = $request->get('distribution_type')) {
            $statsQuery->where('distribution_type', $type);
        }
        if ($program = $request->get('program')) {
            $statsQuery->where('program_name', 'like', "%{$program}%");
        }
        if ($request->has('received_status') && $request->received_status !== '') {
            $statsQuery->where('is_received', $request->received_status === 'received');
        }
        if ($from = $request->get('date_from')) {
            $statsQuery->whereDate('distribution_date', '>=', $from);
        }
        if ($to = $request->get('date_to')) {
            $statsQuery->whereDate('distribution_date', '<=', $to);
        }

        $stats = [
            'total_amount' => (clone $statsQuery)->sum('amount'),
            'total_count' => (clone $statsQuery)->count(),
            'this_month' => (clone $statsQuery)->whereMonth('distribution_date', date('m'))->sum('amount'),
            'pending_receipt' => (clone $statsQuery)->where('is_received', false)->count(),
            'available_balance' => self::availableBalance(),
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'distributions' => $distributions->items(),
                'pagination' => [
                    'current_page' => $distributions->currentPage(),
                    'last_page' => $distributions->lastPage(),
                    'from' => $distributions->firstItem(),
                    'to' => $distributions->lastItem(),
                    'total' => $distributions->total(),
                ],
                'statistics' => $stats,
            ]
        ]);
    }
}
