<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KirimSurat; 
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index()
    {
        
        $allSurat = KirimSurat::orderBy('created_at', 'desc')->get();
        $totalSuratCount = $allSurat->count();
        $suratMasukCount = KirimSurat::whereNotNull('user_id_2')->count();
        $suratKeluarCount = KirimSurat::whereNotNull('user_id_1')->count();
        
        return view('admin.dashboard', [
            'totalSuratCount' => $totalSuratCount,
            'suratMasukCount' => $suratMasukCount,
            'suratKeluarCount' => $suratKeluarCount,
            'suratMasuk' => $allSurat, 
        ]);
    }
}
