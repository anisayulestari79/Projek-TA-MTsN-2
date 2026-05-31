:root {
    --bg: #f0f2f5;
    --card: #ffffff;
    --muted: #6c757d;
    --text: #333;
    --brand: #1abc9c;
    --brand-2: #16a085;
    --ok: #34d399;
    --warn: #f59e0b;
    --danger: #e74c3c;
    --border: #dee2e6;
    --shadow: 0 4px 12px rgba(0, 0, 0, .1);
    --radius: 12px;
}

* { box-sizing: border-box; }
html, body { height: 100%; }
body {
    margin: 0; 
    background: var(--bg);
    font-family: 'Poppins', sans-serif;
    color: var(--text);
}
.app {
    display: grid;
    grid-template-columns: 280px 1fr;
    min-height: 100vh;
}
aside {
    border-right: 1px solid rgba(255, 255, 255, 0.2);
    padding: 24px; 
    position: sticky; 
    top: 0; 
    height: 100vh; 
    overflow: auto;
    background: var(--brand);
    color: white;
}
.brand {
    display: flex; 
    align-items: center; 
    gap: 12px; 
    margin-bottom: 22px;
}
.logo-main {
    width: 38px; 
    height: 38px; 
    object-fit: contain;
}
.brand h1 { font-size: 24px; margin: 0; letter-spacing: .3px; }
.brand .subtitle {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.7);
}
.search { position: relative; margin: 10px 0 18px; }
.search input {
    width: 100%; 
    padding: 14px 16px; 
    border-radius: 12px; 
    border: 1px solid rgba(255, 255, 255, 0.2);
    background: rgba(255, 255, 255, 0.1);
    color: white;
    outline: none;
    font-size: 16px;
}
.search input::placeholder {
    color: rgba(255, 255, 255, 0.7);
}
nav { display: grid; gap: 8px; }
.nav-btn {
    display: flex; 
    align-items: center; 
    gap: 10px; 
    padding: 12px 14px; 
    border-radius: 12px; 
    border: 1px solid transparent;
    color: white;
    background: transparent; 
    cursor: pointer; 
    text-align: left; 
    transition: .2s;
    font-size: 16px;
}
.nav-btn:hover { background: rgba(255, 255, 255, 0.1); border-color: rgba(255, 255, 255, 0.2); }
.nav-btn.active {
    background: white;
    border-color: rgba(255, 255, 255, 0.2);
    color: var(--brand);
    font-weight: 600;
}
.nav-btn.active .chip {
    background: var(--brand);
    color: white;
}

main { padding: 26px; }
header.top {
    display: flex; 
    align-items: center; 
    gap: 12px; 
    justify-content: space-between; 
    margin-bottom: 16px;
}
.title { display: flex; align-items: center; gap: 12px; }
.title h2 { margin: 0; font-size: 20px; }
.toolbar { display: flex; gap: 8px; }
.btn {
    padding: 10px 14px; 
    border-radius: 12px; 
    background: var(--card); 
    border: 1px solid var(--border); 
    color: var(--text); 
    cursor: pointer;
}
.btn.primary { 
    background: #3498db;
    color: white; 
    border: none; 
    font-weight: 600;
}
.btn.primary:hover {
    background: #2980b9; 
}
.btn.secondary {
    background: #e74c3c;
    color: white;
    border: none;
    font-weight: 600;
}
.btn.secondary:hover {
    background: #c0392b;
}
.grid { display: grid; gap: 16px; }
.grid.cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
.grid.cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
.card { background: var(--card); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); }
.card .card-head { padding: 14px 16px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 10px; color: var(--text); }
.card .card-body { padding: 16px; }

table { width: 100%; border-collapse: collapse; }
th, td { padding: 10px 12px; border-bottom: 1px solid var(--border); text-align: left; font-size: 14px; }
th { color: var(--muted); font-weight: 600; }

.form-grid { display: grid; gap: 12px; grid-template-columns: repeat(2, minmax(0, 1fr)); }
label { font-size: 13px; color: var(--muted); }
input, select, textarea {
    width: 100%; 
    padding: 10px 12px; 
    border-radius: 12px; 
    border: 1px solid var(--border); 
    background: var(--bg); 
    color: var(--text); 
    outline: none;
}
textarea { min-height: 96px; resize: vertical; }

.status { padding: 6px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; display: inline-block; }
.status.proses { background: rgba(245, 158, 11, .15); color: #fbbf24; }
.status.selesai { background: rgba(52, 211, 153, .15); color: #6ee7b7; }
.status.baru { background: rgba(96, 165, 250, .15); color: #93c5fd; }

.hidden { display: none; }

.profile-dropdown { position: relative; }
.profile-dropdown-content {
    display: none;
    position: absolute;
    background-color: var(--card);
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 10;
    top: 50px;
    right: 0;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid var(--border);
}
.profile-dropdown.active .profile-dropdown-content { display: block; }
.profile-button {
    background: var(--bg);
    border: 1px solid var(--border);
    color: var(--text);
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    padding: 8px 12px;
    display: flex;
    align-items: center;
    border-radius: 12px;
    transition: .2s;
}
.profile-button:hover { border-color: #60a5fa; }
.profile-photo {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    margin-right: 8px;
    object-fit: cover;
}
.profile-dropdown-content a, 
.profile-dropdown-content button {
    color: var(--text);
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    background: none;
    border: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    transition: background-color 0.3s ease;
}
.profile-dropdown-content button:hover, .profile-dropdown-content a:hover {
    background-color: var(--bg);
}

.modal {
    display: none; 
    position: fixed; 
    top: 0; 
    left: 0; 
    width: 100%; 
    height: 100%; 
    background-color: rgba(0, 0, 0, 0.7); 
    z-index: 20; 
    justify-content: center; 
    align-items: center;
}
.modal-content {
    background-color: var(--card); 
    padding: 30px; 
    border-radius: 15px; 
    text-align: center; 
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); 
    width: 300px; 
    border: 1px solid var(--border);
}
.modal-content h3 { color: var(--text); margin-top: 0; }
.modal-content p { color: var(--muted); }
.modal-buttons { display: flex; gap: 10px; margin-top: 20px; }
.modal-buttons button { flex: 1; padding: 10px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; }
.modal-buttons .btn-confirm { background-color: var(--danger); color: white; }
.modal-buttons .btn-confirm:hover { background-color: #c0392b; }
.modal-buttons .btn-cancel { background-color: var(--muted); color: white; }
.modal-buttons .btn-cancel:hover { background-color: #5a6268; }

.profile-card {
    background: var(--card);
    padding: 40px;
    border-radius: 20px;
    box-shadow: var(--shadow);
    max-width: 500px;
    margin: 40px auto;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.profile-card:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 80px;
    background: linear-gradient(135deg, var(--brand), var(--brand-2));
    z-index: 1;
}
.profile-pic-container {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    border: 5px solid var(--card);
    margin: 0 auto 15px;
    position: relative;
    z-index: 2;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}
.profile-pic {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}
.profile-info h3 {
    margin: 0;
    font-size: 20px;
    color: var(--text);
    font-weight: 700;
}
.profile-info p {
    margin: 4px 0 0;
    color: var(--muted);
    font-size: 14px;
}
.profile-details {
    text-align: left;
    margin-top: 20px;
    border-top: 1px solid var(--border);
    padding-top: 20px;
}
.profile-item {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
    padding: 8px;
    border-radius: 10px;
    background: #fcfcfd;
}
.profile-item .icon {
    font-size: 16px;
    color: var(--brand);
    width: 25px;
    text-align: center;
}
.profile-item .detail-content { flex-grow: 1; }
.profile-item label {
    font-size: 11px;
    color: var(--muted);
    display: block;
    margin-bottom: 2px;
}
.profile-item p {
    margin: 0;
    font-weight: 600;
    color: var(--text);
    font-size: 14px;
}
.btn-edit, .btn-form {
    display: block;
    width: 100%;
    padding: 14px;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    margin-top: 25px;
    transition: 0.3s ease;
}
.btn-edit:hover {
    background: var(--brand-2);
    transform: translateY(-2px);
}
.profile-form {
    text-align: left;
    padding: 30px;
    background: var(--card);
    border-radius: 20px;
    box-shadow: var(--shadow);
    max-width: 500px;
    margin: 40px auto;
    display: none;
}
.profile-form h3 {
    margin-top: 0;
    border-bottom: 1px solid var(--border);
    padding-bottom: 15px;
    font-weight: 600;
    color: var(--text);
}
.profile-form .profile-item {
    display: block;
    background: none;
    padding: 0;
    margin-bottom: 20px;
}
.profile-form input, .profile-form select {
    width: 100%;
    padding: 12px;
    margin-top: 6px;
    border: 1px solid var(--border);
    border-radius: 10px;
    font-family: 'Poppins', sans-serif;
    font-size: 15px;
    transition: border-color 0.3s;
}
.profile-form input:focus, .profile-form select:focus {
    outline: none;
    border-color: var(--brand);
}
.profile-form label {
    font-weight: 600;
    font-size: 14px;
    color: var(--text);
}
.form-buttons {
    display: flex;
    gap: 15px;
}
.btn-form {
    flex: 1;
    margin: 0;
    font-size: 16px;
}
.btn-form.cancel { background: #95a5a6; }
.btn-form.cancel:hover { background: #7f8c8d; }
.view.hidden { display: none; }

.btn-edit-siswa {
    background-color: #3498db;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    margin-right: 8px;
}
.btn-edit-siswa:hover {
    background-color: #2980b9;
}
.btn-hapus-siswa {
    background-color: #e74c3c;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
}
.btn-hapus-siswa:hover {
    background-color: #c0392b;
}
.btn-hapus-riwayat-text {
    background-color: var(--danger);
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 12px;
}
.btn-hapus-riwayat-text:hover {
    background-color: #c0392b;
}

.siswa-profile-card-modal {
    background: var(--card);
    padding: 30px;
    border-radius: 20px;
    box-shadow: var(--shadow);
    max-width: 450px;
    width: 90%;
    position: relative;
    overflow: hidden;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.siswa-profile-card-modal:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 80px;
    background: linear-gradient(135deg, var(--brand), var(--brand-2));
    z-index: 1;
}
.siswa-profile-card-modal .close-btn {
    position: absolute;
    top: 15px;
    right: 20px;
    background: none;
    border: none;
    font-size: 28px;
    color: white;
    cursor: pointer;
    z-index: 3;
}
.siswa-profile-card-modal .profile-pic-container-modal {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    border: 5px solid var(--card);
    background-color: var(--brand);
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0 auto 15px;
    position: relative;
    z-index: 2;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    font-size: 3rem;
    font-weight: 700;
    color: white;
}
.siswa-profile-card-modal h3 {
    margin: 0;
    font-size: 22px;
    color: var(--text);
    font-weight: 700;
    position: relative;
    z-index: 2;
}
.siswa-profile-card-modal .profile-details-modal {
    text-align: left;
    width: 100%;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid var(--border);
}
.siswa-profile-card-modal .profile-item-modal {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
    padding: 10px 15px;
    border-radius: 10px;
    background: #fcfcfd;
}
.siswa-profile-card-modal .profile-item-modal .icon {
    font-size: 18px;
    color: var(--brand);
    width: 30px;
    text-align: center;
}
.siswa-profile-card-modal .profile-item-modal .detail-content-modal { flex-grow: 1; }
.siswa-profile-card-modal .profile-item-modal label {
    font-size: 12px;
    color: var(--muted);
    display: block;
    margin-bottom: 2px;
}
.siswa-profile-card-modal .profile-item-modal p {
    margin: 0;
    font-weight: 600;
    color: var(--text);
    font-size: 15px;
}

.modal-siswa-form .modal-content {
    max-width: 500px;
    width: 90%;
    text-align: left;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}
.modal-siswa-form .modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.modal-siswa-form .modal-header h3 {
    margin: 0;
    color: var(--text);
}
.modal-siswa-form .close-button {
    background: none;
    border: none;
    font-size: 2rem;
    color: var(--muted);
    cursor: pointer;
}
.btn-tambah-siswa {
    background-color: var(--brand);
    color: white;
    border: none;
    padding: 10px 14px;
    border-radius: 12px;
    cursor: pointer;
    font-weight: 600;
    font-size: 16px;
}
.btn-tambah-siswa:hover {
    background-color: var(--brand-2);
}

.modal-guru-form .modal-content {
    max-width: 550px !important;
    width: 95%;
    text-align: left;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}
.modal-guru-form .form-grid {
    grid-template-columns: 1fr 1fr;
}
.modal-guru-form .full-width {
    grid-column: 1 / -1;
}

.autocomplete-container {
    position: relative;
}
.autocomplete-list, #nama-list {
    position: absolute;
    z-index: 10;
    width: 100%;
    max-height: 200px;
    overflow-y: auto;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 0 0 12px 12px;
    border-top: none;
    box-shadow: var(--shadow);
    display: none;
}
.autocomplete-list div, #nama-list div {
    padding: 10px 12px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.2s;
}
.autocomplete-list div:hover, #nama-list div:hover {
    background-color: var(--bg);
}

.data-filter { display: flex; gap: 10px; margin-bottom: 16px; }
.data-filter input, .data-filter select { flex-grow: 1; }
.data-filter-siswa {
    display: flex;
    gap: 10px;
    margin-bottom: 16px;
}
.data-filter-siswa select {
    flex-grow: 1;
}

.sortable-header { cursor: pointer; user-select: none; }
.sortable-header .fa-sort { margin-left: 5px; color: var(--muted); }

@media(max-width: 960px) {
    .app { grid-template-columns: 1fr; }
    aside { position: relative; height: auto; box-shadow: none; }
    main { padding: 15px; }
    header.top { flex-direction: column; align-items: flex-start; padding-bottom: 10px; }
    .title h2 { font-size: 22px; }
    .profile-card, .profile-form { margin: 20px auto; padding: 25px; }
    .grid.cols-3 { grid-template-columns: 1fr; }
    .grid.cols-2 { grid-template-columns: 1fr; }
}

