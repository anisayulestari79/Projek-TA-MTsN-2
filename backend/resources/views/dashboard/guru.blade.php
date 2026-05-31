<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Pelanggaran Poin Siswa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        @include('dashboard.styles')
    </style>
</head>
<body>
    <div class="app">
        @include('dashboard.sidebar', ['role' => 'guru'])
        <main>
            @include('dashboard.header', ['user' => $user ?? session('user', [])])
            @include('dashboard.views.guru', ['user' => $user ?? session('user', [])])
        </main>
    </div>
    @include('dashboard.modals.guru')
    <script>
        const API_BASE_URL = '{{ url('/api') }}';
        const AUTH_TOKEN = '{{ session('auth_token') }}';
        const USER = @json(session('user'));
        const CSRF_TOKEN = '{{ csrf_token() }}';
    </script>
    <script>
        // Basic navigation
        function toggleSidebarView(viewId) {
            document.querySelectorAll('.view').forEach(v => v.classList.add('hidden'));
            const view = document.getElementById('view-' + viewId);
            if (view) view.classList.remove('hidden');
            document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
            const activeBtn = document.querySelector(`[data-view="${viewId}"]`);
            if (activeBtn) {
                activeBtn.classList.add('active');
                const titleText = activeBtn.textContent.trim().split(' ').slice(1).join(' ');
                document.getElementById('page-title').textContent = titleText;
            }
        }
        
        // Function to convert file to base64
        function readURL(input) {
            return new Promise((resolve) => {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        resolve(e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                } else {
                    resolve(null);
                }
            });
        }

        // Function to show info modal
        function showInfoModal(title, message) {
            const modal = document.getElementById('infoModal');
            if (modal) {
                document.getElementById('infoModalTitle').textContent = title;
                document.getElementById('infoModalMessage').textContent = message;
                modal.style.display = 'flex';
            } else {
                alert(title + ': ' + message);
            }
        }

        // Function to close info modal
        function closeInfoModal() {
            const modal = document.getElementById('infoModal');
            if (modal) {
                modal.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.nav-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (btn.dataset.view) {
                        toggleSidebarView(btn.dataset.view);
                    }
                });
            });
            
            const profileDropdown = document.querySelector('.profile-dropdown');
            const profileButton = document.getElementById('profileButton');
            if (profileButton) {
                profileButton.addEventListener('click', function(event) {
                    event.stopPropagation();
                    profileDropdown.classList.toggle('active');
                });
            }
            document.addEventListener('click', function(e) {
                if (profileDropdown && !profileDropdown.contains(e.target)) {
                    profileDropdown.classList.remove('active');
                }
            });

            // Edit Profile functionality
            const profileView = document.getElementById('profileView');
            const profileForm = document.getElementById('profileForm');
            const btnEdit = document.getElementById('btnEditProfile');
            const btnCancel = document.getElementById('btnCancelEdit');

            if (btnEdit && profileView && profileForm) {
                btnEdit.addEventListener('click', () => {
                    profileView.style.display = 'none';
                    profileForm.style.display = 'block';
                    window.scrollTo({top: 0, behavior: 'smooth'});
                });
            }

            if (btnCancel && profileView && profileForm) {
                btnCancel.addEventListener('click', () => {
                    profileForm.style.display = 'none';
                    profileView.style.display = 'block';
                });
            }

            // Handle profile form submit
            if (profileForm) {
                profileForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const editPhoto = document.getElementById('editPhoto');
                    const editNama = document.getElementById('editNama');
                    const editGender = document.getElementById('editGender');
                    const editPhone = document.getElementById('editPhone');
                    
                    if (!editNama) {
                        showInfoModal('Error', 'Nama wajib diisi');
                        return;
                    }

                    try {
                        // Create FormData for file upload
                        const formData = new FormData();
                        formData.append('name', editNama.value.trim());
                        formData.append('gender', editGender.value || '');
                        formData.append('phone', editPhone.value.trim() || '');
                        
                        if (editPhoto && editPhoto.files && editPhoto.files[0]) {
                            formData.append('photo', editPhoto.files[0]);
                        }

                        // Send to web route (not API directly)
                        const response = await fetch('{{ route("profile.update") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': CSRF_TOKEN,
                                'Accept': 'application/json',
                            },
                            body: formData
                        });

                        const data = await response.json();

                        if (response.ok) {
                            showInfoModal('Berhasil', 'Profil berhasil diperbarui!');
                            
                            // Update foto profil di card jika ada
                            if (data.data && data.data.photo) {
                                const mainProfilePic = document.getElementById('mainProfilePic');
                                if (mainProfilePic) {
                                    mainProfilePic.src = data.data.photo;
                                }
                                const profilePhoto = document.getElementById('profilePhoto');
                                if (profilePhoto) {
                                    profilePhoto.src = data.data.photo;
                                }
                            }
                            
                            // Update nama di card
                            if (data.data && data.data.name) {
                                const profileNama = document.getElementById('profileNama');
                                if (profileNama) {
                                    profileNama.textContent = data.data.name;
                                }
                                const profileName = document.getElementById('profileName');
                                if (profileName) {
                                    profileName.textContent = data.data.name;
                                }
                            }
                            
                            // Update detail profil
                            if (data.data) {
                                const profileGender = document.getElementById('profileGender');
                                if (profileGender && data.data.gender) {
                                    profileGender.textContent = data.data.gender;
                                }
                                const profilePhone = document.getElementById('profilePhone');
                                if (profilePhone && data.data.phone) {
                                    profilePhone.textContent = data.data.phone;
                                }
                            }
                            
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            const errorMsg = data.message || data.error || 'Gagal memperbarui profil';
                            showInfoModal('Error', errorMsg);
                        }
                    } catch (error) {
                        console.error('Error updating profile:', error);
                        showInfoModal('Error', 'Terjadi kesalahan: ' + error.message);
                    }
                });
            }

            // Close info modal button
            const closeInfoModalBtn = document.getElementById('closeInfoModalButton');
            if (closeInfoModalBtn) {
                closeInfoModalBtn.addEventListener('click', closeInfoModal);
            }

            // Handle "Edit Profil" button in dropdown
            const editProfileDropdownBtn = document.querySelector('.profile-dropdown-content button[data-view="profile"]');
            if (editProfileDropdownBtn) {
                editProfileDropdownBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const profileDropdown = document.querySelector('.profile-dropdown');
                    if (profileDropdown) {
                        profileDropdown.classList.remove('active');
                    }
                    toggleSidebarView('profile');
                });
            }

            // Load data when view changes
            document.querySelectorAll('.nav-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (btn.dataset.view === 'data-siswa') {
                        loadSiswa();
                    } else if (btn.dataset.view === 'poin') {
                        loadPoinData();
                    } else if (btn.dataset.view === 'dashboard') {
                        loadDashboardData();
                    }
                });
            });

            // Initial load
            loadSiswa();
            loadPoinData();
            loadDashboardData();
        });

        // ========== DASHBOARD ==========
        let dashboardSiswaData = [];

        async function loadDashboardData() {
            try {
                const response = await fetch(API_BASE_URL + '/siswa', {
                    headers: {
                        'Authorization': 'Bearer ' + AUTH_TOKEN,
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    dashboardSiswaData = data.data || [];
                    populateDashboardFilters(dashboardSiswaData);
                    // Apply filter if search term exists, otherwise show all
                    filterData();
                    setupPoinSort();
                } else {
                    console.error('Error loading dashboard data:', data.message);
                }
            } catch (error) {
                console.error('Error loading dashboard data:', error);
            }
        }

        function renderDashboardPoin(siswaList) {
            const tbody = document.querySelector('#tbl-poin-keseluruhan tbody');
            if (!tbody) return;

            tbody.innerHTML = '';
            siswaList.forEach(siswa => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${siswa.nisn}</td>
                    <td>${siswa.nama}</td>
                    <td>${siswa.kelas}</td>
                    <td>${siswa.poin || 0}</td>
                `;
                tbody.appendChild(tr);
            });
            
            // Setup sort after render
            setTimeout(() => setupPoinSort(), 100);
        }

        function populateDashboardFilters(siswaList) {
            const filterTingkat = document.getElementById('filter-tingkat-dashboard');
            const filterKelas = document.getElementById('filter-kelas-dashboard');
            
            if (!filterTingkat || !filterKelas) return;

            // Populate tingkat
            const tingkatSet = new Set();
            siswaList.forEach(s => {
                if (s.kelas) {
                    const tingkat = s.kelas.split('.')[0];
                    if (tingkat) tingkatSet.add(tingkat);
                }
            });
            
            filterTingkat.innerHTML = '<option value="">Semua Tingkat</option>';
            Array.from(tingkatSet).sort().forEach(tingkat => {
                const option = document.createElement('option');
                option.value = tingkat;
                option.textContent = tingkat;
                filterTingkat.appendChild(option);
            });

            // Initial populate kelas (all classes)
            updateDashboardKelasFilter(siswaList);

            // Add event listener for tingkat change
            filterTingkat.onchange = function() {
                updateDashboardKelasFilter(siswaList);
                filterData(); // Auto filter when tingkat changes
            };
        }

        function updateDashboardKelasFilter(siswaList) {
            const filterTingkat = document.getElementById('filter-tingkat-dashboard');
            const filterKelas = document.getElementById('filter-kelas-dashboard');
            const selectedTingkat = filterTingkat?.value || '';
            const currentKelasValue = filterKelas?.value || '';
            
            if (!filterKelas) return;

            // Populate kelas based on selected tingkat
            const kelasSet = new Set();
            siswaList.forEach(s => {
                if (s.kelas) {
                    if (selectedTingkat) {
                        // Only show classes from selected tingkat
                        if (s.kelas.startsWith(selectedTingkat + '.')) {
                            kelasSet.add(s.kelas);
                        }
                    } else {
                        // Show all classes if no tingkat selected
                        kelasSet.add(s.kelas);
                    }
                }
            });
            
            filterKelas.innerHTML = '<option value="">Semua Kelas</option>';
            Array.from(kelasSet).sort().forEach(kelas => {
                const option = document.createElement('option');
                option.value = kelas;
                option.textContent = kelas;
                filterKelas.appendChild(option);
            });
            
            // Reset kelas selection if current value is not in the new list
            if (currentKelasValue && !Array.from(kelasSet).includes(currentKelasValue)) {
                filterKelas.value = '';
            } else if (currentKelasValue && Array.from(kelasSet).includes(currentKelasValue)) {
                filterKelas.value = currentKelasValue;
            }
        }

        window.filterData = function() {
            const searchName = document.getElementById('filter-nama')?.value.toLowerCase().trim() || '';
            const filterTingkat = document.getElementById('filter-tingkat-dashboard')?.value || '';
            const filterKelas = document.getElementById('filter-kelas-dashboard')?.value || '';
            
            let filtered = dashboardSiswaData.filter(siswa => {
                const nameMatch = !searchName || siswa.nama.toLowerCase().includes(searchName);
                const tingkatMatch = !filterTingkat || (siswa.kelas && siswa.kelas.startsWith(filterTingkat + '.'));
                const kelasMatch = !filterKelas || siswa.kelas === filterKelas;
                return nameMatch && tingkatMatch && kelasMatch;
            });
            
            renderDashboardPoin(filtered);
        };

        // Sort functionality for poin column
        let poinSortDirection = 'asc';
        function setupPoinSort() {
            const sortableHeader = document.querySelector('.sortable-header[data-sort-by="poin"]');
            if (sortableHeader && !sortableHeader.dataset.sortSetup) {
                sortableHeader.dataset.sortSetup = 'true';
                sortableHeader.style.cursor = 'pointer';
                sortableHeader.addEventListener('click', function() {
                    const sorted = [...dashboardSiswaData].sort((a, b) => {
                        const poinA = a.poin || 0;
                        const poinB = b.poin || 0;
                        return poinSortDirection === 'asc' ? poinA - poinB : poinB - poinA;
                    });
                    poinSortDirection = poinSortDirection === 'asc' ? 'desc' : 'asc';
                    renderDashboardPoin(sorted);
                    // Reset setup flag to allow re-setup
                    sortableHeader.dataset.sortSetup = 'false';
                    setTimeout(() => setupPoinSort(), 100);
                });
            }
        }

        // ========== SISWA CRUD (Guru can view only) ==========
        async function loadSiswa() {
            try {
                const response = await fetch(API_BASE_URL + '/siswa', {
                    headers: {
                        'Authorization': 'Bearer ' + AUTH_TOKEN,
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    allSiswaDataForFilter = data.data || []; // Store all data for filtering
                    populateFilterOptions(allSiswaDataForFilter);
                    // Apply filter if search term exists, otherwise show all
                    filterSiswa();
                    // Update dashboard data too
                    dashboardSiswaData = data.data || [];
                    renderDashboardPoin(dashboardSiswaData);
                } else {
                    console.error('Error loading siswa:', data.message);
                }
            } catch (error) {
                console.error('Error loading siswa:', error);
            }
        }

        let allSiswaDataForFilter = []; // Store all siswa data for filter

        function populateFilterOptions(siswaList) {
            allSiswaDataForFilter = siswaList; // Store for later use
            const filterTingkat = document.getElementById('filter-tingkat');
            const filterKelas = document.getElementById('filter-kelas-siswa');
            
            if (!filterTingkat || !filterKelas) return;

            // Populate tingkat
            const tingkatSet = new Set();
            siswaList.forEach(s => {
                if (s.kelas) {
                    const tingkat = s.kelas.split('.')[0];
                    if (tingkat) tingkatSet.add(tingkat);
                }
            });
            
            filterTingkat.innerHTML = '<option value="">Semua Tingkat</option>';
            Array.from(tingkatSet).sort().forEach(tingkat => {
                const option = document.createElement('option');
                option.value = tingkat;
                option.textContent = tingkat;
                filterTingkat.appendChild(option);
            });

            // Initial populate kelas (all classes)
            updateKelasFilter();

            // Add event listener for tingkat change
            filterTingkat.onchange = function() {
                updateKelasFilter();
                filterSiswa(); // Auto filter when tingkat changes
            };
        }

        function updateKelasFilter() {
            const filterTingkat = document.getElementById('filter-tingkat');
            const filterKelas = document.getElementById('filter-kelas-siswa');
            const selectedTingkat = filterTingkat?.value || '';
            const currentKelasValue = filterKelas?.value || '';
            
            if (!filterKelas) return;

            // Populate kelas based on selected tingkat
            const kelasSet = new Set();
            allSiswaDataForFilter.forEach(s => {
                if (s.kelas) {
                    if (selectedTingkat) {
                        // Only show classes from selected tingkat
                        if (s.kelas.startsWith(selectedTingkat + '.')) {
                            kelasSet.add(s.kelas);
                        }
                    } else {
                        // Show all classes if no tingkat selected
                        kelasSet.add(s.kelas);
                    }
                }
            });
            
            filterKelas.innerHTML = '<option value="">Semua Kelas</option>';
            Array.from(kelasSet).sort().forEach(kelas => {
                const option = document.createElement('option');
                option.value = kelas;
                option.textContent = kelas;
                filterKelas.appendChild(option);
            });
            
            // Reset kelas selection if current value is not in the new list
            if (currentKelasValue && !Array.from(kelasSet).includes(currentKelasValue)) {
                filterKelas.value = '';
            } else if (currentKelasValue && Array.from(kelasSet).includes(currentKelasValue)) {
                filterKelas.value = currentKelasValue;
            }
        }

        window.filterSiswa = function() {
            const searchTerm = document.getElementById('search-siswa')?.value.toLowerCase().trim() || '';
            const tingkat = document.getElementById('filter-tingkat')?.value;
            const kelas = document.getElementById('filter-kelas-siswa')?.value;
            
            // Use stored data instead of fetching again
            let filtered = allSiswaDataForFilter || [];
            
            // Apply search filter (nama or NISN)
            if (searchTerm) {
                filtered = filtered.filter(s => 
                    (s.nama && s.nama.toLowerCase().includes(searchTerm)) ||
                    (s.nisn && s.nisn.toLowerCase().includes(searchTerm))
                );
            }
            
            // Apply tingkat filter
            if (tingkat) {
                filtered = filtered.filter(s => s.kelas && s.kelas.startsWith(tingkat));
            }
            
            // Apply kelas filter
            if (kelas) {
                filtered = filtered.filter(s => s.kelas === kelas);
            }
            
            renderSiswa(filtered);
        };

        function renderSiswa(siswaList) {
            const tbody = document.querySelector('#tbl-siswa tbody');
            if (!tbody) return;

            tbody.innerHTML = '';
            siswaList.forEach(siswa => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${siswa.nisn}</td>
                    <td>${siswa.nama}</td>
                    <td>${siswa.kelas}</td>
                    <td>${siswa.kontak_ortu || '-'}</td>
                    <td>${siswa.poin || 0}</td>
                    <td>
                        <button class="btn-edit-siswa" onclick="viewSiswa('${siswa.nisn}')">Lihat</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        window.viewSiswa = async function(nisn) {
            try {
                const response = await fetch(API_BASE_URL + '/siswa/' + nisn, {
                    headers: {
                        'Authorization': 'Bearer ' + AUTH_TOKEN,
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    const siswa = data.data;
                    // Show siswa profile modal
                    const modal = document.getElementById('siswaProfileModal');
                    const picContainer = document.getElementById('siswaProfilePic');
                    const namaEl = document.getElementById('siswaProfileNama');
                    const nisnEl = document.getElementById('siswaProfileNisn');
                    const jkEl = document.getElementById('siswaProfileJk');
                    const kelasEl = document.getElementById('siswaProfileKelas');
                    const poinEl = document.getElementById('siswaProfilePoin');
                    const kontakEl = document.getElementById('siswaProfileKontak');
                    
                    if (modal) {
                        // Set photo
                        if (picContainer) {
                            if (siswa.photo) {
                                picContainer.innerHTML = `<img src="${siswa.photo}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;" alt="Foto Siswa">`;
                            } else {
                                const initial = siswa.nama ? siswa.nama.charAt(0).toUpperCase() : 'S';
                                picContainer.innerHTML = `<div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 3rem; font-weight: 700; color: white;">${initial}</div>`;
                            }
                        }
                        
                        if (namaEl) namaEl.textContent = siswa.nama || '-';
                        if (nisnEl) nisnEl.textContent = siswa.nisn || '-';
                        if (jkEl) jkEl.textContent = siswa.jk || '-';
                        if (kelasEl) kelasEl.textContent = siswa.kelas || '-';
                        if (poinEl) poinEl.textContent = siswa.poin || 0;
                        if (kontakEl) kontakEl.textContent = siswa.kontak_ortu || '-';
                        
                        modal.style.display = 'flex';
                    }
                } else {
                    showInfoModal('Error', data.message || 'Gagal memuat data siswa');
                }
            } catch (error) {
                console.error('Error loading siswa:', error);
                showInfoModal('Error', 'Terjadi kesalahan: ' + error.message);
            }
        };

        window.closeSiswaProfileModal = function() {
            const modal = document.getElementById('siswaProfileModal');
            if (modal) {
                modal.style.display = 'none';
            }
        };

        // ========== POIN SISWA ==========
        let allSiswaData = []; // Store all siswa data for autocomplete
        let allPelanggaranData = []; // Store all pelanggaran data

        async function loadPoinData() {
            // Load siswa data for autocomplete
            try {
                const response = await fetch(API_BASE_URL + '/siswa', {
                    headers: {
                        'Authorization': 'Bearer ' + AUTH_TOKEN,
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    allSiswaData = data.data || [];
                    setupSiswaAutocomplete();
                    populatePoinTingkatKelas();
                }
            } catch (error) {
                console.error('Error loading siswa for poin:', error);
            }

            // Load pelanggaran data
            try {
                const response = await fetch(API_BASE_URL + '/pelanggaran', {
                    headers: {
                        'Authorization': 'Bearer ' + AUTH_TOKEN,
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    allPelanggaranData = data.data || [];
                    setupPelanggaranAutocomplete();
                }
            } catch (error) {
                console.error('Error loading pelanggaran:', error);
            }

            // Load riwayat poin
            loadRiwayatPoin();
        }

        function populatePoinTingkatKelas() {
            const pTingkat = document.getElementById('p_tingkat');
            const pKelas = document.getElementById('p_kelas_poin');
            
            if (!pTingkat || !pKelas) return;

            // Populate tingkat
            const tingkatSet = new Set();
            allSiswaData.forEach(s => {
                if (s.kelas) {
                    const tingkat = s.kelas.split('.')[0];
                    if (tingkat) tingkatSet.add(tingkat);
                }
            });

            pTingkat.innerHTML = '<option value="">Pilih Tingkat</option>';
            Array.from(tingkatSet).sort().forEach(tingkat => {
                const option = document.createElement('option');
                option.value = tingkat;
                option.textContent = tingkat;
                pTingkat.appendChild(option);
            });

            // Initial populate kelas (empty, wait for tingkat selection)
            pKelas.innerHTML = '<option value="">Pilih Kelas</option>';

            // Handle tingkat change
            pTingkat.onchange = function() {
                const tingkat = pTingkat.value;
                pKelas.innerHTML = '<option value="">Pilih Kelas</option>';
                
                if (tingkat) {
                    const kelasSet = new Set();
                    allSiswaData.forEach(s => {
                        // Only show classes that start with tingkat + '.' (e.g., "IX." not just "IX")
                        if (s.kelas && s.kelas.startsWith(tingkat + '.')) {
                            kelasSet.add(s.kelas);
                        }
                    });

                    Array.from(kelasSet).sort().forEach(kelas => {
                        const option = document.createElement('option');
                        option.value = kelas;
                        option.textContent = kelas;
                        pKelas.appendChild(option);
                    });
                } else {
                    // If no tingkat selected, show all classes
                    const kelasSet = new Set();
                    allSiswaData.forEach(s => {
                        if (s.kelas) kelasSet.add(s.kelas);
                    });
                    Array.from(kelasSet).sort().forEach(kelas => {
                        const option = document.createElement('option');
                        option.value = kelas;
                        option.textContent = kelas;
                        pKelas.appendChild(option);
                    });
                }
            };
        }

        function setupSiswaAutocomplete() {
            const namaInput = document.getElementById('p_nama');
            const namaList = document.getElementById('nama-list');
            const nisnInput = document.getElementById('p_nisn');
            const pTingkat = document.getElementById('p_tingkat');
            const pKelas = document.getElementById('p_kelas_poin');

            if (!namaInput || !namaList || !nisnInput) return;

            // Remove existing event listener if any
            const newNamaInput = namaInput.cloneNode(true);
            namaInput.parentNode.replaceChild(newNamaInput, namaInput);
            const newNamaInputEl = document.getElementById('p_nama');

            newNamaInputEl.addEventListener('input', function() {
                const query = newNamaInputEl.value.toLowerCase().trim();
                namaList.innerHTML = '';

                if (query.length > 0) {
                    const filtered = allSiswaData.filter(s => 
                        s.nama.toLowerCase().includes(query) || 
                        s.nisn.toLowerCase().includes(query)
                    );

                    if (filtered.length > 0) {
                        namaList.style.display = 'block';
                        filtered.forEach(siswa => {
                            const div = document.createElement('div');
                            div.textContent = `${siswa.nama} (${siswa.nisn}) - ${siswa.kelas}`;
                            div.style.cursor = 'pointer';
                            div.style.padding = '10px 12px';
                            div.style.borderBottom = '1px solid #eee';
                            div.addEventListener('mouseenter', function() {
                                this.style.backgroundColor = '#f0f0f0';
                            });
                            div.addEventListener('mouseleave', function() {
                                this.style.backgroundColor = 'transparent';
                            });
                            div.addEventListener('click', function() {
                                const namaInputEl = document.getElementById('p_nama');
                                const nisnInputEl = document.getElementById('p_nisn');
                                const pTingkatEl = document.getElementById('p_tingkat');
                                const pKelasEl = document.getElementById('p_kelas_poin');
                                
                                if (namaInputEl) namaInputEl.value = siswa.nama;
                                if (nisnInputEl) nisnInputEl.value = siswa.nisn;
                                
                                // Auto-fill tingkat dan kelas
                                if (siswa.kelas) {
                                    const [tingkat, ...rest] = siswa.kelas.split('.');
                                    if (tingkat && pTingkatEl) {
                                        pTingkatEl.value = tingkat;
                                        
                                        // Trigger change event untuk populate kelas
                                        if (pTingkatEl.onchange) {
                                            pTingkatEl.onchange();
                                        }
                                        
                                        setTimeout(() => {
                                            if (pKelasEl) {
                                                pKelasEl.value = siswa.kelas;
                                            }
                                        }, 200);
                                    }
                                }
                                
                                namaList.style.display = 'none';
                            });
                            namaList.appendChild(div);
                        });
                    } else {
                        namaList.style.display = 'none';
                    }
                } else {
                    namaList.style.display = 'none';
                }
            });

            // Close autocomplete when clicking outside
            document.addEventListener('click', function(e) {
                const namaInputEl = document.getElementById('p_nama');
                const namaListEl = document.getElementById('nama-list');
                if (namaInputEl && namaListEl && !namaInputEl.contains(e.target) && !namaListEl.contains(e.target)) {
                    namaListEl.style.display = 'none';
                }
            });
        }

        function setupPelanggaranAutocomplete() {
            const searchInput = document.getElementById('p_search_pelanggaran');
            const pelanggaranList = document.getElementById('pelanggaran-list');
            const jenisPelanggaranSelect = document.getElementById('p_jenis_pelanggaran');
            const jumlahPoinInput = document.getElementById('p_jumlah_poin');
            const keteranganInput = document.getElementById('p_keterangan_pelanggaran');

            if (!searchInput || !pelanggaranList || !jenisPelanggaranSelect) return;

            // Update hidden fields when user types manually
            searchInput.addEventListener('input', function() {
                const query = searchInput.value.toLowerCase().trim();
                pelanggaranList.innerHTML = '';

                // Update keterangan from manual input
                if (keteranganInput && query.length > 0) {
                    keteranganInput.value = searchInput.value;
                }

                if (query.length > 0) {
                    const filtered = allPelanggaranData.filter(p => 
                        p.jenis.toLowerCase().includes(query)
                    );

                    // Jika ada pelanggaran yang cocok persis, isi jumlah poin otomatis
                    const exactMatch = allPelanggaranData.find(p => 
                        p.jenis.toLowerCase() === query
                    );
                    if (exactMatch && jumlahPoinInput) {
                        jumlahPoinInput.value = exactMatch.skor_poin;
                    }

                    if (filtered.length > 0) {
                        pelanggaranList.style.display = 'block';
                        filtered.forEach(pelanggaran => {
                            const div = document.createElement('div');
                            div.textContent = `${pelanggaran.jenis} (${pelanggaran.skor_poin} poin)`;
                            div.style.cursor = 'pointer';
                            div.style.padding = '10px 12px';
                            div.style.borderBottom = '1px solid #eee';
                            div.addEventListener('mouseenter', function() {
                                this.style.backgroundColor = '#f0f0f0';
                            });
                            div.addEventListener('mouseleave', function() {
                                this.style.backgroundColor = 'transparent';
                            });
                            div.addEventListener('click', function() {
                                searchInput.value = pelanggaran.jenis;
                                if (jenisPelanggaranSelect) {
                                    jenisPelanggaranSelect.value = pelanggaran.jenis;
                                }
                                if (jumlahPoinInput) {
                                    jumlahPoinInput.value = pelanggaran.skor_poin;
                                }
                                if (keteranganInput) {
                                    keteranganInput.value = pelanggaran.jenis + (pelanggaran.sanksi ? ' - ' + pelanggaran.sanksi : '');
                                }
                                pelanggaranList.style.display = 'none';
                            });
                            pelanggaranList.appendChild(div);
                        });
                    } else {
                        pelanggaranList.style.display = 'none';
                    }
                } else {
                    pelanggaranList.style.display = 'none';
                    // Clear fields if search is empty
                    if (keteranganInput) {
                        keteranganInput.value = '';
                    }
                    if (jumlahPoinInput) {
                        jumlahPoinInput.value = '';
                    }
                }
            });

            // Close autocomplete when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !pelanggaranList.contains(e.target)) {
                    pelanggaranList.style.display = 'none';
                }
            });
        }

        let allRiwayatPoinData = [];

        async function loadRiwayatPoin() {
            try {
                const response = await fetch(API_BASE_URL + '/poin/riwayat', {
                    headers: {
                        'Authorization': 'Bearer ' + AUTH_TOKEN,
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    allRiwayatPoinData = data.data || [];
                    renderRiwayatPoin(allRiwayatPoinData);
                }
            } catch (error) {
                console.error('Error loading riwayat poin:', error);
            }
        }

        function renderRiwayatPoin(riwayatList) {
            const tbody = document.querySelector('#tbl-poin tbody');
            if (!tbody) return;

            tbody.innerHTML = '';
            riwayatList.forEach(riwayat => {
                const tr = document.createElement('tr');
                const waktu = new Date(riwayat.waktu).toLocaleString('id-ID');
                tr.innerHTML = `
                    <td>${riwayat.nisn}</td>
                    <td>${riwayat.nama}</td>
                    <td>${riwayat.kelas}</td>
                    <td>${riwayat.jenis === 'Tambah' ? '+' : '-'}${riwayat.jumlah}</td>
                    <td>${riwayat.ket || '-'}</td>
                    <td>${waktu}</td>
                    <td>
                        <button class="btn-hapus-riwayat-text" onclick="deleteRiwayatPoin(${riwayat.id})">Hapus</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        window.filterRiwayatPoin = function() {
            const searchInput = document.getElementById('search-riwayat-poin');
            if (!searchInput) {
                renderRiwayatPoin(allRiwayatPoinData);
                return;
            }

            const keyword = searchInput.value.toLowerCase().trim();
            if (!keyword) {
                renderRiwayatPoin(allRiwayatPoinData);
                return;
            }

            const filtered = allRiwayatPoinData.filter(item => {
                const nisn = (item.nisn || '').toString().toLowerCase();
                const nama = (item.nama || '').toLowerCase();
                const kelas = (item.kelas || '').toLowerCase();
                const ket = (item.ket || '').toLowerCase();
                return nisn.includes(keyword) || nama.includes(keyword) || kelas.includes(keyword) || ket.includes(keyword);
            });

            renderRiwayatPoin(filtered);
        };

        window.updatePoin = async function(e) {
            e.preventDefault();
            
            const nisn = document.getElementById('p_nisn')?.value.trim();
            const jumlahInput = document.getElementById('p_jumlah_poin')?.value.trim();
            const ketInput = document.getElementById('p_keterangan_pelanggaran')?.value.trim();
            const searchPelanggaran = document.getElementById('p_search_pelanggaran')?.value.trim();

            // Validasi
            if (!nisn) {
                showInfoModal('Error', 'Silakan pilih nama siswa terlebih dahulu!');
                return;
            }

            // Jika jumlah poin tidak ada, coba ambil dari pelanggaran yang dipilih
            let jumlah = parseInt(jumlahInput);
            if (!jumlah || isNaN(jumlah)) {
                // Cari pelanggaran yang sesuai dengan input
                const pelanggaran = allPelanggaranData.find(p => 
                    p.jenis.toLowerCase() === searchPelanggaran?.toLowerCase()
                );
                if (pelanggaran) {
                    jumlah = pelanggaran.skor_poin;
                    // Update hidden field
                    if (document.getElementById('p_jumlah_poin')) {
                        document.getElementById('p_jumlah_poin').value = jumlah;
                    }
                } else {
                    showInfoModal('Error', 'Jumlah poin wajib diisi! Silakan pilih jenis pelanggaran atau isi manual.');
                    return;
                }
            }

            // Jika keterangan tidak ada, gunakan input pelanggaran
            let ket = ketInput;
            if (!ket || ket.trim() === '') {
                ket = searchPelanggaran || 'Pelanggaran';
                // Update hidden field
                if (document.getElementById('p_keterangan_pelanggaran')) {
                    document.getElementById('p_keterangan_pelanggaran').value = ket;
                }
            }

            if (!ket || ket.trim() === '') {
                showInfoModal('Error', 'Keterangan pelanggaran wajib diisi!');
                return;
            }

            try {
                const response = await fetch(API_BASE_URL + '/poin', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + AUTH_TOKEN,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        nisn: nisn,
                        jumlah: jumlah,
                        ket: ket
                    })
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    showInfoModal('Berhasil', data.message || 'Poin siswa berhasil diupdate!');
                    // Reset form
                    document.getElementById('poinForm').reset();
                    document.getElementById('p_nisn').value = '';
                    document.getElementById('p_jumlah_poin').value = '';
                    document.getElementById('p_keterangan_pelanggaran').value = '';
                    document.getElementById('p_tingkat').value = '';
                    document.getElementById('p_kelas_poin').innerHTML = '<option value="">Pilih Kelas</option>';
                    loadRiwayatPoin();
                    loadSiswa(); // Reload siswa to update poin
                    loadDashboardData(); // Reload dashboard to update poin
                } else {
                    const errorMsg = data.message || data.errors ? JSON.stringify(data.errors || data.message) : 'Gagal mengupdate poin siswa';
                    showInfoModal('Error', errorMsg);
                }
            } catch (error) {
                console.error('Error updating poin:', error);
                showInfoModal('Error', 'Terjadi kesalahan: ' + error.message);
            }
        };

        window.deleteRiwayatPoin = async function(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus riwayat poin ini?')) {
                return;
            }

            try {
                const response = await fetch(API_BASE_URL + '/poin/riwayat/' + id, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + AUTH_TOKEN,
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    showInfoModal('Berhasil', 'Riwayat poin berhasil dihapus!');
                    loadRiwayatPoin();
                    loadSiswa(); // Reload siswa to update poin
                    loadDashboardData(); // Update dashboard too
                } else {
                    showInfoModal('Error', data.message || 'Gagal menghapus riwayat poin');
                }
            } catch (error) {
                console.error('Error deleting riwayat poin:', error);
                showInfoModal('Error', 'Terjadi kesalahan: ' + error.message);
            }
        };
    </script>
</body>
</html>

