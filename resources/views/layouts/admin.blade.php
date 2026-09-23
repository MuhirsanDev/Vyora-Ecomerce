<!DOCTYPE html>
<html lang="id">

<head>
  <title>@yield('title', 'Admin Dashboard - VYORA')</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  @php
    $faviconUrl = \App\Models\Setting::getFaviconUrl();
  @endphp
  <!-- Dynamic Favicon linked to Store Logo -->
  <link rel="icon" href="{{ $faviconUrl }}">
  <link rel="shortcut icon" href="{{ $faviconUrl }}">
  <link rel="apple-touch-icon" href="{{ $faviconUrl }}">

  <!-- Google Fonts: Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Tailwind CSS v4 CDN -->
  <script src="https://unpkg.com/@tailwindcss/browser@4"></script>

  <!-- FontAwesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    body {
      font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    }
  </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased min-h-screen">

  <!-- Mobile Backdrop Overlay -->
  <div id="sidebarBackdrop" class="fixed inset-0 bg-slate-900/50 z-40 hidden md:hidden transition-opacity"></div>

  <div class="flex min-h-screen relative">
    
    <!-- Sidebar Navigation (Responsive Mobile Drawer & Desktop Fixed) -->
    <aside id="adminSidebar" 
           class="fixed inset-y-0 left-0 z-50 w-64 bg-blue-600 border-r border-blue-700 text-white flex flex-col shrink-0 min-h-screen p-4 shadow-xl transform -translate-x-full md:translate-x-0 md:static md:z-auto transition-transform duration-300 ease-in-out">
      
      <!-- Brand Header & Mobile Close Button -->
      @php
        $logoPath = \App\Models\Setting::get('store_logo');
      @endphp
      <div class="py-4 border-b border-blue-500/60 mb-6 flex items-center justify-between px-2">
        <div class="flex items-center gap-2">
          @if($logoPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($logoPath))
            <img src="{{ asset('storage/' . $logoPath) }}" alt="Vyora Logo" class="h-8 w-auto max-w-[150px] object-contain drop-shadow-sm">
          @else
            <h1 class="text-2xl font-black text-white tracking-widest">VYORA</h1>
          @endif
        </div>
        <!-- Close button on mobile -->
        <button id="closeSidebarBtn" class="md:hidden text-white/80 hover:text-white p-2 rounded-lg hover:bg-blue-700 focus:outline-none cursor-pointer" title="Tutup Menu">
          <i class="fa-solid fa-xmark text-xl"></i>
        </button>
      </div>

      <!-- Navigation Links Grouped -->
      <nav class="flex-1 space-y-4 overflow-y-auto">
        <!-- Group 1: UTAMA -->
        <div>
          <span class="block px-4 mb-1.5 text-[10px] font-black uppercase tracking-wider text-blue-200/70">Utama</span>
          <a href="{{ route('admin.dashboard') }}" 
             class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-semibold transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-800 text-white shadow-md shadow-blue-900/40' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
            <i class="fa-solid fa-gauge-high w-5 text-center text-lg"></i>
            <span>Dashboard</span>
          </a>
        </div>

        <!-- Group 2: KATALOG & PRODUK -->
        <div>
          <span class="block px-4 mb-1.5 text-[10px] font-black uppercase tracking-wider text-blue-200/70">Katalog & Produk</span>
          <div class="space-y-1">
            <a href="{{ route('admin.products.index') }}" 
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-semibold transition-all duration-200 {{ request()->routeIs('admin.products.*') ? 'bg-blue-800 text-white shadow-md shadow-blue-900/40' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
              <i class="fa-solid fa-shirt w-5 text-center text-lg"></i>
              <span>Kelola Produk</span>
            </a>

            <a href="{{ route('admin.categories.index') }}" 
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-semibold transition-all duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-blue-800 text-white shadow-md shadow-blue-900/40' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
              <i class="fa-solid fa-tags w-5 text-center text-lg"></i>
              <span>Kategori</span>
            </a>

            <a href="{{ route('admin.sliders.index') }}" 
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-semibold transition-all duration-200 {{ request()->routeIs('admin.sliders.*') ? 'bg-blue-800 text-white shadow-md shadow-blue-900/40' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
              <i class="fa-solid fa-images w-5 text-center text-lg"></i>
              <span>Banner Slider</span>
            </a>
          </div>
        </div>

        <!-- Group 3: KEUANGAN & PEMBUKUAN -->
        <div>
          <span class="block px-4 mb-1.5 text-[10px] font-black uppercase tracking-wider text-blue-200/70">Keuangan & Laporan</span>
          <a href="{{ route('admin.financial.index') }}" 
             class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-semibold transition-all duration-200 {{ request()->routeIs('admin.financial.*') ? 'bg-blue-800 text-white shadow-md shadow-blue-900/40' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
            <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-lg"></i>
            <span>Pembukuan & Laporan</span>
          </a>
        </div>

        <!-- Group 4: PENGGUNA -->
        <div>
          <span class="block px-4 mb-1.5 text-[10px] font-black uppercase tracking-wider text-blue-200/70">Pengguna</span>
          <a href="{{ route('admin.users.index') }}" 
             class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-semibold transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-blue-800 text-white shadow-md shadow-blue-900/40' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
            <i class="fa-solid fa-users-gear w-5 text-center text-lg"></i>
            <span>Manajemen User</span>
          </a>
        </div>

        <!-- Group 5: PENGATURAN TOKO -->
        <div>
          <span class="block px-4 mb-1.5 text-[10px] font-black uppercase tracking-wider text-blue-200/70">Pengaturan</span>
          <a href="{{ route('admin.settings.index') }}" 
             class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-semibold transition-all duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-blue-800 text-white shadow-md shadow-blue-900/40' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
            <i class="fa-solid fa-sliders w-5 text-center text-lg"></i>
            <span>Pengaturan WA & Logo</span>
          </a>
        </div>
      </nav>

      <div class="pt-4 border-t border-blue-500/60 space-y-2">
        <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold text-white bg-blue-700/60 hover:bg-blue-700 transition">
          <i class="fa-solid fa-up-right-from-square text-blue-200"></i>
          <span>Lihat Website</span>
        </a>
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold text-rose-100 bg-rose-600 hover:bg-rose-700 transition cursor-pointer">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
            <span>Keluar</span>
          </button>
        </form>
      </div>

    </aside>

    <!-- Main Body Area -->
    <div class="flex-1 flex flex-col min-w-0">
      
      <!-- Top Navbar -->
      <header class="bg-white border-b border-slate-200 px-4 md:px-8 py-3.5 flex justify-between items-center shadow-xs">
        
        <!-- Toggle Button for Mobile -->
        <div class="flex items-center gap-3">
          <button id="openSidebarBtn" class="md:hidden p-2 rounded-xl text-slate-600 hover:text-blue-600 hover:bg-slate-100 focus:outline-none transition cursor-pointer" title="Buka Menu Sidebar">
            <i class="fa-solid fa-bars text-xl"></i>
          </button>
          <span class="font-black text-slate-800 text-lg md:hidden tracking-wider">VYORA</span>
        </div>

        <a href="{{ route('profile.show') }}" class="flex items-center gap-3 text-decoration-none group cursor-pointer" title="Edit Profil Saya">
          <div class="text-right hidden sm:block">
            <span class="block text-sm font-bold text-slate-800 group-hover:text-blue-600 transition">{{ auth()->user()->name }}</span>
          </div>
          <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold border border-blue-200 group-hover:bg-blue-600 group-hover:text-white transition">
            <i class="fa-solid fa-user text-lg"></i>
          </div>
        </a>
      </header>

      <!-- Main Content -->
      <main class="flex-1 p-4 md:p-8">
        @if(session('success'))
          <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 text-sm font-medium shadow-xs">
            <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
            <span class="flex-1">{{ session('success') }}</span>
          </div>
        @endif

        @if(session('error'))
          <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center gap-3 text-sm font-medium shadow-xs">
            <i class="fa-solid fa-triangle-exclamation text-rose-600 text-lg"></i>
            <span class="flex-1">{{ session('error') }}</span>
          </div>
        @endif

        @yield('content')
      </main>

    </div>

  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const sidebar = document.getElementById('adminSidebar');
      const backdrop = document.getElementById('sidebarBackdrop');
      const openBtn = document.getElementById('openSidebarBtn');
      const closeBtn = document.getElementById('closeSidebarBtn');

      function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        backdrop.classList.remove('hidden');
      }

      function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        backdrop.classList.add('hidden');
      }

      if (openBtn) openBtn.addEventListener('click', openSidebar);
      if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
      if (backdrop) backdrop.addEventListener('click', closeSidebar);

      // Prevent mouse wheel from accidentally changing number input values while scrolling
      document.addEventListener('wheel', function(e) {
        if (document.activeElement && document.activeElement.type === 'number') {
          document.activeElement.blur();
        }
      }, { passive: true });
    });
  </script>

  @stack('scripts')
</body>

</html>
