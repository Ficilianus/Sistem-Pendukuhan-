<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DokumenPenduduk;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'nama_kepala_keluarga' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'rt' => 'required|string|max:10',
            'jenis_dokumen' => 'required|string|in:KTP,KK,Akte Lahir,Foto Rumah,Buku Nikah',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'nullable|date',
            'file' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);


        if ($validated['jenis_dokumen'] === 'KTP') {
            $request->validate([
                'gender' => 'required',
                'tanggal_lahir' => 'required|date',
            ]);
        }

        $file = $request->file('file');

        $namaKepalaKeluarga = preg_replace('/\s+/', '', $validated['nama_kepala_keluarga']);
        $jenis = str_replace(' ', '', $validated['jenis_dokumen']);
        $rt = $validated['rt'];
        $nama = strtolower(preg_replace('/\s+/', '', $validated['nama']));
        $extension = $file->getClientOriginalExtension();

        $filename = "{$namaKepalaKeluarga}_{$jenis}_{$rt}_{$nama}.{$extension}";

        $file->storeAs('public/dokumen', $filename);


        DokumenPenduduk::create([
            'nama_kepala_keluarga' => $validated['nama_kepala_keluarga'],
            'nama' => $validated['nama'],
            'rt' => $validated['rt'],
            'jenis_dokumen' => $validated['jenis_dokumen'],
            'gender' => $validated['jenis_dokumen'] === 'KTP' ? $validated['gender'] : null,
            'tanggal_lahir' => $validated['jenis_dokumen'] === 'KTP' ? $validated['tanggal_lahir'] : null,
            'nama_file' => $filename,
        ]);


        return redirect()->back()->with('success', 'Data berhasil disimpan.');
    }
    public function index()
    {
        $dokumenKK = DokumenPenduduk::where('jenis_dokumen', 'KK')->get();
        return view('data', compact('dokumenKK'));
    }
}
