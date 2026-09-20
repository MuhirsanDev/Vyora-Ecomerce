@extends('layouts.admin')

@section('title', 'Edit Produk - Vyora Admin')

@section('content')

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
  <div>
    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Edit Produk</h2>
    <p class="text-sm text-slate-500">Ubah rincian item #{{ $product->id }} - {{ $product->name }}</p>
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

  <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <div class="lg:col-span-2 space-y-6">
        <div>
          <label class="block text-sm font-bold text-slate-900 mb-2">Nama Produk <span class="text-rose-500">*</span></label>
          <input type="text" name="name" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500 text-sm font-medium transition" value="{{ old('name', $product->name) }}" required>
        </div>

        <div>
          <label class="block text-sm font-bold text-slate-900 mb-2">Kategori Produk</label>
          <select name="category_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500 text-sm font-medium transition bg-white">
            <option value="">-- Pilih Kategori --</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="block text-sm font-bold text-slate-900 mb-2">Deskripsi Produk</label>
          <textarea name="description" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500 text-sm font-medium transition" rows="6">{{ old('description', $product->description) }}</textarea>
        </div>
      </div>

      <div class="space-y-6">
        <div>
          <label class="block text-sm font-bold text-slate-900 mb-2">Harga Normal (Rp) <span class="text-rose-500">*</span></label>
          <input type="number" name="price" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500 text-sm font-medium transition" value="{{ old('price', (int)$product->price) }}" required min="0">
        </div>

        <div>
          <label class="block text-sm font-bold text-slate-900 mb-2">Harga Diskon (Rp)</label>
          <input type="number" name="discount_price" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500 text-sm font-medium transition" value="{{ old('discount_price', $product->discount_price ? (int)$product->discount_price : '') }}" min="0">
        </div>

        <div>
          <label class="block text-sm font-bold text-slate-900 mb-2">Batas Waktu Promo (Hari & Jam) <span class="text-xs font-normal text-slate-400">(Opsional)</span></label>
          <input type="datetime-local" name="promo_ends_at" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500 text-sm font-medium transition bg-white" value="{{ old('promo_ends_at', $product->promo_ends_at ? $product->promo_ends_at->format('Y-m-d\TH:i') : '') }}">
          <span class="block mt-1 text-xs text-slate-400">Jika diisi, harga promo otomatis kadaluarsa & kembali ke harga normal pada jam ini. Kosongkan jika promo tanpa batas waktu.</span>
        </div>

        <div>
          <label class="block text-sm font-bold text-slate-900 mb-2">Stok (Pcs) <span class="text-rose-500">*</span></label>
          <input type="number" name="stock" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500 text-sm font-medium transition" value="{{ old('stock', $product->stock) }}" required min="0">
        </div>

        <div>
          <label class="block text-sm font-bold text-slate-900 mb-2">Foto Produk Saat Ini</label>
          <div class="mb-3">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-24 h-24 rounded-xl object-cover shadow-xs border border-slate-200">
          </div>
          <input type="file" name="image" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" accept="image/*">
        </div>

        <div class="flex items-center gap-3 pt-2">
          <input type="checkbox" name="is_active" value="1" id="is_active" class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
          <label for="is_active" class="text-sm font-bold text-slate-900 cursor-pointer">Aktifkan Produk di Katalog</label>
        </div>

        <button type="submit" class="w-full py-3 px-6 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-md shadow-indigo-600/30 transition cursor-pointer">
          Update Produk
        </button>
      </div>
    </div>
  </form>
</div>

@endsection
