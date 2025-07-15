<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DokumenPenduduk;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Support\Str;
use App\Models\Dokumen;

class DokumenController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kepala_keluarga' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'rt' => 'required|string|max:10',
            'jenis_dokumen' => 'required|in:KTP,KK,Akte Lahir,Foto Rumah,Buku Nikah,BPJS',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'nullable|date',
            'status_keluarga' => 'nullable|in:Lengkap,Duda,Janda',
            'file' => 'required|image|max:5120', // max 5MB
        ]);


        if (in_array($validated['jenis_dokumen'], ['KTP', 'Akte Lahir'])) {
            $request->validate([
                'gender' => 'required|in:Laki-laki,Perempuan',
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
            'gender' => in_array($validated['jenis_dokumen'], ['KTP', 'Akte Lahir']) ? $validated['gender'] : null,
            'tanggal_lahir' => in_array($validated['jenis_dokumen'], ['KTP', 'Akte Lahir']) ? $validated['tanggal_lahir'] : null,
            'status_keluarga' => $validated['jenis_dokumen'] === 'KK' ? $validated['status_keluarga'] : null,
            'nama_file' => $filename,
        ]);


        return redirect()->back()->with('success', 'Data berhasil disimpan.');
    }
    public function index(Request $request)
    {
        $search = $request->query('search');
        $filterRT = $request->query('rt');

        $dokumenKK = DokumenPenduduk::select('nama_kepala_keluarga', 'rt')
            ->when($search, function ($query) use ($search) {
                $query->where('nama_kepala_keluarga', 'like', '%' . $search . '%');
            })
            ->when($filterRT, function ($query) use ($filterRT) {
                $query->where('rt', $filterRT);
            })
            ->groupBy('nama_kepala_keluarga', 'rt')
            ->paginate(6); // ✅ Gunakan pagination, bukan get()


        return view('data', compact('dokumenKK', 'search', 'filterRT'));
    }


    public function keluarga(Request $request)
    {
        $namaKK = $request->nama_kepala_keluarga;
        $rt = $request->rt;

        $dataKeluarga = DokumenPenduduk::where('nama_kepala_keluarga', $namaKK)
            ->where('rt', $rt)
            ->orderByRaw("FIELD(jenis_dokumen, 'KK', 'KTP', 'Akte Lahir', 'Foto Rumah', 'Buku Nikah','BPJS')")
            ->get();

        return view('dataKeluarga', compact('dataKeluarga', 'namaKK', 'rt'));
    }

    public function update(Request $request, $id)
    {
        $dokumen = DokumenPenduduk::findOrFail($id);

        $validated = $request->validate([
            'nama_kepala_keluarga' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'rt' => 'required|string|max:10',
            'jenis_dokumen' => 'required|string|in:KTP,KK,Akte Lahir,Foto Rumah,Buku Nikah',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'nullable|date',
            'status_keluarga' => 'nullable|in:Lengkap,Duda,Janda',
            'file' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validated['jenis_dokumen'] === 'KTP') {
            $request->validate([
                'gender' => 'required',
                'tanggal_lahir' => 'required|date',
            ]);
        } else if ($validated['jenis_dokumen'] === 'KK') {
            $request->validate([
                'status_keluarga' => 'required|in:Lengkap,Duda,Janda',
            ]);
        }

        $dokumen->update([
            'nama_kepala_keluarga' => $validated['nama_kepala_keluarga'],
            'nama' => $validated['nama'],
            'rt' => $validated['rt'],
            'jenis_dokumen' => $validated['jenis_dokumen'],
            'gender' => $validated['jenis_dokumen'] === 'KTP' ? $validated['gender'] : null,
            'status_keluarga' => $validated['jenis_dokumen'] === 'KK' ? $validated['status_keluarga'] : null,

            'tanggal_lahir' => $validated['jenis_dokumen'] === 'KTP' ? $validated['tanggal_lahir'] : null,
        ]);

        // Ganti file jika ada yang diupload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $namaKepalaKeluarga = preg_replace('/\s+/', '', $validated['nama_kepala_keluarga']);
            $jenis = str_replace(' ', '', $validated['jenis_dokumen']);
            $rt = $validated['rt'];
            $nama = strtolower(preg_replace('/\s+/', '', $validated['nama']));
            $extension = $file->getClientOriginalExtension();
            $filename = "{$namaKepalaKeluarga}_{$jenis}_{$rt}_{$nama}.{$extension}";

            $file->storeAs('public/dokumen', $filename);
            $dokumen->nama_file = $filename;
            $dokumen->save();
        }

        return redirect()->back()->with('success', 'Data berhasil diperbarui.');
    }
    public function destroy($id)
    {
        $dokumen = DokumenPenduduk::findOrFail($id);

        // Hapus file dari storage jika ada
        if ($dokumen->nama_file && Storage::exists('public/dokumen/' . $dokumen->nama_file)) {
            Storage::delete('public/dokumen/' . $dokumen->nama_file);
        }

        $dokumen->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
    public function download(Request $request)
    {
        $namaKK = $request->nama_kepala_keluarga;
        $rt = $request->rt;

        // Ambil semua file berdasarkan nama KK dan RT
        $dokumens = \App\Models\Dokumen::where('nama_kepala_keluarga', $namaKK)
            ->where('rt', $rt)
            ->get();

        if ($dokumens->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada dokumen untuk diunduh.');
        }

        // Nama zip
        $zipFileName = 'dokumen_' . Str::slug($namaKK . '_' . $rt) . '.zip';
        $zipFilePath = storage_path('app/public/temp/' . $zipFileName);

        // Pastikan direktori temp ada
        if (!Storage::exists('public/temp')) {
            Storage::makeDirectory('public/temp');
        }

        // Buat file zip
        $zip = new ZipArchive;
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($dokumens as $doc) {
                $path = storage_path('app/public/dokumen/' . $doc->nama_file);
                if (file_exists($path)) {
                    $zip->addFile($path, $doc->jenis_dokumen . '_' . $doc->nama_file);
                }
            }
            $zip->close();
        }

        // Kembalikan file zip sebagai response
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }

    public function downloadByRT(Request $request)
    {
        $rt = $request->query('rt');

        if (!$rt) {
            return redirect()->back()->with('error', 'RT tidak ditemukan.');
        }

        $dokumens = Dokumen::where('rt', $rt)->get();

        if ($dokumens->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada dokumen untuk RT tersebut.');
        }

        $zipFileName = 'RT_' . $rt . '_dokumen.zip';
        $zipPath = storage_path('app/public/temp/' . $zipFileName);

        if (!Storage::exists('public/temp')) {
            Storage::makeDirectory('public/temp');
        }

        $zip = new ZipArchive;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($dokumens as $doc) {
                $kkFolder = Str::slug($doc->nama_kepala_keluarga); // nama folder per KK
                $fullPath = storage_path('app/public/dokumen/' . $doc->nama_file);

                if (file_exists($fullPath)) {
                    $zip->addFile($fullPath, "{$rt}/{$kkFolder}/{$doc->jenis_dokumen}_{$doc->nama_file}");
                }
            }

            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
