<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\User;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * Oturum listesi: kim, ne zaman son aktivite, IP, tarayıcı (sadece giriş yapmış kullanıcı oturumları)
     */
    public function index(Request $request)
    {
        $query = Session::with('user')
            ->whereNotNull('user_id')
            ->orderByDesc('last_activity');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $sessions = $query->paginate(20)->withQueryString();
        $users = User::orderBy('name')->get(['id', 'name', 'email', 'role']);

        return view('admin.sessions.index', compact('sessions', 'users'));
    }
}
