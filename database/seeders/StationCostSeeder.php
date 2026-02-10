<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StationCost;
use App\Models\PackagingCost;

class StationCostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // İstasyon maliyetleri
        $stations = [
            ['station_code' => 'cam', 'station_name' => 'CAM İstasyonu', 'cost_per_minute' => 5.00],
            ['station_code' => 'lazer', 'station_name' => 'Lazer İstasyonu', 'cost_per_minute' => 6.00],
            ['station_code' => 'cmm', 'station_name' => 'CMM Ölçüm', 'cost_per_minute' => 7.50],
            ['station_code' => 'tesviye', 'station_name' => 'Tesviye İstasyonu', 'cost_per_minute' => 4.00],
            ['station_code' => 'planning', 'station_name' => 'Planlama', 'cost_per_minute' => 50.00], // Sabit ücret
            ['station_code' => 'logistics', 'station_name' => 'Lojistik/İrsaliye', 'cost_per_minute' => 25.00], // Sabit ücret
        ];

        foreach ($stations as $station) {
            StationCost::updateOrCreate(
                ['station_code' => $station['station_code']],
                [
                    'station_name' => $station['station_name'],
                    'cost_per_minute' => $station['cost_per_minute'],
                    'currency' => 'USD'
                ]
            );
        }

        // Paketleme fiyatları
        $packages = [
            ['package_type' => 'Kucuk', 'package_name' => 'Küçük Paket', 'price' => 10.00],
            ['package_type' => 'Orta', 'package_name' => 'Orta Paket', 'price' => 25.00],
            ['package_type' => 'Buyuk', 'package_name' => 'Büyük Paket', 'price' => 50.00],
        ];

        foreach ($packages as $package) {
            PackagingCost::updateOrCreate(
                ['package_type' => $package['package_type']],
                [
                    'package_name' => $package['package_name'],
                    'price' => $package['price'],
                    'currency' => 'USD'
                ]
            );
        }
    }
}
