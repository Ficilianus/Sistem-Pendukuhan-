<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body style="background-color: #F6F0F0;">
  <!-- Tombol Menu -->
  <button id="menuButton" class="fixed top-4 left-4 z-50 bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800">
    ☰ Menu
  </button>

  <!-- Sidebar -->
  <div id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-[#BDB395] text-white p-6 transform -translate-x-full transition-transform duration-300 z-40">
    <h2 class="text-2xl font-bold mb-6"></h2>
    <br>
    <a href="{{ url('/') }}" class="block py-2 px-4 rounded hover:bg-white hover:text-black">Home</a>
    <a href="{{ url('/statistik') }}" class="block py-2 px-4 rounded hover:bg-white hover:text-black">Statistik</a>
    <a href="{{ url('/data') }}" class="block py-2 px-4 rounded hover:bg-white hover:text-black">Data</a>
  </div>

  <!-- Konten Halaman -->
  <main class="p-16">
    @yield('content')
  </main>

  <script>
    const menuButton = document.getElementById('menuButton');
    const sidebar = document.getElementById('sidebar');
    menuButton.addEventListener('click', () => {
      sidebar.classList.toggle('-translate-x-full');
    });
  </script>
</body>
</html>
