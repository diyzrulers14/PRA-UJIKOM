<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Peminjaman Alat</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px }
        .header h2, .header p { margin-bottom: 20px; border-bottom: 2px solid #333; paddind-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; vertical-align: top; }
        th { background-color: #f4f4f4; }
        .text-center { text-align: center; }
        .footer { margin-top: 30px; float: right; text-align: center; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px; background: #e2e8f0; padding: 10px border-radius: 5px; text-align: right;">
        <button onclick="window.print()" style="background: #2563eb; color: #fff; border: none; padding: 8px 16px; border-radius; 4px: cursor: pointer; font-weight: bold;">
            Cetak Sekarang
        </button>
        <button onclick="window.close()" style="background: #64748b; color: #fff; border: none; padding: 8px 16px; border-radius; 4px: cursor: pointer; font-weight: bold; margin-left: 5px;">
            Tutup
        </button>
    </div>

    <div class=""header>
        <h2>LAPORAN PEMINJAMAN DAN PENGEMBALIAN ALAT</h2>
        <p>Sistem Informasi Manajemen Peminjaman Alat</p>
        @if($request_status = request('status'))
        <p style="font-size: 11px;">
            Periode: {{ request('dari_tanggal') }} s/d {{ request('sampai_tanggal') }}
        </p>
    @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="20%">Peminjam</th>
                <th width="15%">Tgl Pinjam</th>
                <th width="15%">Rencana Kembali</th>
                <th width="15%">Status</th>
                <th width="20%">Detail Alat</th>
                <th width="10%">Denda</th>
            </tr>
        </thead>

        <tbody>
            @forelse($laporan as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>

                    <td>{{ $item->user->name ?? '-' }}</td>

                    <td>{{ $item->tgl_pinjam }}</td>

                    <td>{{ $item->tgl_kembali_plan }}</td>

                    <td>
                        <span class="px-2.5 py-1 rounded text-xs font-semibold
                            {{ $item->status === 'selesai' ? 'bg-emerald-100 text-emerald-700' : '' }}
                            {{ $item->status === 'dipinjam' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $item->status === 'diajukan' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>

                    <td>
                        <ul class="list-disc list-inside space-y-1 text-xs">
                            @foreach($item->detailPinjam as $detail)
                                <li>
                                    {{ $detail->alat->nama_alat ?? '-' }}
                                    ({{ $detail->jumlah }})
                                </li>
                            @endforeach
                        </ul>
                    </td>

                    <td class="font-semibold">
                        Rp {{ number_format($item->pengembalian->denda ?? 0, 0, ',', '.') }}
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="7" class="py-6 text-center text-gray-500">
                        Tidak ada data laporan yang sesuai filter.
                    </td>
                </tr>
            @endforelse
</tbody>
</table>

    <div class="footer">
        <p>Balendah, {{ date('d F Y') }}</p>
        <p>Petugas Pengelola,</p>

        <br><br><br>

        <p><b>{{ auth()->user()->name }}</b></p>
    </div> 