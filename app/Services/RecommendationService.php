<?php

namespace App\Services;

class RecommendationService
{
    /**
     * Menghasilkan daftar rekomendasi berdasarkan kondisi cuaca.
     */
    public function getRecommendations($temp, $rainChance, $uv, $condition)
    {
        $condition = strtolower($condition);

        $rules = [
            [
                'condition' => $rainChance >= 70,
                'type'      => 'danger',
                'icon'      => 'fa-umbrella',
                'title'     => 'Bawa Payung!',
                'desc'      => "Kemungkinan hujan {$rainChance}%. Siapkan payung atau jas hujan sebelum keluar.",
                'color'     => '#ff4d4d'
            ],
            [
                'condition' => $rainChance >= 40 && $rainChance < 70,
                'type'      => 'warning',
                'icon'      => 'fa-umbrella',
                'title'     => 'Siapkan Payung',
                'desc'      => "Ada {$rainChance}% kemungkinan hujan. Lebih baik bawa payung untuk berjaga.",
                'color'     => '#fbbf24'
            ],
            [
                'condition' => $rainChance < 40 && !str_contains($condition, 'rain'),
                'type'      => 'good',
                'icon'      => 'fa-sun',
                'title'     => 'Cuaca Baik',
                'desc'      => "Kemungkinan hujan hanya {$rainChance}%. Hari yang baik untuk aktivitas outdoor.",
                'color'     => '#10b981'
            ],
            [
                'condition' => $uv >= 8,
                'type'      => 'danger',
                'icon'      => 'fa-sun',
                'title'     => 'UV Sangat Tinggi!',
                'desc'      => "UV Index {$uv}. Gunakan sunscreen SPF 50+, kacamata, dan topi jika keluar.",
                'color'     => '#ff4d4d'
            ],
            [
                'condition' => $uv >= 6 && $uv < 8,
                'type'      => 'warning',
                'icon'      => 'fa-sun',
                'title'     => 'Pakai Sunscreen',
                'desc'      => "UV Index {$uv} cukup tinggi. Hindari paparan matahari langsung.",
                'color'     => '#fbbf24'
            ],
            [
                'condition' => $temp < 23,
                'type'      => 'warning',
                'icon'      => 'fa-tshirt',
                'title'     => 'Udara Dingin',
                'desc'      => "Suhu {$temp}°C cenderung rendah. Pastikan Anda mengenakan pakaian nyaman.",
                'color'     => '#3b82f6'
            ],
            [
                'condition' => str_contains($condition, 'mist') || str_contains($condition, 'fog'),
                'type'      => 'danger',
                'icon'      => 'fa-smog',
                'title'     => 'Jarak Pandang',
                'desc'      => 'Kondisi berkabut. Harap berhati-hati saat berkendara di jalan raya.',
                'color'     => '#94a3b8'
            ]
        ];

        // Filter hanya yang kondisinya terpenuhi (true)
        return collect($rules)->where('condition', true)->values();
    }
}
