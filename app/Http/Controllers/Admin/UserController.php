<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /** İzin verilen roller (admin panelden atanabilir) */
    public const ROLES = [
        'admin' => 'Admin',
        'manager' => 'Manager',
        'cam' => 'CAM',
        'lazer' => 'Lazer',
        'cmm' => 'CMM',
        'tesviye' => 'Tesviye',
        'torna' => 'Torna',
        'planning' => 'Planlama',
        'packaging' => 'Paketleme',
        'logistics' => 'Lojistik',
        'accounting' => 'Muhasebe',
    ];

    /**
     * Kullanıcı listesi (sadece admin/manager)
     */
    public function index(Request $request)
    {
        $query = User::query()->orderBy('name');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qry) use ($q) {
                $qry->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $users = $query->with('department')->paginate(15)->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => self::ROLES,
        ]);
    }

    /**
     * Yeni kullanıcı formu
     */
    public function create()
    {
        $departments = Department::orderBy('name')->get();

        return view('admin.users.create', [
            'roles' => self::ROLES,
            'departments' => $departments,
        ]);
    }

    /**
     * Yeni kullanıcı kaydet
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'string', 'in:' . implode(',', array_keys(self::ROLES))],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
        ], [
            'role.in' => 'Geçerli bir rol seçin.',
        ]);

        unset($validated['password_confirmation']);
        $validated['department_id'] = $request->filled('department_id') ? (int) $request->department_id : null;
        $validated['usertype'] = $validated['role']; // canlıda usertype NULL kalmasın diye

        User::create($validated); // password model'de 'hashed' cast ile hash'lenir

        return redirect()->route('admin.users.index')->with('success', 'Kullanıcı başarıyla eklendi.');
    }

    /**
     * Kullanıcı düzenleme formu
     */
    public function edit(User $user)
    {
        $departments = Department::orderBy('name')->get();

        return view('admin.users.edit', [
            'user' => $user,
            'roles' => self::ROLES,
            'departments' => $departments,
        ]);
    }

    /**
     * Kullanıcı güncelle (ad, email, rol, opsiyonel şifre)
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'string', 'in:' . implode(',', array_keys(self::ROLES))],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        $validated = $request->validate($rules, [
            'role.in' => 'Geçerli bir rol seçin.',
        ]);

        if (!empty($validated['password'])) {
            $user->password = $validated['password'];
            unset($validated['password'], $validated['password_confirmation']);
        }

        $user->fill($validated);
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Kullanıcı güncellendi.');
    }

    /**
     * Admin: Seçilen kullanıcının şifresini değiştir (mevcut şifre gerekmez)
     */
    public function updatePassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->password = $validated['password'];
        $user->save();

        return back()->with('success', 'Şifre güncellendi.');
    }

    /**
     * Kullanıcı sil (kendini silemez)
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Kendi hesabınızı buradan silemezsiniz. Profil ayarlarından hesabınızı kapatabilirsiniz.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Kullanıcı silindi.');
    }
}
