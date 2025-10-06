<?php

namespace App\Http\Controllers;

use App\Models\Surat_masuk;
use Illuminate\Http\Request;

class SuratMasukController extends Controller
{
    public function index()
    {
        $suratMasuk = Surat_masuk::all();
        return view('surats.index', compact('suratMasuk'));
    }

    public function create()
    {
        return view('surats.create');
    }

    public function store(Request $request)
    {
        Surat_masuk::create($request->all());
        return redirect()->route('surat-masuk.index');
    }

    public function show($id)
    {
        $surat = Surat_masuk::findOrFail($id);
        return view('surats.show', compact('surat'));
    }

    public function edit($id)
    {
        $surat = Surat_masuk::findOrFail($id);
        return view('surats.edit', compact('surat'));
    }

    public function update(Request $request, $id)
    {
        $surat = Surat_masuk::findOrFail($id);
        $surat->update($request->all());
        return redirect()->route('surat-masuk.index');
    }

    public function destroy($id)
    {
        Surat_masuk::destroy($id);
        return redirect()->route('surat-masuk.index');
    }
}