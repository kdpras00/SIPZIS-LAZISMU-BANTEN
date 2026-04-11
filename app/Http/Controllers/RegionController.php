<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RegionController extends Controller
{
    // Semua negara
    public function countries()
    {
        // Use local data for faster response
        $localCountries = [
            ['id' => 'ID', 'name' => 'Indonesia'],
            ['id' => 'MY', 'name' => 'Malaysia'],
            ['id' => 'SG', 'name' => 'Singapore'],
            ['id' => 'TH', 'name' => 'Thailand'],
            ['id' => 'PH', 'name' => 'Philippines'],
            ['id' => 'VN', 'name' => 'Vietnam'],
            ['id' => 'BN', 'name' => 'Brunei'],
            ['id' => 'KH', 'name' => 'Cambodia'],
            ['id' => 'LA', 'name' => 'Laos'],
            ['id' => 'MM', 'name' => 'Myanmar'],
            ['id' => 'TL', 'name' => 'Timor-Leste'],
            ['id' => 'US', 'name' => 'United States'],
            ['id' => 'GB', 'name' => 'United Kingdom'],
            ['id' => 'AU', 'name' => 'Australia'],
            ['id' => 'JP', 'name' => 'Japan'],
            ['id' => 'KR', 'name' => 'South Korea'],
            ['id' => 'CN', 'name' => 'China'],
            ['id' => 'IN', 'name' => 'India'],
            ['id' => 'SA', 'name' => 'Saudi Arabia'],
            ['id' => 'AE', 'name' => 'United Arab Emirates'],
        ];

        // Try to fetch from external API with timeout
        try {
            $response = Http::timeout(3)->get('https://restcountries.com/v3.1/all?fields=name,cca2');
            
            if ($response->successful()) {
                $countries = collect($response->json())->map(function ($country) {
                    return [
                        'id' => $country['cca2'] ?? $country['name']['common'],
                        'name' => $country['name']['common'],
                    ];
                })->sortBy('name')->values();

                return response()->json($countries);
            }
        } catch (\Exception $e) {
            // Fallback to local data if API fails
        }

        // Return local data if API fails or times out
        return response()->json(collect($localCountries)->sortBy('name')->values());
    }

    // Provinsi berdasarkan negara
    public function provinces($country)
    {
        if (strtolower($country) !== 'indonesia') {
            return response()->json([]);
        }

        // Cache for 24 hours
        $provinces = cache()->remember('provinces_indonesia', 86400, function () {
            $response = Http::timeout(5)->get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
            return $response->successful() ? $response->json() : [];
        });

        return response()->json($provinces);
    }

    // Kota berdasarkan provinsi
    public function cities($provinceId)
    {
        // Cache for 24 hours per province
        $cities = cache()->remember("cities_province_{$provinceId}", 86400, function () use ($provinceId) {
            $response = Http::timeout(5)->get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$provinceId}.json");
            return $response->successful() ? $response->json() : [];
        });

        return response()->json($cities);
    }

    // Kecamatan berdasarkan kota
    public function districts($cityId)
    {
        // Cache for 24 hours per city
        $districts = cache()->remember("districts_city_{$cityId}", 86400, function () use ($cityId) {
            $response = Http::timeout(5)->get("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$cityId}.json");
            return $response->successful() ? $response->json() : [];
        });

        return response()->json($districts);
    }

    // Kelurahan berdasarkan kecamatan
    public function villages($districtId)
    {
        // Cache for 24 hours per district
        $villages = cache()->remember("villages_district_{$districtId}", 86400, function () use ($districtId) {
            $response = Http::timeout(5)->get("https://www.emsifa.com/api-wilayah-indonesia/api/villages/{$districtId}.json");
            return $response->successful() ? $response->json() : [];
        });

        return response()->json($villages);
    }

    // Validate postal code based on district and village
    public function validatePostalCode(Request $request)
    {
        $request->validate([
            'district' => 'required|string',
            'village' => 'nullable|string',
        ]);

        try {
            // Use the kodepos API to validate postal code
            $response = Http::get("https://kodepos.vercel.app/search?q=" . urlencode($request->district));

            if ($response->successful()) {
                $data = $response->json();

                if (!empty($data) && isset($data['data']) && is_array($data['data'])) {
                    // If village is specified, filter by village
                    if ($request->village) {
                        $filteredData = array_filter($data['data'], function ($item) use ($request) {
                            return isset($item['village']) &&
                                strtolower($item['village']) === strtolower($request->village);
                        });

                        if (!empty($filteredData)) {
                            $firstMatch = reset($filteredData);
                            return response()->json([
                                'success' => true,
                                'postal_code' => $firstMatch['code'],
                                'village' => $firstMatch['village'],
                                'district' => $firstMatch['district'],
                                'message' => 'Kode pos valid untuk kelurahan ' . $firstMatch['village']
                            ]);
                        }
                    }

                    // Get unique postal codes for the district
                    $postalCodes = collect($data['data'])->pluck('code')->unique()->values();
                    return response()->json([
                        'success' => true,
                        'postal_codes' => $postalCodes,
                        'suggestion' => $postalCodes->first() ?? null,
                        'message' => 'Berikut kode pos yang tersedia untuk kecamatan ' . $request->district
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Kode pos tidak ditemukan untuk kecamatan ini'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memvalidasi kode pos: ' . $e->getMessage()
            ], 500);
        }
    }

    // Get postal code for a specific village
    public function getPostalCodeByVillage(Request $request)
    {
        $request->validate([
            'village' => 'required|string',
        ]);

        try {
            // Use the kodepos API to get postal code by village
            $response = Http::get("https://kodepos.vercel.app/search?q=" . urlencode($request->village));

            if ($response->successful()) {
                $data = $response->json();

                if (!empty($data) && is_array($data)) {
                    // Return the first match
                    return response()->json([
                        'success' => true,
                        'postal_code' => $data[0]['code'] ?? null,
                        'data' => $data[0]
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Kode pos tidak ditemukan untuk kelurahan ini'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil kode pos: ' . $e->getMessage()
            ], 500);
        }
    }
}
