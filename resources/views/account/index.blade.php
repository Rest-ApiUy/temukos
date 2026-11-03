@extends('layouts.app')

@section('title', 'Pesanan Saya · TemuKost')

@section('content')
<style>
    .account-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 32px;
        align-items: start;
    }
    .profile-card {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 24px;
        box-shadow: var(--shadow-subtle);
    }
    .order-card {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: var(--shadow-subtle);
        transition: transform 0.15s ease, border-color 0.15s ease;
    }
    .order-card:hover {
        border-color: #cbd5cb;
    }
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
        flex-wrap: wrap;
        gap: 10px;
    }
    .order-meta {
        font-size: 13px;
        color: var(--muted);
    }
    .order-grid-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 12px;
        background: var(--cream);
        border-radius: var(--radius-sm);
        padding: 14px;
        margin-bottom: 16px;
        font-size: 13px;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
    }
    @media (max-width: 768px) {
        .account-layout {
            grid-template-columns: 1fr;
        }
    }
</style>

<div style="margin-bottom: 28px;">
    <h1 style="font-size: 28px;">Hai, {{ explode(' ', $user->name)[0] }}</h1>
    <p style="color:var(--muted); font-size: 14px;">Pantau perkembangan pencarian kos dan konsultasi dengan kurator di sini.</p>
</div>

<div class="account-layout">
    <aside>
        <div class="profile-card">
            <div style="width: 52px; height: 52px; border-radius: 50%; background: var(--mint); color: var(--green); display: grid; place-items: center; font-weight: 800; font-size: 20px; margin-bottom: 14px;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h2 style="font-size: 18px; margin-bottom: 2px;">{{ $user->name }}</h2>
            <div style="color: var(--muted); font-size: 13px; margin-bottom: 16px;">{{ $user->email }}</div>

            <div style="border-top: 1px solid var(--border); padding-top: 16px;">
                <a href="{{ route('order.create') }}" class="btn btn-primary btn-block btn-sm" style="margin-bottom: 8px;">
                    + Buat Pencarian Baru
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline btn-block btn-sm">Keluar Akun</button>
                </form>
            </div>
        </div>
    </aside>

    <section>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
            <h2 style="font-size: 20px;">Daftar Pesanan ({{ $orders->count() }})</h2>
        </div>

        @forelse($orders as $ord)
            <div class="order-card">
                <div class="order-header">
                    <div>
                        <a href="{{ route('order.show', $ord->order_code) }}" style="font-size: 18px; font-weight: 700; color: var(--green); text-decoration: underline;">
                            {{ $ord->order_code }}
                        </a>
                        <div class="order-meta">
                            Dibuat {{ $ord->created_at->translatedFormat('d M Y') }} · {{ $ord->package_name }}
                        </div>
                    </div>

                    <div>
                        @if($ord->payment_status === 'paid')
                            <span class="badge badge-success">{{ $ord->status }}</span>
                        @else
                            <span class="badge badge-warning">Menunggu Pembayaran</span>
                        @endif
                    </div>
                </div>

                <div class="order-grid-summary">
                    <div>
                        <div style="font-size: 11px; color: var(--muted); text-transform: uppercase;">Area Tujuan</div>
                        <strong>{{ $ord->city }}</strong>
                    </div>
                    <div>
                        <div style="font-size: 11px; color: var(--muted); text-transform: uppercase;">Budget Bulanan</div>
                        <strong>{{ $ord->budget }}</strong>
                    </div>
                    <div>
                        <div style="font-size: 11px; color: var(--muted); text-transform: uppercase;">Fasilitas Utama</div>
                        <strong>{{ !empty($ord->features) ? implode(', ', array_slice($ord->features, 0, 2)) . (count($ord->features) > 2 ? '...' : '') : 'Diskusikan' }}</strong>
                    </div>
                    <div>
                        <div style="font-size: 11px; color: var(--muted); text-transform: uppercase;">Penjemputan</div>
                        <strong>{{ $ord->pickup ? 'Diminta' : 'Tidak' }}</strong>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                    <div style="font-size: 13px; color: var(--muted);">
                        @if($ord->messages->count() > 0)
                            💬 {{ $ord->messages->count() }} pesan di ruang konsultasi
                        @else
                            Belum ada percakapan
                        @endif
                    </div>

                    <a href="{{ route('order.show', $ord->order_code) }}" class="btn btn-outline btn-sm">
                        Buka Ruang Pesanan &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div style="font-size: 36px; margin-bottom: 12px;">🏠</div>
                <h3 style="font-size: 18px; margin-bottom: 8px;">Belum Ada Pencarian Kos</h3>
                <p style="color:var(--muted); font-size: 14px; max-width: 400px; margin: 0 auto 20px;">
                    Mulai dengan mengisi kriteria kos impianmu. Tim kami siap melakukan survei lapangan.
                </p>
                <a href="{{ route('order.create') }}" class="btn btn-primary">
                    Mulai Pencarian Pertama
                </a>
            </div>
        @endforelse
    </section>
</div>
@endsection
