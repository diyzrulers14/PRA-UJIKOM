@extends('layouts.app')

@section('title', 'Kelola Alat - Panel Admin')
@section('header-title', 'Manajemen Data Alat')

@section('content')

@if(session('success'))
    <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl shadow-sm text-sm">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 p-4 rounded-xl shadow-sm text-sm">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-indigo-100/80">

    {{-- Header / Search & Action Bar --}}
    <div class="p-5 border-b border-indigo-50 bg-indigo-50/20 flex flex-col md:flex-row justify-between items-center gap-4">

        <h3 class="font-bold text-slate-800">
            Daftar Alat 
        </h3>

        <div class="flex items-center gap-3 w-full md:w-auto">

            {{-- Form Search --}}
            <form action="{{ route('admin.alat.index') }}"
                  method="GET"
                  class="flex w-full md:w-80">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama alat, kategori..."
                    class="w-full px-3.5 py-2 text-sm border border-indigo-200 rounded-l-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">

                <button
                    type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 text-sm font-semibold rounded-r-xl transition">
                    Cari
                </button>

                @if(request('search'))
                    <a
                        href="{{ route('admin.alat.index') }}"
                        class="ml-2 bg-slate-200 hover:bg-slate-300 text-slate-700 px-3 py-2 text-sm rounded-xl flex items-center transition">
                        Reset
                    </a>
                @endif

            </form>

            {{-- Tombol Tambah --}}
            <a
                href="{{ route('admin.alat.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm shadow-indigo-600/25 transition whitespace-nowrap flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Alat
            </a>

        </div>
    </div>

    {{-- Tabel --}}
    <div class="overflow-x-auto">

        <table class="w-full text-left border-collapse">

            <thead>
                <tr class="bg-indigo-50/40 text-slate-400 text-xs uppercase tracking-wider border-b border-indigo-50">
                    <th class="py-3.5 px-4 font-semibold">Gambar</th>
                    <th class="py-3.5 px-4 font-semibold">Nama Alat</th>
                    <th class="py-3.5 px-4 font-semibold">Kategori</th>
                    <th class="py-3.5 px-4 font-semibold">Stok</th>
                    <th class="py-3.5 px-4 font-semibold">Kondisi</th>
                    <th class="py-3.5 px-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="text-slate-700 text-sm divide-y divide-slate-100">

                @forelse($alat as $item)

                    <tr class="hover:bg-indigo-50/30 transition align-middle">

                        {{-- Gambar --}}
                        <td class="py-4 px-4">
                            @if($item->gambar)
                                <img
                                    src="{{ asset('storage/' . $item->gambar) }}"
                                    alt="{{ $item->nama_alat }}"
                                    class="w-12 h-12 object-cover rounded-xl border border-indigo-100 shadow-sm">
                            @else
                                <span class="text-xs text-slate-400 italic bg-slate-100 px-2.5 py-1 rounded-lg">
                                    Tidak ada
                                </span>
                            @endif
                        </td>

                        {{-- Nama --}}
                        <td class="py-4 px-4 font-medium text-slate-800">
                            {{ $item->nama_alat }}
                        </td>

                        {{-- Kategori --}}
                        <td class="py-4 px-4 text-slate-600">
                            {{ $item->kategori->nama_kategori ?? '-' }}
                        </td>

                        {{-- Stok --}}
                        <td class="py-4 px-4 font-semibold text-slate-800">
                            <span class="bg-indigo-50 text-indigo-600 border border-indigo-100 px-2.5 py-1 rounded-full text-xs">
                                {{ $item->stok }} pcs
                            </span>
                        </td>

                        {{-- Kondisi --}}
                        <td class="py-4 px-4">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                @if(strtolower($item->status_kondisi) == 'baik')
                                    bg-emerald-50 text-emerald-600 border border-emerald-100
                                @else
                                    bg-amber-50 text-amber-600 border border-amber-100
                                @endif">
                                {{ $item->status_kondisi }}
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td class="py-4 px-4">
                            <div class="flex items-center justify-center gap-2">

                                {{-- Edit --}}
                                <a
                                    href="{{ route('admin.alat.edit', $item->id) }}"
                                    class="bg-amber-50 hover:bg-amber-100 text-amber-600 border border-amber-200 px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center justify-center"
                                    title="Edit Data">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>

                                {{-- Hapus --}}
                                <form
                                    action="{{ route('admin.alat.destroy', $item->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus alat ini?')">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center justify-center"
                                        title="Hapus Data">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1.001 1.001 0 00-1-1h-4a1.001 1.001 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>

                                </form>

                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400 text-sm">
                            Belum ada data alat.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- Pagination --}}
    <div class="p-4 border-t border-indigo-50 bg-indigo-50/20">
        {{ $alat->links() }}
    </div>

</div>

@endsection