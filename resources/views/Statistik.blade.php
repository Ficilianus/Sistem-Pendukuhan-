@extends('layouts.app')

@section('title', 'Statistik')

@section('content')
    <div class="text-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Statistik Penduduk</h1>
    </div>

    <!-- Tombol Navigasi -->
    <div
        class="flex flex-col sm:flex-row justify-center items-center bg-[#f1efe5] py-4 mb-8 gap-2 sm:gap-4 rounded shadow mx-2 sm:mx-0">
        <button id="btn-keluarga" onclick="showSection('keluarga')"
            class="tab-btn px-4 py-2 rounded-xl font-semibold text-white transition-all duration-300 shadow-md
        bg-gradient-to-r from-gray-600 to-gray-800 hover:scale-105 hover:ring-2 hover:ring-offset-2 hover:ring-gray-400">
            Keluarga
        </button>

        <button id="btn-gender" onclick="showSection('gender')"
            class="tab-btn px-4 py-2 rounded-xl font-semibold text-white transition-all duration-300 shadow-md
        bg-gradient-to-r from-gray-600 to-gray-800 hover:scale-105 hover:ring-2 hover:ring-offset-2 hover:ring-gray-400">
            Jenis Kelamin
        </button>

        <button id="btn-lansia" onclick="showSection('lansia')"
            class="tab-btn px-4 py-2 rounded-xl font-semibold text-white transition-all duration-300 shadow-md
        bg-gradient-to-r from-gray-600 to-gray-800 hover:scale-105 hover:ring-2 hover:ring-offset-2 hover:ring-gray-400">
            Lansia
        </button>
    </div>



    <!-- Load Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- KELUARGA -->

    <div id="section-keluarga" class="flex flex-wrap -mx-4">
        <div class="w-full px-4 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <h2 class="text-xl font-bold text-gray-700 mb-4">Statistik Total Keluarga Seluruh RT</h2>
                <canvas id="chart-total-keluarga" class="mx-auto w-[250px]"></canvas>

                <script>
                    new Chart(document.getElementById('chart-total-keluarga'), {
                        type: 'pie',
                        data: {
                            labels: ['Lengkap', 'Duda', 'Janda'],
                            datasets: [{
                                data: [
                                    {{ $totalStatistik['keluarga']['lengkap'] }},
                                    {{ $totalStatistik['keluarga']['duda'] }},
                                    {{ $totalStatistik['keluarga']['janda'] }}
                                ],
                                backgroundColor: ['#FAB98F', '#2F2DE7', '#F14970'],
                                borderColor: '#fff',
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                },
                                title: {
                                    display: true,
                                    text: 'Total Status Keluarga Semua RT'
                                }
                            }
                        }
                    });
                </script>
            </div>
        </div>

        @foreach ($dataKeluarga as $data)
            <div class="w-full md:w-1/2 px-4 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-md h-full flex flex-col md:flex-row items-center gap-6">
                    <div class="w-full sm:w-1/2 px-2 sm:px-4 mb-6 sm:mb-8">

                        <canvas id="chart-keluarga-{{ $data['rt'] }}"
                            class="w-full max-w-[250px] h-auto aspect-square"></canvas>
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
                                legend: {
                                    position: 'bottom'
                                },
                                title: {
                                    display: true,
                                    text: 'Status Keluarga RT {{ $data['rt'] }}'
                                }
                            }
                        }
                    });
                </script>
            </div>
        @endforeach
    </div>
    <!-- GENDER -->
    <div id="section-gender" class="hidden flex flex-wrap -mx-4">
        <div class="w-full px-4 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <h2 class="text-xl font-bold text-gray-700 mb-4">Statistik Total Jenis Kelamin Seluruh RT</h2>
                <canvas id="chart-total-gender" class="mx-auto w-[250px]"></canvas>


            </div>
        </div>

        @foreach ($dataGender as $data)
            {{-- <h3>{{ $data['rt'] }}</h3>
            <p>Laki-laki ({{ $data['laki'] }}): {{ implode(', ', $data['nama_laki']) }}</p>
            <p>Perempuan ({{ $data['perempuan'] }}): {{ implode(', ', $data['nama_perempuan']) }}</p> --}}

            <div class="w-full md:w-1/2 px-4 mb-8">
                <div
                    class="bg-white p-4 sm:p-6 rounded-lg shadow-md h-full flex flex-col sm:flex-row items-center gap-4 sm:gap-6">

                    <div class="w-full md:w-1/2">
                        <canvas id="chart-gender-{{ $data['rt'] }}"
                            class="w-full max-w-[250px] h-auto aspect-square"></canvas>
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
                                legend: {
                                    position: 'bottom'
                                },
                                title: {
                                    display: true,
                                    text: 'Jenis Kelamin RT {{ $data['rt'] }}'
                                }
                            }
                        }
                    });
                </script>
            </div>
        @endforeach
    </div>

    <!-- LANSIA -->
    <div id="section-lansia" class="hidden flex flex-wrap -mx-4">
        <div class="w-full px-4 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <h2 class="text-xl font-bold text-gray-700 mb-4">Statistik Total Lansia Seluruh RT</h2>
                <canvas id="chart-total-lansia" class="mx-auto w-[250px]"></canvas>

            </div>

        </div>
        @foreach ($dataLansia as $data)
            {{-- <h3>{{ $data['rt'] }}</h3>
            <p>Lansia 65+ ({{ $data['atas_65'] }}): {{ implode(', ', $data['nama_atas_65']) }}</p>
            <p>Di bawah 65 ({{ $data['bawah_65'] }}): {{ implode(', ', $data['nama_bawah_65']) }}</p> --}}
            <div class="w-full md:w-1/2 px-4 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-md h-full flex flex-col md:flex-row items-center gap-6">
                    <div class="w-full md:w-1/2">
                        <canvas id="chart-lansia-{{ $data['rt'] }}"
                            class="w-full max-w-[250px] h-auto aspect-square"></canvas>
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
                                legend: {
                                    position: 'bottom'
                                },
                                title: {
                                    display: true,
                                    text: 'Data Lansia RT {{ $data['rt'] }}'
                                }
                            }
                        }
                    });
                </script>
            </div>
        @endforeach
    </div>
    <script>
        let chartGenderTotal, chartLansiaTotal;

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

            // Render chart jika belum dirender
            setTimeout(() => {
                if (section === 'gender' && !chartGenderTotal) {
                    chartGenderTotal = new Chart(document.getElementById('chart-total-gender'), {
                        type: 'pie',
                        data: {
                            labels: ['Laki-laki', 'Perempuan'],
                            datasets: [{
                                data: [{{ $totalStatistik['gender']['laki'] }},
                                    {{ $totalStatistik['gender']['perempuan'] }}
                                ],
                                backgroundColor: ['#2F2DE7', '#F14970'],
                                borderColor: '#fff',
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                },
                                title: {
                                    display: true,
                                    text: 'Total Jenis Kelamin Semua RT'
                                }
                            }
                        }
                    });
                }

                if (section === 'lansia' && !chartLansiaTotal) {
                    chartLansiaTotal = new Chart(document.getElementById('chart-total-lansia'), {
                        type: 'pie',
                        data: {
                            labels: ['Lansia (65+)', 'Bukan Lansia'],
                            datasets: [{
                                data: [{{ $totalStatistik['lansia']['atas_65'] }},
                                    {{ $totalStatistik['lansia']['bawah_65'] }}
                                ],
                                backgroundColor: ['#F14970', '#FAB98F'],
                                borderColor: '#fff',
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                },
                                title: {
                                    display: true,
                                    text: 'Total Data Lansia Semua RT'
                                }
                            }
                        }
                    });
                }
            }, 100);
        }

        document.addEventListener('DOMContentLoaded', () => {
            showSection('keluarga');
        });
    </script>

@endsection
