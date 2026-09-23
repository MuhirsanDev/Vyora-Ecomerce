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

        <div>
          <label class="block text-sm font-bold text-slate-900 mb-2">Pilihan Warna Produk</label>
          <p class="text-xs text-slate-500 mb-3">Pilih warna yang tersedia untuk produk ini agar pengunjung dapat memilih saat membeli:</p>
          <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-4">
            @php
              $presetColors = ['Hitam', 'Coffee', 'Cream', 'Hijau', 'Merah', 'Navy', 'Putih', 'Pink', 'Lilac', 'Cokelat'];
              $selectedColors = old('colors', []);
            @endphp
            @foreach($presetColors as $c)
              <label class="inline-flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 cursor-pointer transition text-xs font-semibold text-slate-800">
                <input type="checkbox" name="colors[]" value="{{ $c }}" class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500" {{ in_array($c, $selectedColors) ? 'checked' : '' }}>
                <span>{{ $c }}</span>
              </label>
            @endforeach
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Warna Tambahan / Lainnya <span class="font-normal text-slate-400">(Opsional, pisahkan dengan koma)</span></label>
            <input type="text" name="custom_colors" class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500 text-sm font-medium transition" placeholder="Contoh: Maroon, Rose Gold, Sage" value="{{ old('custom_colors') }}">
          </div>
        </div>

        <div>
          <label class="block text-sm font-bold text-slate-900 mb-2">Pilihan Ukuran (Size) Produk</label>
          <p class="text-xs text-slate-500 mb-3">Pilih variasi ukuran yang tersedia untuk produk ini (bisa klik tombol aksi cepat):</p>
          
          <!-- Quick Action Buttons -->
          <div class="flex flex-wrap gap-2 mb-4">
            <button type="button" onclick="selectSizes(['36','37','38','39','40'])" class="px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition">
              <i class="fa-solid fa-shoe-prints me-1"></i> Sepatu 36-40
            </button>
            <button type="button" onclick="selectSizes(['S','M','L','XL'])" class="px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition">
              <i class="fa-solid fa-shirt me-1"></i> Baju S-XL
            </button>
            <button type="button" onclick="selectSizes(['27','28','29','30','31','32'])" class="px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition">
              <i class="fa-solid fa-ruler-horizontal me-1"></i> Celana 27-32
            </button>
            <button type="button" onclick="clearAllSizes()" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold transition">
              Reset Ukuran
            </button>
          </div>

          <!-- Presets Grid -->
          @php
            $presetSizes = [
              'Pakaian / Baju' => ['S', 'M', 'L', 'XL', 'XXL', 'XXXL', 'All Size'],
              'Sepatu' => ['36', '37', '38', '39', '40', '41', '42', '43', '44'],
              'Celana / Angka' => ['27', '28', '29', '30', '31', '32', '33', '34', '35', '36']
            ];
            $selectedSizes = old('sizes', []);
          @endphp

          <div class="space-y-4 mb-4">
            @foreach($presetSizes as $categoryName => $sizeGroup)
              <div>
                <span class="block text-xs font-bold text-slate-700 mb-2">{{ $categoryName }}:</span>
                <div class="grid grid-cols-3 sm:grid-cols-7 gap-2">
                  @foreach($sizeGroup as $s)
                    <label class="inline-flex items-center justify-center gap-1.5 p-2 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 cursor-pointer transition text-xs font-semibold text-slate-800">
                      <input type="checkbox" name="sizes[]" value="{{ $s }}" class="size-checkbox w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500" {{ in_array($s, $selectedSizes) ? 'checked' : '' }}>
                      <span>{{ $s }}</span>
                    </label>
                  @endforeach
                </div>
              </div>
            @endforeach
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Ukuran Tambahan / Lainnya <span class="font-normal text-slate-400">(Opsional, pisahkan dengan koma)</span></label>
            <input type="text" name="custom_sizes" class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500 text-sm font-medium transition" placeholder="Contoh: 110cm, EU 38, Jumbo" value="{{ old('custom_sizes') }}">
          </div>
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
          <label class="block text-sm font-bold text-slate-900 mb-2">Batas Waktu Promo (Hari & Jam) <span class="text-xs font-normal text-slate-400">(Opsional)</span></label>
          <input type="datetime-local" name="promo_ends_at" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500 text-sm font-medium transition bg-white" value="{{ old('promo_ends_at') }}">
          <span class="block mt-1 text-xs text-slate-400">Jika diisi, harga promo otomatis kadaluarsa & kembali ke harga normal pada jam ini. Kosongkan jika promo tanpa batas waktu.</span>
        </div>

        <div>
          <label class="block text-sm font-bold text-slate-900 mb-2">Stok (Pcs) <span class="text-rose-500">*</span></label>
          <input type="number" name="stock" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500 text-sm font-medium transition" value="{{ old('stock', 10) }}" required min="0">
        </div>

        <div>
          <label class="block text-sm font-bold text-slate-900 mb-2">Foto Utama Produk</label>
          <input type="file" name="image" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" accept="image/*">
          <span class="block mt-1 text-xs text-slate-400">Foto sampul utama katalog. Format: JPG, PNG, WEBP (Maks: 2MB)</span>
        </div>

        <div>
          <label class="block text-sm font-bold text-slate-900 mb-2">Foto Galeri Pendukung <span class="text-xs font-normal text-slate-400">(Opsional)</span></label>
          <input type="file" name="additional_images[]" multiple class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" accept="image/*">
          <span class="block mt-1 text-xs text-slate-400">Pilih beberapa foto sekaligus untuk galeri produk (detail bahan, varian, dll).</span>
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

@push('scripts')
<script>
  function selectSizes(arr) {
    document.querySelectorAll('.size-checkbox').forEach(cb => {
      if (arr.includes(cb.value)) {
        cb.checked = true;
      }
    });
  }
  function clearAllSizes() {
    document.querySelectorAll('.size-checkbox').forEach(cb => cb.checked = false);
  }
</script>
@endpush

@endsection
