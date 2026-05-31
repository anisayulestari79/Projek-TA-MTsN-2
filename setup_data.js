// setup_data.js

function setupInitialUsers() {
    let users = {};

    // --- Data Akun Guru (Diperbarui dengan properti profil dasar) ---
    users['1234'] = { 
        password: 'guru123', 
        role: 'guru', 
        nama: 'Ibu Annisa', 
        nip: '1234', 
        gender: 'Perempuan', 
        phone: '08111222333', 
        photo: '' 
    };
    users['5678'] = { 
        password: 'guru456', 
        role: 'guru', 
        nama: 'Bapak Rahmat', 
        nip: '5678', 
        gender: 'Laki-laki', 
        phone: '08555666777', 
        photo: '' 
    };

    // --- Data Akun Admin (Diperbarui dengan properti profil lengkap) ---
    users['adminmt2'] = { 
        password: 'adminpass', 
        role: 'admin', 
        nama: 'Kepala Tata Usaha', 
        nip: '987654321',  // Ditambahkan
        gender: 'Laki-laki', // Ditambahkan
        phone: '08121212121', // Ditambahkan
        photo: ''  // Ditambahkan
    };
    
    // Admin Operator Sekolah
    users['operator'] = { 
        password: 'op123', 
        role: 'admin', 
        nama: 'Operator Sekolah',
        nip: '',
        gender: '',
        phone: '',
        photo: ''
    };


    // Simpan data ini ke Local Storage
    localStorage.setItem('userDatabase', JSON.stringify(users));
    console.log("Database pengguna awal telah disiapkan di Local Storage.");
}

// Jalankan fungsi setup ini
setupInitialUsers();