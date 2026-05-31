<section id="view-poin" class="view hidden">
    <div class="card">
        <div class="card-head"><strong>Tambah Poin Siswa</strong></div>
        <div class="card-body">
            <form class="form-grid" id="poinForm" onsubmit="updatePoin(event)">
                 <div class="autocomplete-container">
                     <label>Nama Siswa</label>
                     <input id="p_nama" placeholder="Ketik nama siswa" autocomplete="off" />
                     <div id="nama-list"></div>
                 </div>
                <div>
                    <label>Tingkat</label>
                    <select id="p_tingkat">
                        <option value="">Pilih Tingkat</option>
                    </select>
                </div>
                <div>
                    <label>Kelas</label>
                    <select id="p_kelas_poin">
                        <option value="">Pilih Kelas</option>
                    </select>
                </div>
                <div class="form-item autocomplete-container" style="grid-column:1/-1;">
                    <label>Jenis Pelanggaran</label>
                    <input id="p_search_pelanggaran" placeholder="Cari jenis pelanggaran..." autocomplete="off" />
                    <div id="pelanggaran-list" class="autocomplete-list"></div>
                    <select id="p_jenis_pelanggaran" class="hidden" style="display: none;"></select>
                </div>
                <input type="hidden" id="p_nisn" name="nisn" value="">
                <input type="hidden" id="p_jumlah_poin" name="p_jumlah" value="">
                <input type="hidden" id="p_keterangan_pelanggaran" name="p_ket" value="">
                <div style="grid-column:1/-1; display:flex; gap:8px">
                    <button class="btn primary" type="submit">Simpan</button>
                    <button class="btn secondary" type="button" onclick="document.getElementById('poinForm').reset();">Batal</button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card" style="margin-top:16px">
    <div class="card-head" style="display:flex; justify-content:space-between; align-items:center;">
            <strong>Riwayat Poin</strong>
            @php
                $user = $user ?? session('user', []);
                $role = $user['role'] ?? 'guru';
            @endphp
            @if($role === 'admin')
            <button class="btn" style="background-color: var(--danger); color: white; padding: 6px 12px;" onclick="showDeleteAllRiwayatModal()">Hapus Semua</button>
            @endif
        </div>
        <div class="card-body">
            <div style="margin-bottom: 12px; max-width: 260px;">
                <input 
                    type="text" 
                    id="search-riwayat-poin" 
                    placeholder="Cari NISN / Nama / Kelas / Keterangan..." 
                    style="width: 100%; padding: 8px 10px; border-radius: 6px; border: 1px solid #e5e7eb; font-size: 0.9rem;"
                    oninput="filterRiwayatPoin()"
                />
            </div>
            <table id="tbl-poin">
            <thead>
                <tr>
                    <th>NISN</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</section>

