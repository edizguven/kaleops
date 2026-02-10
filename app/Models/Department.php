<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    // Veritabanındaki 'departments' tablosuna yazılmasına izin verilen alanlar
    protected $fillable = [
        'name', 
        'hourly_rate',
        'currency'
    ];

    /**
     * Bu departmana bağlı kullanıcılar
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}