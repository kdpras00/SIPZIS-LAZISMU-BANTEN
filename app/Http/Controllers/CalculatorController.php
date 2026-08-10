<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    
    public function index()
    {
        return view('calculator.index');
    }

    
    public function getGoldPrice()
    {
        
        $goldPricePerGram = 1000000; 

        return response()->json([
            'price_per_gram' => $goldPricePerGram,
            'nisab_85_grams' => $goldPricePerGram * 85,
            'currency' => 'IDR',
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);
    }

    
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
            
        ];

        return view('calculator.guide', compact('guides'));
    }
}
