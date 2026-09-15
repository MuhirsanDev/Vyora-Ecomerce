@extends('layouts.admin')

@section('title', 'Admin Dashboard - Vyora Fashion')

@section('content')

<!-- Welcome Hero Banner (Tailwind v4 Gradient) -->
<div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 p-8 text-white shadow-xl shadow-indigo-600/20 mb-8">
  <div class="relative z-10 max-w-3xl">
    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 text-xs font-semibold text-white backdrop-blur-md mb-4 border border-white/20">
      <i class="fa-solid fa-sparkles text-amber-300"></i> Dashboard Vyora Store
    </span>
    <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-3">Halo, {{ auth()->user()->name }}! 👋</h2>
    <p class="text-indigo-100 text-sm sm:text-base mb-6 leading-relaxed">
      Kelola katalog produk, atur diskon promo, buat kategori produk baru, dan atur nomor tujuan pemesanan WhatsApp secara langsung.
    </p>
    <div class="flex flex-wrap gap-3">
      <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white text-indigo-700 font-bold text-sm shadow-md hover:bg-slate-100 transition">
        <i class="fa-solid fa-plus text-indigo-600"></i> Tambah Produk Baru
      </a>
      <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-sm backdrop-blur-md border border-white/20 transition">
        <i class="fa-brands fa-whatsapp"></i> Atur No. WA Store
      </a>
    </div>
  </div>
</div>

<!-- 4 Key Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
  <!-- Total Produk -->
  <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition">
    <div class="flex items-center justify-between mb-4">
      <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Produk</span>
      <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 text-white flex items-center justify-center text-lg shadow-md shadow-purple-500/20">
        <i class="fa-solid fa-shirt"></i>
      </div>
    </div>
    <div class="text-3xl font-black text-slate-900 mb-2">{{ $totalProducts }}</div>
    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
      <i class="fa-solid fa-circle-check"></i> {{ $totalProducts }} Produk Aktif
    </span>
  </div>

  <!-- Total Kategori -->
  <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition">
    <div class="flex items-center justify-between mb-4">
      <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Kategori</span>
      <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-600 text-white flex items-center justify-center text-lg shadow-md shadow-emerald-500/20">
        <i class="fa-solid fa-layer-group"></i>
      </div>
    </div>
    <div class="text-3xl font-black text-slate-900 mb-2">{{ $totalCategories }}</div>
    <span class="text-xs text-slate-500 font-medium">Terbagi dalam {{ $totalCategories }} kategori</span>
  </div>

  <!-- Pelanggan -->
  <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition">
    <div class="flex items-center justify-between mb-4">
      <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pelanggan Terdaftar</span>
      <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 text-white flex items-center justify-center text-lg shadow-md shadow-amber-500/20">
        <i class="fa-solid fa-users"></i>
      </div>
    </div>
    <div class="text-3xl font-black text-slate-900 mb-2">{{ $totalCustomers }}</div>
    <span class="text-xs text-slate-500 font-medium">User mendaftar untuk belanja</span>
  </div>

  <!-- WA Info -->
  <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition">
    <div class="flex items-center justify-between mb-4">
      <span class="text-xs font-bold uppercase tracking-wider text-slate-400">No. WhatsApp Admin</span>
      <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-sky-400 to-blue-600 text-white flex items-center justify-center text-lg shadow-md shadow-sky-500/20">
        <i class="fa-brands fa-whatsapp"></i>
      </div>
    </div>
    <div class="text-xl font-extrabold text-slate-900 mb-2">+{{ $whatsappNumber }}</div>
    <a href="{{ route('admin.settings.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition inline-flex items-center gap-1">
      Ubah Nomor <i class="fa-solid fa-arrow-right text-[10px]"></i>
    </a>
  </div>
</div>

<!-- Main Content Layout -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
  
  <!-- Latest Products Table -->
  <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
      <div>
        <h3 class="text-base font-extrabold text-slate-900">Katalog Produk Terbaru</h3>
        <p class="text-xs text-slate-400">Daftar item produk yang aktif di landing page</p>
      </div>
      <a href="{{ route('admin.products.index') }}" class="px-3 py-1.5 rounded-lg border border-indigo-200 text-indigo-600 hover:bg-indigo-50 text-xs font-bold transition">
        Lihat Semua
      </a>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse text-sm">
        <thead>
          <tr class="bg-slate-50 text-slate-400 text-xs uppercase tracking-wider font-bold border-b border-slate-100">
            <th class="py-3 px-6">Item</th>
            <th class="py-3 px-4">Kategori</th>
            <th class="py-3 px-4">Harga Normal</th>
            <th class="py-3 px-4">Harga Diskon</th>
            <th class="py-3 px-4">Stok</th>
            <th class="py-3 px-6 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($latestProducts as $product)
            <tr class="hover:bg-slate-50/80 transition">
              <td class="py-4 px-6">
                <div class="flex items-center gap-3">
                  <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-xl object-cover shadow-xs border border-slate-200">
                  <div>
                    <div class="font-bold text-slate-900 text-sm">{{ $product->name }}</div>
                    <div class="text-xs text-slate-400">Slug: {{ $product->slug }}</div>
                  </div>
                </div>
              </td>
              <td class="py-4 px-4">
                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                  {{ $product->category->name ?? 'Umum' }}
                </span>
              </td>
              <td class="py-4 px-4 font-bold text-slate-900">{{ $product->formatted_price }}</td>
              <td class="py-4 px-4">
                @if($product->discount_price)
                  <span class="font-bold text-rose-600">{{ $product->formatted_discount_price }}</span>
                @else
                  <span class="text-slate-400">-</span>
                @endif
              </td>
              <td class="py-4 px-4">
                @if($product->stock > 0)
                  <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    {{ $product->stock }} Pcs
                  </span>
                @else
                  <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                    Habis
                  </span>
                @endif
              </td>
              <td class="py-4 px-6 text-right">
                <a href="{{ route('admin.products.edit', $product->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-indigo-600 hover:bg-indigo-600 hover:text-white transition" title="Edit">
                  <i class="fa-solid fa-pen-to-square text-xs"></i>
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center py-8 text-slate-400 font-medium">Belum ada produk.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Sidebar Quick Actions -->
  <div class="space-y-6">
    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
      <h4 class="text-sm font-extrabold text-slate-900 mb-4">Tautan Cepat Admin</h4>
      <div class="space-y-3">
        <a href="{{ route('admin.products.create') }}" class="flex items-center justify-between p-3.5 rounded-xl border border-slate-200/80 hover:border-indigo-300 hover:bg-indigo-50/50 transition group">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm">
              <i class="fa-solid fa-plus"></i>
            </div>
            <div>
              <div class="text-xs font-bold text-slate-900 group-hover:text-indigo-600">Tambah Produk</div>
              <div class="text-[11px] text-slate-400">Buat item baru di katalog</div>
            </div>
          </div>
          <i class="fa-solid fa-chevron-right text-xs text-slate-300 group-hover:text-indigo-600"></i>
        </a>

        <a href="{{ route('admin.categories.create') }}" class="flex items-center justify-between p-3.5 rounded-xl border border-slate-200/80 hover:border-emerald-300 hover:bg-emerald-50/50 transition group">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm">
              <i class="fa-solid fa-folder-plus"></i>
            </div>
            <div>
              <div class="text-xs font-bold text-slate-900 group-hover:text-emerald-600">Tambah Kategori</div>
              <div class="text-[11px] text-slate-400">Pengelompokan barang</div>
            </div>
          </div>
          <i class="fa-solid fa-chevron-right text-xs text-slate-300 group-hover:text-emerald-600"></i>
        </a>

        <a href="{{ route('admin.settings.index') }}" class="flex items-center justify-between p-3.5 rounded-xl border border-slate-200/80 hover:border-sky-300 hover:bg-sky-50/50 transition group">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-sky-100 text-sky-600 flex items-center justify-center text-sm">
              <i class="fa-brands fa-whatsapp"></i>
            </div>
            <div>
              <div class="text-xs font-bold text-slate-900 group-hover:text-sky-600">Pengaturan WA</div>
              <div class="text-[11px] text-slate-400">Ubah nomor penerima pesanan</div>
            </div>
          </div>
          <i class="fa-solid fa-chevron-right text-xs text-slate-300 group-hover:text-sky-600"></i>
        </a>
      </div>
    </div>
  </div>

</div>

@endsection
