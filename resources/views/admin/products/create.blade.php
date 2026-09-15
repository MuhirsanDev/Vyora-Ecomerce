@extends('layouts.admin')

@section('title', 'Tambah Produk Baru - Vyora Admin')

@section('content')

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
  <div>
    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Tambah Produk Baru</h2>
    <p class="text-sm text-slate-500">Lengkapi data produk untuk menambahkannya ke katalog</p>
  </div>
  <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-sm transition">
    <i class="fa-solid fa-arrow-left"></i> Kembali
  </a>
</div>

<div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-8">
  @if($errors->any())
    <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-medium">
      <ul class="list-disc ps-5 space-y-1">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <div class="lg:col-span-2 space-y-6">
        <div>
          <label class="block text-sm font-bold text-slate-900 mb-2">Nama Produk <span class="text-rose-500">*</span></label>
          <input type="text" name="name" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500 text-sm font-medium transition" value="{{ old('name') }}" required placeholder="Contoh: Dress Floral Musim Panas">
        </div>

        <div>
          <label class="block text-sm font-bold text-slate-900 mb-2">Kategori Produk</label>
          <select name="category_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500 text-sm font-medium transition bg-white">
            <option value="">-- Pilih Kategori --</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="block text-sm font-bold text-slate-900 mb-2">Deskripsi Produk</label>
          <textarea name="description" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500 text-sm font-medium transition" rows="6" placeholder="Tuliskan rincian bahan, ukuran, dan deskripsi produk...">{{ old('description') }}</textarea>
        </div>
      </div>

      <div class="space-y-6">
        <div>
          <label class="block text-sm font-bold text-slate-900 mb-2">Harga Normal (Rp) <span class="text-rose-500">*</span></label>
          <input type="number" name="price" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500 text-sm font-medium transition" value="{{ old('price') }}" required placeholder="150000" min="0">
        </div>

        <div>
          <label class="block text-sm font-bold text-slate-900 mb-2">Harga Diskon (Rp) <span class="text-xs font-normal text-slate-400">(Opsional)</span></label>
          <input type="number" name="discount_price" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500 text-sm font-medium transition" value="{{ old('discount_price') }}" placeholder="120000" min="0">
        </div>

        <div>
          <label class="block text-sm font-bold text-slate-900 mb-2">Stok (Pcs) <span class="text-rose-500">*</span></label>
          <input type="number" name="stock" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500 text-sm font-medium transition" value="{{ old('stock', 10) }}" required min="0">
        </div>

        <div>
          <label class="block text-sm font-bold text-slate-900 mb-2">Foto Produk</label>
          <input type="file" name="image" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" accept="image/*">
          <span class="block mt-1 text-xs text-slate-400">Format: JPG, PNG, WEBP (Maks: 2MB)</span>
        </div>

        <div class="flex items-center gap-3 pt-2">
          <input type="checkbox" name="is_active" value="1" id="is_active" class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500" checked>
          <label for="is_active" class="text-sm font-bold text-slate-900 cursor-pointer">Aktifkan Produk di Katalog</label>
        </div>

        <button type="submit" class="w-full py-3 px-6 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-md shadow-indigo-600/30 transition cursor-pointer">
          Simpan Produk
        </button>
      </div>
    </div>
  </form>
</div>

@endsection
