<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body style="background-color: #F6F0F0;" class="relative">
    <!-- Tombol Menu -->
    <button id="menuButton"
        class="fixed top-4 left-4 z-50 bg-gray-700 text-white px-4 py-2 rounded shadow-md hover:bg-gray-800 ">
        ☰ Menu
    </button>

    <!-- Sidebar -->
    <div id="sidebar"
        class="fixed top-0 left-0 h-full w-64 bg-gradient-to-b from-[#BDB395] to-[#a59d85] text-white p-6 transform -translate-x-full transition-transform duration-300 z-40 shadow-2xl rounded-tr-3xl rounded-br-3xl">
        <h2 class="text-2xl font-bold mb-6"></h2>
        <br>

        <a href="{{ url('/') }}"
            class="block py-2 px-4 mb-2 bg-white bg-opacity-10 rounded shadow hover:bg-white hover:text-black hover:-translate-y-1 hover:scale-105 transition-all duration-200 transform">
            Home
        </a>
        <a href="{{ url('/data') }}"
            class="block py-2 px-4 mb-2 bg-white bg-opacity-10 rounded shadow hover:bg-white hover:text-black hover:-translate-y-1 hover:scale-105 transition-all duration-200 transform">
            Data
        </a>
        <a href="{{ url('/statistik') }}"
            class="block py-2 px-4 mb-2 bg-white bg-opacity-10 rounded shadow hover:bg-white hover:text-black hover:-translate-y-1 hover:scale-105 transition-all duration-200 transform">
            Statistik
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full text-left py-2 px-4 mt-2 bg-white bg-opacity-10 rounded shadow hover:bg-white hover:text-black hover:-translate-y-1 hover:scale-105 transition-all duration-200 transform">
                Logout
            </button>
        </form>
    </div>

    <!-- Konten Halaman -->
    <main class="p-16 relative z-10">
        @yield('content')
    </main>

    <!-- Script -->
    <script>
        const menuButton = document.getElementById('menuButton');
        const sidebar = document.getElementById('sidebar');

        menuButton.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
    </script>
</body>

</html>
