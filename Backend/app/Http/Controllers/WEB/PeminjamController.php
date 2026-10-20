<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\DetailPinjam;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeminjamController extends Controller
{
    // Melihat daftar/katalog alat yang tersedia
    public function katalogAlat()
    {
        $alats = Alat::with('kategori')->where('stok', '>', 0)->get();
        return view('peminjam.katalog', compact('alats'));
    }

    public function ajukanPeminjaman(Request $request)
    {
        $alatIdsInput = $request->input('alat_id');

        if (!is_array($alatIdsInput) || empty($alatIdsInput)) {
            return redirect()->route('peinjaman.katalog')->with('error', 'Pilih minimal satu alat sebelum mengajukan peminjaman.');
        }

        $request->validate([
            'tgl_kembali_plan' => 'required|date|after:today',
            'alat_id' => 'required|array',
            'jumlah' => 'required|array',
        ]);

        $alatIds = array_filter($alatIdsInput, function ($id) {
            return $id > 0;
        });

        if (empty($alatIds)) {
            return redirect()->route('peinjaman.katalog')->with('error', 'Pilih minimal satu alat sebelum mengajukan peminjaman.');
        }

        DB::beginTransaction();
        try {
            // Buat header peminjaman
            $peminjaman = Peminjaman::create([
                'user_id' => auth()->id(),
                'tgl_pinjam' => now(),
                'tgl_kembali_plan' => $request->tgl_kembali_plan,
                'jumlah' => count($alatIds),
                'status' => 'diajukan',
            ]);

            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Mengajukan peminjaman alat baru untuk kebutuhan praktik.',
            ]);

        // Masukkan daftar alat yang dipinjam ke detail_pinjam menggunakan key asli dari input
            foreach ($alatIds as $alatId) {
                $jumlah = (int) ($request->input("jumlah.$alatId", 0));

                if ($jumlah > 0) {
                    DetailPinjam::create([
                        'peminjaman_id' => $peminjaman->id,
                        'alat_id' => $alatId,
                        'jumlah' => $jumlah,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('peminjam.riwayat')->with('success', 'Pengajuan peminjaman berhasil dikirim.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal mengajukan peminjaman: ' . $e->getMessage());
        }
    }

    public function riwayatPeminjam()
    {
        $userId = auth()->id();

        // Data riwayat peminjaman
        $peminjaman = Peminjaman::with('detailPinjams.alat')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        // Statistik Ringkas untuk User
        $totalDiajukan = Peminjaman::where('user_id', $userId)->where('status', 'diajukan')->count();
        $totalDipinjam = Peminjaman::where('user_id', $userId)->where('status', 'dipinjam')->count();
        
        // Cek jika status dipinjam tapi melewati rencana tanggal kembali
        $totalTerlambat = Peminjaman::where('user_id', $userId)
                            ->where('status', 'dipinjam')
                            ->where('tgl_kembali_plan', '<', Carbon::today())
                            ->count();

        // Total transaksi peminjaman user yang sudah selesai (dikembalikan)
        $totalSelesai = Peminjaman::where('user_id', $userId)
                            ->where('status', 'selesai')
                            ->count();

        return view('peminjam.riwayat', compact(
            'peminjaman', 
            'totalDiajukan', 
            'totalDipinjam', 
            'totalTerlambat', 
            'totalSelesai'
        ));
    }
}