@extends('layouts.admin')

@section('title', 'Kelola Banner Slider - Vyora Admin')

@section('content')

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
  <div>
    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Kelola Banner Hero Slider</h2>
    <p class="text-sm text-slate-500">Atur gambar banner promo, judul, dan urutan tampilan di beranda utama</p>
  </div>
  <a href="{{ route('admin.sliders.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-600/30 transition">
    <i class="fa-solid fa-plus"></i> Tambah Banner Baru
  </a>
</div>

<div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse text-sm">
      <thead>
        <tr class="bg-slate-50 text-slate-400 text-xs uppercase tracking-wider font-bold border-b border-slate-100">
          <th class="py-3 px-6">Gambar Banner</th>
          <th class="py-3 px-4">Judul Banner</th>
          <th class="py-3 px-4">Sub-Judul</th>
          <th class="py-3 px-4">Urutan</th>
          <th class="py-3 px-4">Status</th>
          <th class="py-3 px-6 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        @forelse($sliders as $slider)
          <tr class="hover:bg-slate-50/80 transition">
            <td class="py-4 px-6">
              <img src="{{ $slider->image_url }}" alt="{{ $slider->title }}" class="w-24 h-14 rounded-xl object-cover shadow-xs border border-slate-200">
            </td>
            <td class="py-4 px-4 font-bold text-slate-900">{{ $slider->title }}</td>
            <td class="py-4 px-4 text-slate-500 text-xs">{{ $slider->subtitle ?? '-' }}</td>
            <td class="py-4 px-4">
              <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                #{{ $slider->order }}
              </span>
            </td>
            <td class="py-4 px-4">
              @if($slider->is_active)
                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                  <i class="fa-solid fa-eye me-1"></i> Tampil
                </span>
              @else
                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500 border border-slate-200">
                  Disembunyikan
                </span>
              @endif
            </td>
            <td class="py-4 px-6 text-right space-x-1">
              <a href="{{ route('admin.sliders.edit', $slider->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-blue-600 hover:bg-blue-600 hover:text-white transition" title="Edit">
                <i class="fa-solid fa-pen-to-square text-xs"></i>
              </a>
              <form action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus banner ini?')">
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
            <td colspan="6" class="text-center py-10 text-slate-400 font-medium">Belum ada banner slider ditambahkan.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($sliders->hasPages())
    <div class="p-4 border-t border-slate-100 bg-white">
      {{ $sliders->links() }}
    </div>
  @endif
</div>

@endsection
