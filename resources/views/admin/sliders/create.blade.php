@extends('layouts.admin')

@section('title', 'Tambah Banner Slider - Vyora Admin')

@section('content')

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
  <div>
    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Tambah Banner Hero Slider Baru</h2>
    <p class="text-sm text-slate-500">Lengkapi formulir untuk membuat slide promo di beranda depan</p>
  </div>
  <a href="{{ route('admin.sliders.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-sm transition">
    <i class="fa-solid fa-arrow-left"></i> Kembali
  </a>
</div>

<div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-8 max-w-2xl">
  @if($errors->any())
    <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-medium">
      <ul class="list-disc ps-5 space-y-1">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <div>
      <label class="block text-sm font-bold text-slate-900 mb-2">Judul Banner Utama <span class="text-rose-500">*</span></label>
      <input type="text" name="title" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium transition" value="{{ old('title') }}" required placeholder="Contoh: Fashion Elegan, Harga Bersahabat">
    </div>

    <div>
      <label class="block text-sm font-bold text-slate-900 mb-2">Sub-Judul / Deskripsi Singkat</label>
      <input type="text" name="subtitle" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium transition" value="{{ old('subtitle') }}" placeholder="Contoh: Koleksi Musim Panas Terbaru 2026">
    </div>

    <div>
      <label class="block text-sm font-bold text-slate-900 mb-2">Foto / Gambar Banner</label>
      <input type="file" name="image" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer" accept="image/*">
    </div>

    <div>
      <label class="block text-sm font-bold text-slate-900 mb-2">Tautan Target / Link <span class="text-xs font-normal text-slate-400">(Opsional)</span></label>
      <input type="text" name="link" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium transition" value="{{ old('link') }}" placeholder="Contoh: #catalog atau /product/summer-dress">
    </div>

    <div>
      <label class="block text-sm font-bold text-slate-900 mb-2">Urutan Tampilan</label>
      <input type="number" name="order" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium transition" value="{{ old('order', 0) }}" min="0">
    </div>

    <div class="flex items-center gap-3 pt-2">
      <input type="checkbox" name="is_active" value="1" id="is_active" class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500" checked>
      <label for="is_active" class="text-sm font-bold text-slate-900 cursor-pointer">Tampilkan Banner di Beranda</label>
    </div>

    <button type="submit" class="w-full py-3 px-6 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-600/30 transition cursor-pointer">
      Simpan Banner
    </button>
  </form>
</div>

@endsection
