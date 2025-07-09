@extends('layouts.app')

@section('title', 'Statistik')

@section('content')
    <div class="text-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Statistik Penduduk</h1>
    </div>

    <!-- Tombol Navigasi -->
    <div class="flex justify-center bg-[#f1efe5] py-4 mb-8 gap-4 rounded shadow">
    <button id="btn-keluarga" onclick="showSection('keluarga')" class="tab-btn bg-gray-800 text-white px-4 py-2 rounded hover:bg-green-700 transition">Keluarga</button>
    <button id="btn-gender" onclick="showSection('gender')" class="tab-btn bg-gray-800 text-white px-4 py-2 rounded hover:bg-green-700 transition">Jenis Kelamin</button>
    <button id="btn-lansia" onclick="showSection('lansia')" class="tab-btn bg-gray-800 text-white px-4 py-2 rounded hover:bg-green-700 transition">Lansia</button>
</div>


    <!-- Load Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- KELUARGA -->
    <div id="section-keluarga" class="flex flex-wrap -mx-4">
        @foreach ($dataKeluarga as $data)
            <div class="w-full md:w-1/2 px-4 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-md h-full flex flex-col md:flex-row items-center gap-6">
                    <div class="w-full md:w-1/2">
                        <canvas id="chart-keluarga-{{ $data['rt'] }}" class="w-[250px] h-[250px]"></canvas>
                    </div>
                    <div class="w-full md:w-1/2 space-y-2 text-gray-700">
                        <p><strong>RT:</strong> {{ $data['rt'] }}</p>
                        <p><strong>Lengkap:</strong> {{ $data['lengkap'] }}</p>
                        <p><strong>Duda:</strong> {{ $data['duda'] }}</p>
                        <p><strong>Janda:</strong> {{ $data['janda'] }}</p>
                        <p><strong>Total KK:</strong> {{ $data['total'] }}</p>
                    </div>
                </div>
                <script>
                    new Chart(document.getElementById('chart-keluarga-{{ $data['rt'] }}'), {
                        type: 'pie',
                        data: {
                            labels: ['Lengkap', 'Duda', 'Janda'],
                            datasets: [{
                                data: [{{ $data['lengkap'] }}, {{ $data['duda'] }}, {{ $data['janda'] }}],
                                backgroundColor: ['#FAB98F', '#2F2DE7', '#F14970'],
                                borderColor: '#fff',
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { position: 'bottom' },
                                title: { display: true, text: 'Status Keluarga RT {{ $data['rt'] }}' }
                            }
                        }
                    });
                </script>
            </div>
        @endforeach
    </div>

    <!-- GENDER -->
    <div id="section-gender" class="hidden flex flex-wrap -mx-4">
        @foreach ($dataGender as $data)
            <div class="w-full md:w-1/2 px-4 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-md h-full flex flex-col md:flex-row items-center gap-6">
                    <div class="w-full md:w-1/2">
                        <canvas id="chart-gender-{{ $data['rt'] }}" class="w-[250px] h-[250px]"></canvas>
                    </div>
                    <div class="w-full md:w-1/2 space-y-2 text-gray-700">
                        <p><strong>RT:</strong> {{ $data['rt'] }}</p>
                        <p><strong>Laki-laki:</strong> {{ $data['laki'] }}</p>
                        <p><strong>Perempuan:</strong> {{ $data['perempuan'] }}</p>
                        <p><strong>Total Penduduk:</strong> {{ $data['total'] }}</p>
                    </div>
                </div>
                <script>
                    new Chart(document.getElementById('chart-gender-{{ $data['rt'] }}'), {
                        type: 'pie',
                        data: {
                            labels: ['Laki-laki', 'Perempuan'],
                            datasets: [{
                                data: [{{ $data['laki'] }}, {{ $data['perempuan'] }}],
                                backgroundColor: ['#2F2DE7', '#F14970'],
                                borderColor: '#fff',
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { position: 'bottom' },
                                title: { display: true, text: 'Jenis Kelamin RT {{ $data['rt'] }}' }
                            }
                        }
                    });
                </script>
            </div>
        @endforeach
    </div>

    <!-- LANSIA -->
    <div id="section-lansia" class="hidden flex flex-wrap -mx-4">
        @foreach ($dataLansia as $data)
            <div class="w-full md:w-1/2 px-4 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-md h-full flex flex-col md:flex-row items-center gap-6">
                    <div class="w-full md:w-1/2">
                        <canvas id="chart-lansia-{{ $data['rt'] }}" class="w-[250px] h-[250px]"></canvas>
                    </div>
                    <div class="w-full md:w-1/2 space-y-2 text-gray-700">
                        <p><strong>RT:</strong> {{ $data['rt'] }}</p>
                        <p><strong>Lansia (65+):</strong> {{ $data['atas_65'] }}</p>
                        <p><strong>Bukan Lansia:</strong> {{ $data['bawah_65'] }}</p>
                        <p><strong>Total Penduduk:</strong> {{ $data['total'] }}</p>
                    </div>
                </div>
                <script>
                    new Chart(document.getElementById('chart-lansia-{{ $data['rt'] }}'), {
                        type: 'pie',
                        data: {
                            labels: ['Lansia (65+)', 'Bukan Lansia'],
                            datasets: [{
                                data: [{{ $data['atas_65'] }}, {{ $data['bawah_65'] }}],
                                backgroundColor: ['#F14970', '#FAB98F'],
                                borderColor: '#fff',
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { position: 'bottom' },
                                title: { display: true, text: 'Data Lansia RT {{ $data['rt'] }}' }
                            }
                        }
                    });
                </script>
            </div>
        @endforeach
    </div>

    <!-- Script Toggle -->
    <script>
        function showSection(section) {
            document.getElementById('section-keluarga').classList.add('hidden');
            document.getElementById('section-gender').classList.add('hidden');
            document.getElementById('section-lansia').classList.add('hidden');

            document.getElementById(`section-${section}`).classList.remove('hidden');
        }
    </script>
    <script>
    function showSection(section) {
        // Sembunyikan semua section
        ['keluarga', 'gender', 'lansia'].forEach(id => {
            document.getElementById('section-' + id).classList.add('hidden');
        });

        // Tampilkan section yang dipilih
        document.getElementById('section-' + section).classList.remove('hidden');

        // Atur tombol aktif
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-green-700', 'ring', 'ring-offset-2');
            btn.classList.add('bg-gray-800');
        });

        const activeBtn = document.getElementById('btn-' + section);
        activeBtn.classList.remove('bg-gray-800');
        activeBtn.classList.add('bg-green-700', 'ring', 'ring-offset-2');
    }

    // Optional: set default active saat halaman dibuka
    document.addEventListener('DOMContentLoaded', () => {
        showSection('keluarga');
    });
</script>

@endsection
