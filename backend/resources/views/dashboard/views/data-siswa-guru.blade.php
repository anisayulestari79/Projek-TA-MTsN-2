<section id="view-data-siswa" class="view hidden">
    <div class="card">
        <div class="card-head">
            <strong>Daftar Siswa</strong>
        </div>
        <div class="card-body">
            <div class="data-filter-siswa">
                <div>
                    <label>Cari Nama/NISN</label>
                    <input type="text" id="search-siswa" placeholder="Cari nama atau NISN..." oninput="filterSiswa()" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
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

