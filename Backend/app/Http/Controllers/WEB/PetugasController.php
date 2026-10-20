<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Alat;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetugasController extends Controller
{
    // Menampilkan daftar pengajuan peminjaman
    public function indexPeminjaman(Request $request)
    {
        $search = $request->input('search');

        $peminjaman = Peminjaman::with(['user', 'detailPinjam.alat'])
            ->where('status', 'diajukan')
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        return view('petugas.peminjaman.index', compact('peminjaman', 'search'));
    }

    // Menyetujui peminjaman
    public function setujuiPeminjaman($id)
    {
        DB::beginTransaction();

        try {
            $peminjaman = Peminjaman::with('detailPinjam')
                ->findOrFail($id);

            // Ubah status menjadi dipinjam
            $peminjaman->update([
                'status' => 'dipinjam'
            ]);
            
            // Kurangi stok alat
            foreach ($peminjaman->detailPinjam as $detail) {
                $alat = Alat::findOrFail($detail->alat_id);

                $alat->stok -= $detail->jumlah;
                $alat->save();
            }

            // Catat aktivitas petugas
            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Menyetujui permohonan peminjaman ID #' . $peminjaman->id,
            ]);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Peminjaman disetujui dan stok alat dikurangi.');

        } catch (\Exception $e) {

            DB::rollback();

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function tolakPeminjaman($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        // Pastikan status awal masih 'diajukan'
        if ($peminjaman->status == 'diajukan') {
            $peminjaman->update([
                'status' => 'ditolak'
            ]);
    
            return redirect()->back()->with('success', 'Pengajuan peminjaman berhasil ditolak.');
        }
    
        return redirect()->back()->with('error', 'Status peminjaman sudah berubah atau tidak valid.');
    }

    // Menampilkan halaman pemantauan pengembalian
    public function indexPengembalian()
    {
        // Ambil data peminjaman yang statusnya sedang dipinjam atau sudah selesai dikembalikan
        $peminjaman = Peminjaman::with(['user', 'detailPinjam.alat', 'pengembalian'])
            ->whereIn('status', ['dipinjam', 'selesai'])
            ->latest()
            ->get();

        return view('petugas.pengembalian.index', compact('peminjaman'));
    }

    // Menampilkan halaman cetak laporan
    public function indexLaporan()
    {
        $peminjaman = Peminjaman::with(['user', 'detailPinjam.alat'])->latest()->get();
        return view('petugas.laporan.index', compact('peminjaman'));
    }

    // Memproses pengembalian alat
    public function prosesPengembalian($id)
    {
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::with('detailPinjam')->findOrFail($id);
            
            // Ubah status peminjaman jadi selesai
            $peminjaman->update(['status' => 'selesai']);

            // Kembalikan stok alat secara otomatis
            foreach ($peminjaman->detailPinjam as $detail) {
                $alat = Alat::findOrFail($detail->alat_id);
                $alat->stok += $detail->jumlah;
                $alat->save();
            }

            // Catat log aktivitas
            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Memproses pengembalian alat untuk peminjaman ID #' . $peminjaman->id,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Pengembalian berhasil diproses dan stok alat dikembalikan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function laporan(Request $request)
    {
        $status = $request->input('status');
        $dari_tanggal = $request->input('dari_tanggal');
        $sampai_tanggal = $request->input('sampai_tanggal');

        $laporan = Peminjaman::with(['user', 'detailPinjam.alat', 'pengembalian'])
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($dari_tanggal && $sampai_tanggal, function ($query) use ($dari_tanggal, $sampai_tanggal) {
                return $query->whereBetween('tgl_pinjam', [$dari_tanggal, $sampai_tanggal]);
            })
            ->latest()
            ->get();

        return view('petugas.laporan.index', compact('laporan', 'status', 'dari_tanggal', 'sampai_tanggal'));
    }

    // Menampilkan halaman khusus cetak (print preview)
    public function cetakLaporan(Request $request)
    {
        $status = $request->input('status');
        $dari_tanggal = $request->input('dari_tanggal');
        $sampai_tanggal = $request->input('sampai_tanggal');

        $laporan = Peminjaman::with(['user', 'detailPinjam.alat', 'pengembalian'])
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($dari_tanggal && $sampai_tanggal, function ($query) use ($dari_tanggal, $sampai_tanggal) {
                return $query->whereBetween('tgl_pinjam', [$dari_tanggal, $sampai_tanggal]);
            })
            ->latest()
            ->get();

        return view('petugas.laporan.cetak', compact('laporan', 'status', 'dari_tanggal', 'sampai_tanggal'));
    }
}
