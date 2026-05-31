<section id="view-data-siswa" class="view hidden">
    <div class="card">
        <div class="card-head" style="display:flex; justify-content:space-between; align-items:center;">
            <strong>Daftar Siswa</strong>
            <div style="display: flex; gap: 8px;">
                {{-- <button class="btn" style="background-color: #10b981; color: white;" onclick="showImportSiswaModal()">Import Excel</button> --}}
                <button class="btn btn-tambah-siswa" onclick="showSiswaFormModal()">Tambah Siswa</button>
            </div>
        </div>
        <div class="card-body">
            <div class="data-filter-siswa">
                <div>
                    <label>Cari Nama/NISN</label>
                    <input type="text" id="search-siswa" placeholder="Cari nama atau NISN..." oninput="filterSiswa()"
                        style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
                </div>
                <div>
                    <label>Tingkat</label>
                    <select id="filter-tingkat" onchange="filterSiswa()">
                        <option value="">Semua Tingkat</option>
                    </select>
                </div>
                <div>
                    <label>Kelas</label>
                    <select id="filter-kelas-siswa" onchange="filterSiswa()">
                        <option value="">Semua Kelas</option>
                    </select>
                </div>
            </div>
            <table id="tbl-siswa">
                <thead>
                    <tr>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Kontak Ortu</th>
                        <th>Poin</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</section>
