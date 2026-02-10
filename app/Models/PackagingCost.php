<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackagingCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_type',
        'package_name',
        'price',
        'currency'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];
}
