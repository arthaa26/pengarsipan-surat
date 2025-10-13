<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use Illuminate\Http\Request;

class SuratMasukController extends Controller
{
    public function index()
    {
        $suratMasuk = SuratMasuk::all();
        return view('surats.index', compact('suratMasuk'));
    }

    public function create()
    {
        return view('surats.create');
    }

    public function store(Request $request)
    {
        SuratMasuk::create($request->all());
        return redirect()->route('surat-masuk.index');
    }

    public function show($id)
    {
        $surat = SuratMasuk::findOrFail($id);
        return view('surats.show', compact('surat'));
    }

    public function edit($id)
    {
        $surat = SuratMasuk::findOrFail($id);
        return view('surats.edit', compact('surat'));
    }

    public function update(Request $request, $id)
    {
        $surat = SuratMasuk::findOrFail($id);
        $surat->update($request->all());
        return redirect()->route('surat-masuk.index');
    }

    public function destroy($id)
    {
        SuratMasuk::destroy($id);
        return redirect()->route('surat-masuk.index');
    }
}
