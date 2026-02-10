<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Birimleri listeleme sayfası
     */
    public function index()
    {
        // Tüm birimleri veritabanından çekiyoruz
        $departments = Department::all();
        
        // admin/departments/index.blade.php dosyasına verileri gönderiyoruz
        return view('admin.departments.index', compact('departments'));
    }

    /**
     * Yeni birim kaydetme işlemi
     */
    public function store(Request $request)
    {
        // Basit bir doğrulama: isim şart, saatlik ücret sayı olmalı
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'hourly_rate' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3|in:USD,EUR,TRY',
        ], [
            'name.unique' => 'Bu birim adı zaten kullanılıyor.',
            'currency.in' => 'Geçerli bir para birimi seçin (USD, EUR, TRY).',
        ]);

        // Veritabanına kaydet
        Department::create($validated);

        // İşlem bitince sayfayı yenile ve başarı mesajı gönder
        return back()->with('success', 'Birim ve saatlik ücret başarıyla kaydedildi.');
    }
}