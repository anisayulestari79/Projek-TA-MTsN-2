<div id="guruFormModal" class="modal modal-guru-form">
    <div class="modal-content" style="max-width: 600px; padding: 25px;">
        <div class="modal-header">
            <h3 id="guruModalTitle">Tambah Guru</h3>
            <button class="close-button" onclick="closeGuruFormModal()">&times;</button>
        </div>
        <form class="form-grid" id="guruForm" onsubmit="addGuru(event)">
        <div class="full-width">
            <label>NIP</label>
            <input id="g_nip" placeholder="Masukkan NIP Guru" />
        </div>
        <div class="full-width">
            <label>Nama Lengkap</label>
            <input required id="g_nama" placeholder="Masukkan Nama" />
        </div>
        <div>
            <label>Jenis Kelamin</label>
            <select id="g_jk">
                <option value="">Pilih...</option>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
        </div>
        <div>
            <label>Pendidikan</label>
            <select id="g_pendidikan">
                <option value="">Pilih Pendidikan</option>
                <option value="Sarjana (S1)">Sarjana (S1)</option>
                <option value="Magister (S2)">Magister (S2)</option>
                <option value="Doktor (S3)">Doktor (S3)</option>
            </select>
        </div>
        <div>
            <label>Tempat Lahir</label>
            <input id="g_tempat_lahir" placeholder="Masukkan Tempat Lahir" />
        </div>
        <div>
            <label>Tanggal Lahir</label>
            <input type="date" id="g_tanggal_lahir" />
        </div>
        <div>
            <label>Password</label>
            <input type="text" id="g_password" placeholder="Kosongkan jika password ingin di-generate" />
        </div>
        <div>
            <label>Wali Kelas</label>
            <select id="g_wali_kelas">
                <option value="">Pilih Wali Kelas</option>
            </select>
        </div>
        <div class="full-width" style="display: flex; justify-content: flex-end; gap: 8px; margin-top: 15px;">
            <button class="btn secondary" type="button" onclick="closeGuruFormModal()">Batal</button>
            <button class="btn primary" type="submit">Simpan Perubahan</button>
        </div>
    </form>
    </div>
</div>

