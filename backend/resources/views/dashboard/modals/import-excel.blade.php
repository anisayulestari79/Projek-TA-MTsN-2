<div id="importSiswaModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3>Import Data Siswa dari Excel</h3>
            <button class="close-button" onclick="closeImportSiswaModal()">&times;</button>
        </div>
        <div style="padding: 20px;">
            <p style="margin-bottom: 15px; color: var(--muted); font-size: 14px;">
                Format Excel: <strong>NISN | Nama | Jenis Kelamin | Kelas | Kontak Ortu</strong><br>
                Baris pertama adalah header, data dimulai dari baris kedua.<br>
                <small>Jenis Kelamin: Laki-laki atau Perempuan (opsional)</small>
            </p>
            <form id="importSiswaForm" onsubmit="importSiswaExcel(event)">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Pilih File Excel (.xlsx, .xls)</label>
                    <input type="file" id="siswaExcelFile" accept=".xlsx,.xls" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px;">
                </div>
                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" class="btn secondary" onclick="closeImportSiswaModal()">Batal</button>
                    <button type="submit" class="btn primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="importGuruModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3>Import Data Guru dari Excel</h3>
            <button class="close-button" onclick="closeImportGuruModal()">&times;</button>
        </div>
        <div style="padding: 20px;">
            <p style="margin-bottom: 15px; color: var(--muted); font-size: 14px;">
                Format Excel: <strong>NIP | Nama | JK | Pendidikan | Tempat Lahir | Tanggal Lahir | Password | Wali Kelas</strong><br>
                Baris pertama adalah header, data dimulai dari baris kedua.<br>
                <small>JK: Laki-laki atau Perempuan (opsional)</small><br>
                <small>NUPTK akan di-generate otomatis</small>
            </p>
            <form id="importGuruForm" onsubmit="importGuruExcel(event)">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Pilih File Excel (.xlsx, .xls)</label>
                    <input type="file" id="guruExcelFile" accept=".xlsx,.xls" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px;">
                </div>
                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" class="btn secondary" onclick="closeImportGuruModal()">Batal</button>
                    <button type="submit" class="btn primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Hasil Import -->
<div id="importResultModal" class="modal">
    <div class="modal-content" style="max-width: 650px; max-height: 90vh; overflow-y: auto;">
        <div class="modal-header">
            <h3 id="importResultTitle">
                <i class="fas fa-check-circle" style="color: #10b981; margin-right: 8px;"></i>
                Hasil Import
            </h3>
            <button class="close-button" onclick="closeImportResultModal()">&times;</button>
        </div>
        <div style="padding: 25px;">
            <!-- Statistik Cards -->
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 25px;">
                <div id="importSuccessCard" style="background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border: 2px solid #10b981; border-radius: 12px; padding: 20px; text-align: center; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.15);">
                    <div style="font-size: 36px; font-weight: 700; color: #10b981; margin-bottom: 8px;" id="importSuccessCount">0</div>
                    <div style="font-size: 13px; color: #065f46; font-weight: 600;">Berhasil Diimport</div>
                </div>
                <div id="importSkippedCard" style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border: 2px solid #f59e0b; border-radius: 12px; padding: 20px; text-align: center; box-shadow: 0 2px 8px rgba(245, 158, 11, 0.15);">
                    <div style="font-size: 36px; font-weight: 700; color: #f59e0b; margin-bottom: 8px;" id="importSkippedCount">0</div>
                    <div style="font-size: 13px; color: #92400e; font-weight: 600;">Data Dilewati</div>
                </div>
                <div id="importErrorCard" style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border: 2px solid #ef4444; border-radius: 12px; padding: 20px; text-align: center; box-shadow: 0 2px 8px rgba(239, 68, 68, 0.15);">
                    <div style="font-size: 36px; font-weight: 700; color: #ef4444; margin-bottom: 8px;" id="importErrorCount">0</div>
                    <div style="font-size: 13px; color: #991b1b; font-weight: 600;">Error</div>
                </div>
            </div>

            <!-- Pesan Utama -->
            <div id="importResultMessage" style="background: #f0f9ff; border-left: 4px solid #3b82f6; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <p style="margin: 0; font-size: 14px; color: var(--text); font-weight: 500;" id="importResultMessageText"></p>
            </div>

            <!-- Detail Error -->
            <div id="importErrorDetails" style="display: none;">
                <div style="background: #fef2f2; border: 1px solid #ef4444; border-radius: 12px; padding: 18px; max-height: 350px; overflow-y: auto;">
                    <h4 style="margin: 0 0 15px 0; color: #ef4444; font-size: 15px; font-weight: 600; display: flex; align-items: center;">
                        <i class="fas fa-exclamation-circle" style="margin-right: 8px; font-size: 18px;"></i>
                        Detail Error:
                    </h4>
                    <div id="importErrorList" style="list-style: none; padding: 0; margin: 0;">
                        <!-- Error items akan diisi oleh JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Tombol Tutup -->
            <div style="display: flex; gap: 10px; justify-content: flex-end; padding-top: 20px; border-top: 1px solid var(--border); margin-top: 20px;">
                <button type="button" class="btn primary" onclick="closeImportResultModal()" style="min-width: 120px;">
                    <i class="fas fa-check" style="margin-right: 6px;"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>

