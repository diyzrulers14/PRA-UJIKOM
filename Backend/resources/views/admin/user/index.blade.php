@extends('layouts.app')

@section('title', 'Kelola User - Panel Admin')
@section('header-title', 'Manajemen Pengguna Sistem')

@section('content')

{{-- Notifikasi sukses --}}
@if(session('success'))
    <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl shadow-sm text-sm">
        {{ session('success') }}
    </div>
@endif

{{-- Notifikasi error --}}
@if(session('error'))
    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl shadow-sm text-sm">
        {{ session('error') }}
    </div>
@endif

{{-- Kotak Statistik User --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">

    <!-- Total Admin -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-indigo-100/80 hover:border-indigo-400/80 flex items-center justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-pointer">
        <div>
            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Admin</p>
            <h3 class="text-2xl font-bold text-blue-600 mt-1">{{ $totalAdmin ?? 0 }}</h3>
        </div>
        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shadow-inner">
            <i class="fas fa-user-shield text-lg"></i>
        </div>
    </div>

    <!-- Total Petugas -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-indigo-100/80 hover:border-indigo-400/80 flex items-center justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-pointer">
        <div>
            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Petugas</p>
            <h3 class="text-2xl font-bold text-amber-600 mt-1">{{ $totalPetugas ?? 0 }}</h3>
        </div>
        <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center shadow-inner">
            <i class="fas fa-chalkboard-teacher text-lg"></i>
        </div>
    </div>

    <!-- Total Peminjam -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-indigo-100/80 hover:border-indigo-400/80 flex items-center justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-pointer">
        <div>
            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Peminjam</p>
            <h3 class="text-2xl font-bold text-emerald-600 mt-1">{{ $totalPeminjam ?? 0 }}</h3>
        </div>
        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center shadow-inner">
            <i class="fas fa-user-graduate text-lg"></i>
        </div>
    </div>

    <!-- Total Semua User -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-indigo-100/80 hover:border-indigo-400/80 flex items-center justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-pointer">
        <div>
            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Total User</p>
            <h3 class="text-2xl font-bold text-indigo-600 mt-1">{{ $totalUser ?? 0 }}</h3>
        </div>
        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shadow-inner">
            <i class="fas fa-users text-lg"></i>
        </div>
    </div>

</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-indigo-100/80">

    {{-- HEADER --}}
    <div class="p-5 border-b border-indigo-50 bg-indigo-50/20">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <h3 class="font-bold text-slate-800">
                Daftar Pengguna Sistem
            </h3>

            {{-- FORM SEARCH + TAMBAH USER --}}
            <div class="flex flex-col sm:flex-row gap-2.5 w-full md:w-auto">

                {{-- Search --}}
                <form action="{{ route('admin.user.index') }}" method="GET" class="flex w-full sm:w-auto">

                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Cari nama, email, role..."
                        class="w-full sm:w-64 px-3.5 py-2 text-sm border border-indigo-200 rounded-l-xl
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">

                    <button
                        type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold
                               px-4 py-2 rounded-r-xl transition">
                        Cari
                    </button>

                    {{-- Tombol Reset --}}
                    @if(!empty($search))
                        <a
                            href="{{ route('admin.user.index') }}"
                            class="ml-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-sm font-semibold
                                   px-3 py-2 rounded-xl flex items-center transition">
                            Reset
                        </a>
                    @endif

                </form>

                {{-- Tombol Tambah User --}}
                <a
                    href="{{ route('admin.user.create') }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold
                           px-4 py-2.5 rounded-xl shadow-sm shadow-indigo-600/25 transition whitespace-nowrap flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah User
                </a>

            </div>

        </div>

    </div>


    {{-- TABEL --}}
    <div class="overflow-x-auto">

        <table class="w-full text-left border-collapse">

            <thead>
                <tr class="bg-indigo-50/40 text-slate-400 text-xs uppercase tracking-wider border-b border-indigo-50">

                    <th class="py-3.5 px-4 font-semibold">
                        Nama
                    </th>

                    <th class="py-3.5 px-4 font-semibold">
                        Email
                    </th>

                    <th class="py-3.5 px-4 font-semibold">
                        Role / Hak Akses
                    </th>

                    <th class="py-3.5 px-4 font-semibold">
                        No. HP
                    </th>

                    <th class="py-3.5 px-4 font-semibold">
                        Alamat
                    </th>

                    <th class="py-3.5 px-4 font-semibold text-center">
                        Aksi
                    </th>

                </tr>
            </thead>

            <tbody class="text-slate-700 text-sm divide-y divide-slate-100">

                @forelse($users as $user)

                    <tr class="hover:bg-indigo-50/30 transition align-middle">

                        {{-- Nama --}}
                        <td class="py-4 px-4 font-medium text-slate-800">
                            {{ $user->name }}
                        </td>

                        {{-- Email --}}
                        <td class="py-4 px-4 text-slate-600">
                            {{ $user->email }}
                        </td>

                        {{-- Role --}}
                        <td class="py-4 px-4">

                            <span
                                class="px-3 py-1 rounded-full text-xs font-semibold border
                                @if($user->role == 'admin')
                                    bg-purple-50 text-purple-600 border-purple-100
                                @elseif($user->role == 'petugas')
                                    bg-blue-50 text-blue-600 border-blue-100
                                @elseif($user->role == 'guru')
                                    bg-indigo-50 text-indigo-600 border-indigo-100
                                @else
                                    bg-emerald-50 text-emerald-600 border-emerald-100
                                @endif">

                                {{ ucfirst($user->role) }}

                            </span>

                        </td>

                        {{-- No HP --}}
                        <td class="py-4 px-4 text-slate-600">
                            {{ $user->no_hp ?? '-' }}
                        </td>

                        {{-- Alamat --}}
                        <td class="py-4 px-4 text-slate-600">
                            @if($user->alamat)
                                {{ Str::limit($user->alamat, 50) }}
                            @else
                                -
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="py-4 px-4">
                            <div class="flex items-center justify-center gap-2">

                                {{-- Edit --}}
                                <a
                                    href="{{ route('admin.user.edit', $user->id) }}"
                                    class="bg-amber-50 hover:bg-amber-100 text-amber-600 border border-amber-200 px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center justify-center"
                                    title="Edit User">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>

                                {{-- Hapus --}}
                                <form
                                    action="{{ route('admin.user.destroy', $user->id) }}"
                                    method="POST"
                                    class="inline"
                                    onsubmit="return confirm('Yakin ingin menghapus user ini?')">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center justify-center"
                                        title="Hapus User">
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
                            colspan="5"
                            class="py-8 text-center text-slate-400 text-sm">

                            @if(!empty($search))
                                Tidak ditemukan user dengan kata kunci
                                "<strong class="text-slate-600">{{ $search }}</strong>".
                            @else
                                Belum ada data pengguna.
                            @endif

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- PAGINATION --}}
    @if($users->hasPages())

        <div class="p-4 border-t border-indigo-50 bg-indigo-50/20">

            {{ $users->links() }}

        </div>

    @endif

</div>

@endsection