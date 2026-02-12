<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    // Veritabanına toplu veri girişine izin verdiğimiz alanlar
    protected $fillable = [
        'job_no', 'title', 'description', 'priority', 'quantity', 'image_path', 'pdf_path',
        'current_stage',
        'assign_cam', 'assign_lazer', 'assign_cmm', 'assign_tesviye', 'assign_torna',
        'cam_minutes',
        'lazer_minutes',
        'cmm_minutes',
        'tesviye_minutes',
        'torna_minutes',
        'planning_date',
        'packaging_type',
        'delivery_note_path',
        'invoice_path', 'total_amount', 'paid_amount', 'payment_status',
        'is_completed'
    ];

    protected $casts = [
        'assign_cam' => 'boolean',
        'assign_lazer' => 'boolean',
        'assign_cmm' => 'boolean',
        'assign_tesviye' => 'boolean',
        'assign_torna' => 'boolean',
    ];

    /**
     * Atanmış üretim istasyonlarının hepsi süre girmiş mi? (Planning aşaması için)
     * Sütun yoksa (eski veritabanı) varsayılan true kabul edilir.
     */
    public function hasAllAssignedProductionStationsFilled(): bool
    {
        if (($this->getAttribute('assign_cam') ?? true) && $this->cam_minutes === null) return false;
        if (($this->getAttribute('assign_lazer') ?? true) && $this->lazer_minutes === null) return false;
        if (($this->getAttribute('assign_cmm') ?? true) && $this->cmm_minutes === null) return false;
        if (($this->getAttribute('assign_tesviye') ?? true) && $this->tesviye_minutes === null) return false;
        if (($this->getAttribute('assign_torna') ?? true) && $this->torna_minutes === null) return false;
        return true;
    }

    /**
     * İşin toplam üretim süresini hesapla (dakika)
     */
    public function getTotalMinutesAttribute()
    {
        return ($this->cam_minutes ?? 0) + ($this->lazer_minutes ?? 0) + ($this->cmm_minutes ?? 0) + ($this->tesviye_minutes ?? 0) + ($this->torna_minutes ?? 0);
    }

    /**
     * CAM istasyonu maliyeti
     */
    public function getCamCostAttribute()
    {
        $cost = \App\Models\StationCost::where('station_code', 'cam')->first();
        if (!$cost || !$this->cam_minutes) return 0;
        return $this->cam_minutes * $cost->cost_per_minute;
    }

    /**
     * Lazer istasyonu maliyeti
     */
    public function getLazerCostAttribute()
    {
        $cost = \App\Models\StationCost::where('station_code', 'lazer')->first();
        if (!$cost || !$this->lazer_minutes) return 0;
        return $this->lazer_minutes * $cost->cost_per_minute;
    }

    /**
     * CMM istasyonu maliyeti
     */
    public function getCmmCostAttribute()
    {
        $cost = \App\Models\StationCost::where('station_code', 'cmm')->first();
        if (!$cost || !$this->cmm_minutes) return 0;
        return $this->cmm_minutes * $cost->cost_per_minute;
    }

    /**
     * Tesviye istasyonu maliyeti
     */
    public function getTesviyeCostAttribute()
    {
        $cost = \App\Models\StationCost::where('station_code', 'tesviye')->first();
        if (!$cost || !$this->tesviye_minutes) return 0;
        return $this->tesviye_minutes * $cost->cost_per_minute;
    }

    /**
     * Planlama maliyeti (sabit ücret - dakika yok)
     */
    public function getPlanningCostAttribute()
    {
        $cost = \App\Models\StationCost::where('station_code', 'planning')->first();
        if (!$cost || !$this->planning_date) return 0;
        // Planlama için sabit ücret varsa
        return $cost->cost_per_minute ?? 0; // cost_per_minute burada sabit ücret olarak kullanılabilir
    }

    /**
     * Paketleme maliyeti (paket tipine göre)
     */
    public function getPackagingCostAttribute()
    {
        if (!$this->packaging_type) return 0;
        
        $packaging = \App\Models\PackagingCost::where('package_type', $this->packaging_type)->first();
        return $packaging ? $packaging->price : 0;
    }

    /**
     * Lojistik maliyeti (sabit ücret)
     */
    public function getLogisticsCostAttribute()
    {
        $cost = \App\Models\StationCost::where('station_code', 'logistics')->first();
        if (!$cost || !$this->delivery_note_path) return 0;
        return $cost->cost_per_minute ?? 0; // Sabit ücret
    }

    /**
     * Toplam Üretim Maliyeti
     */
    public function getTotalProductionCostAttribute()
    {
        return $this->cam_cost 
             + $this->lazer_cost 
             + $this->cmm_cost 
             + $this->tesviye_cost 
             + $this->planning_cost 
             + $this->packaging_cost 
             + $this->logistics_cost;
    }

    /** Öncelik değerleri → Türkçe etiket */
    public const PRIORITY_LABELS = [
        'dusuk' => 'Düşük',
        'orta' => 'Orta',
        'yuksek' => 'Yüksek',
        'acil' => 'Acil',
        'cok_acil' => 'Çok Acil',
    ];

    /** Öncelik → Tailwind renk sınıfları (İş No hücresi için) */
    public function getPriorityColorClassAttribute(): string
    {
        return match ($this->priority) {
            'dusuk' => 'text-gray-600',
            'orta' => 'text-indigo-600',
            'yuksek' => 'text-amber-600 font-semibold',
            'acil' => 'text-red-600 font-bold',
            'cok_acil' => 'text-red-800 font-black',
            default => 'text-indigo-600',
        };
    }

    /** Öncelik badge (liste hücresi: arka plan + metin + acil/cok_acil için yanıp sönme) */
    public function getPriorityBadgeClassAttribute(): string
    {
        $base = 'inline-block px-2 py-1 rounded text-xs font-bold ';
        return $base . match ($this->priority) {
            'dusuk' => 'bg-gray-100 text-gray-700',
            'orta' => 'bg-indigo-100 text-indigo-800',
            'yuksek' => 'bg-amber-100 text-amber-800',
            'acil' => 'bg-red-100 text-red-800',
            'cok_acil' => 'bg-red-200 text-red-900 font-black',
            default => 'bg-gray-100 text-gray-500',
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return self::PRIORITY_LABELS[$this->priority] ?? '—';
    }

    /**
     * İş emrine bağlı dosyalar (çoklu)
     */
    public function jobFiles()
    {
        return $this->hasMany(JobFile::class);
    }

    /**
     * İstasyon bazlı parça detayları (Parça No, En, Boy, Yükseklik, Adet, Cinsi)
     */
    public function jobStationDetails()
    {
        return $this->hasMany(JobStationDetail::class);
    }

    /**
     * Kar/Zarar Hesaplama (Muhasebe total_amount - Toplam üretim maliyeti = adet × 1 adet maliyeti)
     */
    public function getProfitLossAttribute()
    {
        $totalProductionCost = $this->total_quantity_cost;
        $totalAmount = $this->total_amount ?? 0;
        return $totalAmount - $totalProductionCost;
    }

    /**
     * Kar/Zarar Yüzdesi
     */
    public function getProfitLossPercentageAttribute()
    {
        $totalProductionCost = $this->total_quantity_cost;
        if ($totalProductionCost == 0) return 0;
        
        $profitLoss = $this->profit_loss;
        return ($profitLoss / $totalProductionCost) * 100;
    }

    /**
     * 1 adet üretim maliyeti (tüm istasyonların toplamı = 1 adet için maliyet)
     */
    public function getUnitProductionCostAttribute()
    {
        return $this->total_production_cost;
    }

    /**
     * Toplam üretim maliyeti = Adet × 1 adet maliyeti
     */
    public function getTotalQuantityCostAttribute()
    {
        $qty = (int) ($this->quantity ?? 1);
        if ($qty <= 0) return 0;
        return $qty * $this->unit_production_cost;
    }
}