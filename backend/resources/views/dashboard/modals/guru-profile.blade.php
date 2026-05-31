<div id="guruProfileModal" class="modal">
    <div class="modal-content guru-profile-card-modal">
        <button class="close-btn" onclick="closeGuruProfileModal()">&times;</button>
        <div class="profile-pic-container-modal-guru" id="guruProfilePic"></div>
        <h3 id="guruProfileNama"></h3>
        
        <div class="profile-details-modal">
            <div class="profile-item-modal">
                <i class="icon fas fa-id-badge"></i>
                <div class="detail-content-modal">
                    <label>NIP</label>
                    <p id="guruProfileNip"></p>
                </div>
            </div>
            <div class="profile-item-modal">
                <i class="icon fas fa-user"></i>
                <div class="detail-content-modal">
                    <label>Jenis Kelamin</label>
                    <p id="guruProfileJk"></p>
                </div>
            </div>
            <div class="profile-item-modal">
                <i class="icon fas fa-graduation-cap"></i>
                <div class="detail-content-modal">
                    <label>Pendidikan</label>
                    <p id="guruProfilePendidikan"></p>
                </div>
            </div>
            <div class="profile-item-modal">
                <i class="icon fas fa-map-marker-alt"></i>
                <div class="detail-content-modal">
                    <label>Tempat Lahir</label>
                    <p id="guruProfileTempatLahir"></p>
                </div>
            </div>
            <div class="profile-item-modal">
                <i class="icon fas fa-calendar-alt"></i>
                <div class="detail-content-modal">
                    <label>Tanggal Lahir</label>
                    <p id="guruProfileTanggalLahir"></p>
                </div>
            </div>
            <div class="profile-item-modal">
                <i class="icon fas fa-chalkboard"></i>
                <div class="detail-content-modal">
                    <label>Wali Kelas</label>
                    <p id="guruProfileWaliKelas"></p>
                </div>
            </div>
            <div class="profile-item-modal">
                <i class="icon fas fa-key"></i>
                <div class="detail-content-modal">
                    <label>Password</label>
                    <p id="guruProfilePassword"></p>
                </div>
            </div>
        </div>
        
        <div class="profile-details-modal" id="guruAccountSection" style="margin-top: 12px; padding-top: 12px; border-top: 2px solid var(--border);">
            <h4 style="margin: 0 0 10px 0; font-size: 12px; color: var(--muted); font-weight: 600;">Informasi Akun Login</h4>
            <div class="account-info-grid">
                <div class="account-item-modal">
                    <i class="icon fas fa-envelope"></i>
                    <div class="account-detail-content">
                        <label>Email</label>
                        <p id="guruProfileEmail"></p>
                    </div>
                </div>
                <div class="account-item-modal">
                    <i class="icon fas fa-phone"></i>
                    <div class="account-detail-content">
                        <label>No. Telepon</label>
                        <p id="guruProfilePhone"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.guru-profile-card-modal {
    background: var(--card);
    padding: 16px;
    border-radius: 12px;
    box-shadow: var(--shadow);
    max-width: 400px;
    width: 90%;
    position: relative;
    overflow: hidden;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.guru-profile-card-modal:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 50px;
    background: linear-gradient(135deg, var(--brand), var(--brand-2));
    z-index: 1;
}

.guru-profile-card-modal .close-btn {
    position: absolute;
    top: 10px;
    right: 12px;
    background: none;
    border: none;
    font-size: 20px;
    color: white;
    cursor: pointer;
    z-index: 3;
}

.guru-profile-card-modal .profile-pic-container-modal-guru {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: 3px solid var(--card);
    background-color: var(--brand);
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0 auto 10px;
    position: relative;
    z-index: 2;
}

.guru-profile-card-modal h3 {
    margin: 0;
    font-size: 16px;
    color: var(--text);
    font-weight: 700;
    position: relative;
    z-index: 2;
}

.guru-profile-card-modal .profile-details-modal {
    text-align: left;
    width: 100%;
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid var(--border);
}

.guru-profile-card-modal .profile-item-modal {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
    padding: 6px 10px;
    border-radius: 6px;
    background: #fcfcfd;
}

.guru-profile-card-modal .profile-item-modal .icon {
    font-size: 14px;
    color: var(--brand);
    width: 20px;
    text-align: center;
}

.guru-profile-card-modal .profile-item-modal .detail-content-modal {
    flex-grow: 1;
}

.guru-profile-card-modal .profile-item-modal label {
    font-size: 10px;
    color: var(--muted);
    display: block;
    margin-bottom: 2px;
}

.guru-profile-card-modal .profile-item-modal p {
    margin: 0;
    font-weight: 600;
    color: var(--text);
    font-size: 12px;
}

.guru-profile-card-modal .account-info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
    width: 100%;
}

.guru-profile-card-modal .account-item-modal {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 8px 6px;
    border-radius: 6px;
    background: #fcfcfd;
    min-width: 0;
}

.guru-profile-card-modal .account-item-modal .icon {
    font-size: 14px;
    color: var(--brand);
    margin-bottom: 4px;
}

.guru-profile-card-modal .account-item-modal .account-detail-content {
    width: 100%;
}

.guru-profile-card-modal .account-item-modal label {
    font-size: 9px;
    color: var(--muted);
    display: block;
    margin-bottom: 2px;
}

.guru-profile-card-modal .account-item-modal p {
    margin: 0;
    font-weight: 600;
    color: var(--text);
    font-size: 11px;
    word-break: break-word;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>

