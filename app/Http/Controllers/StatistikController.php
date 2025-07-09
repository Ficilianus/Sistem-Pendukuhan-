<?php

namespace App\Http\Controllers;

use App\Models\DokumenPenduduk;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StatistikController extends Controller
{
    public function index()
    {
        $rtList = DokumenPenduduk::select('rt')->distinct()->pluck('rt');

        $dataKeluarga = [];
        $dataGender = [];
        $dataLansia = [];

        foreach ($rtList as $rt) {
            // === KELUARGA ===
            $keluargaLengkap = DokumenPenduduk::where('rt', $rt)->where('status_keluarga', 'Lengkap')->distinct('nama_kepala_keluarga')->count('nama_kepala_keluarga');
            $keluargaDuda = DokumenPenduduk::where('rt', $rt)->where('status_keluarga', 'Duda')->distinct('nama_kepala_keluarga')->count('nama_kepala_keluarga');
            $keluargaJanda = DokumenPenduduk::where('rt', $rt)->where('status_keluarga', 'Janda')->distinct('nama_kepala_keluarga')->count('nama_kepala_keluarga');
            $totalKK = $keluargaLengkap + $keluargaDuda + $keluargaJanda;

            $dataKeluarga[] = [
                'rt' => $rt,
                'lengkap' => $keluargaLengkap,
                'duda' => $keluargaDuda,
                'janda' => $keluargaJanda,
                'total' => $totalKK,
            ];

            // === GENDER ===
            $laki = DokumenPenduduk::where('rt', $rt)->where('gender', 'Laki-laki')->count();
            $perempuan = DokumenPenduduk::where('rt', $rt)->where('gender', 'Perempuan')->count();
            $totalPenduduk = $laki + $perempuan;

            $dataGender[] = [
                'rt' => $rt,
                'laki' => $laki,
                'perempuan' => $perempuan,
                'total' => $totalPenduduk,
            ];

            // === LANSIA ===
            $pendudukRt = DokumenPenduduk::where('rt', $rt)->whereNotNull('tanggal_lahir')->get();

            $lansia = 0;
            $nonLansia = 0;
            $today = Carbon::now();

            foreach ($pendudukRt as $penduduk) {
                $umur = Carbon::parse($penduduk->tanggal_lahir)->diffInYears($today);
                if ($umur >= 65) {
                    $lansia++;
                } else {
                    $nonLansia++;
                }
            }

            $dataLansia[] = [
                'rt' => $rt,
                'atas_65' => $lansia,
                'bawah_65' => $nonLansia,
                'total' => $lansia + $nonLansia,
            ];
        }

        return view('statistik', [
            'dataKeluarga' => $dataKeluarga,
            'dataGender' => $dataGender,
            'dataLansia' => $dataLansia,
        ]);
    }
}
