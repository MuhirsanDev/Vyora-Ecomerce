@extends('layouts.admin')

@section('title', 'Kelola Kategori - Vyora Admin')

@section('content')

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
  <div>
    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Kelola Kategori Produk</h2>
    <p class="text-sm text-slate-500">Kelola pengelompokan produk toko Vyora</p>
  </div>
  <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-md shadow-indigo-600/30 transition">
    <i class="fa-solid fa-plus"></i> Tambah Kategori
  </a>
</div>

<div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse text-sm">
      <thead>
        <tr class="bg-slate-50 text-slate-400 text-xs uppercase tracking-wider font-bold border-b border-slate-100">
          <th class="py-3 px-6">Sampul</th>
          <th class="py-3 px-4">Nama Kategori</th>
          <th class="py-3 px-4">Slug</th>
          <th class="py-3 px-4">Jumlah Item</th>
          <th class="py-3 px-6 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        @forelse($categories as $cat)
          <tr class="hover:bg-slate-50/80 transition">
            <td class="py-4 px-6">
              @if($cat->image)
                <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="w-12 h-12 rounded-xl object-cover shadow-xs border border-slate-200">
              @else
                <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center border border-slate-200">
                  <i class="fa-solid fa-layer-group"></i>
                </div>
              @endif
            </td>
            <td class="py-4 px-4 font-bold text-slate-900">{{ $cat->name }}</td>
            <td class="py-4 px-4 text-slate-400 text-xs">{{ $cat->slug }}</td>
            <td class="py-4 px-4">
              <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                {{ $cat->products_count }} Produk
              </span>
            </td>
            <td class="py-4 px-6 text-right space-x-1">
              <a href="{{ route('admin.categories.edit', $cat->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-indigo-600 hover:bg-indigo-600 hover:text-white transition" title="Edit">
                <i class="fa-solid fa-pen-to-square text-xs"></i>
              </a>
              <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus kategori ini?')">
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
            <td colspan="5" class="text-center py-10 text-slate-400 font-medium">Belum ada kategori ditambahkan.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection
