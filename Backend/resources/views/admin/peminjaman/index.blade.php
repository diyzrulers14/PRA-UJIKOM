@extends('layouts.app')

@section('title', 'Kelola Peminjaman - Panel Admin')
@section('header-title', 'Manajemen Transaksi Peminjaman')

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

{{-- Kotak Statistik / Summary Cards dengan Animasi Slide-in dari Samping & Border Aksen Indigo --}}
<style>
    @keyframes slideInLeft {
        0% {
            opacity: 0;
            transform: translateX(-40px);
        }
        100% {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .animate-card-1 { animation: slideInLeft 0.5s ease-out 0.1s both; }
    .animate-card-2 { animation: slideInLeft 0.5s ease-out 0.2s both; }
    .animate-card-3 { animation: slideInLeft 0.5s ease-out 0.3s both; }
    .animate-card-4 { animation: slideInLeft 0.5s ease-out 0.4s both; }
</style>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    <!-- Card Sedang Dipinjam -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-indigo-100/80 hover:border-indigo-400/80 flex items-center justify-between animate-card-1 transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-pointer">
        <div>
            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Sedang Dipinjam</p>
            <h3 class="text-2xl font-bold text-indigo-600 mt-1">{{ $totalDipinjam ?? 0 }}</h3>
        </div>
        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shadow-inner">
            <i class="fas fa-hand-holding text-lg"></i>
        </div>
    </div>

    <!-- Card Menunggu Persetujuan (Diajukan) -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-indigo-100/80 hover:border-indigo-400/80 flex items-center justify-between animate-card-2 transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-pointer">
        <div>
            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Menunggu ACC</p>
            <h3 class="text-2xl font-bold text-amber-600 mt-1">{{ $totalDiajukan ?? 0 }}</h3>
        </div>
        <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center shadow-inner">
            <i class="fas fa-clock text-lg"></i>
        </div>
    </div>

    <!-- Card Terlambat (Telat) -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-indigo-100/80 hover:border-indigo-400/80 flex items-center justify-between animate-card-3 transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-pointer">
        <div>
            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Terlambat</p>
            <h3 class="text-2xl font-bold text-red-600 mt-1">{{ $totalTelat ?? 0 }}</h3>
        </div>
        <div class="w-12 h-12 bg-red-50 text-red-600 rounded-xl flex items-center justify-center shadow-inner">
            <i class="fas fa-exclamation-triangle text-lg"></i>
        </div>
    </div>

    <!-- Card Total Selesai -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-indigo-100/80 hover:border-indigo-400/80 flex items-center justify-between animate-card-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-pointer">
        <div>
            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Selesai</p>
            <h3 class="text-2xl font-bold text-emerald-600 mt-1">{{ $totalSelesai ?? 0 }}</h3>
        </div>
        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center shadow-inner">
            <i class="fas fa-check-circle text-lg"></i>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-indigo-100/80">

    {{-- Header / Search & Action Bar --}}
    <div class="p-5 border-b border-indigo-50 bg-indigo-50/20 flex flex-col md:flex-row justify-between items-center gap-4">

        <h3 class="font-bold text-slate-800">
            Daftar Transaksi Peminjaman
        </h3>

        <div class="flex items-center gap-3 w-full md:w-auto">

            {{-- Search Form --}}
            <form
                action="{{ route('admin.peminjaman.index') }}"
                method="GET"
                class="flex w-full md:w-80"
            >

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama peminjam / status..."
                    class="w-full px-3.5 py-2 text-sm border border-indigo-200 rounded-l-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white"
                >

                <button
                    type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 text-sm font-semibold rounded-r-xl transition"
                >
                    Cari
                </button>

                @if(request('search'))
                    <a
                        href="{{ route('admin.peminjaman.index') }}"
                        class="ml-2 bg-slate-200 hover:bg-slate-300 text-slate-700 px-3 py-2 text-sm rounded-xl flex items-center transition"
                    >
                        Reset
                    </a>
                @endif

            </form>

            {{-- Tambah Peminjaman --}}
            <a
                href="{{ route('admin.peminjaman.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm shadow-indigo-600/25 transition whitespace-nowrap flex items-center gap-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Peminjaman
            </a>

        </div>
    </div>


    {{-- Tabel Peminjaman --}}
    <div class="overflow-x-auto">

        <table class="w-full text-left border-collapse">

            <thead>
                <tr class="bg-indigo-50/40 text-slate-400 text-xs uppercase tracking-wider border-b border-indigo-50">

                    <th class="py-3.5 px-4 font-semibold">
                        Peminjam
                    </th>

                    <th class="py-3.5 px-4 font-semibold">
                        Alat yang Dipinjam
                    </th>

                    <th class="py-3.5 px-4 font-semibold">
                        Tgl Pinjam / Rencana Kembali
                    </th>

                    <th class="py-3.5 px-4 font-semibold">
                        Status
                    </th>

                    <th class="py-3.5 px-4 font-semibold text-center">
                        Aksi
                    </th>

                </tr>
            </thead>


            <tbody class="text-slate-700 text-sm divide-y divide-slate-100">

                @forelse($peminjaman as $item)

                    <tr class="hover:bg-indigo-50/30 transition align-top">

                        {{-- Peminjam --}}
                        <td class="py-4 px-4 font-medium text-slate-800">
                            {{ $item->user->name ?? 'User Dihapus' }}
                        </td>


                        {{-- Alat --}}
                        <td class="py-4 px-4">
                            @forelse($item->detailPinjam as $detail)
                                <div class="mb-1 flex items-center gap-2">
                                    <span class="font-medium text-slate-700">
                                        {{ $detail->alat->nama_alat ?? 'Alat Dihapus' }}
                                    </span>
                                    <span class="text-xs bg-indigo-50 text-indigo-600 border border-indigo-100 px-2 py-0.5 rounded-full font-semibold">
                                        {{ $detail->jumlah }} pcs
                                    </span>
                                </div>
                            @empty
                                <span class="text-slate-400 italic">
                                    Tidak ada alat
                                </span>
                            @endforelse
                        </td>


                        {{-- Tanggal --}}
                        <td class="py-4 px-4 text-xs text-slate-500 space-y-1">
                            <span class="block">
                                Pinjam: <span class="font-medium text-slate-700">{{ optional($item->tgl_pinjam)->format('d-m-Y') ?? '-' }}</span>
                            </span>
                            <span class="block">
                                Rencana: <span class="font-semibold text-slate-800">{{ optional($item->tgl_kembali_plan)->format('d-m-Y') ?? '-' }}</span>
                            </span>
                        </td>


                        {{-- Status --}}
                        <td class="py-4 px-4">
                            @if($item->status == 'diajukan')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-amber-50 text-amber-600 border border-amber-100">
                                    Diajukan
                                </span>
                            @elseif($item->status == 'dipinjam')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-600 border border-blue-100">
                                    Dipinjam
                                </span>
                            @elseif($item->status == 'selesai')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100">
                                    Selesai
                                </span>
                            @elseif($item->status == 'telat')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-50 text-red-600 border border-red-100">
                                    Telat
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ ucfirst($item->status) }}
                                </span>
                            @endif
                        </td>


                        {{-- Aksi --}}
                        <td class="py-4 px-4">
                            <div class="flex items-center justify-center gap-2">

                                {{-- Ubah Status Dropdown --}}
                                <form
                                    action="{{ route('admin.peminjaman.updateStatus', $item->id) }}"
                                    method="POST"
                                >
                                    @csrf
                                    @method('PUT')

                                    <select
                                        name="status"
                                        onchange="this.form.submit()"
                                        class="text-xs border border-indigo-200 bg-white rounded-lg px-2.5 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium text-slate-700 cursor-pointer shadow-sm"
                                    >
                                        <option value="diajukan" {{ $item->status == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                                        <option value="dipinjam" {{ $item->status == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                        <option value="selesai" {{ $item->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="telat" {{ $item->status == 'telat' ? 'selected' : '' }}>Telat</option>
                                    </select>
                                </form>

                                {{-- Hapus Tombol --}}
                                <form
                                    action="{{ route('admin.peminjaman.destroy', $item->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus data peminjaman ini?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center justify-center"
                                        title="Hapus Data"
                                    >
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
                        <td colspan="5" class="py-8 text-center text-slate-400 text-sm">
                            Belum ada data peminjaman.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- Pagination --}}  
    <div class="p-4 border-t border-indigo-50 bg-indigo-50/20">
        {{ $peminjaman->links() }}
    </div>

</div>

@endsection