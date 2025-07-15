@extends('layouts.app')

@section('title', 'Data Keluarga')

@section('content')
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            <br>
            <br>
            Data Keluarga: {{ $namaKK }} ({{ $rt }})
        </h1>
    </div>
    <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow space-y-4">
        @foreach ($dataKeluarga as $item)
            <div class="bg-gray-100 p-4 rounded flex flex-col sm:flex-row gap-4">
                <!-- Gambar -->
                <div class="w-full sm:w-1/3 flex justify-center">
                    <img src="{{ asset('storage/dokumen/' . $item->nama_file) }}" alt="{{ $item->nama }}"
                        class="w-full max-w-[180px] rounded shadow object-contain cursor-pointer"
                        onclick="openImageModal('{{ asset('storage/dokumen/' . $item->nama_file) }}')" />

                </div>

                <!-- Info -->
                <div class="w-full sm:w-2/3 relative">
                    <div class="space-y-1 text-gray-800">
                        <p><strong>Nama:</strong> {{ $item->nama }}</p>
                        <p><strong>Jenis Dokumen:</strong> {{ $item->jenis_dokumen }}</p>

                        @if (in_array($item->jenis_dokumen, ['KTP', 'Akte Lahir']))
                            <p><strong>Gender:</strong> {{ $item->gender }}</p>
                            <p><strong>Tanggal Lahir:</strong> {{ $item->tanggal_lahir }}</p>
                        @endif
                    </div>

                    <!-- Label status & tombol -->
                    <div class="mt-3 flex flex-wrap gap-2 items-center gap-y-1  ">

                        @if (in_array($item->status_keluarga, ['Duda', 'Janda']))
                            <span class="bg-red-100 text-red-600 text-sm font-semibold px-3 py-1 rounded">
                                Status: {{ $item->status_keluarga }}
                            </span>
                        @endif

                        <button onclick="openEditModal({{ $item->id }})"
                            class="bg-blue-600 text-white text-sm px-3 py-1 rounded hover:bg-blue-700 ">
                            Edit
                        </button>

                        <form action="{{ route('dokumen.destroy', $item->id) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus data ini?')"
                            class="h-full flex items-center  m-auto">

                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white text-sm px-3 py-1 rounded hover:bg-red-700">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Modal Edit -->
            <div id="editModal-{{ $item->id }}"
                class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center transition duration-300">
                <div class="bg-white rounded-lg w-full mx-4 max-w-md p-4 sm:p-6 relative shadow-lg">
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
                                @foreach (['KTP', 'KK', 'Akte Lahir', 'Foto Rumah', 'Buku Nikah', 'BPJS'] as $doc)
                                    <option value="{{ $doc }}"
                                        {{ $item->jenis_dokumen === $doc ? 'selected' : '' }}>
                                        {{ $doc }}</option>
                                @endforeach
                            </select>
                            <div class="ktp-fields {{ in_array($item->jenis_dokumen, ['KTP', 'Akte Lahir']) ? '' : 'hidden' }}">
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
        <!-- Modal Zoom Gambar -->
        <div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-80 z-50 flex items-center justify-center">
            <div id="modalOverlay" class="relative max-w-4xl w-full mx-4">
                <button onclick="closeImageModal()"
                    class="absolute top-2 right-2 text-white text-3xl font-bold z-50">&times;</button>
                <img id="zoomedImage" src="" class="w-full h-auto max-h-[90vh] object-contain rounded shadow-xl"
                    alt="Zoomed Image">
            </div>
        </div>
    </div>
    <script>
        function openEditModal(id) {
            document.getElementById(`editModal-${id}`).classList.remove('hidden');
            document.getElementById('blurContainer').classList.add('blur-sm');
        }

        function closeEditModal(id) {
            document.getElementById(`editModal-${id}`).classList.add('hidden');
            document.getElementById('blurContainer').classList.remove('blur-sm');
        }
        // Toggle KTP fields on jenis_dokumen change
        document.querySelectorAll('select[name="jenis_dokumen"]').forEach(select => {
            select.addEventListener('change', function() {
                const container = this.closest('form').querySelector('.ktp-fields');
                if (this.value === 'KTP' || this.value === 'Akte Lahir') {
                    container.classList.remove('hidden');
                } else {
                    container.classList.add('hidden');
                }

            });
        });
        function openImageModal(src) {
            const modal = document.getElementById('imageModal');
            const image = document.getElementById('zoomedImage');
            image.src = src;
            modal.classList.remove('hidden');
        }
        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.getElementById('zoomedImage').src = "";
        }
        // Tutup modal jika klik di luar gambar (area hitam)
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target.id === 'imageModal') {
                closeImageModal();
            }
        });
        // Tutup modal jika tekan tombol ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
@endsection
