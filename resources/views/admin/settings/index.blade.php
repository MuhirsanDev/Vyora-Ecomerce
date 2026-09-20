@extends('layouts.admin')

@section('title', 'Pengaturan Toko, Logo & WhatsApp - Vyora Admin')

@section('content')

<div class="mb-8">
  <h2 class="text-2xl font-black text-slate-900 tracking-tight">Pengaturan Toko, Logo & WhatsApp</h2>
  <p class="text-sm text-slate-500">Atur logo toko Vyora, nama brand, nomor WhatsApp penerima pesanan, dan template pesan</p>
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

  <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <!-- Logo Upload Section -->
    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-4">
      <label class="block text-sm font-bold text-slate-900">
        <i class="fa-solid fa-image me-1 text-indigo-600"></i> Logo Toko Vyora
      </label>
      
      @if($storeLogo)
        <div class="flex items-center gap-4 p-3 bg-white rounded-xl border border-slate-200">
          <img src="{{ asset('storage/' . $storeLogo) }}" alt="Logo Vyora" class="h-14 w-auto object-contain rounded-lg">
          <div>
            <span class="block text-xs font-bold text-emerald-600"><i class="fa-solid fa-check-circle"></i> Logo Aktif Terpasang</span>
            <span class="text-xs text-slate-400">Pilih file baru di bawah jika ingin mengganti logo.</span>
          </div>
        </div>
      @else
        <div class="p-4 bg-white rounded-xl border border-dashed border-slate-300 text-center text-slate-400 text-xs">
          Belum ada logo khusus. Teks brand <strong>VYORA</strong> akan digunakan sebagai fallback.
        </div>
      @endif

      <input type="file" name="store_logo" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer" accept="image/*">
      <p class="text-xs text-slate-400">Rekomendasi: Gambar PNG transparan atau JPG berkualitas baik (Maks: 2MB).</p>
    </div>

    <div>
      <label class="block text-sm font-bold text-slate-900 mb-2">Nama Toko</label>
      <input type="text" name="store_name" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-medium transition" value="{{ old('store_name', $storeName) }}" required>
    </div>

    <div>
      <label class="block text-sm font-bold text-slate-900 mb-2">
        <i class="fa-solid fa-location-dot me-1 text-rose-600"></i> Alamat Toko <span class="text-rose-500">*</span>
      </label>
      <input type="text" name="store_address" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-medium transition" value="{{ old('store_address', $storeAddress) }}" required placeholder="Jl. Raya Rangkasbitung No. 8, Kareo, Serang, Kabupaten Serang, Banten 42177">
    </div>

    <div>
      <label class="block text-sm font-bold text-slate-900 mb-2">
        Nomor WhatsApp Admin <span class="text-rose-500">*</span>
      </label>
      <div class="flex rounded-xl overflow-hidden border border-slate-300 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500">
        <span class="inline-flex items-center px-4 bg-slate-100 text-slate-500 text-sm border-r border-slate-300">
          <i class="fa-brands fa-whatsapp text-emerald-600 text-lg"></i>
        </span>
        <input type="text" name="whatsapp_number" class="w-full px-4 py-2.5 text-sm font-medium focus:outline-none" value="{{ old('whatsapp_number', $whatsappNumber) }}" placeholder="Contoh: 081234567890 atau 6281234567890" required>
      </div>
      <p class="mt-1.5 text-xs text-slate-500">Nomor ini akan digunakan sebagai rujukan otomatis saat pembeli melakukan order via WhatsApp.</p>
    </div>

    <div>
      <label class="block text-sm font-bold text-slate-900 mb-2">Pesan Pembuka Otomatis</label>
      <textarea name="whatsapp_message" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-medium transition" rows="4" required>{{ old('whatsapp_message', $whatsappMessage) }}</textarea>
      <p class="mt-1.5 text-xs text-slate-500">Pesan ini akan muncul di baris teratas saat WhatsApp otomatis terbuka di perangkat pembeli.</p>
    </div>

    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-600/30 transition cursor-pointer">
      <i class="fa-solid fa-floppy-disk"></i> Simpan Pengaturan & Logo
    </button>
  </form>
</div>

@endsection
