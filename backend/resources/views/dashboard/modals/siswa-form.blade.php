<div id="siswaFormModal" class="modal modal-siswa-form">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="siswaModalTitle">Tambah Data Siswa</h3>
            <button class="close-button" onclick="closeSiswaFormModal()">&times;</button>
        </div>
        <form class="form-grid" id="siswaForm" onsubmit="addSiswa(event)">
            <div class="full-width">
                <label>Foto Profil Siswa (Opsional)</label>
                <input type="file" id="s_photo" accept="image/*">
            </div>
            <div>
                <label>Nama Lengkap</label>
                <input required id="s_nama" placeholder="Nama siswa" />
            </div>
            <div>
                <label>NISN</label>
                <input required id="s_nisn" placeholder="NISN" />
            </div>
            <div>
                <label>Jenis Kelamin</label>
                <select id="s_jk">
                    <option value="">Pilih...</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>
            <div>
                <label>Tingkat</label>
                <select id="s_tingkat">
                    <option value="">Pilih Tingkat</option>
                    <option value="VII">VII</option>
                    <option value="VIII">VIII</option>
                    <option value="IX">IX</option>
                </select>
            </div>
            <div>
                <label>Kelas</label>
                <select id="s_kelas">
                    <option value="">Pilih Kelas</option>
                </select>
            </div>
            <div class="full-width">
                <label>Kontak Orang Tua</label>
                <input id="s_kontak" placeholder="0812xxxx" />
            </div>
            <div class="full-width" style="display:flex; gap:8px">
                <button class="btn primary" type="submit">Simpan Siswa</button>
                <button class="btn secondary" type="button" onclick="closeSiswaFormModal()">Batal</button>
            </div>
        </form>
    </div>
</div>

