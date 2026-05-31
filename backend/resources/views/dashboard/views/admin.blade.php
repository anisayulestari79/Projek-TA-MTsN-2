@php
    $user = $user ?? session('user', []);
@endphp
@include('dashboard.views.dashboard')
@include('dashboard.views.data-guru')
@include('dashboard.views.data-siswa')
@include('dashboard.views.poin')
@include('dashboard.views.profile', ['user' => $user])

