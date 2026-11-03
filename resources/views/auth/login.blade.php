@extends('layouts.app')

@section('title', 'Masuk ke Akun · TemuKost')

@section('content')
<div style="max-width: 440px; margin: 40px auto;">
    <div class="card">
        <div style="text-align: center; margin-bottom: 24px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--mint); color: var(--green); display: grid; place-items: center; margin: 0 auto 16px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="width:24px; height:24px;">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <h1 style="font-size: 24px; margin-bottom: 6px;">Masuk ke TemuKost</h1>
            <p style="color:var(--muted); font-size: 14px;">Akses status pencarian, laporan survei, dan obrolan tim.</p>
        </div>

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name" class="form-label">Nama lengkap (opsional)</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Rian Anggara" value="{{ old('name') }}">
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Alamat email <span style="color:red;">*</span></label>
                <input type="email" name="email" id="email" class="form-control" required placeholder="nama@email.com" value="{{ old('email') }}">
            </div>

            <button type="submit" class="btn btn-primary btn-block" style="padding: 12px;">
                Lanjutkan ke Akun
            </button>
        </form>

        <div style="margin: 28px 0 20px; border-top: 1px solid var(--border); position: relative; text-align: center;">
            <span style="position: relative; top: -11px; background: #ffffff; padding: 0 12px; font-size: 12px; color: var(--muted); text-transform: uppercase;">
                Mode Demonstrasi Cepat
            </span>
        </div>

        <div style="display: flex; gap: 10px;">
            <form action="{{ route('quick-login') }}" method="POST" style="flex: 1;">
                @csrf
                <input type="hidden" name="role" value="customer">
                <button type="submit" class="btn btn-outline btn-block btn-sm" style="font-size: 12px;">
                    Demo: Andi (Customer)
                </button>
            </form>

            <form action="{{ route('quick-login') }}" method="POST" style="flex: 1;">
                @csrf
                <input type="hidden" name="role" value="staff">
                <button type="submit" class="btn btn-outline btn-block btn-sm" style="font-size: 12px; border-color: #cbd5cb; background: #f4f7f4;">
                    Demo: Tim Staf TemuKost
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
