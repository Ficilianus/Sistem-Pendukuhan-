<?php

namespace App\Http\Controllers;

use App\Models\DokumenPenduduk;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StatistikController extends Controller
{
    public function index()
    {
        $rtList = ['RT01', 'RT02', 'RT03', 'RT04', 'RT05'];

        $dataKeluarga = [];
        $dataGender = [];
        $dataLansia = [];

        foreach ($rtList as $rt) {
            // === KELUARGA ===
            $keluargaLengkap = DokumenPenduduk::where('rt', $rt)
                ->where('status_keluarga', 'Lengkap')
                ->distinct('nama_kepala_keluarga')->count('nama_kepala_keluarga');

            $keluargaDuda = DokumenPenduduk::where('rt', $rt)
                ->where('status_keluarga', 'Duda')
                ->distinct('nama_kepala_keluarga')->count('nama_kepala_keluarga');

            $keluargaJanda = DokumenPenduduk::where('rt', $rt)
                ->where('status_keluarga', 'Janda')
                ->distinct('nama_kepala_keluarga')->count('nama_kepala_keluarga');

            $dataKeluarga[] = [
                'rt' => $rt,
                'lengkap' => $keluargaLengkap,
                'duda' => $keluargaDuda,
                'janda' => $keluargaJanda,
                'total' => $keluargaLengkap + $keluargaDuda + $keluargaJanda,
            ];

            // === GABUNGKAN KTP DAN AKTE LAHIR ===
            $penduduk = DokumenPenduduk::where('rt', $rt)
                ->whereIn('jenis_dokumen', ['KTP', 'Akte Lahir'])
                ->whereNotNull('nama')
                ->orderByRaw("FIELD(jenis_dokumen, 'KTP', 'Akte Lahir')") // KTP lebih dulu
                ->get();

            // === HAPUS DUPLIKAT BERDASARKAN NAMA ===
            $pendudukUnik = $penduduk->unique('nama')->values(); // nama persis sama hanya satu

            // === GENDER ===
            $lakiList = $pendudukUnik->where('gender', 'Laki-laki');
            $perempuanList = $pendudukUnik->where('gender', 'Perempuan');

            $dataGender[] = [
                'rt' => $rt,
                'laki' => $lakiList->count(),
                'perempuan' => $perempuanList->count(),
                'total' => $lakiList->count() + $perempuanList->count(),
                'nama_laki' => $lakiList->pluck('nama')->values()->all(),
                'nama_perempuan' => $perempuanList->pluck('nama')->values()->all(),
            ];

            // === LANSIA ===
            $lansia = 0;
            $nonLansia = 0;
            $namaLansia = [];
            $namaNonLansia = [];

            foreach ($pendudukUnik as $p) {
                if ($p->tanggal_lahir) {
                    $umur = Carbon::parse($p->tanggal_lahir)->age;
                    if ($umur >= 65) {
                        $lansia++;
                        $namaLansia[] = $p->nama;
                    } else {
                        $nonLansia++;
                        $namaNonLansia[] = $p->nama;
                    }
                }
            }

            $dataLansia[] = [
                'rt' => $rt,
                'atas_65' => $lansia,
                'bawah_65' => $nonLansia,
                'total' => $lansia + $nonLansia,
                'nama_atas_65' => $namaLansia,
                'nama_bawah_65' => $namaNonLansia,
            ];
        }

        // === TOTAL AGGREGAT ===
        $totalKeluarga = collect($dataKeluarga)->reduce(function ($carry, $item) {
            return [
                'lengkap' => $carry['lengkap'] + $item['lengkap'],
                'duda' => $carry['duda'] + $item['duda'],
                'janda' => $carry['janda'] + $item['janda'],
                'total' => $carry['total'] + $item['total'],
            ];
        }, ['lengkap' => 0, 'duda' => 0, 'janda' => 0, 'total' => 0]);

        $totalGender = collect($dataGender)->reduce(function ($carry, $item) {
            return [
                'laki' => $carry['laki'] + $item['laki'],
                'perempuan' => $carry['perempuan'] + $item['perempuan'],
                'total' => $carry['total'] + $item['total'],
            ];
        }, ['laki' => 0, 'perempuan' => 0, 'total' => 0]);

        $totalLansia = collect($dataLansia)->reduce(function ($carry, $item) {
            return [
                'atas_65' => $carry['atas_65'] + $item['atas_65'],
                'bawah_65' => $carry['bawah_65'] + $item['bawah_65'],
                'total' => $carry['total'] + $item['total'],
            ];
        }, ['atas_65' => 0, 'bawah_65' => 0, 'total' => 0]);

        return view('statistik', [
            'dataKeluarga' => $dataKeluarga,
            'dataGender' => $dataGender,
            'dataLansia' => $dataLansia,
            'totalStatistik' => [
                'keluarga' => $totalKeluarga,
                'gender' => $totalGender,
                'lansia' => $totalLansia,
            ],
        ]);
    }
}
