<?php

namespace App\Http\Controllers;

use App\Models\Kirim_surat;
use Illuminate\Http\Request;

class KirimSuratController extends Controller
{
    public function index()
    {
        $kirimSurat = Kirim_surat::all();
        return view('surats.index', compact('kirimSurat'));
    }

    public function create()
    {
        return view('surats.create');
    }

    public function store(Request $request)
    {
        Kirim_surat::create($request->all());
        return redirect()->route('kirim-surat.index');
    }

    public function show($id)
    {
        $surat = Kirim_surat::findOrFail($id);
        return view('surats.show', compact('surat'));
    }

    public function edit($id)
    {
        $surat = Kirim_surat::findOrFail($id);
        return view('surats.edit', compact('surat'));
    }

    public function update(Request $request, $id)
    {
        $surat = Kirim_surat::findOrFail($id);
        $surat->update($request->all());
        return redirect()->route('kirim-surat.index');
    }

    public function destroy($id)
    {
        Kirim_surat::destroy($id);
        return redirect()->route('kirim-surat.index');
    }
}