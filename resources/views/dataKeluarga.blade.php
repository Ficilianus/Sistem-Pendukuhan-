@extends('layouts.app')

@section('title', 'Data Keluarga')

@section('content')
  <div class="text-center mb-6">
    <h1 class="text-2xl font-bold">Data Keluarga: {{ $namaKK }} (RT: {{ $rt }})</h1>
  </div>

  <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow space-y-4">
    @forelse ($dataKeluarga as $item)
      <div class="bg-gray-100 p-4 rounded">
        <p><strong>Nama:</strong> {{ $item->nama }}</p>
        <p><strong>Jenis Dokumen:</strong> {{ $item->jenis_dokumen }}</p>
        @if ($item->jenis_dokumen === 'KTP')
          <p><strong>Gender:</strong> {{ $item->gender }}</p>
          <p><strong>Tanggal Lahir:</strong> {{ $item->tanggal_lahir }}</p>
        @endif
        <div class="mt-2">
          <img src="{{ asset('storage/dokumen/' . $item->nama_file) }}" alt="{{ $item->nama }}" class="max-w-xs rounded shadow" />
        </div>
      </div>
    @empty
      <p class="text-gray-600">Tidak ada data dokumen untuk keluarga ini.</p>
    @endforelse
  </div>
@endsection
