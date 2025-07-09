@extends('layouts.app')

@section('title', 'Data Keluarga')

@section('content')
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold">Data Keluarga: {{ $namaKK }} (RT: {{ $rt }})</h1>
    </div>
    <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow space-y-4">
        @foreach ($dataKeluarga as $item)
            <div class="bg-gray-100 p-4 rounded relative">
                <div class="absolute top-3 right-3 flex gap-3 items-center">
                    @if (in_array($item->status_keluarga, ['Duda', 'Janda']))
                        <span
                            class="min-w-[70px] h-9 text-center bg-red-100 text-red-600 text-sm font-semibold px-3 py-1 rounded flex items-center justify-center">
                            Status: {{ $item->status_keluarga }}
                        </span>
                    @endif
                    <!-- Tombol Edit -->
                    <button onclick="openEditModal({{ $item->id }})"
                        class="min-w-[70px] h-9 text-center bg-blue-600 text-white text-sm px-3 py-1 rounded hover:bg-blue-700">
                        Edit
                    </button>
                    <!-- Tombol Hapus -->
                    <form action="{{ route('dokumen.destroy', $item->id) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="min-w-[70px] h-9 text-center bg-red-600 text-white text-sm px-3 py-1 rounded hover:bg-red-700">
                            Hapus
                        </button>
                    </form>

                    </form>
                </div>

                <p><strong>Nama:</strong> {{ $item->nama }}</p>
                <p><strong>Jenis Dokumen:</strong> {{ $item->jenis_dokumen }}</p>
                @if ($item->jenis_dokumen === 'KTP')
                    <p><strong>Gender:</strong> {{ $item->gender }}</p>
                    <p><strong>Tanggal Lahir:</strong> {{ $item->tanggal_lahir }}</p>
                @endif
                <div class="mt-2">
                    <img src="{{ asset('storage/dokumen/' . $item->nama_file) }}" alt="{{ $item->nama }}"
                        class="max-w-xs rounded shadow" />
                </div>
            </div>

            <!-- Modal Edit -->
            <div id="editModal-{{ $item->id }}"
                class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-white p-6 rounded-lg w-full max-w-md relative">
                    <button onclick="closeEditModal({{ $item->id }})"
                        class="absolute top-2 right-2 text-gray-600 hover:text-red-600 text-xl">&times;</button>
                    <h2 class="text-lg font-bold mb-4">Edit Data</h2>

                    <form action="{{ route('dokumen.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="rt" value="{{ $item->rt }}">
                        <input type="hidden" name="nama_kepala_keluarga" value="{{ $item->nama_kepala_keluarga }}">

                        <div class="space-y-3">
                            <input type="text" name="nama" value="{{ $item->nama }}"
                                class="w-full px-4 py-2 border rounded" required>

                            <select name="jenis_dokumen" class="w-full px-4 py-2 border rounded" required>
                                @foreach (['KTP', 'KK', 'Akte Lahir', 'Foto Rumah', 'Buku Nikah'] as $doc)
                                    <option value="{{ $doc }}"
                                        {{ $item->jenis_dokumen === $doc ? 'selected' : '' }}>{{ $doc }}</option>
                                @endforeach
                            </select>

                            <div class="ktp-fields {{ $item->jenis_dokumen === 'KTP' ? '' : 'hidden' }}">
                                <label class="block mt-2">Gender:</label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="gender" value="Laki-laki"
                                        {{ $item->gender === 'Laki-laki' ? 'checked' : '' }} class="mr-2"> Laki-laki
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="gender" value="Perempuan"
                                        {{ $item->gender === 'Perempuan' ? 'checked' : '' }} class="mr-2"> Perempuan
                                </label>

                                <input type="date" name="tanggal_lahir" value="{{ $item->tanggal_lahir }}"
                                    class="w-full px-4 py-2 border rounded mt-2">
                            </div>

                            <label>Ganti Gambar (opsional):</label>
                            <input type="file" name="file" accept="image/*" class="w-full">

                            <button type="submit"
                                class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">Simpan
                                Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach


        <script>
            function openEditModal(id) {
                document.getElementById(`editModal-${id}`).classList.remove('hidden');
            }

            function closeEditModal(id) {
                document.getElementById(`editModal-${id}`).classList.add('hidden');
            }

            // Toggle KTP fields on jenis_dokumen change
            document.querySelectorAll('select[name="jenis_dokumen"]').forEach(select => {
                select.addEventListener('change', function() {
                    const container = this.closest('form').querySelector('.ktp-fields');
                    if (this.value === 'KTP') {
                        container.classList.remove('hidden');
                    } else {
                        container.classList.add('hidden');
                    }
                });
            });
        </script>
