<?php

namespace App\Http\Controllers;

use App\Models\ZakatType;
use Illuminate\Http\Request;

class ZakatCalculatorController extends Controller
{
    /**
     * Show zakat calculator page
     */
    public function index()
    {
        $zakatTypes = cache()->remember('active_zakat_types', 300, function() { return ZakatType::active()->get(); });
        return view('calculator.index', compact('zakatTypes'));
    }

    /**
     * Calculate zakat amount
     */
    public function calculate(Request $request)
    {
        $request->validate([
            'zakat_type_id' => 'required|exists:zakat_types,id',
            'wealth_amount' => 'required|numeric|min:0',
        ]);

        $zakatType = ZakatType::findOrFail($request->zakat_type_id);
        $wealthAmount = $request->wealth_amount;

        // Calculate zakat based on type
        $calculation = $this->performZakatCalculation($zakatType, $wealthAmount, $request->all());

        return response()->json($calculation);
    }

    /**
     * Perform zakat calculation based on zakat type
     */
    private function performZakatCalculation(ZakatType $zakatType, $wealthAmount, $additionalData = [])
    {
        $calculation = [
            'zakat_type' => $zakatType->name,
            'wealth_amount' => $wealthAmount,
            'nisab_amount' => $zakatType->nisab_amount,
            'nisab_unit' => $zakatType->nisab_unit,
            'rate' => $zakatType->rate,
            'is_eligible' => false,
            'zakat_amount' => 0,
            'excess_amount' => 0,
            'notes' => [],
        ];

        // Special handling for different zakat types
        switch ($zakatType->slug) {
            case 'zakat-fitrah':
                $calculation = $this->calculateZakatFitrah($zakatType, $additionalData, $calculation);
                break;

            case 'zakat-pertanian':
                $calculation = $this->calculateZakatPertanian($zakatType, $wealthAmount, $additionalData, $calculation);
                break;

            case 'zakat-profesi':
                $calculation = $this->calculateZakatProfesi($zakatType, $wealthAmount, $additionalData, $calculation);
                break;

            default:
                $calculation = $this->calculateZakatMal($zakatType, $wealthAmount, $calculation);
                break;
        }

        return $calculation;
    }

    /**
     * Calculate Zakat Mal (General wealth zakat)
     */
    private function calculateZakatMal(ZakatType $zakatType, $wealthAmount, $calculation)
    {
        if ($wealthAmount >= $zakatType->nisab_amount) {
            $calculation['is_eligible'] = true;
            $calculation['zakat_amount'] = $wealthAmount * $zakatType->rate;
            $calculation['excess_amount'] = $wealthAmount - $zakatType->nisab_amount;
            $calculation['notes'][] = 'Harta Anda telah mencapai nisab dan wajib zakat.';
        } else {
            $shortfall = $zakatType->nisab_amount - $wealthAmount;
            $calculation['notes'][] = "Harta Anda belum mencapai nisab. Kekurangan: Rp " . number_format($shortfall, 0, ',', '.');
        }

        return $calculation;
    }

    /**
     * Calculate Zakat Fitrah
     */
    private function calculateZakatFitrah(ZakatType $zakatType, $additionalData, $calculation)
    {
        $numberOfPeople = $additionalData['number_of_people'] ?? 1;

        $calculation['is_eligible'] = true;
        $calculation['zakat_amount'] = $zakatType->nisab_amount * $numberOfPeople;
        $calculation['wealth_amount'] = $numberOfPeople;
        $calculation['notes'][] = "Zakat fitrah untuk {$numberOfPeople} orang.";
        $calculation['notes'][] = 'Zakat fitrah wajib dibayar sebelum shalat Ied.';

        return $calculation;
    }

    /**
     * Calculate Zakat Pertanian (Agriculture)
     */
    private function calculateZakatPertanian(ZakatType $zakatType, $wealthAmount, $additionalData, $calculation)
    {
        $irrigationType = $additionalData['irrigation_type'] ?? 'rain'; // rain or irrigation

        if ($wealthAmount >= $zakatType->nisab_amount) {
            $calculation['is_eligible'] = true;

            // 10% for rain-fed, 5% for irrigated
            $rate = $irrigationType === 'rain' ? 0.1 : 0.05;
            $calculation['rate'] = $rate;
            $calculation['zakat_amount'] = $wealthAmount * $rate;
            $calculation['excess_amount'] = $wealthAmount - $zakatType->nisab_amount;

            $irrigationText = $irrigationType === 'rain' ? 'tadah hujan (10%)' : 'irigasi (5%)';
            $calculation['notes'][] = "Hasil pertanian dengan sistem {$irrigationText}.";
            $calculation['notes'][] = 'Zakat pertanian dibayar saat panen.';
        } else {
            $shortfall = $zakatType->nisab_amount - $wealthAmount;
            $calculation['notes'][] = "Hasil panen belum mencapai nisab. Kekurangan: {$shortfall} kg.";
        }

        return $calculation;
    }

    /**
     * Calculate Zakat Profesi (Professional income)
     */
    private function calculateZakatProfesi(ZakatType $zakatType, $wealthAmount, $additionalData, $calculation)
    {
        $period = $additionalData['period'] ?? 'monthly'; // monthly or yearly

        // Convert to yearly if monthly
        $yearlyIncome = $period === 'monthly' ? $wealthAmount * 12 : $wealthAmount;

        // Nisab for zakat profesi is usually equivalent to 85 grams of gold per year
        $yearlyNisab = $zakatType->nisab_amount * 12;

        if ($yearlyIncome >= $yearlyNisab) {
            $calculation['is_eligible'] = true;
            $calculation['zakat_amount'] = $yearlyIncome * $zakatType->rate;
            $calculation['wealth_amount'] = $yearlyIncome;
            $calculation['nisab_amount'] = $yearlyNisab;
            $calculation['excess_amount'] = $yearlyIncome - $yearlyNisab;

            $periodText = $period === 'monthly' ? 'bulanan' : 'tahunan';
            $calculation['notes'][] = "Perhitungan berdasarkan penghasilan {$periodText}.";
            $calculation['notes'][] = 'Zakat profesi dapat dibayar setiap bulan atau setiap tahun.';
        } else {
            $shortfall = $yearlyNisab - $yearlyIncome;
            $calculation['notes'][] = "Penghasilan tahunan belum mencapai nisab. Kekurangan: Rp " . number_format($shortfall, 0, ',', '.');
        }

        return $calculation;
    }

    /**
     * Get current gold price (mock - in real implementation, this would fetch from API)
     */
    public function getGoldPrice()
    {
        // Mock gold price - in real implementation, fetch from reliable source
        $goldPricePerGram = 1000000; // Rp 1,000,000 per gram

        return response()->json([
            'price_per_gram' => $goldPricePerGram,
            'nisab_85_grams' => $goldPricePerGram * 85,
            'currency' => 'IDR',
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Get zakat calculation guide
     */
    public function guide()
    {
        $guides = [
            'zakat-mal-emas-perak' => [
                'title' => 'Zakat Mal - Emas & Perak',
                'description' => 'Zakat yang dikeluarkan dari kepemilikan emas dan perak',
                'nisab' => '85 gram emas atau 595 gram perak',
                'rate' => '2.5%',
                'conditions' => [
                    'Kepemilikan telah mencapai nisab',
                    'Telah dimiliki selama 1 tahun (haul)',
                    'Merupakan harta yang berkembang atau untuk investasi'
                ]
            ],
            'zakat-mal-uang-tabungan' => [
                'title' => 'Zakat Mal - Uang & Tabungan',
                'description' => 'Zakat dari uang tunai, tabungan, deposito',
                'nisab' => 'Setara dengan 85 gram emas',
                'rate' => '2.5%',
                'conditions' => [
                    'Kepemilikan telah mencapai nisab',
                    'Telah dimiliki selama 1 tahun (haul)',
                    'Merupakan harta yang tidak untuk kebutuhan pokok'
                ]
            ],
            // Add more guides...
        ];

        return view('calculator.guide', compact('guides'));
    }
}
