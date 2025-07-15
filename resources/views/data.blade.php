@extends('layouts.app')

@section('title', 'Data')

@section('content')
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            Data Penduduk
        </h1>
    </div>
    <!-- Notifikasi Pop-up -->
    @if (session('success') || session('error'))
        <div id="popup-notification"
            class="fixed top-6 left-1/2 -translate-x-1/2 z-50 max-w-sm w-full px-6 py-4 rounded-lg shadow-lg text-white text-sm font-semibold
    {{ session('success') ? 'bg-green-600' : 'bg-red-600' }}
    animate-fade-in">
            {{ session('success') ?? session('error') }}
        </div>
    @endif
    <!-- Box Pertama -->
    <div class="max-w-2xl mx-auto bg-[#BDB395] p-6 rounded-lg shadow-md space-y-6">
        <form action="{{ route('dokumen.index') }}" method="GET">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-500">
                        <!-- Ikon Search -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari kepala keluarga..."
                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:border-gray-600 bg-white text-black shadow-sm transition-all duration-200" />
                </div>

                <button type="submit"
                    class="flex items-center justify-center px-4 py-2 bg-gray-800 text-white rounded-lg shadow hover:bg-gray-900 transition-all duration-200">
                    Cari
                </button>
            </div>
        </form>



        <!-- Tombol RT -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
            @foreach (['RT01', 'RT02', 'RT03', 'RT04', 'RT05'] as $rt)
                <a href="{{ route('dokumen.index', array_merge(request()->only('search'), ['rt' => $rt])) }}"
                    class="w-full h-12 flex items-center justify-center font-semibold rounded-xl transition-all duration-300 shadow-md
            {{ request('rt') === $rt ? 'bg-[#f1efe5] text-black' : 'bg-gray-700 text-white' }}
            hover:scale-105 hover:ring-2 hover:ring-offset-2 hover:ring-gray-300">
                    {{ $rt }}
                </a>
            @endforeach
        </div>




    </div>
    @if (request('rt'))
        <div class="text-center mt-4">
            <a href="{{ route('dokumen.downloadByRT', ['rt' => request('rt')]) }}"
                class="inline-block bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 shadow-md hover:scale-105 transition duration-300">
                Download Semua Dokumen Untuk {{ request('rt') }}
            </a>

        </div>
    @endif

    <!-- Box Kedua untuk menampilkan data KK -->
    <div class="max-w-2xl mx-auto bg-[#BDB395] p-6 rounded-lg shadow-md mt-6 space-y-4">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Data Keluarga</h2>

        @forelse ($dokumenKK as $item)
            <div
                class="tilt-card flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white p-3 rounded shadow space-y-2 sm:space-y-0 transition-transform duration-300">

                <div>
                    <span class="text-gray-800 font-medium">{{ $item->nama_kepala_keluarga }}</span>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('dokumen.keluarga', ['nama_kepala_keluarga' => $item->nama_kepala_keluarga, 'rt' => $item->rt]) }}"
                        class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Lihat
                    </a>
                    <a href="{{ route('dokumen.download', ['nama_kepala_keluarga' => $item->nama_kepala_keluarga, 'rt' => $item->rt]) }}"
                        class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                        Download
                    </a>
                </div>
            </div>

        @empty

            <p class="text-gray-700">Belum ada data dokumen KK.</p>
        @endforelse
        <div class="mt-4">
            {{ $dokumenKK->withQueryString()->links() }}
        </div>

    </div>

    <!-- Tombol Add -->
    <div class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-50">
        <button onclick="document.getElementById('formModal').classList.remove('hidden')"
            class="bg-blue-600 text-white px-6 py-3 rounded-full shadow-lg hover:bg-blue-700 hover:scale-105 transform transition duration-300 ease-in-out animate-bounce hover:animate-none">
            + Tambah
        </button>
    </div>

    @if ($errors->any())
        <div class="bg-red-200 text-red-800 p-4 rounded mb-4">
            <strong>Terjadi kesalahan:</strong>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Modal Form -->
    <div id="formModal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white rounded-lg w-full mx-4 max-w-md p-4 sm:p-6 relative">

            <!-- Tombol Close -->
            <button onclick="document.getElementById('formModal').classList.add('hidden')"
                class="absolute top-2 right-2 text-gray-600 hover:text-red-600 text-xl">&times;</button>

            <h2 class="text-lg font-bold mb-4">Tambah Data Penduduk</h2>
            <form id="formTambahPenduduk" action="{{ route('dokumen.store') }}" method="POST"
                enctype="multipart/form-data">


                @csrf
                <div class="space-y-3">
                    <input type="text" name="nama_kepala_keluarga" placeholder="Nama Kepala Keluarga"
                        class="w-full px-4 py-2 border rounded" required>
                    <input type="text" name="nama" placeholder="Nama" class="w-full px-4 py-2 border rounded"
                        required>

                    <select name="rt" class="w-full px-4 py-2 border rounded" required>
                        <option value="">-- Pilih RT --</option>
                        <option value="RT01">RT 01</option>
                        <option value="RT02">RT 02</option>
                        <option value="RT03">RT 03</option>
                        <option value="RT04">RT 04</option>
                        <option value="RT05">RT 05</option>
                    </select>

                    <!-- Jenis Dokumen -->
                    <label class="block font-medium">Jenis Dokumen:</label>
                    @foreach (['KTP', 'KK', 'Akte Lahir', 'Foto Rumah', 'Buku Nikah', 'BPJS','Akte Kematian'] as $doc)
                        <label class="inline-flex items-center">
                            <input type="radio" name="jenis_dokumen" value="{{ $doc }}" class="mr-2 jenis-radio"
                                required> {{ $doc }}
                        </label>
                    @endforeach

                    <!-- Field Tambahan Jika KTP atau Akte Lahir -->
                    <div id="identitasFields" class="hidden">
                        <label class="block mt-2">Jenis Kelamin:</label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="gender" value="Laki-laki" class="mr-2"> Laki-laki
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="gender" value="Perempuan" class="mr-2"> Perempuan
                        </label>
                        <input type="date" name="tanggal_lahir" class="w-full px-4 py-2 border rounded mt-2">
                    </div>


                    <!-- Field Tambahan Jika KK -->
                    <div id="kkFields" class="hidden">
                        <label class="block mt-2">Status Keluarga:</label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="status_keluarga" value="Lengkap" class="mr-2"> Lengkap
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="status_keluarga" value="Duda" class="mr-2"> Duda
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="status_keluarga" value="Janda" class="mr-2"> Janda
                        </label>
                    </div>


                    <label class="block font-medium mt-2">Upload Gambar:</label>
                    <input type="file" name="file" accept="image/*" class="w-full" required>

                    <button type="submit" id="btnSimpan"
                        class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition duration-300 hover:scale-105 shadow-md">
                        Simpan
                    </button>

                </div>
            </form>
        </div>
    </div>

    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeOutUp {
            from {
                opacity: 1;
                transform: translateY(0);
            }

            to {
                opacity: 0;
                transform: translateY(-10px);
            }
        }

        .animate-fade-in {
            animation: fadeInUp 0.4s ease-out;
        }

        .animate-fade-out {
            animation: fadeOutUp 0.4s ease-in forwards;
        }
    </style>


    <!-- Script untuk interaktif -->
    <script>
        document.querySelectorAll('.jenis-radio').forEach((radio) => {
            radio.addEventListener('change', function() {
                if (this.value === 'KTP') {
                    document.getElementById('ktpFields').classList.remove('hidden');
                } else {
                    document.getElementById('ktpFields').classList.add('hidden');
                }
            });
        });
    </script>
    <!-- Modal Preview Gambar -->
    <div id="imageModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-4 relative">
            <button onclick="document.getElementById('imageModal').classList.add('hidden')"
                class="absolute top-2 right-2 text-gray-600 hover:text-red-600 text-xl">&times;</button>
            <img id="previewImage" src="" alt="Preview Gambar" class="max-w-full max-h-[70vh] rounded" />
        </div>
    </div>

    <script>
        function showImage(src) {
            document.getElementById('previewImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
        }
    </script>
    <script>
        document.querySelectorAll('.jenis-radio').forEach((radio) => {
            radio.addEventListener('change', function() {
                const identitas = document.getElementById('identitasFields');
                const kk = document.getElementById('kkFields');

                if (this.value === 'KTP' || this.value === 'Akte Lahir') {
                    identitas.classList.remove('hidden');
                    kk.classList.add('hidden');
                } else if (this.value === 'KK') {
                    identitas.classList.add('hidden');
                    kk.classList.remove('hidden');
                } else {
                    identitas.classList.add('hidden');
                    kk.classList.add('hidden');
                }
            });
        });
    </script>
    <style>
        .tilt-card {
            transform-style: preserve-3d;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            perspective: 1000px;
        }

        .tilt-card:hover {
            transform: rotateY(6deg) rotateX(4deg) scale(1.02);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
            z-index: 10;
        }
    </style>
    <script>
        // Hilangkan notifikasi sukses setelah 3 detik
        setTimeout(() => {
            const successAlert = document.getElementById('success-alert');
            if (successAlert) {
                successAlert.classList.add('opacity-0');
                setTimeout(() => successAlert.remove(), 500); // hapus elemen setelah animasi
            }
        }, 3000);

        // Hilangkan notifikasi error setelah 5 detik (opsional)
        setTimeout(() => {
            const errorAlert = document.getElementById('error-alert');
            if (errorAlert) {
                errorAlert.classList.add('opacity-0');
                setTimeout(() => errorAlert.remove(), 500);
            }
        }, 5000);
    </script>
    <script>
        setTimeout(() => {
            const popup = document.getElementById('popup-notification');
            if (popup) {
                popup.classList.remove('animate-fade-in');
                popup.classList.add('animate-fade-out');
                setTimeout(() => popup.remove(), 500); // setelah animasi keluar selesai
            }
        }, 3000); // tampil selama 3 detik
    </script>

@endsection
