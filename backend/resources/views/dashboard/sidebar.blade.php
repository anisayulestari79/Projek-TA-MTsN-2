@php
    // Mengecek apakah admin sedang berada di halaman Konsultasi BK
    $isKonsultasiPage = request()->routeIs('admin.konsultasi.*');
@endphp

<aside>
    <div class="brand">
        <img src="https://ppdb.mtsn2kotabjm.sch.id/img/logo.png" alt="Logo Sekolah" class="logo-main" />
        <div>
            <h1>Sistem Pelanggaran Poin Siswa</h1>
            <div class="subtitle">MTsN 2 Kota Banjarmasin</div>
        </div>
    </div>
    <nav id="menu">
        {{-- Jika berada di halaman konsultasi, tombol ini akan membawa kembali ke halaman utama dashboard --}}
        <button class="nav-btn {{ !$isKonsultasiPage ? 'active' : '' }}"
            @if ($isKonsultasiPage) onclick="window.location.href='{{ route('admin.dashboard') }}'" @else data-view="dashboard" @endif>
            <i class="fas fa-chart-line"></i> Dashboard
        </button>

        @if ((isset($role) && $role === 'admin') || (Auth::check() && Auth::user()->role === 'admin'))
            <button class="nav-btn"
                @if ($isKonsultasiPage) onclick="window.location.href='{{ route('admin.dashboard') }}?view=data-guru'" @else data-view="data-guru" @endif>
                <i class="fas fa-chalkboard-teacher"></i> Data Guru
            </button>
        @endif

        <button class="nav-btn"
            @if ($isKonsultasiPage) onclick="window.location.href='{{ route('admin.dashboard') }}?view=data-siswa'" @else data-view="data-siswa" @endif>
            <i class="fas fa-users"></i> Data Siswa
        </button>

        <!-- MENU BARU: KONSULTASI BK -->
        <button class="nav-btn {{ $isKonsultasiPage ? 'active' : '' }}"
            onclick="window.location.href='{{ route('admin.konsultasi.index') }}'">
            <i class="fas fa-comments"></i> Konsultasi BK
        </button>

        <button class="nav-btn"
            @if ($isKonsultasiPage) onclick="window.location.href='{{ route('admin.dashboard') }}?view=poin'" @else data-view="poin" @endif>
            <i class="fas fa-star"></i> Poin Siswa
        </button>

        <button class="nav-btn"
            @if ($isKonsultasiPage) onclick="window.location.href='{{ route('admin.dashboard') }}?view=profile'" @else data-view="profile" @endif>
            <i class="fas fa-user-edit"></i> Edit Profil
        </button>
    </nav>
</aside>

### Tips Tambahan (Sangat Direkomendasikan):
Agar saat Admin mengklik tombol "Data Siswa" dari halaman Konsultasi BK dan kembali ke dashboard, halaman dashboard Anda
langsung membuka tab "Data Siswa", Anda bisa menambahkan sedikit skrip Javascript di bagian bawah halaman dashboard
utama Anda (`admin.blade.php`):

```javascript
document.addEventListener("DOMContentLoaded", function() {
// Mengecek apakah ada parameter '?view=' di URL browser
const urlParams = new URLSearchParams(window.location.search);
const targetView = urlParams.get('view');

if (targetView) {
// Memicu klik otomatis pada tombol navigasi sidebar yang sesuai
const targetButton = document.querySelector(`.nav-btn[data-view="${targetView}"]`);
if (targetButton) {
targetButton.click();
}
}
});

Silakan terapkan perubahan sidebar ini! Setelah disimpan, silakan buka browser dan nikmati navigasi yang super mulus
antara Dashboard SPA lama dan halaman Konsultasi BK yang baru. Beritahu saya jika sudah berhasil dicoba ya!
