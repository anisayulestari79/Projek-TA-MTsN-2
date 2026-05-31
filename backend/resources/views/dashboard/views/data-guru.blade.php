<section id="view-data-guru" class="view hidden">
    <div class="card">
        <div class="card-head" style="display: flex; justify-content: space-between; align-items: center;">
            <strong>Daftar Guru</strong>
            <div style="display: flex; gap: 8px;">
                {{-- <button class="btn" style="background-color: #10b981; color: white;" onclick="showImportGuruModal()">Import Excel</button> --}}
                <button class="btn btn-tambah-siswa" onclick="showGuruFormModal()">Tambah Guru</button>
            </div>
        </div>
        <div class="card-body">
            <div class="data-filter-guru" style="margin-bottom: 15px;">
                <div style="max-width: 300px;">
                    <label>Cari Nama/NIP</label>
                    <input type="text" id="search-guru" placeholder="Cari nama atau NIP..." oninput="filterGuru()"
                        style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
                </div>
            </div>
            <table id="tbl-guru">
                <thead>
                    <tr>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Pendidikan</th>
                        <th>Password</th>
                        <th>Wali Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</section>
