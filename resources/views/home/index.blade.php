@extends('layouts.app')

@section('title', 'TemuKost · Cari Kos Lebih Tenang, Dibantu Tim Nyata')

@section('content')
<style>
    .hero-grid {
        display: grid;
        grid-template-columns: 1.15fr 0.85fr;
        gap: 48px;
        align-items: center;
        padding: 40px 0 64px;
    }
    .hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 700;
        color: var(--green);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 16px;
    }
    .hero-title {
        font-size: 48px;
        font-weight: 800;
        line-height: 1.15;
        margin-bottom: 20px;
        letter-spacing: -0.04em;
    }
    .hero-title span {
        color: var(--green);
    }
    .hero-lead {
        font-size: 17px;
        line-height: 1.6;
        color: var(--muted);
        margin-bottom: 32px;
        max-width: 520px;
    }
    .hero-actions {
        display: flex;
        gap: 14px;
        align-items: center;
        flex-wrap: wrap;
    }
    .hero-card-preview {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 24px;
        box-shadow: var(--shadow-elevated);
        position: relative;
    }
    .preview-badge {
        display: inline-block;
        padding: 4px 10px;
        background: var(--mint);
        color: var(--green-dark);
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 12px;
    }
    .preview-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f1f3f0;
        font-size: 14px;
    }
    .preview-item:last-child {
        border-bottom: none;
    }
    .section-wrap {
        padding: 64px 0;
        border-top: 1px solid var(--border);
    }
    .section-header {
        max-width: 620px;
        margin-bottom: 40px;
    }
    .section-title {
        font-size: 32px;
        margin-bottom: 12px;
    }
    .steps-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }
    .step-box {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 24px;
    }
    .step-number {
        font-family: 'Manrope', sans-serif;
        font-size: 28px;
        font-weight: 800;
        color: var(--green);
        margin-bottom: 12px;
    }
    .step-heading {
        font-size: 18px;
        margin-bottom: 8px;
    }
    .benefit-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }
    .benefit-item {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 24px;
    }
    .benefit-item h3 {
        font-size: 18px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .benefit-icon {
        color: var(--green);
        display: inline-flex;
    }
    @media (max-width: 860px) {
        .hero-grid {
            grid-template-columns: 1fr;
            padding: 20px 0 40px;
        }
        .hero-title {
            font-size: 36px;
        }
        .steps-grid, .benefit-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<section class="hero-grid">
    <div>
        <div class="hero-eyebrow">
            <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:var(--green);"></span>
            Layanan Pencarian Kos Personal
        </div>
        <h1 class="hero-title">
            Fokus pada aktivitasmu. <span>Biar kami bantu carikan kos.</span>
        </h1>
        <p class="hero-lead">
            Kirimkan kriteria kos yang kamu butuhkan. Tim TemuKost memeriksa langsung ke lokasi, mengecek kondisi lewat video atau video call, serta membantu penjemputan saat kamu mulai pindah.
        </p>
        <div class="hero-actions">
            <a href="{{ route('order.create') }}" class="btn btn-primary" style="padding: 13px 24px; font-size: 15px;">
                Mulai isi kebutuhan kos
            </a>
            <a href="#cara-kerja" class="btn btn-outline" style="padding: 13px 20px; font-size: 15px;">
                Pelajari alur kerja
            </a>
        </div>
    </div>

    <div>
        <div class="hero-card-preview">
            <span class="preview-badge">Transparan & Terpantau</span>
            <h3 style="font-size: 19px; margin-bottom: 14px;">Simulasi Status Pencarian</h3>
            
            <div class="preview-item">
                <span style="color:var(--muted)">Area Prioritas</span>
                <strong>Margonda, Sekitar Kampus UI</strong>
            </div>
            <div class="preview-item">
                <span style="color:var(--muted)">Fasilitas Kunci</span>
                <strong>AC, KM Dalam, Wi-Fi 50Mbps</strong>
            </div>
            <div class="preview-item">
                <span style="color:var(--muted)">Pengecekan Tim</span>
                <strong style="color:var(--green)">Video Walkthrough 360°</strong>
            </div>
            <div class="preview-item">
                <span style="color:var(--muted)">Bantuan Pindahan</span>
                <strong>Dijemput di Stasiun Pocin</strong>
            </div>

            <div style="margin-top: 20px; padding: 14px; background: var(--cream); border-radius: var(--radius-sm); font-size: 13px; color: var(--muted);">
                Semua pembaruan dan obrolan dengan kurator kos berlangsung di satu ruang chat terintegrasi pada akunmu.
            </div>
        </div>
    </div>
</section>

<!-- Section Cara Kerja -->
<section class="section-wrap" id="cara-kerja">
    <div class="section-header">
        <div class="hero-eyebrow">Alur Layanan</div>
        <h2 class="section-title">Bagaimana TemuKost bekerja untukmu</h2>
        <p style="color:var(--muted);">Proses jelas dari pengisian kriteria hingga kunci kamar kos ada di tanganmu.</p>
    </div>

    <div class="steps-grid">
        <div class="step-box">
            <div class="step-number">01</div>
            <h3 class="step-heading">Isi spesifikasi yang diinginkan</h3>
            <p style="color:var(--muted); font-size: 14px;">
                Tentukan kota, rentang budget, jarak maksimal ke kampus atau kantor, preferensi kamar mandi, dan catatan khusus seperti jam malam.
            </p>
        </div>

        <div class="step-box">
            <div class="step-number">02</div>
            <h3 class="step-heading">Tim verifikasi dan survei langsung</h3>
            <p style="color:var(--muted); font-size: 14px;">
                Setelah pesanan dikonfirmasi, tim TemuKost menyaring listing, mengecek ke lokasi asli, dan mengirimkan bukti visual video aktual.
            </p>
        </div>

        <div class="step-box">
            <div class="step-number">03</div>
            <h3 class="step-heading">Pilih yang pas dan siap pindah</h3>
            <p style="color:var(--muted); font-size: 14px;">
                Kamu memutuskan kamar terbaik. Jika butuh bantuan penjemputan dari stasiun atau terminal, tim kami siap mendampingi.
            </p>
        </div>
    </div>
</section>

<!-- Section Benefit -->
<section class="section-wrap" id="alur-benefit">
    <div class="section-header">
        <div class="hero-eyebrow">Keuntungan Nyata</div>
        <h2 class="section-title">Lebih hemat waktu, tanpa drama foto palsu</h2>
        <p style="color:var(--muted);">Kami bukan sekadar direktori pasif, kami adalah asisten lapangan personalmu.</p>
    </div>

    <div class="benefit-grid">
        <div class="benefit-item">
            <h3>
                <span class="benefit-icon">✓</span>
                Kondisi Kamar Riil & Video Jujur
            </h3>
            <p style="color:var(--muted); font-size: 14px;">
                Menghindari jebakan foto listing lama yang sudah diedit. Kami periksa sirkulasi udara, kebersihan toilet, dan kecepatan internet saat survei.
            </p>
        </div>

        <div class="benefit-item">
            <h3>
                <span class="benefit-icon">✓</span>
                Satu Ruang Chat dengan Kurator
            </h3>
            <p style="color:var(--muted); font-size: 14px;">
                Tidak perlu berpindah-pindah aplikasi obrolan. Setiap rekomendasi kos, tautan lokasi, dan invoice tambahan tercatat di pesananmu.
            </p>
        </div>

        <div class="benefit-item">
            <h3>
                <span class="benefit-icon">✓</span>
                Dukungan Penjemputan Lokal
            </h3>
            <p style="color:var(--muted); font-size: 14px;">
                Khusus perantau baru, tim kami menyediakan opsi pendampingan dan penjemputan dari titik kedatangan menuju gerbang kos tujuan.
            </p>
        </div>

        <div class="benefit-item">
            <h3>
                <span class="benefit-icon">✓</span>
                Biaya Layanan Tetap & Terbuka
            </h3>
            <p style="color:var(--muted); font-size: 14px;">
                Satu tarif jasa pencarian tetap (flat fee) tanpa komisi terselubung pada biaya bulanan kosmu kepada pemilik kos.
            </p>
        </div>
    </div>

    <div style="margin-top: 48px; text-align: center; background: #ffffff; border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 36px 20px;">
        <h3 style="font-size: 22px; margin-bottom: 10px;">Siap menemukan tempat tinggal barumu?</h3>
        <p style="color:var(--muted); margin-bottom: 24px; font-size: 15px;">Mulai dengan mengisi beberapa preferensi sederhana dalam 2 menit.</p>
        <a href="{{ route('order.create') }}" class="btn btn-primary" style="padding: 13px 26px;">
            Mulai formulir pencarian kos
        </a>
    </div>
</section>
@endsection
