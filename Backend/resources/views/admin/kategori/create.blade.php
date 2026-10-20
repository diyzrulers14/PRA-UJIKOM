@extends('layouts.app')

@section('title', 'Tambah Kategori - Panel Admin')
@section('header-title', 'Tambah Kategori Baru')

@section('content')

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">

    {{-- Header --}}
    <div class="p-5 border-b border-gray-200 bg-gray-50">
        <h3 class="text-lg font-bold text-gray-800">
            Form Tambah Kategori
        </h3>

        <p class="text-sm text-gray-500 mt-1">
            Tambahkan kategori alat baru ke dalam sistem.
        </p>
    </div>

    {{-- Form --}}
    <div class="p-6">

        <form action="{{ route('admin.kategori.store') }}" method="POST">

            @csrf

            {{-- Nama Kategori --}}
            <div class="mb-6">

                <label
                    for="nama_kategori"
                    class="block text-gray-700 text-sm font-semibold mb-2">
                    Nama Kategori
                </label>

                <input
                    type="text"
                    id="nama_kategori"
                    name="nama_kategori"
                    value="{{ old('nama_kategori') }}"
                    required
                    autofocus
                    placeholder="Contoh: Jaringan, Mikrokontroler, Power Tools"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">

                @error('nama_kategori')
                    <span class="text-red-500 text-xs mt-1 block">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            {{-- Tombol --}}
            <div class="flex justify-end space-x-2">

                <a
                    href="{{ route('admin.kategori.index') }}"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg text-sm font-semibold transition">
                    Batal
                </a>

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection