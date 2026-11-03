@extends('layouts.app')

@section('title', 'Panel Tim Kurator · TemuKost')

@section('content')
<style>
    .staff-layout {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 28px;
        align-items: start;
    }
    .order-sidebar-list {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        overflow: hidden;
    }
    .order-sidebar-item {
        display: block;
        padding: 14px 18px;
        border-bottom: 1px solid var(--border);
        transition: background 0.15s ease;
    }
    .order-sidebar-item:hover {
        background: var(--cream);
    }
    .order-sidebar-item.active {
        background: var(--mint);
        border-left: 4px solid var(--green);
    }
    .staff-chat-box {
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        background: #ffffff;
        display: flex;
        flex-direction: column;
        height: 380px;
        margin-top: 16px;
    }
    .staff-chat-body {
        flex: 1;
        padding: 16px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .staff-bubble {
        max-width: 80%;
        padding: 10px 14px;
        border-radius: 12px;
        font-size: 13px;
        line-height: 1.5;
    }
    .staff-bubble.staff {
        align-self: flex-end;
        background: var(--green);
        color: #ffffff;
    }
    .staff-bubble.customer {
        align-self: flex-start;
        background: #f1f3f0;
        color: var(--ink);
    }
    @media (max-width: 860px) {
        .staff-layout {
            grid-template-columns: 1fr;
        }
    }
</style>

<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 12px;">
    <div>
        <span class="badge badge-neutral" style="margin-bottom: 6px;">Portal Internal Staf</span>
        <h1 style="font-size: 26px;">Panel Pengelola & Kurasi Lapangan</h1>
        <p style="color:var(--muted); font-size: 13px;">Kelola pesanan masuk, pantau status pencarian, dan diskusikan rekomendasi kamar.</p>
    </div>

    <div>
        <form action="{{ route('staff.index') }}" method="GET" style="display: flex; gap: 8px;">
            <select name="status" class="form-control" style="font-size: 13px; padding: 6px 12px;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="Menunggu pembayaran" {{ request('status') == 'Menunggu pembayaran' ? 'selected' : '' }}>Menunggu pembayaran</option>
                <option value="Kriteria ditinjau" {{ request('status') == 'Kriteria ditinjau' ? 'selected' : '' }}>Kriteria ditinjau</option>
                <option value="Pencarian berjalan" {{ request('status') == 'Pencarian berjalan' ? 'selected' : '' }}>Pencarian berjalan</option>
                <option value="Pilihan dibagikan" {{ request('status') == 'Pilihan dibagikan' ? 'selected' : '' }}>Pilihan dibagikan</option>
                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </form>
    </div>
</div>

<div class="staff-layout">
    <!-- Sidebar List Orders -->
    <aside>
        <div class="order-sidebar-list">
            <div style="padding: 12px 18px; background: #fafbfa; border-bottom: 1px solid var(--border); font-weight: 700; font-size: 13px;">
                Antrean Pesanan Masuk ({{ $orders->total() }})
            </div>

            @forelse($orders as $ord)
                <a href="{{ route('staff.index', ['select' => $ord->order_code, 'status' => request('status')]) }}" class="order-sidebar-item {{ ($selectedOrder && $selectedOrder->id === $ord->id) ? 'active' : '' }}">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <strong style="font-size: 14px;">{{ $ord->user->name }}</strong>
                        <span style="font-size: 11px; padding: 2px 6px; border-radius: 4px; background: {{ $ord->payment_status === 'paid' ? 'var(--mint)' : '#fef08a' }}; color: {{ $ord->payment_status === 'paid' ? 'var(--green-dark)' : '#854d0e' }};">
                            {{ $ord->payment_status === 'paid' ? 'Lunas' : 'Belum Bayar' }}
                        </span>
                    </div>
                    <div style="font-size: 12px; color: var(--muted); margin-bottom: 2px;">
                        {{ $ord->order_code }} · {{ $ord->city }}
                    </div>
                    <div style="font-size: 11px; color: var(--green); font-weight: 600;">
                        {{ $ord->status }}
                    </div>
                </a>
            @empty
                <div style="padding: 24px 18px; text-align: center; color: var(--muted); font-size: 13px;">
                    Tidak ada pesanan pada filter ini.
                </div>
            @endforelse
        </div>

        <div style="margin-top: 12px;">
            {{ $orders->withQueryString()->links() }}
        </div>
    </aside>

    <!-- Main Detail & Control Panel -->
    <section>
        @if($selectedOrder)
            <div class="card" style="margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px; margin-bottom: 16px;">
                    <div>
                        <h2 style="font-size: 20px;">{{ $selectedOrder->order_code }} · {{ $selectedOrder->user->name }}</h2>
                        <div style="font-size: 13px; color: var(--muted);">
                            Email: {{ $selectedOrder->user->email }} · Telp: {{ $selectedOrder->user->phone ?: '-' }}
                        </div>
                    </div>

                    <a href="{{ route('order.show', $selectedOrder->order_code) }}" target="_blank" class="btn btn-outline btn-sm">
                        Buka Tampilan Customer &rarr;
                    </a>
                </div>

                <!-- Update Status Form -->
                <div style="background: var(--cream); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 14px; margin-bottom: 18px;">
                    <form action="{{ route('staff.status', $selectedOrder->order_code) }}" method="POST" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                        @csrf
                        <label for="status_select" style="font-size: 13px; font-weight: 600;">Perbarui Status Pencarian:</label>
                        <select name="status" id="status_select" class="form-control" style="width: auto; padding: 6px 12px; font-size: 13px;">
                            @foreach(['Kriteria ditinjau', 'Pencarian berjalan', 'Pilihan dibagikan', 'Selesai', 'Dibatalkan'] as $st)
                                <option value="{{ $st }}" {{ $selectedOrder->status === $st ? 'selected' : '' }}>
                                    {{ $st }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">Simpan Status</button>
                    </form>
                </div>

                <!-- Ringkasan Kriteria -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 10px; font-size: 13px; margin-bottom: 18px;">
                    <div style="background:#fafbfa; padding:10px; border:1px solid var(--border); border-radius:6px;">
                        <div style="color:var(--muted); font-size:11px;">Area</div>
                        <strong>{{ $selectedOrder->city }}</strong>
                    </div>
                    <div style="background:#fafbfa; padding:10px; border:1px solid var(--border); border-radius:6px;">
                        <div style="color:var(--muted); font-size:11px;">Budget</div>
                        <strong>{{ $selectedOrder->budget }}</strong>
                    </div>
                    <div style="background:#fafbfa; padding:10px; border:1px solid var(--border); border-radius:6px;">
                        <div style="color:var(--muted); font-size:11px;">Patokan</div>
                        <strong>{{ $selectedOrder->near ?: '-' }} ({{ $selectedOrder->distance ?: 'Bebas' }})</strong>
                    </div>
                    <div style="background:#fafbfa; padding:10px; border:1px solid var(--border); border-radius:6px;">
                        <div style="color:var(--muted); font-size:11px;">Penjemputan</div>
                        <strong>{{ $selectedOrder->pickup ? ($selectedOrder->pickup_point ?: 'Ya') : 'Tidak' }}</strong>
                    </div>
                </div>

                <!-- Chat Antara Tim & Customer -->
                <h3 style="font-size: 16px; margin-bottom: 6px;">Obrolan Kurasi dengan {{ $selectedOrder->user->name }}</h3>
                <div class="staff-chat-box">
                    <div class="staff-chat-body" id="staff-chat-stream">
                        @forelse($selectedOrder->messages as $msg)
                            <div class="staff-bubble {{ $msg->sender_role }}">
                                <div>{{ $msg->message }}</div>
                                <div style="font-size: 10px; opacity: 0.8; margin-top: 3px;">
                                    {{ $msg->sender_name }} · {{ $msg->created_at->format('H:i') }}
                                </div>
                            </div>
                        @empty
                            <div style="margin: auto; color: var(--muted); font-size: 12px;">
                                Belum ada obrolan.
                            </div>
                        @endforelse
                    </div>

                    <div style="padding: 10px 14px; border-top: 1px solid var(--border);">
                        <form action="{{ route('order.chat', $selectedOrder->order_code) }}" method="POST" style="display: flex; gap: 8px;">
                            @csrf
                            <input type="text" name="message" class="form-control" placeholder="Kirim pesan sebagai Tim TemuKost..." required style="font-size: 13px;">
                            <button type="submit" class="btn btn-primary btn-sm" style="white-space: nowrap;">Balas</button>
                        </form>
                    </div>
                </div>

                <!-- Terbitkan Invoice Tambahan -->
                <div style="margin-top: 24px; border-top: 1px solid var(--border); padding-top: 18px;">
                    <h3 style="font-size: 16px; margin-bottom: 6px;">Terbitkan Penawaran / Invoice Layanan Khusus</h3>
                    <p style="font-size: 12px; color: var(--muted); margin-bottom: 12px;">
                        Gunakan untuk biaya survei tambahan jarak jauh, pendampingan khusus, atau sewa angkutan barang.
                    </p>

                    <form action="{{ route('staff.invoice', $selectedOrder->order_code) }}" method="POST" style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 10px; align-items: end;">
                        @csrf
                        <div>
                            <label class="form-label" style="font-size: 12px;">Judul Layanan</label>
                            <input type="text" name="title" class="form-control" placeholder="Contoh: Survei Ekstra Area Jauh" required style="font-size: 13px;">
                        </div>
                        <div>
                            <label class="form-label" style="font-size: 12px;">Nominal (Rp)</label>
                            <input type="number" name="amount" class="form-control" placeholder="Contoh: 75000" min="10000" step="5000" required style="font-size: 13px;">
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary btn-sm btn-block" style="padding: 11px;">
                                Terbitkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="card" style="text-align: center; padding: 40px;">
                <p style="color:var(--muted);">Pilih pesanan dari antrean di sebelah kiri untuk melihat detail.</p>
            </div>
        @endif
    </section>
</div>

@section('scripts')
<script>
    const chatBox = document.getElementById('staff-chat-stream');
    if (chatBox) {
        chatBox.scrollTop = chatBox.scrollHeight;
    }
</script>
@endsection
@endsection
