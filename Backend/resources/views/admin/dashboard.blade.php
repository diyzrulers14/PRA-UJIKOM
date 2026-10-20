@extends('layouts.app')

@section('title', 'Dashboard Admin - Sistem Peminjaman')

@section('header-title', 'Ringkasan Aktivitas Sistem')

@section('content')
<div class="space-y-6">
    
    {{-- Header Sambutan dengan Aksen Indigo Premium --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-gradient-to-r from-indigo-50/70 via-white to-white p-6 rounded-2xl shadow-sm border border-indigo-100/80 relative overflow-hidden">
        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-indigo-600"></div>

        <div>
            <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>
            <p class="text-sm text-slate-500 mt-0.5">Selamat datang, <span class="font-semibold text-slate-700">{{ Auth::user()->name }}</span>. Berikut ringkasan sistem hari ini.</p>
        </div>
        
        <a href="{{ route('admin.peminjaman.index') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-sm shadow-indigo-600/20 transition-all duration-200 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Transaksi Baru
        </a>
    </div>

    {{-- CSS Keyframes untuk Animasi Slide-in dari Atas --}}
<style>
    @keyframes slideInFromTop {
        0% {
            opacity: 0;
            transform: translateY(-25px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-card-1 { animation: slideInFromTop 0.5s ease-out 0.1s both; }
    .animate-card-2 { animation: slideInFromTop 0.5s ease-out 0.2s both; }
    .animate-card-3 { animation: slideInFromTop 0.5s ease-out 0.3s both; }
    .animate-card-4 { animation: slideInFromTop 0.5s ease-out 0.4s both; }
</style>

{{-- Kotak-Kotak Statistik (Cards) dengan Border Aksen Indigo --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        
    {{-- Card 1: Total Alat --}}
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-indigo-100/80 hover:border-indigo-400/80 flex items-center justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-pointer">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Alat</p>
            <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $totalAlat }}</h3>
        </div>
        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shadow-inner">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
        </div>
    </div>

    {{-- Card 2: Peminjaman Aktif --}}
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-indigo-100/80 hover:border-indigo-400/80 flex items-center justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-pointer">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Peminjaman Aktif</p>
            <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $totalPeminjaman }}</h3>
        </div>
        <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center shadow-inner">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </div>

    {{-- Card 3: Total Pengguna --}}
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-indigo-100/80 hover:border-indigo-400/80 flex items-center justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-pointer">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Pengguna</p>
            <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $totalUser }}</h3>
        </div>
        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shadow-inner">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
        </div>
    </div>

    {{-- Card 4: Total Selesai --}}
    <div class="bg-slate-900 text-white p-5 rounded-2xl shadow-md border border-indigo-900 hover:border-indigo-500 flex items-center justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-lg cursor-pointer">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Selesai</p>
            <h3 class="text-2xl font-bold mt-1">{{ $totalPengembalian }}</h3>
        </div>
        <div class="w-12 h-12 bg-slate-800 text-emerald-400 rounded-xl flex items-center justify-center shadow-inner">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </div>

</div>

    {{-- Bagian Bawah: Tabel Transaksi Terbaru & Quick Actions --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
    {{-- Log Aktivitas Terbaru (Ditambahkan lg:col-span-2 di sini) --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-indigo-100/80 p-5 lg:col-span-2">

        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-slate-800">
                Log Aktivitas Terbaru
            </h3>
            <span class="text-xs text-slate-400">
                Aktivitas sistem terakhir
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-indigo-50/40 text-slate-400 text-xs uppercase tracking-wider border-b border-indigo-50">
                        <th class="py-3.5 px-4 font-semibold">Waktu</th>
                        <th class="py-3.5 px-4 font-semibold">User</th>
                        <th class="py-3.5 px-4 font-semibold">Aktivitas</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700 text-sm divide-y divide-slate-100">
                    @forelse($logs ?? [] as $log)
                        <tr class="hover:bg-indigo-50/30 transition">
                            <td class="py-3.5 px-4 text-xs text-slate-500 font-medium">
                                {{ $log->created_at ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-800">
                                {{ $log->user->name ?? 'Sistem' }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 text-xs">
                                {{ $log->aktivitas ?? $log->description ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-slate-400 text-sm">
                                Belum ada log aktivitas sistem.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    {{-- Quick Actions / Pintasan Cepat (1 Kolom di sebelah kanan) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-indigo-100/80 p-5 space-y-4">
        <h2 class="font-bold text-slate-800">Quick Actions</h2>
        <div class="space-y-2.5">
            <a href="{{ route('admin.peminjaman.index') }}" class="flex items-center gap-3 p-3 bg-indigo-50/40 hover:bg-indigo-50 hover:text-indigo-900 rounded-xl text-sm font-medium text-slate-700 transition group">
                <span class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold group-hover:scale-105 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </span>
                Kelola Peminjaman
            </a>
            <a href="{{ route('admin.pengembalian.index') }}" class="flex items-center gap-3 p-3 bg-indigo-50/40 hover:bg-indigo-50 hover:text-indigo-900 rounded-xl text-sm font-medium text-slate-700 transition group">
                <span class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold group-hover:scale-105 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </span>
                Kelola Pengembalian
            </a>
        </div>
    </div>

</div>

</div>
@endsection