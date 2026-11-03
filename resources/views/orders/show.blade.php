@extends('layouts.app')

@section('title', 'Pesanan ' . $order->order_code . ' · TemuKost')

@section('content')
<style>
    .order-detail-grid {
        display: grid;
        grid-template-columns: 1.45fr 0.95fr;
        gap: 32px;
        align-items: start;
    }
    .status-timeline {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 20px 0 28px;
        position: relative;
    }
    .status-timeline::before {
        content: '';
        position: absolute;
        top: 14px;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--border);
        z-index: 1;
    }
    .timeline-node {
        position: relative;
        z-index: 2;
        background: #ffffff;
        text-align: center;
        font-size: 12px;
        font-weight: 600;
        color: var(--muted);
    }
    .timeline-dot {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #ffffff;
        border: 2px solid var(--border);
        color: var(--muted);
        display: grid;
        place-items: center;
        margin: 0 auto 6px;
        font-size: 12px;
    }
    .timeline-node.done .timeline-dot {
        border-color: var(--green);
        background: var(--green);
        color: #ffffff;
    }
    .timeline-node.done {
        color: var(--green-dark);
    }
    .timeline-node.current .timeline-dot {
        border-color: var(--orange);
        color: var(--orange);
        box-shadow: 0 0 0 4px var(--orange-soft);
    }
    .specs-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-top: 16px;
    }
    .spec-item {
        background: var(--cream);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 12px 14px;
        font-size: 13px;
    }
    .spec-item small {
        display: block;
        color: var(--muted);
        font-size: 11px;
        text-transform: uppercase;
        margin-bottom: 2px;
    }
    /* Chat Box Styling */
    .chat-container {
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        background: #ffffff;
        display: flex;
        flex-direction: column;
        height: 480px;
        margin-top: 24px;
    }
    .chat-header {
        padding: 14px 18px;
        border-bottom: 1px solid var(--border);
        background: #fafbfa;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .chat-body {
        flex: 1;
        padding: 18px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .chat-bubble {
        max-width: 80%;
        padding: 12px 16px;
        border-radius: 14px;
        font-size: 14px;
        line-height: 1.5;
    }
    .chat-bubble.customer {
        align-self: flex-end;
        background: var(--green);
        color: #ffffff;
        border-bottom-right-radius: 2px;
    }
    .chat-bubble.staff {
        align-self: flex-start;
        background: #f0f4f1;
        color: var(--ink);
        border-bottom-left-radius: 2px;
        border: 1px solid #e2eae4;
    }
    .chat-meta {
        font-size: 11px;
        margin-top: 4px;
        opacity: 0.8;
    }
    .chat-footer {
        padding: 12px 16px;
        border-top: 1px solid var(--border);
        background: #ffffff;
    }
    @media (max-width: 860px) {
        .order-detail-grid {
            grid-template-columns: 1fr;
        }
        .specs-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 12px;">
    <div>
        <a href="{{ route('account.index') }}" style="font-size: 13px; color: var(--muted);">&larr; Kembali ke daftar pesanan</a>
        <h1 style="font-size: 26px; margin-top: 6px;">Pesanan {{ $order->order_code }}</h1>
        <p style="color:var(--muted); font-size: 13px;">
            Dibuat pada {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB · Pemesan: {{ $order->user->name }}
        </p>
    </div>

    <div>
        @if($order->payment_status === 'paid')
            <span class="badge badge-success">Layanan Aktif</span>
        @else
            <span class="badge badge-warning">Menunggu Konfirmasi Pembayaran</span>
        @endif
    </div>
</div>

<div class="order-detail-grid">
    <div>
        <!-- Card Status & Progress -->
        <div class="card" style="margin-bottom: 24px;">
            <h2 style="font-size: 18px; margin-bottom: 4px;">Tahapan Pencarian Kos</h2>
            <p style="color:var(--muted); font-size: 13px;">Proses dipantau secara transparan oleh tim dan kurator lapangan.</p>

            @php
                $stages = ['Kriteria ditinjau', 'Pencarian berjalan', 'Pilihan dibagikan', 'Selesai'];
                $currentIndex = array_search($order->status, $stages);
                if ($order->payment_status !== 'paid') {
                    $currentIndex = -1;
                }
            @endphp

            <div class="status-timeline">
                @foreach($stages as $idx => $stg)
                    @php
                        $isDone = $currentIndex !== false && $idx < $currentIndex;
                        $isCurrent = $currentIndex !== false && $idx === $currentIndex;
                    @endphp
                    <div class="timeline-node {{ $isDone ? 'done' : '' }} {{ $isCurrent ? 'current' : '' }}">
                        <div class="timeline-dot">
                            @if($isDone) ✓ @else {{ $idx + 1 }} @endif
                        </div>
                        <span>{{ $stg }}</span>
                    </div>
                @endforeach
            </div>

            @if($order->payment_status !== 'paid')
                <div style="background: var(--orange-soft); border: 1px solid #fed7aa; padding: 16px; border-radius: var(--radius-md); margin-top: 16px;">
                    <h3 style="font-size: 15px; color: #9a3412; margin-bottom: 4px;">Service Fee Belum Dikonfirmasi</h3>
                    <p style="font-size: 13px; color: #7c2d12; margin-bottom: 12px;">
                        Ruang konsultasi chat dan peninjauan lapangan akan aktif setelah konfirmasi pembayaran simulasi selesai.
                    </p>
                    <form action="{{ route('order.pay', $order->order_code) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm">
                            Simulasikan Pembayaran (Rp {{ number_format($order->price, 0, ',', '.') }})
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- Chat Konsultasi -->
        <div class="card">
            <h2 style="font-size: 18px; margin-bottom: 4px;">Konsultasi & Update Tim</h2>
            <p style="color:var(--muted); font-size: 13px;">Diskusikan opsi kos, tautan video, atau jadwal survei di sini.</p>

            <div class="chat-container">
                <div class="chat-header">
                    <div>
                        <strong>Ruang Diskusi Kurator</strong>
                        <div style="font-size: 12px; color: var(--muted);">Respon aktif jam kerja tim TemuKost</div>
                    </div>
                    <span class="badge badge-neutral">{{ $order->messages->count() }} Pesan</span>
                </div>

                <div class="chat-body" id="chat-stream">
                    @forelse($order->messages as $msg)
                        <div class="chat-bubble {{ $msg->sender_role }}">
                            <div>{{ $msg->message }}</div>
                            <div class="chat-meta">
                                {{ $msg->sender_name }} · {{ $msg->created_at->format('H:i, d M') }}
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; color: var(--muted); margin: auto; font-size: 13px;">
                            Belum ada pesan. Kirim pesan pertama untuk tim kurator.
                        </div>
                    @endforelse
                </div>

                <div class="chat-footer">
                    @if($order->payment_status === 'paid')
                        <form action="{{ route('order.chat', $order->order_code) }}" method="POST" style="display: flex; gap: 10px;">
                            @csrf
                            <input type="text" name="message" class="form-control" placeholder="Tulis pesan atau pertanyaan untuk tim..." required maxlength="1000">
                            <button type="submit" class="btn btn-primary" style="white-space: nowrap;">Kirim</button>
                        </form>
                    @else
                        <div style="font-size: 13px; color: var(--muted); text-align: center;">
                            Chat akan aktif setelah status pembayaran layanan selesai dikonfirmasi.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Sisi Kanan: Detail Kebutuhan & Invoices -->
    <div>
        <div class="card" style="margin-bottom: 24px;">
            <h2 style="font-size: 18px; margin-bottom: 12px;">Detail Kriteria Kos</h2>

            <div class="specs-grid">
                <div class="spec-item">
                    <small>Area / Kota</small>
                    <strong>{{ $order->city }}</strong>
                </div>
                <div class="spec-item">
                    <small>Budget Bulanan</small>
                    <strong>{{ $order->budget }}</strong>
                </div>
                <div class="spec-item">
                    <small>Patokan Terdekat</small>
                    <strong>{{ $order->near ?: 'Belum ditentukan' }}</strong>
                </div>
                <div class="spec-item">
                    <small>Batas Jarak</small>
                    <strong>{{ $order->distance ?: 'Fleksibel' }}</strong>
                </div>
                <div class="spec-item">
                    <small>Preferensi Lingkungan</small>
                    <strong>{{ $order->gender ?: 'Bebas / Campur' }}</strong>
                </div>
                <div class="spec-item">
                    <small>Rencana Pindah</small>
                    <strong>{{ $order->movein_date ? $order->movein_date->format('d/m/Y') : 'Fleksibel' }}</strong>
                </div>
            </div>

            <div style="margin-top: 14px; font-size: 13px;">
                <small style="color:var(--muted); text-transform: uppercase;">Fasilitas Wajib:</small>
                <div style="margin-top: 4px; display: flex; flex-wrap: wrap; gap: 6px;">
                    @if(!empty($order->features))
                        @foreach($order->features as $f)
                            <span style="background: #edf3ed; color: var(--green-dark); padding: 3px 8px; border-radius: 4px; font-size: 12px;">
                                {{ $f }}
                            </span>
                        @endforeach
                    @else
                        <span style="color:var(--muted);">Tidak ada fasilitas khusus yang dipilih</span>
                    @endif
                </div>
            </div>

            @if($order->notes)
                <div style="margin-top: 14px; font-size: 13px; background: var(--cream); padding: 12px; border-radius: var(--radius-sm);">
                    <small style="color:var(--muted); text-transform: uppercase; display: block; margin-bottom: 4px;">Catatan Khusus:</small>
                    {{ $order->notes }}
                </div>
            @endif

            <div style="margin-top: 14px; font-size: 13px;">
                <small style="color:var(--muted); text-transform: uppercase;">Layanan Tambahan:</small>
                <div style="margin-top: 4px;">
                    @if($order->pickup)
                        <div style="color: var(--green); font-weight: 600;">
                            ✓ Bantuan Penjemputan di: {{ $order->pickup_point ?: 'Titik kedatangan dikonfirmasi tim' }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Invoices Tambahan -->
        <div class="card">
            <h2 style="font-size: 18px; margin-bottom: 6px;">Invoice Penawaran Tambahan</h2>
            <p style="color:var(--muted); font-size: 13px; margin-bottom: 14px;">Penawaran opsional khusus yang diterbitkan oleh kurator.</p>

            @forelse($order->invoices as $inv)
                <div style="border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 14px; margin-bottom: 12px; background: #fafbfa;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 6px;">
                        <div>
                            <strong>{{ $inv->title }}</strong>
                            <div style="font-size: 12px; color: var(--muted);">Kode: {{ $inv->invoice_code }}</div>
                        </div>
                        <span style="font-weight: 700; color: var(--green-dark);">
                            Rp {{ number_format($inv->amount, 0, ',', '.') }}
                        </span>
                    </div>

                    @if($inv->note)
                        <p style="font-size: 12px; color: var(--muted); margin-bottom: 10px;">{{ $inv->note }}</p>
                    @endif

                    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #eaeaea; padding-top: 8px;">
                        <div>
                            @if($inv->paid)
                                <span class="badge badge-success">Lunas</span>
                            @elseif($inv->approved)
                                <span class="badge badge-warning">Disetujui · Siap Bayar</span>
                            @else
                                <span class="badge badge-neutral">Menunggu Persetujuan</span>
                            @endif
                        </div>

                        <div>
                            @if(!$inv->approved && !$inv->paid)
                                <form action="{{ route('order.invoice.approve', [$order->order_code, $inv->invoice_code]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline btn-sm">Setujui Penawaran</button>
                                </form>
                            @elseif($inv->approved && !$inv->paid)
                                <form action="{{ route('order.invoice.pay', [$order->order_code, $inv->invoice_code]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">Simulasikan Bayar</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div style="text-align: center; color: var(--muted); font-size: 13px; padding: 20px 0;">
                    Belum ada penawaran layanan ekstra yang diterbitkan.
                </div>
            @endforelse
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Auto scroll chat to bottom
    const chatStream = document.getElementById('chat-stream');
    if (chatStream) {
        chatStream.scrollTop = chatStream.scrollHeight;
    }
</script>
@endsection
@endsection
