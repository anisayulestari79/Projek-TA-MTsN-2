@extends('layouts.app')

@section('title', 'Daftar Akun - Sistem Penilaian Poin Siswa - MTS Negeri 2 Kota Banjarmasin')

@push('styles')
<style>
    body {
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        color: #333;
        background-image: url('https://mtsn2kotabjm.sch.id/asset/foto_berita/WhatsApp_Image_2024-10-22_at_17_58_26.png');
        background-size: cover;
        background-position: center;
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
    
    .container {
        text-align: center;
        position: relative;
        z-index: 10;
    }

    .header {
        color: white;
        margin-bottom: 20px;
    }

    .header h1, .header h2 {
        margin: 5px 0;
    }

    .register-box {
        background-color: rgba(255, 255, 255, 0.5);
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        width: 350px;
        text-align: center;
        backdrop-filter: blur(10px);
    }

    .logo {
        width: 100px;
        margin-bottom: 25px;
    }

    .input-group {
        margin-bottom: 20px;
        position: relative;
    }

    .input-group input {
        width: 100%;
        padding: 12px 20px;
        border: none;
        border-radius: 50px;
        background-color: rgba(255, 255, 255, 0.85);
        font-size: 16px;
        box-sizing: border-box;
    }
    
    .input-group input:focus {
        outline: none;
        background-color: #dbe4f6;
    }
    
    .input-group .btn-register {
        background: linear-gradient(90deg, #1abc9c, #16a085);
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        padding: 12px 20px;
        border: none;
        border-radius: 50px;
    }

    .input-group .btn-register:hover {
        background: linear-gradient(90deg, #16a085, #1abc9c);
    }

    .login-link {
        font-size: 14px;
        margin-top: 15px;
        color: white;
    }

    .login-link a {
        color: #3498db;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .login-link a:hover {
        color: #2980b9;
    }
    
    .toggle-password {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        width: 20px;
        height: 20px;
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

    @if ($errors->any())
    .error-message {
        display: block;
        text-align: center;
        margin-top: 20px;
        color: red;
    }
    @endif
</style>
@endpush

@section('content')
<div class="container">
    <div class="header">
        <h1>SISTEM PELANGGARAN POIN SISWA</h1>
        <h2>MTsN 2 KOTA BANJARMASIN</h2>
    </div>
    
    <div class="register-box">
        <img src="https://ppdb.mtsn2kotabjm.sch.id/img/logo.png" alt="Logo Kemenag" class="logo">
        
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 20px; text-align: left;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success">
            <h3 style="color: #1abc9c; margin: 0 0 10px 0;">Pendaftaran Berhasil!</h3>
            <p style="color: #333; margin: 0;">{{ session('success') }}</p>
        </div>
        @endif

        <form id="registerForm" action="{{ route('register.submit') }}" method="POST">
            @csrf
            <div class="input-group">
                <input type="text" name="name" placeholder="Masukkan Nama Lengkap" value="{{ old('name') }}" required>
            </div>
            <div class="input-group">
                <input type="email" name="email" placeholder="Masukkan Email" value="{{ old('email') }}" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Masukkan Kata Sandi" id="passwordInput1" required>
                <svg class="toggle-password" id="togglePassword1" fill="#888" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 144a110.18 110.18 0 0 0-33.8 5.46c-13.62 4.24-27.42 6.64-41.52 6.64-28.52 0-56.1-5.73-82.26-16.73a.87.87 0 0 1-.22-.09A111.41 111.41 0 0 0 0 288c0 47.92 18.29 93.18 51.27 127.16c49.98 51.57 121.14 77.62 195.14 77.62c74 0 145.16-26.05 195.14-77.62c32.98-33.98 51.27-79.24 51.27-127.16a111.41 111.41 0 0 0-20.7-65.06c-.08.06-.15.12-.22.18c-26.16 11-53.74 16.73-82.26 16.73c-14.1 0-27.9-2.4-41.52-6.64A110.18 110.18 0 0 0 288 144zm0 240a96 96 0 1 1 0-192 96 96 0 0 1 0 192z"/></svg>
            </div>
            <div class="input-group">
                <input type="password" name="password_confirmation" placeholder="Masukkan Ulang Kata Sandi" id="passwordInput2" required>
                <svg class="toggle-password" id="togglePassword2" fill="#888" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 144a110.18 110.18 0 0 0-33.8 5.46c-13.62 4.24-27.42 6.64-41.52 6.64-28.52 0-56.1-5.73-82.26-16.73a.87.87 0 0 1-.22-.09A111.41 111.41 0 0 0 0 288c0 47.92 18.29 93.18 51.27 127.16c49.98 51.57 121.14 77.62 195.14 77.62c74 0 145.16-26.05 195.14-77.62c32.98-33.98 51.27-79.24 51.27-127.16a111.41 111.41 0 0 0-20.7-65.06c-.08.06-.15.12-.22.18c-26.16 11-53.74 16.73-82.26 16.73c-14.1 0-27.9-2.4-41.52-6.64A110.18 110.18 0 0 0 288 144zm0 240a96 96 0 1 1 0-192 96 96 0 0 1 0 192z"/></svg>
            </div>
            <div class="input-group">
                <button type="submit" class="btn-register">Daftar</button>
            </div>
        </form>
        <div class="login-link">
            Sudah punya akun? <a href="{{ route('guru.login') }}">Masuk</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const togglePassword1 = document.getElementById('togglePassword1');
    const passwordInput1 = document.getElementById('passwordInput1');
    const togglePassword2 = document.getElementById('togglePassword2');
    const passwordInput2 = document.getElementById('passwordInput2');

    togglePassword1.addEventListener('click', function() {
        const type = passwordInput1.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput1.setAttribute('type', type);
    });

    togglePassword2.addEventListener('click', function() {
        const type = passwordInput2.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput2.setAttribute('type', type);
    });
</script>
@endpush
@endsection

