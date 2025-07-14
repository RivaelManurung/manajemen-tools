<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        // 1. Path view diperbaiki sesuai struktur folder Anda
        return view('Admin.Auth.login'); 
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // 2. Logika Redirect Sesuai Peran
            $user = Auth::user();
            if ($user->peran === 'admin' || $user->peran === 'storeman') {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->peran === 'mekanik') {
                // Arahkan ke route baru untuk mekanik
                return redirect()->intended(route('user.borrow.index')); 
            }

            // Fallback jika ada peran lain
            return redirect('/');
        }

        return back()->withErrors([
            'username' => 'Username atau password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    // Fungsi register juga menggunakan redirect berbasis peran
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'peran' => 'required|in:mekanik,storeman,admin',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'peran' => $request->peran,
        ]);

        Auth::login($user);

        // Logika Redirect Sesuai Peran setelah registrasi
        if ($user->peran === 'admin' || $user->peran === 'storeman') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->peran === 'mekanik') {
            return redirect()->route('user.borrow.index');
        }

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}