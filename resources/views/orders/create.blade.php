@extends('layouts.app')

@section('title', 'Isi Kebutuhan Kos · TemuKost')

@section('content')
<style>
    .order-layout {
        display: grid;
        grid-template-columns: 1.4fr 0.9fr;
        gap: 36px;
        align-items: start;
    }
    .stepper {
        display: flex;
        gap: 8px;
        margin-bottom: 24px;
    }
    .step-indicator {
        flex: 1;
        height: 6px;
        background: var(--border);
        border-radius: 4px;
        transition: background 0.2s ease;
    }
    .step-indicator.active {
        background: var(--green);
    }
    .chips-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 10px;
        margin-top: 8px;
    }
    .chip-label {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 9px 12px;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        background: #ffffff;
        font-size: 13px;
        cursor: pointer;
        user-select: none;
        transition: all 0.15s ease;
    }
    .chip-label:hover {
        border-color: var(--green);
    }
    .chip-label:has(input:checked) {
        border-color: var(--green);
        background: var(--mint);
        color: var(--green-dark);
        font-weight: 600;
    }
    .step-panel {
        display: none;
    }
    .step-panel.current {
        display: block;
    }
    .two-cols {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    @media (max-width: 860px) {
        .order-layout {
            grid-template-columns: 1fr;
        }
        .two-cols {
            grid-template-columns: 1fr;
        }
    }
</style>

<div style="margin-bottom: 24px;">
    <a href="{{ route('home') }}" style="font-size: 13px; color: var(--muted);">&larr; Kembali ke beranda</a>
    <h1 style="font-size: 28px; margin-top: 8px;">Kriteria Pencarian Kos</h1>
    <p style="color:var(--muted); font-size: 14px;">Beri tahu kriteria yang kamu butuhkan agar tim kami dapat menyaring opsi terbaik.</p>
</div>

<div class="order-layout">
    <div class="card">
        <div class="stepper">
            <div class="step-indicator active" id="bar-1"></div>
            <div class="step-indicator" id="bar-2"></div>
            <div class="step-indicator" id="bar-3"></div>
        </div>

        <form action="{{ route('order.store') }}" method="POST" id="wizard-form">
            @csrf

            <!-- STEP 1: Lokasi & Budget -->
            <div class="step-panel current" id="step-1">
                <h2 style="font-size: 20px; margin-bottom: 16px;">Langkah 1: Lokasi & Anggaran</h2>
                
                <div class="form-group">
                    <label for="city" class="form-label">Kota atau area tujuan kos <span style="color:red;">*</span></label>
                    <input type="text" name="city" id="city" class="form-control" required placeholder="Contoh: Margonda Depok, Sekitar Kampus UI, atau Grogol Jakbar" value="{{ old('city') }}">
                    <div class="form-hint">Tuliskan nama area atau kampus tujuan yang menjadi patokan utamamu.</div>
                </div>

                <div class="two-cols">
                    <div class="form-group">
                        <label for="budget" class="form-label">Budget per bulan <span style="color:red;">*</span></label>
                        <select name="budget" id="budget" class="form-control" required>
                            <option value="">Pilih rentang biaya</option>
                            <option value="Di bawah Rp1 juta" {{ old('budget') == 'Di bawah Rp1 juta' ? 'selected' : '' }}>Di bawah Rp1 juta</option>
                            <option value="Rp1–1,5 juta" {{ old('budget') == 'Rp1–1,5 juta' ? 'selected' : '' }}>Rp1 - 1,5 juta</option>
                            <option value="Rp1,5–2 juta" {{ old('budget') == 'Rp1,5–2 juta' ? 'selected' : '' }}>Rp1,5 - 2 juta</option>
                            <option value="Rp2–3 juta" {{ old('budget') == 'Rp2–3 juta' ? 'selected' : '' }}>Rp2 - 3 juta</option>
                            <option value="Di atas Rp3 juta" {{ old('budget') == 'Di atas Rp3 juta' ? 'selected' : '' }}>Di atas Rp3 juta</option>
                            <option value="Fleksibel, diskusikan" {{ old('budget') == 'Fleksibel, diskusikan' ? 'selected' : '' }}>Fleksibel, diskusikan dengan tim</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="movein_date" class="form-label">Rencana mulai tinggal</label>
                        <input type="date" name="movein_date" id="movein_date" class="form-control" value="{{ old('movein_date') }}">
                    </div>
                </div>

                <div style="margin-top: 24px; display: flex; justify-content: flex-end;">
                    <button type="button" class="btn btn-primary" onclick="goToStep(2)">
                        Lanjut ke Spesifikasi &rarr;
                    </button>
                </div>
            </div>

            <!-- STEP 2: Spesifikasi & Fasilitas -->
            <div class="step-panel" id="step-2">
                <h2 style="font-size: 20px; margin-bottom: 16px;">Langkah 2: Spesifikasi & Fasilitas</h2>

                <div class="two-cols">
                    <div class="form-group">
                        <label for="near" class="form-label">Dekat dengan patokan apa?</label>
                        <input type="text" name="near" id="near" class="form-control" placeholder="Nama fakultas, stasiun KRL, halte busway" value="{{ old('near') }}">
                    </div>

                    <div class="form-group">
                        <label for="distance" class="form-label">Jarak maksimal yang diinginkan</label>
                        <select name="distance" id="distance" class="form-control">
                            <option value="">Belum ada patokan khusus</option>
                            <option value="Maksimal 1 km" {{ old('distance') == 'Maksimal 1 km' ? 'selected' : '' }}>Maksimal 1 km (bisa jalan kaki)</option>
                            <option value="Maksimal 3 km" {{ old('distance') == 'Maksimal 3 km' ? 'selected' : '' }}>Maksimal 3 km (sepeda motor dekat)</option>
                            <option value="Maksimal 5 km" {{ old('distance') == 'Maksimal 5 km' ? 'selected' : '' }}>Maksimal 5 km</option>
                            <option value="Fleksibel" {{ old('distance') == 'Fleksibel' ? 'selected' : '' }}>Bisa lebih jauh asal akses transportasi mudah</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Fasilitas yang diutamakan</label>
                    <div class="chips-grid">
                        @php
                            $availableFeatures = ['AC', 'Kamar mandi dalam', 'Wi-Fi', 'Kasur / furnished', 'Dapur bersama', 'Parkir motor', 'Parkir mobil', 'Water heater', 'Laundry'];
                        @endphp
                        @foreach($availableFeatures as $feat)
                            <label class="chip-label">
                                <input type="checkbox" name="features[]" value="{{ $feat }}">
                                <span>{{ $feat }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label for="gender" class="form-label">Preferensi lingkungan kos</label>
                    <select name="gender" id="gender" class="form-control">
                        <option value="">Tidak ada preferensi khusus</option>
                        <option value="Kos putra">Khusus Putra</option>
                        <option value="Kos putri">Khusus Putri</option>
                        <option value="Kos campur">Kos Campur</option>
                    </select>
                </div>

                <div style="margin-top: 24px; display: flex; justify-content: space-between;">
                    <button type="button" class="btn btn-outline" onclick="goToStep(1)">&larr; Kembali</button>
                    <button type="button" class="btn btn-primary" onclick="goToStep(3)">Lanjut ke Catatan &rarr;</button>
                </div>
            </div>

            <!-- STEP 3: Catatan & Layanan Pendukung -->
            <div class="step-panel" id="step-3">
                <h2 style="font-size: 20px; margin-bottom: 16px;">Langkah 3: Preferensi & Bantuan Khusus</h2>

                <div class="form-group">
                    <label for="priority" class="form-label">Prioritas utama</label>
                    <select name="priority" id="priority" class="form-control">
                        <option value="Harga paling sesuai budget">Harga paling hemat / sesuai budget</option>
                        <option value="Lokasi paling dekat tujuan">Lokasi sangat dekat dengan kampus/kantor</option>
                        <option value="Fasilitas kamar lengkap">Fasilitas kamar dan gedung terlengkap</option>
                        <option value="Lingkungan aman dan tenang">Ketenangan dan keamanan lingkungan</option>
                        <option value="Belum tahu, ingin konsultasi">Ingin konsultasi dengan tim</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="notes" class="form-label">Catatan tambahan</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Contoh: sirkulasi udara baik, jendela menghadap luar, bebas jam malam, dsb. Jangan cantumkan data rahasia/password.">{{ old('notes') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Bantuan survei yang diinginkan</label>
                    <div class="chips-grid">
                        <label class="chip-label">
                            <input type="checkbox" name="service_extras[]" value="Video kos" checked>
                            <span>Video Walkthrough</span>
                        </label>
                        <label class="chip-label">
                            <input type="checkbox" name="service_extras[]" value="Video call">
                            <span>Video Call Langsung</span>
                        </label>
                        <label class="chip-label">
                            <input type="checkbox" name="service_extras[]" value="Share loc" checked>
                            <span>Bagikan Titik Lokasi</span>
                        </label>
                    </div>
                </div>

                <div class="form-group" style="padding: 14px; background: var(--cream); border-radius: var(--radius-sm); border: 1px solid var(--border);">
                    <label style="display: flex; gap: 10px; align-items: flex-start; cursor: pointer;">
                        <input type="checkbox" name="pickup" value="1" id="pickup_toggle" onchange="togglePickupInput(this.checked)" style="margin-top: 4px;">
                        <div>
                            <strong>Saya ingin dibantu penjemputan saat mulai pindah</strong>
                            <p style="font-size: 13px; color: var(--muted); margin-top: 2px;">
                                Tim kami akan mengonfirmasi stasiun/terminal kedatangan, kesesuaian jadwal, dan kapasitas kendaraan.
                            </p>
                        </div>
                    </label>

                    <div id="pickup_input_wrapper" style="display: none; margin-top: 12px;">
                        <label for="pickup_point" class="form-label" style="font-size: 13px;">Perkiraan titik jemput</label>
                        <input type="text" name="pickup_point" id="pickup_point" class="form-control" placeholder="Contoh: Stasiun Gambir / Terminal Pulo Gebang">
                    </div>
                </div>

                <div style="margin-top: 24px; display: flex; justify-content: space-between;">
                    <button type="button" class="btn btn-outline" onclick="goToStep(2)">&larr; Kembali</button>
                    <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">
                        Simpan & Buat Pesanan
                    </button>
                </div>
            </div>

        </form>
    </div>

    <!-- Ringkasan Layanan -->
    <aside class="card">
        <span class="badge badge-success" style="margin-bottom: 12px;">Satu Jasa Layanan</span>
        <h3 style="font-size: 18px; margin-bottom: 8px;">Pencarian Kos Terkurasi</h3>
        <p style="color:var(--muted); font-size: 13px; margin-bottom: 18px;">
            Service fee pencarian dibayarkan satu kali untuk mendampingi pencarian hingga kamu menemukan kamar yang pas.
        </p>

        <ul style="list-style: none; font-size: 13px; line-height: 1.8; color: var(--ink); border-top: 1px solid var(--border); padding-top: 14px;">
            <li>✓ Riset & verifikasi listing langsung ke pemilik kos</li>
            <li>✓ Cek sirkulasi, kamar mandi, & koneksi Wi-Fi asli</li>
            <li>✓ Konsultasi tak terbatas di ruang chat pesanan</li>
            <li>✓ Opsi bantuan jemput barang saat mulai pindah</li>
        </ul>

        <div style="margin-top: 20px; padding: 14px; background: var(--mint); border-radius: var(--radius-sm); font-size: 13px; color: var(--green-dark);">
            <strong>Catatan:</strong> Service fee ini merupakan jasa pencarian & kurasi independen, terpisah dari biaya sewa bulanan kos kepada pemilik tempat.
        </div>
    </aside>
</div>

@section('scripts')
<script>
    let currentStep = 1;

    function goToStep(step) {
        if (step > currentStep) {
            // Simple validation step 1
            if (currentStep === 1) {
                const city = document.getElementById('city');
                const budget = document.getElementById('budget');
                if (!city.value.trim() || !budget.value) {
                    alert('Harap isi kota/area dan rentang budget terlebih dahulu.');
                    return;
                }
            }
        }

        document.querySelectorAll('.step-panel').forEach(p => p.classList.remove('current'));
        document.querySelectorAll('.step-indicator').forEach(b => b.classList.remove('active'));

        document.getElementById('step-' + step).classList.add('current');
        for (let i = 1; i <= step; i++) {
            document.getElementById('bar-' + i).classList.add('active');
        }

        currentStep = step;
        window.scrollTo({ top: 100, behavior: 'smooth' });
    }

    function togglePickupInput(checked) {
        document.getElementById('pickup_input_wrapper').style.display = checked ? 'block' : 'none';
    }
</script>
@endsection
@endsection
