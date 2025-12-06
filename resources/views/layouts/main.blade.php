<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
    /* simple fade in for body */
    body {
        opacity: 0;
        transition: opacity .15s ease-in-out;
    }
    body.loaded {
        opacity: 1;
    }

    /* ensure overlay sits between sidebar and page */
    </style>
</head>

<body class="flex h-screen bg-gray-100">

    {{-- SIDEBAR --}}
    {{-- Use fixed on small screens (so it overlays content) and relative on md+ --}}
    <aside id="sidebar"
           class="bg-white w-64 p-6 space-y-6 fixed inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300 shadow-lg z-50"
           aria-hidden="true">
        <h1 class="text-2xl font-bold mb-6 text-blue-600">Perpustakaan</h1>

        @php
            $user = auth()->user();
            $role = $user->peran;
        @endphp

        {{-- INFORMASI USER LOGIN --}}
        <div class="bg-blue-50 p-4 rounded-lg mb-4 border border-blue-200">
            <p class="font-semibold text-gray-800">{{ $user->name }}</p>
            <p class="text-sm text-gray-600"><span class="font-medium">{{ ucfirst($role) }}</span></p>
        </div>

        {{-- MENU --}}
        <ul class="space-y-2">
            @if($role === 'admin')
                <li>
                    <a href="{{ route('dashboard.admin') }}"
                       class="block py-2 px-4 rounded transition {{ request()->routeIs('dashboard.admin') ? 'bg-blue-600 text-white font-semibold' : 'hover:bg-gray-200' }}">
                       Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('buku.index') }}"
                       class="block py-2 px-4 rounded transition {{ request()->routeIs('buku.*') ? 'bg-blue-600 text-white font-semibold' : 'hover:bg-gray-200' }}">
                       Buku
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.siswa') }}"
                       class="block py-2 px-4 rounded transition {{ request()->routeIs('admin.siswa') ? 'bg-blue-600 text-white font-semibold' : 'hover:bg-gray-200' }}">
                       Siswa
                    </a>
                </li>
                <li>
                    <a href="{{ route('transaksi.index') }}"
                       class="block py-2 px-4 rounded transition {{ request()->routeIs('transaksi.*') ? 'bg-blue-600 text-white font-semibold' : 'hover:bg-gray-200' }}">
                       Transaksi
                    </a>
                </li>
                <li>
                    <a href="{{ route('laporan.admin') }}"
                       class="block py-2 px-4 rounded transition {{ request()->routeIs('laporan.admin') ? 'bg-blue-600 text-white font-semibold' : 'hover:bg-gray-200' }}">
                       Laporan
                    </a>
                </li>
            @elseif($role === 'siswa')
                <li>
                    <a href="{{ route('dashboard.siswa') }}"
                       class="block py-2 px-4 rounded transition {{ request()->routeIs('dashboard.siswa') ? 'bg-blue-600 text-white font-semibold' : 'hover:bg-gray-200' }}">
                       Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('catalog.index') }}"
                       class="block py-2 px-4 rounded transition {{ request()->routeIs('catalog.*') ? 'bg-blue-600 text-white font-semibold' : 'hover:bg-gray-200' }}">
                       Katalog Buku
                    </a>
                </li>
                <li>
                    <a href="{{ route('riwayat.index') }}"
                       class="block py-2 px-4 rounded transition {{ request()->routeIs('riwayat.*') ? 'bg-blue-600 text-white font-semibold' : 'hover:bg-gray-200' }}">
                       Riwayat Peminjaman
                    </a>
                </li>
            @elseif($role === 'kepala_perpustakaan')
                <li>
                    <a href="{{ route('dashboard.kepala') }}"
                       class="block py-2 px-4 rounded transition {{ request()->routeIs('dashboard.kepala') ? 'bg-blue-600 text-white font-semibold' : 'hover:bg-gray-200' }}">
                       Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('laporan.index') }}"
                       class="block py-2 px-4 rounded transition {{ request()->routeIs('laporan.index') ? 'bg-blue-600 text-white font-semibold' : 'hover:bg-gray-200' }}">
                       Statistik
                    </a>
                </li>
                <li>
                    <a href="{{ route('bulanan.page') }}"
                       class="block py-2 px-4 rounded transition {{ request()->routeIs('bulanan.page') ? 'bg-blue-600 text-white font-semibold' : 'hover:bg-gray-200' }}">
                       Laporan Bulanan
                    </a>
                </li>
            @endif
        </ul>

        {{-- LOGOUT --}}
        <div class="mt-6">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full bg-red-600 text-white px-4 py-2 rounded-lg shadow hover:bg-red-700 transition font-semibold">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- OVERLAY (hanya tampil di mobile saat sidebar terbuka) --}}
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-40 hidden z-40 md:hidden" aria-hidden="true"></div>

    {{-- AREA KONTEN --}}
    <div class="flex-1 flex flex-col relative z-0">

        {{-- HEADER MOBILE --}}
        <header class="bg-white shadow p-4 flex justify-between items-center md:hidden">
            <button id="btnToggleSidebar" aria-controls="sidebar" aria-expanded="false"
                    class="p-2 rounded bg-gray-200 hover:bg-gray-300 transition">
                <!-- hamburger -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <span class="font-bold text-gray-800">Perpustakaan</span>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 p-6 overflow-auto">
            @yield('content')
        </main>
    </div>

<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const btnToggle = document.getElementById('btnToggleSidebar');

    function openSidebar() {
        // show sidebar (mobile)
        sidebar.classList.remove('-translate-x-full');
        sidebar.setAttribute('aria-hidden', 'false');
        overlay.classList.remove('hidden');
        // accessibility
        if (btnToggle) btnToggle.setAttribute('aria-expanded', 'true');
        // prevent body scroll on mobile when sidebar open
        document.documentElement.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        sidebar.setAttribute('aria-hidden', 'true');
        overlay.classList.add('hidden');
        if (btnToggle) btnToggle.setAttribute('aria-expanded', 'false');
        document.documentElement.style.overflow = '';
    }

    // toggle handler
    function toggleSidebar() {
        if (sidebar.classList.contains('-translate-x-full')) {
            openSidebar();
        } else {
            closeSidebar();
        }
    }

    // button click
    if (btnToggle) {
        btnToggle.addEventListener('click', toggleSidebar);
    }

    // clicking overlay closes sidebar
    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }

    // close sidebar with ESC key on mobile
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
            closeSidebar();
        }
    });

    // page load fade in
    window.addEventListener('load', () => {
        document.body.classList.add('loaded');
    });
</script>

@stack('scripts')
</body>
</html>
