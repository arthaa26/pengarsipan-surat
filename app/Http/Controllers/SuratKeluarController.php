<?php

namespace App\Http\Controllers;

use App\Models\Surat_keluar;
use Illuminate\Http\Request;

class SuratKeluarController extends Controller
{
    public function index()
    {
        $suratKeluar = Surat_keluar::all();
        return view('surats.index', compact('suratKeluar'));
    }

    public function create()
    {
        return view('surats.create');
    }

    public function store(Request $request)
    {
        Surat_keluar::create($request->all());
        return redirect()->route('surat-keluar.index');
    }

    public function show($id)
    {
        $surat = Surat_keluar::findOrFail($id);
        return view('surats.show', compact('surat'));
    }

    public function edit($id)
    {
        $surat = Surat_keluar::findOrFail($id);
        return view('surats.edit', compact('surat'));
    }

    public function update(Request $request, $id)
    {
        $surat = Surat_keluar::findOrFail($id);
        $surat->update($request->all());
        return redirect()->route('surat-keluar.index');
    }

    public function destroy($id)
    {
        Surat_keluar::destroy($id);
        return redirect()->route('surat-keluar.index');
    }
}