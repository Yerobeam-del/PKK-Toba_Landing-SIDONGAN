<?php

namespace App\Http\Controllers\Sidongan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Gunakan guard 'sidongan'
        if (Auth::guard('sidongan')->attempt($credentials, $request->filled('remember'))) {
            $user = Auth::guard('sidongan')->user();
            
            // Cek apakah user punya akses SIDONGAN
            if ($user->hasSidonganAccess()) {
                $request->session()->regenerate();
                
                // Redirect ke /sidongan (dashboard)
                return redirect()->intended(route('sidongan.dashboard'));
            }
            
            // User tidak punya akses
            Auth::guard('sidongan')->logout();
            
            return back()->withErrors([
                'email' => 'Akun Anda tidak memiliki akses ke SIDONGAN.',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('sidongan')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('sidongan.login');
    }
}