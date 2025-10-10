<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Memuat View: resources/views/admin/dashboard.blade.php
        return view('admin.dashboard'); 
    }
}