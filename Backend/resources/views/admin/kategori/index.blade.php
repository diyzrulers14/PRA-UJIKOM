@extends('layouts.app')

@section('title', 'Kelola Kategori - Panel Admin')
@section('header-title', 'Manajemen Kategori Alat')

@section('content')

{{-- Notifikasi sukses --}}
@if(session('success'))
    <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl shadow-sm text-sm">
        {{ session('success') }}
    </div>
@endif

{{-- Notifikasi error --}}
@if(session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 p-4 rounded-xl shadow-sm text-sm">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-indigo-100/80">

    {{-- Header / Search & Action Bar --}}
    <div class="p-5 border-b border-indigo-50 bg-indigo-50/20 flex flex-col md:flex-row md:justify-between md:items-center gap-4">

        <div>
            <h3 class="font-bold text-slate-800">
                Daftar Kategori Alat
            </h3>
            <p class="text-xs text-slate-400 mt-0.5">
                Kelola kategori alat yang tersedia dalam sistem.
            </p>
        </div>

        <div class="flex items-center gap-3 w-full md:w-auto">

            {{-- Form Search --}}
            <form action="{{ route('admin.kategori.index') }}"
                  method="GET"
                  class="flex w-full md:w-80">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama kategori..."
                    class="w-full px-3.5 py-2 text-sm border border-indigo-200 rounded-l-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white"
                >

                <button
                    type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 text-sm font-semibold rounded-r-xl transition">
                    Cari
                </button>

                @if(request('search'))
                    <a
                        href="{{ route('admin.kategori.index') }}"
                        class="ml-2 bg-slate-200 hover:bg-slate-300 text-slate-700 px-3 py-2 text-sm rounded-xl flex items-center transition">
                        Reset
                    </a>
                @endif

            </form>

            {{-- Tombol Tambah --}}
            <a
                href="{{ route('admin.kategori.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm shadow-indigo-600/25 transition whitespace-nowrap flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Kategori
            </a>

        </div>
    </div>

    {{-- Tabel --}}
    <div class="overflow-x-auto">

        <table class="w-full text-left border-collapse">

            <thead>
                <tr class="bg-indigo-50/40 text-slate-400 text-xs uppercase tracking-wider border-b border-indigo-50">

                    <th class="py-3.5 px-4 font-semibold w-16 text-center">
                        No
                    </th>

                    <th class="py-3.5 px-4 font-semibold">
                        Nama Kategori
                    </th>

                    <th class="py-3.5 px-4 font-semibold w-48 text-center">
                        Aksi
                    </th>

                </tr>
            </thead>

            <tbody class="text-slate-700 text-sm divide-y divide-slate-100">

                @forelse($kategoris as $index => $kategori)

                    <tr class="hover:bg-indigo-50/30 transition align-middle">

                        {{-- Nomor --}}
                        <td class="py-4 px-4 text-center font-medium text-slate-500">
                            {{ $kategoris->firstItem() + $index }}
                        </td>

                        {{-- Nama --}}
                        <td class="py-4 px-4 font-medium text-slate-800">
                            {{ $kategori->nama_kategori }}
                        </td>

                        {{-- Aksi --}}
                        <td class="py-4 px-4">

                            <div class="flex items-center justify-center gap-2">

                                {{-- Edit --}}
                                <a
                                    href="{{ route('admin.kategori.edit', $kategori->id) }}"
                                    class="bg-amber-50 hover:bg-amber-100 text-amber-600 border border-amber-200 px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center justify-center"
                                    title="Edit Data">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>

                                {{-- Hapus --}}
                                <form
                                    action="{{ route('admin.kategori.destroy', $kategori->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">

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
                        <td
                            colspan="3"
                            class="py-8 text-center text-slate-400 text-sm">
                            @if(request('search'))
                                Kategori "{{ request('search') }}" tidak ditemukan.
                            @else
                                Belum ada data kategori.
                            @endif
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- Pagination --}}
    @if($kategoris->hasPages())
        <div class="p-4 border-t border-indigo-50 bg-indigo-50/20">
            {{ $kategoris->links() }}
        </div>
    @endif

</div>

@endsection