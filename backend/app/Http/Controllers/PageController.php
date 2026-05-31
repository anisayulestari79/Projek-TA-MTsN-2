<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function adminLogin()
    {
        return view('admin.admin-login');
    }

    public function guruLogin()
    {
        return view('guru.guru-login');
    }

    public function adminRegister()
    {
        return view('auth.admin-register');
    }

    public function register()
    {
        return view('auth.register');
    }
}
