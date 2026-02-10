<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StationCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'station_code',
        'station_name',
        'cost_per_minute',
        'currency'
    ];

    protected $casts = [
        'cost_per_minute' => 'decimal:2',
    ];
}
