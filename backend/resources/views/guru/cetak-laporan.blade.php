<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Rekapitulasi Pelanggaran Siswa</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }

        /* ======================================================= */
        /* GAYA KHUSUS SAAT DILIHAT DI LAYAR (SCREEN)              */
        /* (Membuatnya terlihat seperti kertas di MS Word)         */
        /* ======================================================= */
        @media screen {
            body {
                background-color: #e5e7eb;
                padding: 20px;
            }

            .container {
                background-color: #fff;
                max-width: 21cm;
                min-height: 29.7cm;
                margin: 0 auto;
                padding: 2cm 2.5cm;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            }

            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            table.data-table {
                min-width: 650px;
            }
        }

        /* ======================================================= */
        /* GAYA KHUSUS SAAT DI-PRINT / DIJADIKAN PDF               */
        /* ======================================================= */
        @media print {
            @page {
                size: A4 portrait;
                margin: 2cm 2.5cm;
            }

            body {
                background-color: #fff;
                padding: 0;
            }

            .container {
                width: 100%;
                margin: 0;
                padding: 0;
                box-shadow: none;
                max-width: none;
                min-height: auto;
            }

            .table-responsive {
                overflow-x: visible;
            }

            table.data-table {
                min-width: auto;
            }
        }

        /* Kop Surat Resmi */
        table.kop-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
            margin-bottom: 0;
        }

        table.kop-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        .kop-surat {
            width: 100%;
            padding-bottom: 10px;
            margin-bottom: 20px;
            position: relative;
        }

        .kop-surat::after {
            content: "";
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 100%;
            height: 1px;
            background-color: #000;
            border-bottom: 3px double #000;
        }

        .logo-container {
            width: 110px;
            text-align: center;
        }

        .logo-container img {
            width: 75px;
            height: auto;
        }

        .kop-text {
            text-align: center;
            padding-right: 110px;
        }

        .kop-text h1 {
            margin: 0;
            font-size: 14pt;
            font-weight: normal;
            text-transform: uppercase;
        }

        .kop-text h2 {
            margin: 2px 0;
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .kop-text p {
            margin: 0;
            font-size: 10pt;
        }

        /* Judul & Sub Judul */
        .judul-laporan {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .sub-judul {
            text-align: center;
            font-size: 11pt;
            margin-bottom: 25px;
        }

        /* Info Tambahan */
        table.info-table {
            width: 100%;
            border: none;
            margin-bottom: 10px;
            font-size: 11pt;
        }

        table.info-table td {
            border: none;
            padding: 2px 0;
        }

        /* Tabel Data Formal */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 11pt;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: middle;
        }

        table.data-table th {
            text-align: center;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-bold {
            font-weight: bold;
        }

        .catatan {
            font-size: 10pt;
            font-style: italic;
            text-align: left;
        }

        /* Bagian Tanda Tangan */
        .ttd-container {
            width: 100%;
            margin-top: 40px;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .ttd-box {
            float: right;
            text-align: center;
            width: 300px;
            font-size: 11pt;
        }

        .ttd-name {
            margin-top: 70px;
            font-weight: bold;
            text-decoration: underline;
        }

        .ttd-nip {
            margin-top: 2px;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- KOP SURAT -->
        <div class="kop-surat">
            <table class="kop-table">
                <tr>
                    <td class="logo-container">
                        <img src="https://i.ibb.co.com/Psm2GxFN/5ef6453c-3a7b-47dc-a402-dacf0adb575d-removebg-preview-1.png"
                            alt="Logo MTsN 2">
                    </td>
                    <td class="kop-text">
                        <h1>Kementerian Agama Republik Indonesia</h1>
                        <h2>Madrasah Tsanawiyah Negeri 2 Kota Banjarmasin</h2>
                        <p>Jalan Mahligai Km. 7 Kertak Hanyar, Kota Banjarmasin, Kalimantan Selatan</p>
                        <p>Telepon: (0511) 1234567 | Email: mtsn2bjm@kemenag.go.id</p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- JUDUL -->
        <div class="judul-laporan">LAPORAN REKAPITULASI KEDISIPLINAN SISWA</div>

        @php
            $currentYear = \Carbon\Carbon::now()->year;
            $academicYear = $currentYear . ' / ' . ($currentYear + 1);
        @endphp

        <!-- TAMPILAN SUB JUDUL DINAMIS SESUAI KATEGORI/FILTER -->
        @if (isset($judulKategori))
            <div class="sub-judul">Kategori: {{ $judulKategori }} | Tahun Ajaran {{ $academicYear }}</div>
        @else
            <div class="sub-judul">Tahun Ajaran {{ $academicYear }}</div>
        @endif

        <!-- INFO TAMBAHAN DICETAK OLEH & TANGGAL -->
        <table class="info-table">
            <tr>
                <td style="text-align: left;">
                    <strong>Laporan Dicetak Oleh:</strong> {{ $user['name'] ?? 'Guru / Admin' }}
                </td>
                <td style="text-align: right;">
                    <strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                </td>
            </tr>
        </table>

        <!-- TABEL DATA (Dibungkus div table-responsive agar aman di HP) -->
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 6%;">No.</th>
                        <th style="width: 18%;">NISN</th>
                        <th style="width: 36%;">Nama Lengkap Siswa</th>
                        <th style="width: 12%;">Kelas</th>
                        <th style="width: 14%;">Total Poin</th>
                        <th style="width: 14%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dataSiswa ?? [] as $index => $siswa)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ $siswa->nisn }}</td>
                            <td class="text-left">{{ $siswa->nama }}</td>
                            <td class="text-center">{{ $siswa->kelas }}</td>
                            <td class="text-center text-bold">{{ $siswa->poin ?? 0 }}</td>
                            <td class="text-center">
                                @if (($siswa->poin ?? 0) >= 100)
                                    Dikeluarkan
                                @elseif(($siswa->poin ?? 0) >= 50)
                                    Waspada II
                                @elseif(($siswa->poin ?? 0) >= 25)
                                    Waspada I
                                @else
                                    Aman
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data siswa untuk ditampilkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- CATATAN KAKI -->
        <p class="catatan">
            *Catatan: Dokumen ini dicetak otomatis dari Sistem Monitoring Kedisiplinan Siswa MTsN 2 Kota Banjarmasin dan
            sah untuk dijadikan lampiran evaluasi.
        </p>

        <!-- TANDA TANGAN DIBUNGKUS DALAM CONTAINER ANTI-TERPOTONG -->
        <div class="ttd-container clearfix">
            <div class="ttd-box">
                Banjarmasin, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                <!-- Logika TTD Dinamis: Jika mencetak kelas binaan, maka jabatannya Guru BK -->
                @if (isset($judulKategori) && str_contains($judulKategori, 'Binaan'))
                    Guru Bimbingan Konseling,
                @else
                    Pihak Madrasah,
                @endif

                <div class="ttd-name">{{ $user['name'] ?? '.........................................' }}</div>
                <div class="ttd-nip">NIP. {{ $user['nip'] ?? '.........................................' }}</div>
            </div>
        </div>

    </div>

    <!-- SCRIPT TRIGGER PRINT INVISIBLE -->
    <script>
        window.onload = function() {
            // Memulai proses pencetakan otomatis setelah halaman dimuat
            setTimeout(() => {
                window.print();
            }, 500);
        }
    </script>
</body>

</html>
