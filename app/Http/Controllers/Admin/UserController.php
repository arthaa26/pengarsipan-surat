<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.manajemen_surat.manajemen_user');
    }

    public function daftarSuratAdmin()
    {
        return view('admin.daftarsurat.index');
    }
}