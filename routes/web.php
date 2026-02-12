<?php

use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\OperatorController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Ana Sayfa (/) - Giriş yapmışsa dashboard, değilse login
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->name('home');

/*
|--------------------------------------------------------------------------
| TEK SEFERLİK ADMIN OLUŞTURMA - SADECE LOCAL (GÜVENLİK)
|--------------------------------------------------------------------------
| Canlıda (production) bu URL çalışmaz. Sadece yerelde 1 kez çalıştırılabilir.
*/
Route::get('/__create_admin', function () {
    if (!app()->environment('local')) {
        abort(404);
    }
    // Şifreyi düz yazıyoruz; User modelindeki 'hashed' cast otomatik hash'leyecek (çift hash önlenir)
    User::updateOrCreate(
        ['email' => 'admin@firma.com'],
        [
            'name' => 'Admin',
            'password' => 'password',
            'role' => 'admin',
        ]
    );

    return 'Admin user created/updated. Login: admin@firma.com / password';
});

Route::get('/__create_torna', function () {
    if (!app()->environment('local')) {
        abort(404);
    }
    User::updateOrCreate(
        ['email' => 'torna@firma.com'],
        [
            'name' => 'Torna',
            'password' => 'password',
            'role' => 'torna',
        ]
    );
    return 'Torna user created/updated. Login: torna@firma.com / password';
});

/*
|--------------------------------------------------------------------------
| Standart Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = auth()->user();
    // Yönetici ise doğrudan admin panele, operatör ise operatör paneline yönlendir
    if (in_array($user->role ?? '', ['admin', 'manager'])) {
        return redirect()->route('admin.jobs.index');
    }
    if (in_array($user->role ?? '', ['cam', 'lazer', 'cmm', 'tesviye', 'torna', 'planning', 'packaging', 'logistics', 'accounting'])) {
        return redirect()->route('operator.index');
    }
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| ADMIN VE YETKİLİ ALANI
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // 1. Birim Yönetimi
        Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
        Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');

        // 2. İş Emri Yönetimi
        Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
        Route::post('/jobs', [JobController::class, 'store'])->name('jobs.store');
        Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');
        Route::put('/jobs/{job}', [JobController::class, 'update'])->name('jobs.update');
        Route::post('/jobs/{job}/files', [JobController::class, 'storeFiles'])->name('jobs.files.store');
        Route::delete('/jobs/{job}/files/{file}', [JobController::class, 'destroyFile'])->name('jobs.files.destroy');

        // 3. Ayarlar
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings/stations', [SettingsController::class, 'updateStations'])->name('settings.update.stations');
        Route::post('/settings/packages', [SettingsController::class, 'updatePackages'])->name('settings.update.packages');

        // 4. Kullanıcı Yönetimi (CRUD + şifre)
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::put('/users/{user}/password', [UserController::class, 'updatePassword'])->name('users.updatePassword');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // 5. Oturumlar / Son aktivite
        Route::get('/sessions', [SessionController::class, 'index'])->name('sessions.index');
    });

/*
|--------------------------------------------------------------------------
| PERSONEL / OPERATÖR ALANI
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/operator/panel', [OperatorController::class, 'index'])->name('operator.index');
    Route::post('/operator/job/{job}', [OperatorController::class, 'update'])->name('operator.update');
    // Dosya indirme (admin ve operatörler; tıklanınca PC/telefona iner)
    Route::get('/job-file/{file}/download', [\App\Http\Controllers\Admin\JobController::class, 'downloadFile'])->name('jobfile.download');
});

/*
|--------------------------------------------------------------------------
| Profil Ayarları
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
