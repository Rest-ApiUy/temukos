<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'name' => ['nullable', 'string', 'max:120'],
        ]);

        $user = User::firstOrCreate(
            ['email' => strtolower($validated['email'])],
            [
                'name' => $validated['name'] ?: explode('@', $validated['email'])[0],
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );

        Auth::login($user, true);
        $request->session()->regenerate();

        $intended = session()->pull('url.intended', route('account.index'));
        return redirect()->to($intended)->with('success', 'Selamat datang kembali, ' . $user->name . '.');
    }

    public function quickLogin(Request $request): RedirectResponse
    {
        $role = $request->input('role', 'customer');
        $email = $role === 'staff' ? 'staff@temukost.com' : 'andi@email.com';

        $user = User::where('email', $email)->first();
        if ($user) {
            Auth::login($user, true);
            $request->session()->regenerate();

            if ($user->isStaff()) {
                return redirect()->route('staff.index')->with('success', 'Masuk sebagai Tim TemuKost.');
            }
            return redirect()->route('account.index')->with('success', 'Masuk sebagai ' . $user->name . '.');
        }

        return redirect()->route('login')->withErrors(['email' => 'Akun demo tidak ditemukan.']);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Berhasil keluar dari akun.');
    }
}
