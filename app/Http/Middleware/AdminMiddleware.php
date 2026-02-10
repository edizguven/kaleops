<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Kullanıcı giriş yapmamışsa login sayfasına yönlendir
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Yetkili roller listesi (İleride buraya yeni roller ekleyebiliriz)
        $authorizedRoles = ['admin', 'manager']; // Örnek: 'manager' rolünü ileride ekleyebilirsin

        $user = auth()->user();
        
        if ($user && in_array($user->role, $authorizedRoles)) {
            return $next($request);
        }

        return redirect('/dashboard')->with('error', 'Bu işlemi yapmaya yetkiniz bulunmamaktadır. Sadece yöneticiler bu sayfaya erişebilir.');
    }
}