@php
    $user = $user ?? session('user', []);
@endphp
<header class="top">
    <div class="title">
        <h2 id="page-title">Dashboard</h2>
    </div>
    <div class="toolbar">
        <div class="profile-dropdown">
            <button class="profile-button" id="profileButton">
                @if (isset($user['photo']) && $user['photo'])
                    <img id="profilePhoto" src="{{ $user['photo'] }}" alt="Foto Profil" class="profile-photo">
                @else
                    <img id="profilePhoto"
                        src="https://placehold.co/30x30/1abc9c/ffffff?text={{ substr($user['name'] ?? 'U', 0, 1) }}"
                        alt="Foto Profil" class="profile-photo">
                @endif
                <span id="profileName">{{ $user['name'] ?? 'Nama Pengguna' }}</span>
            </button>
            <div class="profile-dropdown-content">
                <button data-view="profile">Edit Profil</button>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" id="logoutButton"
                        style="width: 100%; text-align: left; background: none; border: none; padding: 12px 16px; cursor: pointer; font-family: 'Poppins', sans-serif; font-size: 14px;">Keluar</button>
                </form>
            </div>
        </div>
    </div>
</header>
