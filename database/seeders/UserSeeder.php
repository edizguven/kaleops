<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Düz metin şifre; User modelindeki 'hashed' cast otomatik hash'ler (çift hash olmaz)
        $password = 'password';

        // 0. ADMIN Kullanıcısı
        User::updateOrCreate(
            ['email' => 'admin@firma.com'],
            [
                'name' => 'Admin',
                'password' => $password,
                'role' => 'admin',
                'usertype' => 'admin',
            ]
        );

        // 1. CAM Personeli
        User::updateOrCreate(
            ['email' => 'cam@firma.com'],
            [
                'name' => 'Ahmet (CAM)',
                'password' => $password,
                'role' => 'cam',
            ]
        );

        // 2. Lazer Personeli
        User::updateOrCreate(
            ['email' => 'lazer@firma.com'],
            [
                'name' => 'Zeynep (Lazer)',
                'password' => $password,
                'role' => 'lazer',
            ]
        );

        // 3. CMM Personeli
        User::updateOrCreate(
            ['email' => 'cmm@firma.com'],
            [
                'name' => 'Mehmet (CMM)',
                'password' => $password,
                'role' => 'cmm',
            ]
        );

        // 4. Tesviye Personeli
        User::updateOrCreate(
            ['email' => 'tesviye@firma.com'],
            [
                'name' => 'Veli (Tesviye)',
                'password' => $password,
                'role' => 'tesviye',
            ]
        );

        // 5. Planlama Personeli
        User::updateOrCreate(
            ['email' => 'planlama@firma.com'],
            [
                'name' => 'Ayşe (Planlama)',
                'password' => $password,
                'role' => 'planning',
            ]
        );

        // 6. Paketleme Personeli
        User::updateOrCreate(
            ['email' => 'paketleme@firma.com'],
            [
                'name' => 'Fatma (Paketleme)',
                'password' => $password,
                'role' => 'packaging',
            ]
        );

        // 7. Lojistik/İrsaliye Personeli
        User::updateOrCreate(
            ['email' => 'lojistik@firma.com'],
            [
                'name' => 'Ali (Lojistik)',
                'password' => $password,
                'role' => 'logistics',
            ]
        );

        // 8. Muhasebe Personeli
        User::updateOrCreate(
            ['email' => 'muhasebe@firma.com'],
            [
                'name' => 'Hüseyin (Muhasebe)',
                'password' => $password,
                'role' => 'accounting',
            ]
        );
    }
}