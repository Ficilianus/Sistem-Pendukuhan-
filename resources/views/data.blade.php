@extends('layouts.app')

@section('title', 'Data')

@section('content')
  <div class="text-center mb-8">
    <h1 class="text-3xl font-bold text-gray-800">
      Data Penduduk
    </h1>
  </div>

  <!-- Box Pertama -->
  <div class="max-w-2xl mx-auto bg-[#BDB395] p-6 rounded-lg shadow-md space-y-6">
    <form action="#" method="GET">
      <div class="flex items-center space-x-2">
        <input 
          type="text" 
          name="search" 
          placeholder="Cari data penduduk..." 
          class="flex-1 px-4 py-2 rounded bg-white text-black focus:outline-none focus:ring-2 focus:ring-gray-700"
        >
        <button 
          type="submit" 
          class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800"
        >
          Cari
        </button>
      </div>
    </form>

    <!-- Tombol RT -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-2">
      <button class="w-full h-10 bg-gray-700 text-white rounded-xl hover:bg-gray-800">RT 01</button>
      <button class="w-full h-10 bg-gray-700 text-white rounded-xl hover:bg-gray-800">RT 02</button>
      <button class="w-full h-10 bg-gray-700 text-white rounded-xl hover:bg-gray-800">RT 03</button>
      <button class="w-full h-10 bg-gray-700 text-white rounded-xl hover:bg-gray-800">RT 04</button>
      <button class="w-full h-10 bg-gray-700 text-white rounded-xl hover:bg-gray-800">RT 05</button>
    </div>
  </div>

  <!-- Box Kedua untuk menampilkan data-->
<!-- Box Kedua untuk menampilkan data KK -->
<div class="max-w-2xl mx-auto bg-[#BDB395] p-6 rounded-lg shadow-md mt-6 space-y-4">
  <h2 class="text-xl font-semibold text-gray-800 mb-4">Data KK</h2>

  @forelse ($dokumenKK as $item)
  <div class="flex justify-between items-center bg-white p-3 rounded shadow">
    <div>
      <span class="text-gray-800 font-medium">{{ $item->nama_kepala_keluarga }}</span>
      <span class="ml-2 text-sm text-gray-500">(RT: {{ $item->rt }})</span>
    </div>
    <a href="{{ route('dokumen.keluarga', ['nama_kepala_keluarga' => $item->nama_kepala_keluarga, 'rt' => $item->rt]) }}"
       class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
      Lihat
    </a>
  </div>
@empty

    <p class="text-gray-700">Belum ada data dokumen KK.</p>
  @endforelse
</div>

  <!-- Tombol Add -->
<div class="fixed bottom-6 right-6">
  <button onclick="document.getElementById('formModal').classList.remove('hidden')" class="bg-blue-600 text-white px-4 py-2 rounded-full shadow-lg hover:bg-blue-700">
    + Add
  </button>
</div>

<!-- Modal Form -->
<div id="formModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
  <div class="bg-white rounded-lg w-full max-w-md p-6 relative">
    <!-- Tombol Close -->
    <button onclick="document.getElementById('formModal').classList.add('hidden')" class="absolute top-2 right-2 text-gray-600 hover:text-red-600 text-xl">&times;</button>
    
    <h2 class="text-lg font-bold mb-4">Tambah Data Penduduk</h2>
    <form action="{{ route('dokumen.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="space-y-3">
        <input type="text" name="nama_kepala_keluarga" placeholder="Nama Kepala Keluarga" class="w-full px-4 py-2 border rounded" required>
        <input type="text" name="nama" placeholder="Nama" class="w-full px-4 py-2 border rounded" required>

        <select name="rt" class="w-full px-4 py-2 border rounded" required>
          <option value="">-- Pilih RT --</option>
          <option value="RT01">RT 01</option>
          <option value="RT02">RT 02</option>
          <option value="RT03">RT 03</option>
          <option value="RT04">RT 04</option>
          <option value="RT05">RT 05</option>
        </select>

        <label class="block font-medium">Jenis Dokumen:</label>
        @foreach(['KTP', 'KK', 'Akte Lahir', 'Foto Rumah', 'Buku Nikah'] as $doc)
          <label class="inline-flex items-center">
            <input type="radio" name="jenis_dokumen" value="{{ $doc }}" class="mr-2 jenis-radio" required> {{ $doc }}
          </label>
        @endforeach

        <div id="ktpFields" class="hidden">
          <label class="block mt-2">Jenis Kelamin:</label>
          <label class="inline-flex items-center">
            <input type="radio" name="gender" value="Laki-laki" class="mr-2"> Laki-laki
          </label>
          <label class="inline-flex items-center">
            <input type="radio" name="gender" value="Perempuan" class="mr-2"> Perempuan
          </label>

          <input type="date" name="tanggal_lahir" class="w-full px-4 py-2 border rounded mt-2">
        </div>

        <label class="block font-medium mt-2">Upload Gambar:</label>
        <input type="file" name="file" accept="image/*" class="w-full" required>

        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700 mt-2">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Script untuk interaktif -->
<script>
  document.querySelectorAll('.jenis-radio').forEach((radio) => {
    radio.addEventListener('change', function () {
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
    <button onclick="document.getElementById('imageModal').classList.add('hidden')" class="absolute top-2 right-2 text-gray-600 hover:text-red-600 text-xl">&times;</button>
    <img id="previewImage" src="" alt="Preview Gambar" class="max-w-full max-h-[70vh] rounded" />
  </div>
</div>

<script>
  function showImage(src) {
    document.getElementById('previewImage').src = src;
    document.getElementById('imageModal').classList.remove('hidden');
  }
</script>

@endsection
