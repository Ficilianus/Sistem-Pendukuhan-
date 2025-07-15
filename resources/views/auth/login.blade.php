@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#F6F0F0] relative overflow-hidden">

    <!-- Gambar Latar Belakang Transparan -->
    <div class="absolute inset-0 bg-cover bg-center opacity-20" style="background-image: url('{{ asset('img/bg-login.jpg') }}'); z-index: 0;"></div>

    <div class="w-full max-w-md bg-[#BDB395]/90 rounded-2xl shadow-2xl p-8 z-10 backdrop-blur-sm">
        <h2 class="text-3xl font-extrabold text-center mb-6 text-white drop-shadow">Login</h2>

        @if(session('error'))
            <div class="bg-red-200 text-red-800 px-4 py-2 rounded mb-4 shadow-md">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="username" class="block text-white font-medium">Username</label>
                <input type="text" name="username" id="username" required
                    class="w-full border border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-700"
                    value="{{ old('username') }}">
            </div>

            <div>
                <label for="password" class="block text-white font-medium">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full border border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-700">
            </div>

            <div class="flex items-center">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="remember" class="form-checkbox text-white bg-white border-white">
                    <span class="text-sm text-white">Remember Me</span>
                </label>
            </div>

            <button type="submit"
                class="w-full bg-gray-800 text-white py-2 rounded-lg shadow-md hover:bg-gray-900 transform hover:-translate-y-1 transition duration-300 font-semibold">
                Login
            </button>
        </form>
    </div>
</div>
@endsection
