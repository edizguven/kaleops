<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobStationDetail extends Model
{
    protected $table = 'job_station_details';

    protected $fillable = [
        'job_id', 'station',
        'parca_no', 'en', 'boy', 'yukseklik', 'adet', 'cinsi',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public static function stationLabel(string $station): string
    {
        return match ($station) {
            'cam' => 'CAM',
            'lazer' => 'Lazer',
            'cmm' => 'CMM',
            'tesviye' => 'Tesviye',
            'torna' => 'Torna',
            default => $station,
        };
    }
}
