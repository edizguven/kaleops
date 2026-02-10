<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StationCost;
use App\Models\PackagingCost;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Settings sayfasını göster
     */
    public function index()
    {
        $stations = StationCost::orderBy('station_code')->get();
        $packages = PackagingCost::orderBy('package_type')->get();
        
        return view('admin.settings.index', compact('stations', 'packages'));
    }

    /**
     * Station maliyetlerini güncelle
     */
    public function updateStations(Request $request)
    {
        $request->validate([
            'stations' => 'required|array',
            'stations.*.id' => 'required|exists:station_costs,id',
            'stations.*.cost_per_minute' => 'required|numeric|min:0',
        ]);

        foreach ($request->stations as $stationData) {
            StationCost::where('id', $stationData['id'])
                ->update(['cost_per_minute' => $stationData['cost_per_minute']]);
        }

        return back()->with('success', 'İstasyon maliyetleri başarıyla güncellendi.');
    }

    /**
     * Paketleme fiyatlarını güncelle
     */
    public function updatePackages(Request $request)
    {
        $request->validate([
            'packages' => 'required|array',
            'packages.*.id' => 'required|exists:packaging_costs,id',
            'packages.*.price' => 'required|numeric|min:0',
        ]);

        foreach ($request->packages as $packageData) {
            PackagingCost::where('id', $packageData['id'])
                ->update(['price' => $packageData['price']]);
        }

        return back()->with('success', 'Paketleme fiyatları başarıyla güncellendi.');
    }
}
