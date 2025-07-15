@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
  <div class="text-center space-y-6">
    <h1 class="text-3xl font-bold text-gray-800 mt-6">
      Sistem Informasi Data Padukuhan Kalinongko Kidul Gayamharjo
    </h1>

    <!-- Gambar Denah dengan efek 3D saat hover -->
    <div class="flex justify-center px-4">
      <img
        src="{{ asset('images/denah.png') }}"
        alt="Denah Padukuhan"
        class="tilt-card w-full max-w-4xl rounded-xl shadow-xl object-contain transition-transform duration-300"
      />
    </div>
  </div>

  <style>
    .tilt-card {
      transform-style: preserve-3d;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      perspective: 1000px;
    }

    .tilt-card:hover {
      transform: rotateY(6deg) rotateX(4deg) scale(1.01);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
      z-index: 10;
    }
  </style>
@endsection
