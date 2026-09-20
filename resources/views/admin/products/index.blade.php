@extends('layouts.admin')

@section('title', 'Kelola Produk - Vyora Admin')

@section('content')

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
  <div>
    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Daftar Katalog Produk</h2>
    <p class="text-sm text-slate-500">Kelola rincian produk, harga promo, stok, dan gambar studio</p>
  </div>
  <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-md shadow-indigo-600/30 transition">
    <i class="fa-solid fa-plus"></i> Tambah Produk Baru
  </a>
</div>

<div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse text-sm">
      <thead>
        <tr class="bg-slate-50 text-slate-400 text-xs uppercase tracking-wider font-bold border-b border-slate-100">
          <th class="py-3 px-6">Item Produk</th>
          <th class="py-3 px-4">Kategori</th>
          <th class="py-3 px-4">Harga Normal</th>
          <th class="py-3 px-4">Harga Diskon</th>
          <th class="py-3 px-4">Stok Status</th>
          <th class="py-3 px-4">Status Katalog</th>
          <th class="py-3 px-6 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        @forelse($products as $product)
          <tr class="hover:bg-slate-50/80 transition">
            <td class="py-4 px-6">
              <div class="flex items-center gap-3">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-14 h-14 rounded-xl object-cover shadow-xs border border-slate-200">
                <div>
                  <div class="font-bold text-slate-900 text-sm">{{ $product->name }}</div>
                  <div class="text-xs text-slate-400">Slug: {{ $product->slug }}</div>
                </div>
              </div>
            </td>
            <td class="py-4 px-4">
              <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                {{ $product->category->name ?? 'Tanpa Kategori' }}
              </span>
            </td>
            <td class="py-4 px-4 font-bold text-slate-900">{{ $product->formatted_price }}</td>
            <td class="py-4 px-4">
              @if($product->discount_price && $product->discount_price > 0)
                @if($product->has_active_promo)
                  <div class="font-bold text-rose-600">{{ $product->formatted_discount_price }}</div>
                  @if($product->promo_ends_at)
                    <div class="text-[11px] text-amber-600 font-semibold mt-0.5">
                      <i class="fa-regular fa-clock me-1"></i>s.d {{ $product->promo_ends_at->format('d M Y H:i') }}
                    </div>
                  @else
                    <div class="text-[11px] text-emerald-600 font-medium mt-0.5">Tanpa Batas</div>
                  @endif
                @else
                  <div class="line-through text-slate-400 text-xs">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</div>
                  <div class="text-[11px] text-rose-500 font-semibold mt-0.5">Promo Berakhir</div>
                @endif
              @else
                <span class="text-slate-400">-</span>
              @endif
            </td>
            <td class="py-4 px-4">
              @if($product->stock > 0)
                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                  {{ $product->stock }} Pcs
                </span>
              @else
                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                  Habis
                </span>
              @endif
            </td>
            <td class="py-4 px-4">
              @if($product->is_active)
                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                  <i class="fa-solid fa-eye me-1"></i> Aktif
                </span>
              @else
                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500 border border-slate-200">
                  Non-Aktif
                </span>
              @endif
            </td>
            <td class="py-4 px-6 text-right space-x-1">
              <a href="{{ route('admin.products.edit', $product->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-indigo-600 hover:bg-indigo-600 hover:text-white transition" title="Edit">
                <i class="fa-solid fa-pen-to-square text-xs"></i>
              </a>
              <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-rose-600 hover:bg-rose-600 hover:text-white transition cursor-pointer" title="Hapus">
                  <i class="fa-solid fa-trash-can text-xs"></i>
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center py-10 text-slate-400 font-medium">Belum ada produk dalam katalog.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($products->hasPages())
    <div class="p-4 border-t border-slate-100 bg-white">
      {{ $products->links() }}
    </div>
  @endif
</div>

@endsection
