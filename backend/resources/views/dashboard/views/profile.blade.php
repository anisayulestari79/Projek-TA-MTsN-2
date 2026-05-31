@php
    $user = $user ?? session('user', []);
@endphp
<section id="view-profile" class="view hidden">
    <div class="profile-card" id="profileView">
        <div class="profile-pic-container">
            @if (isset($user['photo']) && $user['photo'])
                <img src="{{ $user['photo'] }}" class="profile-pic" id="mainProfilePic" alt="Profile Picture">
            @else
                <img src="https://placehold.co/100x100/1abc9c/ffffff?text={{ substr($user['name'] ?? 'U', 0, 1) }}"
                    class="profile-pic" id="mainProfilePic" alt="Profile Picture">
            @endif
        </div>
        <div class="profile-info">
            <h3 id="profileNama">{{ $user['name'] ?? 'Nama Pengguna' }}</h3>
            <p id="profileJabatan">{{ ucfirst($user['role'] ?? 'User') }}</p>
        </div>
        <div class="profile-details">
            <div class="profile-item">
                <i class="icon fas fa-envelope"></i>
                <div class="detail-content">
                    <label>{{ ($user['role'] ?? '') === 'admin' ? 'Username' : 'NIP' }}</label>
                    <p id="profileNIP">{{ $user['username'] ?? ($user['nip'] ?? '-') }}</p>
                </div>
            </div>
            <div class="profile-item">
                <i class="icon fas fa-venus-mars"></i>
                <div class="detail-content">
                    <label>Gender</label>
                    <p id="profileGender">{{ $user['gender'] ?? '-' }}</p>
                </div>
            </div>
            <div class="profile-item">
                <i class="icon fas fa-phone-alt"></i>
                <div class="detail-content">
                    <label>No. Telepon</label>
                    <p id="profilePhone">{{ $user['phone'] ?? '-' }}</p>
                </div>
            </div>
        </div>
        <button class="btn-edit" id="btnEditProfile">Edit Profil</button>
    </div>

    <form id="profileForm" class="profile-form">
        <h3>Edit Profil</h3>
        <div class="profile-item">
            <label>Unggah Foto Profil Baru</label>
            <input type="file" id="editPhoto" accept="image/*">
        </div>
        <div class="profile-item">
            <label>Nama</label>
            <input type="text" id="editNama" value="{{ $user['name'] ?? '' }}" required>
        </div>
        <div class="profile-item">
            <label>{{ ($user['role'] ?? '') === 'admin' ? 'Username' : 'NIP' }}</label>
            <input type="text" id="editNIP" value="{{ $user['username'] ?? ($user['nip'] ?? '') }}" disabled>
        </div>
        <div class="profile-item">
            <label>Gender</label>
            <select id="editGender">
                <option value="">Pilih Gender</option>
                <option value="Laki-laki" {{ ($user['gender'] ?? '') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                </option>
                <option value="Perempuan" {{ ($user['gender'] ?? '') === 'Perempuan' ? 'selected' : '' }}>Perempuan
                </option>
            </select>
        </div>
        <div class="profile-item">
            <label>No. Telepon</label>
            <input type="tel" id="editPhone" value="{{ $user['phone'] ?? '' }}">
        </div>
        <div class="form-buttons">
            <button type="submit" class="btn-form btn-edit">Simpan Perubahan</button>
            <button type="button" class="btn-form cancel" id="btnCancelEdit">Batal</button>
        </div>
    </form>
</section>
