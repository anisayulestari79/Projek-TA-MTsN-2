@extends('layouts.app')

@section('title', 'Daftar Admin - MTsN 2 Kota Banjarmasin')

@push('styles')
<style>
    :root {
        --primary-green: #16a085;
        --dark-green: #16a085;
        --home-link-color: #3498db; 
        --text-color: #333;
    }

    body {
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center; 
        height: 100vh;
        color: var(--text-color);
        background-image: url('https://mtsn2kotabjm.sch.id/asset/foto_berita/WhatsApp_Image_2024-10-22_at_17_58_26.png');
        background-size: cover;
        background-position: center center;
        position: relative;
    }

    body::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4); 
    }
    
    .main-content {
        position: relative;
        z-index: 10;
        width: 100%;
        max-width: 1200px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .logo-bar {
        position: absolute;
        top: 20px;
        left: 20px;
        display: flex;
        align-items: center;
        z-index: 15;
    }

    .logo-bar img {
        height: 65px;
        margin-right: 10px;
    }

    .header {
        color: white; 
        text-align: center;
        margin-bottom: 25px; 
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); 
    }

    .header h1 {
        font-size: 24px; 
        margin: 5px 0;
        font-weight: 700;
    }

    .header h2 {
        font-size: 16px; 
        margin: 0;
        font-weight: 600;
    }

    .register-box {
        background-color: rgba(255, 255, 255, 0.7); 
        backdrop-filter: blur(10px); 
        padding: 30px 40px; 
        border-radius: 20px; 
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2); 
        width: 100%;
        max-width: 400px; 
        text-align: center;
    }

    .admin-icon {
        width: 70px;
        height: 70px;
        color: var(--primary-green);
        display: inline-flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 30px;
        font-size: 55px;
    }

    .input-group {
        margin-bottom: 20px;
        position: relative;
    }

    .input-group input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 50px; 
        background-color: white;
        font-size: 16px;
        box-sizing: border-box;
        transition: border-color 0.3s;
    }
    
    .input-group input:focus {
        outline: none;
        border-color: var(--primary-green);
    }

    .btn-register {
        background-color: var(--primary-green);
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease;
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 50px; 
        font-size: 16px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
    }

    .btn-register:hover {
        background-color: var(--dark-green);
    }

    .toggle-password {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #888;
    }

    .home-link {
        text-align: center;
        margin-top: 15px;
    }

    .home-link a {
        color: var(--home-link-color);
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
    }

    .home-link a:hover {
        text-decoration: underline;
    }

    .alert {
        padding: 12px;
        margin-bottom: 20px;
        border-radius: 8px;
        font-size: 14px;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
</style>
@endpush

@section('content')
<div class="logo-bar">
    <img src="https://ppdb.mtsn2kotabjm.sch.id/img/logo.png" alt="Logo Kemenag">
    <img src="https://i.ibb.co.com/Psm2GxFN/5ef6453c-3a7b-47dc-a402-dacf0adb575d-removebg-preview-1.png" alt="Logo MTsN 2">
</div>

<div class="main-content">
    <div class="header">
        <h1>Sistem Pelanggaran Poin Siswa</h1>
        <h2>DAFTAR ADMIN</h2>
    </div>
    
    <div class="register-box">
        <div class="admin-icon">
            <i class="fas fa-user-shield"></i> 
        </div>
        
        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 20px; text-align: left;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <form id="adminRegisterForm" action="{{ route('admin.register.submit') }}" method="POST">
            @csrf
            <div class="input-group">
                <input type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
            </div>
            
            <div class="input-group">
                <input type="text" name="username" placeholder="Username" value="{{ old('username') }}" required>
            </div>
            
            <div class="input-group">
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
            </div>
            
            <div class="input-group">
                <input type="password" name="password" placeholder="Kata Sandi" id="passwordInput" required>
                <i class="far fa-eye toggle-password" id="togglePassword"></i>
            </div>
            
            <div class="input-group">
                <input type="password" name="password_confirmation" placeholder="Konfirmasi Kata Sandi" id="passwordConfirmInput" required>
                <i class="far fa-eye toggle-password" id="togglePasswordConfirm"></i>
            </div>
            
            <div class="input-group">
                <button type="submit" class="btn-register">Daftar</button>
            </div>
        </form>

        <div class="home-link">
            <a href="{{ route('admin.login') }}">Sudah punya akun? Masuk</a> | 
            <a href="{{ route('index') }}">Pilih Peran Lain</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('passwordInput');
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    const passwordConfirmInput = document.getElementById('passwordConfirmInput');

    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

    togglePasswordConfirm.addEventListener('click', function() {
        const type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirmInput.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
</script>
@endpush
@endsection

