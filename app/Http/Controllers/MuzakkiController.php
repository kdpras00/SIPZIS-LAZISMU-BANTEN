<?php

namespace App\Http\Controllers;

use App\Models\Muzakki;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MuzakkiController extends Controller
{
    

    
    public function index(Request $request)
    {
        $query = Muzakki::with('user')->withCount('payments');

        
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        
        if ($request->has('occupation') && $request->occupation != '') {
            $query->where('occupation', $request->occupation);
        }

        
        if ($request->has('city') && $request->city != '') {
            $query->where('city', 'like', "%{$request->city}%");
        }

        
        if ($request->has('status')) {
            $query->where('is_active', $request->status == 'active');
        }

        $muzakki = $query->latest()->paginate(15)->withQueryString();

        
        $occupations = Muzakki::select('occupation')->distinct()->whereNotNull('occupation')->pluck('occupation');
        $cities = Muzakki::select('city')->distinct()->whereNotNull('city')->pluck('city');

        return view('muzakki.index', compact('muzakki', 'occupations', 'cities'));
    }

    
    public function create()
    {
        return view('muzakki.create');
    }

    
    public function store(\App\Http\Requests\StoreMuzakkiRequest $request)
    {
        $validated = $request->validated();
        $user = null;

        
        if ($request->create_user_account && $request->email) {
            $request->validate([
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'muzakki',
                'is_active' => true,
                'phone' => $request->phone,
            ]);
        }

        
        $country = $request->country;
        $province = $request->province_name ?? $request->province;
        $city = $request->city_name ?? $request->city;
        $district = $request->district_name ?? $request->district;
        $village = $request->village_name ?? $request->village;

        
        $profilePhotoPath = null;
        $ktpPhotoPath = null;

        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        if ($request->hasFile('ktp_photo')) {
            $ktpPhotoPath = $request->file('ktp_photo')->store('ktp_photos', 'public');
        }

        
        $campaignUrl = $request->email ? url('/campaigner/' . $request->email) : null;

        $muzakki = Muzakki::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'address' => $request->address,
            'city' => $city,
            'province' => $province,
            'district' => $district,
            'village' => $village,
            'postal_code' => $request->postal_code,
            'country' => $country, 
            'campaign_url' => $campaignUrl, 
            'profile_photo' => $profilePhotoPath, 
            'ktp_photo' => $ktpPhotoPath, 
            'bio' => $request->bio, 
            'occupation' => $request->occupation,
            'date_of_birth' => $request->date_of_birth,
            'user_id' => $user?->id,
            'is_active' => true,
        ]);

        return redirect()->route('muzakki.index')->with('success', 'Data muzakki berhasil ditambahkan.');
    }

    
    public function show(Muzakki $muzakki)
    {
        $muzakki->load(['user', 'payments.program']);

        $stats = [
            'total_zakat' => $muzakki->payments()->completed()->sum('paid_amount'),
            'payment_count' => $muzakki->payments()->completed()->count(),
            'last_payment' => $muzakki->payments()->completed()->latest('payment_date')->first(),
        ];

        $recentPayments = $muzakki->payments()
            ->with('program')
            ->completed()
            ->latest('payment_date')
            ->take(10)
            ->get();

        return view('muzakki.show', compact('muzakki', 'stats', 'recentPayments'));
    }

    
    public function edit(?Muzakki $muzakki = null)
    {
        
        if (!$muzakki) {
            $user = Auth::user();
            $muzakki = $user->muzakki;

            
            if (!$muzakki) {
                $muzakki = Muzakki::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'user_id' => $user->id,
                    'is_active' => $user->is_active ?? true,
                    'campaign_url' => url('/campaigner/' . $user->email),
                ]);
            }
        }

        
        return view('muzakki.edit', compact('muzakki'));
    }

    
    public function update(\App\Http\Requests\UpdateMuzakkiRequest $request, ?Muzakki $muzakki = null)
    {
        
        if (!$muzakki) {
            $muzakki = Auth::user()->muzakki;
            if (!$muzakki) {
                abort(404, 'Profil muzakki tidak ditemukan.');
            }
        }

        
        if ($request->has('current_password') && $request->has('new_password')) {
            $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            if (!Hash::check($request->current_password, Auth::user()->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
            }

            Auth::user()->update([
                'password' => Hash::make($request->new_password)
            ]);

            return back()->with('success', 'Password berhasil diperbarui.');
        }

        
        if (Auth::user()->role === 'admin' && $request->filled('new_password')) {
            $request->validate([
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            if ($muzakki->user) {
                $muzakki->user->update([
                    'password' => Hash::make($request->new_password)
                ]);
            }

            
            if (count($request->all()) <= 4) { 
                 return redirect()->route('muzakki.index')->with('success', 'Password berhasil diperbarui.');
            }
        }

        
        if ($request->filled(['birth_year', 'birth_month', 'birth_day'])) {
            $request->merge([
                'date_of_birth' => $request->birth_year . '-' . str_pad($request->birth_month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($request->birth_day, 2, '0', STR_PAD_LEFT)
            ]);
        }

        
        $updateData = $muzakki->toArray();

        
        $updateData['name'] = $request->name;
        $updateData['email'] = $request->email;
        $updateData['phone'] = $request->phone;
        $updateData['gender'] = $request->gender;
        $updateData['address'] = $request->address;
        $updateData['occupation'] = $request->occupation;
        $updateData['bio'] = $request->bio;
        $updateData['is_active'] = $request->is_active ?? $muzakki->is_active;

        
        if ($request->hasFile('profile_photo')) {
            $updateData['profile_photo'] = app(\App\Services\MediaService::class)->uploadImage(
                $request->file('profile_photo'), 
                'profile_photos', 
                $muzakki->profile_photo
            );
        }

        if ($request->hasFile('ktp_photo')) {
            $updateData['ktp_photo'] = app(\App\Services\MediaService::class)->uploadImage(
                $request->file('ktp_photo'), 
                'ktp_photos', 
                $muzakki->ktp_photo
            );
        }

        
        if ($request->filled('country_name')) {
            $updateData['country'] = $request->country_name;
        } elseif ($request->filled('country')) {
            $updateData['country'] = $request->country;
        }
        
        if (empty($updateData['country'])) {
            $updateData['country'] = 'Indonesia';
        }

        
        if ($request->filled('campaign_url')) {
            $updateData['campaign_url'] = $request->campaign_url;
        } else {
            
            if (!empty($updateData['email'])) {
                $updateData['campaign_url'] = url('/campaigner/' . $updateData['email']);
            }
        }

        
        if ($request->filled('date_of_birth')) {
            $updateData['date_of_birth'] = $request->date_of_birth;
        }

        
        if ($request->has('phone_verified')) {
            $updateData['phone_verified'] = (int)$request->phone_verified;
        }

        
        if ($request->filled('province_name')) {
            $updateData['province'] = $request->province_name;
        } elseif ($request->filled('province')) {
            $updateData['province'] = $request->province;
        }

        if ($request->filled('city_name')) {
            $updateData['city'] = $request->city_name;
        } elseif ($request->filled('city')) {
            $updateData['city'] = $request->city;
        }

        if ($request->filled('district_name')) {
            $updateData['district'] = $request->district_name;
        } elseif ($request->filled('district')) {
            $updateData['district'] = $request->district;
        }

        if ($request->filled('village_name')) {
            $updateData['village'] = $request->village_name;
        } elseif ($request->filled('village')) {
            $updateData['village'] = $request->village;
        }

        if ($request->filled('postal_code')) {
            $updateData['postal_code'] = $request->postal_code;
        }

        
        unset($updateData['created_at'], $updateData['updated_at']);

        
        \Illuminate\Support\Facades\Log::info('Muzakki Update Data:', $updateData);

        
        $muzakki->update($updateData);

        
        if ($muzakki->user) {
            $muzakki->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'is_active' => $request->is_active ?? true,
            ]);
        }

        
        if (request()->route()->hasParameter('muzakki')) {
            return redirect()->route('muzakki.index')->with('success', 'Data muzakki berhasil diperbarui.');
        } else {
            return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
        }
    }

    
    public function destroy(Muzakki $muzakki)
    {
        
        if ($muzakki->payments()->count() > 0) {
            return redirect()->route('muzakki.index')->with('error', 'Muzakki tidak dapat dihapus karena sudah memiliki riwayat pembayaran zakat.');
        }

        app(\App\Services\MediaService::class)->deleteImage($muzakki->profile_photo);
        app(\App\Services\MediaService::class)->deleteImage($muzakki->ktp_photo);

        
        if ($muzakki->user) {
            $muzakki->user->delete();
        }

        $muzakki->delete();

        return redirect()->route('muzakki.index')->with('success', 'Data muzakki berhasil dihapus.');
    }

    
    public function toggleStatus(Muzakki $muzakki)
    {
        $muzakki->update(['is_active' => !$muzakki->is_active]);

        
        if ($muzakki->user) {
            $muzakki->user->update(['is_active' => $muzakki->is_active]);
        }

        $status = $muzakki->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('muzakki.index')->with('success', "Muzakki berhasil {$status}.");
    }

    
    public function search(Request $request)
    {
        $query = Muzakki::with('user')->withCount('payments');

        
        if ($request->has('search') && $request->search != '') {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        
        if ($request->has('occupation') && $request->occupation != '') {
            $query->where('occupation', $request->occupation);
        }

        
        if ($request->has('city') && $request->city != '') {
            $query->where('city', 'like', "%{$request->city}%");
        }

        
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status == 'active');
        }

        $muzakki = $query->latest()->paginate(15);

        
        $totalCount = Muzakki::count();
        $activeCount = Muzakki::where('is_active', true)->count();
        $inactiveCount = Muzakki::where('is_active', false)->count();
        $thisMonthCount = Muzakki::where('created_at', '>=', now()->startOfMonth())->count();

        return response()->json([
            'success' => true,
            'data' => [
                'muzakki' => $muzakki->items(),
                'pagination' => [
                    'current_page' => $muzakki->currentPage(),
                    'last_page' => $muzakki->lastPage(),
                    'per_page' => $muzakki->perPage(),
                    'total' => $muzakki->total(),
                    'from' => $muzakki->firstItem(),
                    'to' => $muzakki->lastItem(),
                ],
                'statistics' => [
                    'total' => $totalCount,
                    'active' => $activeCount,
                    'inactive' => $inactiveCount,
                    'this_month' => $thisMonthCount,
                ],
            ]
        ]);
    }
}
