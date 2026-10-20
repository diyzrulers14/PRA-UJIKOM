<?php

namespace App\Http\Controllers\WEB;

use Carbon\Carbon;
use App\Models\DetailPinjam;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Alat;
use App\Models\Kategori;
use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // ==========================
    // DASHBOARD
    // ==========================
    public function index() // atau method dashboard kamu
    {
        $totalAlat = Alat::count();
        $totalPeminjaman = Peminjaman::where('status', 'dipinjam')->count();
        $totalUser = User::count();
        $totalPengembalian = Peminjaman::where('status', 'selesai')->count(); // Sesuaikan status selesai jika berbeda
        
        $logs = LogAktivitas::with('user')->latest()->take(5)->get(); 

        return view('admin.dashboard', compact (
            'totalAlat',
            'totalPeminjaman',
            'totalUser',
            'totalPengembalian',
            'logs'
        ));
    
        $totalAlat = \App\Models\Alat::count();
        $totalPeminjaman = \App\Models\Peminjaman::whereIn('status', ['dipinjam', 'telat'])->count();
        $totalUser = \App\Models\User::count();
        $totalPengembalian = \App\Models\Peminjaman::where('status', 'selesai')->count();
    
        // Mengambil 5 transaksi/peminjaman terbaru untuk tabel di dashboard
        $recentPeminjaman = \App\Models\Peminjaman::with(['user', 'detailPinjam.alat'])
            ->latest()
            ->take(5)
            ->get();
    
        return view('admin.dashboard', compact(
            'totalAlat', 
            'totalPeminjaman', 
            'totalUser', 
            'totalPengembalian', 
            'recentPeminjaman'
        ));
    }

    // ==========================
    // CRUD USER
    // ==========================

    public function indexUser(Request $request)
    {
        $search = $request->input('search');

        // Mengambil data user dengan filter pencarian
        $users = User::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('role', 'like', "%{$search}%");
        })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Menghitung statistik untuk card di atas tabel
        $totalUser = User::count();
        $totalAdmin = User::where('role', 'admin')->count();
        $totalPetugas = User::where('role', 'petugas')->count();
        $totalPeminjam = User::where('role', 'peminjam')->count();

        return view('admin.user.index', compact(
            'users', 
            'search', 
            'totalUser', 
            'totalAdmin', 
            'totalPetugas', 
            'totalPeminjam'
        ));
    }

    public function createUser()
    {
        return view('admin.user.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'alamat' => 'nullable|string|max:255',
            'role' => 'required|in:admin,petugas,peminjam',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('admin.user.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);

        return view('admin.user.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,petugas,peminjam',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string|max:255',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.user.index')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()->route('admin.user.index')
            ->with('success', 'User berhasil dihapus.');
    }

    // ====================
    // CRUD KATEGORI
    // ====================

    // 1. Menampilkan daftar kategori + search + pagination
    public function indexKategori(Request $request)
    {
        $search = $request->input('search');

        $kategoris = Kategori::when($search, function ($query, $search) {
            return $query->where('nama_kategori', 'like', "%{$search}%");
        })
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('admin.kategori.index', compact('kategoris', 'search'));
    }

    // 2. Menampilkan form tambah kategori
    public function createKategori()
    {
        return view('admin.kategori.create');
    }

    // 3. Menyimpan kategori baru
    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori',
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    // 4. Menampilkan form edit kategori
    public function editKategori($id)
    {
        $kategori = Kategori::findOrFail($id);

        return view('admin.kategori.edit', compact('kategori'));
    }

    // 5. Memperbarui kategori
    public function updateKategori(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori,' . $id,
        ]);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    // 6. Menghapus kategori
    public function destroyKategori($id)
    {
        $kategori = Kategori::findOrFail($id);

        // Cek apakah kategori masih digunakan oleh alat
        if ($kategori->alat()->count() > 0) {
            return redirect()
                ->route('admin.kategori.index')
                ->with(
                    'error',
                    'Kategori tidak dapat dihapus karena masih digunakan oleh data alat.'
                );
        }

        $kategori->delete();

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }

    // ==========================
    // CRUD ALAT
    // ==========================

    // 1. Menampilkan daftar alat
    public function indexAlat(Request $request)
    {
        $search = $request->input('search');

        $alat = Alat::with('kategori')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('nama_alat', 'like', "%{$search}%")
                        ->orWhere('status_kondisi', 'like', "%{$search}%")
                        ->orWhereHas('kategori', function ($kategoriQuery) use ($search) {
                            $kategoriQuery->where(
                                'nama_kategori',
                                'like',
                                "%{$search}%"
                            );
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.alat.index', compact('alat', 'search'));
    }


    // 2. Menampilkan form tambah alat
    public function createAlat()
    {
        $kategori = Kategori::all();

        return view('admin.alat.create', compact('kategori'));
    }


    // 3. Menyimpan alat baru
    public function storeAlat(Request $request)
    {
        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'stok' => 'required|integer|min:0',
            'status_kondisi' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('gambar');

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('alat', 'public');
            $data['gambar'] = $path;
        }

        Alat::create($data);

        return redirect()
            ->route('admin.alat.index')
            ->with('success', 'Data alat berhasil ditambahkan.');
    }


    // 4. Menampilkan form edit alat
    public function editAlat($id)
    {
        $alat = Alat::findOrFail($id);

        $kategori = Kategori::all();

        return view(
            'admin.alat.edit',
            compact('alat', 'kategori')
        );
    }


    // 5. Memperbarui data alat
    public function updateAlat(Request $request, $id)
    {
        $alat = Alat::findOrFail($id);

        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'stok' => 'required|integer|min:0',
            'status_kondisi' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('gambar');

        // Upload gambar baru menggunakan Storage Laravel
        if ($request->hasFile('gambar')) {
            
            // Hapus gambar lama jika ada
            if ($alat->gambar && \Storage::disk('public')->exists($alat->gambar)) {
                \Storage::disk('public')->delete($alat->gambar);
            }

            // Simpan gambar baru ke folder 'storage/app/public/alat'
            $path = $request->file('gambar')->store('alat', 'public');
            
            // Path yang disimpan ke database jadi: "alat/namagambar.jpg"
            $data['gambar'] = $path;
        }

        $alat->update($data);

        return redirect()
            ->route('admin.alat.index')
            ->with('success', 'Data alat berhasil diperbarui.');
    }


    // 6. Menghapus data alat (Versi Proteksi Ketat)
    public function destroyAlat($id)
    {
        // 1. Mencari data alat berdasarkan ID
        $alat = Alat::findOrFail($id);

        // 2. Melakukan pengecekan relasi bersyarat
        $sedangDipinjam = $alat->detailPinjam()
            ->whereHas('peminjaman', function($query) {
                $query->where('status', 'dipinjam');
            })->exists();

        // 3. Validasi penolakan jika alat sedang aktif dipinjam
        if ($sedangDipinjam) {
            return redirect()->back()->with('error', 'Gagal menghapus! Alat ini sedang aktif dipinjam oleh user.');
        }
        
        // 4. Hapus file gambar fisik dari storage
        if ($alat->gambar && \Storage::disk('public')->exists($alat->gambar)) {
            \Storage::disk('public')->delete($alat->gambar);
        }

        // 5. Eksekusi penghapusan data dari database
        $alat->delete($data);

        // 6. Mengembalikan respons sukses ke halaman sebelumnya
        return redirect()
            ->route('admin.alat.index')
            ->with('success', 'Data alat berhasil dihapus.');
    }
    // ==========================
    // CRUD PEMINJAMAN
    // ==========================
    
    // 1. Menampilkan daftar peminjaman & Statistik Cards
    public function indexPeminjaman(Request $request)
    {
        $search = $request->input('search');
    
        $peminjaman = Peminjaman::with(['user', 'detailPinjam.alat'])
            ->when($search, function ($query, $search) {
                return $query->where('status', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
    
        // Hitung data untuk Kotak Statistik (Cards) Admin
        $totalDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        $totalDiajukan = Peminjaman::where('status', 'diajukan')->count();
        $totalTelat    = Peminjaman::where('status', 'telat')->count();
        $totalSelesai  = Peminjaman::where('status', 'selesai')->count();
    
        return view('admin.peminjaman.index', compact(
            'peminjaman', 
            'search', 
            'totalDipinjam', 
            'totalDiajukan', 
            'totalTelat', 
            'totalSelesai'
        ));
    }
    
    
    // 2. Menampilkan form tambah peminjaman
    public function createPeminjaman()
    {
        $user = User::where('role', 'peminjam')->get();
        $alat = Alat::where('stok', '>', 0)->get();
    
        return view('admin.peminjaman.create', compact('user', 'alat'));
    }
    
    
    // 3. Menyimpan data peminjaman baru
    public function storePeminjaman(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tgl_pinjam' => 'required|date',
            'tgl_kembali_plan' => 'required|date|after_or_equal:tgl_pinjam',
    
            'alat_id' => 'required|array',
            'alat_id.*' => 'exists:alat,id',
    
            'jumlah' => 'required|array',
            'jumlah.*' => 'integer|min:1',
        ]);
    
        DB::beginTransaction();
    
        try {
    
            // Buat data peminjaman
            $peminjaman = Peminjaman::create([
                'user_id' => $request->user_id,
                'tgl_pinjam' => $request->tgl_pinjam,
                'tgl_kembali_plan' => $request->tgl_kembali_plan,
                'status' => 'diajukan',
            ]);

            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Mengajukan peminjaman alat baru untuk kebutuhan praktik.',
            ]);
    
            // Simpan detail alat
            foreach ($request->alat_id as $index => $alatId) {
    
                $jumlahPinjam = $request->jumlah[$index];
    
                $alat = Alat::findOrFail($alatId);
    
                // Cek stok
                if ($alat->stok < $jumlahPinjam) {
                    throw new \Exception(
                        "Stok alat '{$alat->nama_alat}' tidak mencukupi."
                    );
                }
    
                DetailPinjam::create([
                    'peminjaman_id' => $peminjaman->id,
                    'alat_id' => $alatId,
                    'jumlah' => $jumlahPinjam,
                ]);
            }
    
            DB::commit();
    
            return redirect()
                ->route('admin.peminjaman.index')
                ->with('success', 'Data peminjaman berhasil diajukan.');
    
        } catch (\Exception $e) {
    
            DB::rollBack();
    
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
    
    
    // 4. Memperbarui status peminjaman
    public function updateStatusPeminjaman(Request $request, $id)
    {
        $peminjaman = Peminjaman::with('detailPinjam.alat')
            ->findOrFail($id);

        $request->validate([
            'status' => 'required|in:diajukan,dipinjam,selesai,telat',
        ]);
    
        DB::beginTransaction();
    
        try {

            $statusLama = $peminjaman->status;
            $statusBaru = $request->status;
    
            // Dari diajukan -> dipinjam
            if ($statusLama != 'dipinjam' && $statusBaru == 'dipinjam') {
    
                foreach ($peminjaman->detailPinjam as $detail) {
    
                    $alat = $detail->alat;
    
                    if ($alat->stok < $detail->jumlah) {
                        throw new \Exception(
                            "Stok alat '{$alat->nama_alat}' tidak mencukupi."
                        );
                    }
    
                    $alat->decrement('stok', $detail->jumlah);
                }
            }
    
            // Dari dipinjam -> selesai
            elseif ($statusLama == 'dipinjam' && $statusBaru == 'selesai') {
    
                foreach ($peminjaman->detailPinjam as $detail) {
    
                    $detail->alat->increment(
                        'stok',
                        $detail->jumlah
                    );
                }
            }
    
            $peminjaman->update([
                'status' => $statusBaru
            ]);

            DB::commit();
    
            return redirect()
                ->route('admin.peminjaman.index')
                ->with('success', 'Status peminjaman berhasil diperbarui.');
    
        } catch (\Exception $e) {
    
            DB::rollBack();
    
            return back()
                ->with('error', $e->getMessage());
        }
    }
    
    
    public function destroyPeminjaman($id)
    {
        // 1. Memulai transaksi database demi keamanan integritas data
        DB::beginTransaction();
        try {
            // 2. Mencari data peminjaman beserta relasi detail dan alatnya
            $peminjaman = Peminjaman::with('detailPinjam.alat')->findOrFail($id);

            // 3. Validasi status: Jika dihapus saat masih aktif dipinjam, kembalikan stok alatnya
            if ($peminjaman->status == 'dipinjam') {
                foreach ($peminjaman->detailPinjam as $detail) {
                    if ($detail->alat) {
                        $detail->alat->increment('stok', $detail->jumlah);
                    }
                }
            }
    
            // 4. Hapus data anak (detail_pinjam) terlebih dahulu agar bebas dari kendala relasi database
            $peminjaman->detailPinjams()->delete();

            // 5. Hapus data utama peminjaman dari database
            $peminjaman->delete();
    
            // 6. Jika semua sukses, simpan perubahan permanen (commit)
            DB::commit();
            return redirect()
                ->route('admin.peminjaman.index')
                ->with('success', 'Data peminjaman berhasil dihapus.');
    
        } catch (\Exception $e) {
            // 7. Jika ada error di tengah jalan, batalkan semua perubahan (rollback)
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function indexPengembalian()
    {
        $peminjaman = Peminjaman::with([
            'user',
            'detailPinjam.alat'
        ])
        ->whereIn('status', ['diajukan','dipinjam','dikembalikan','telat','selesai','ditolak'])
        ->latest()
        ->get();

        foreach ($peminjaman as $pinjam) {

            if (
                $pinjam->status === 'dipinjam' &&
                Carbon::today()->gt(Carbon::parse($pinjam->tgl_kembali_plan))
            ) {
                $pinjam->update([
                    'status' => 'telat'
                ]);
            }
        }

        return view('admin.pengembalian.index', compact('peminjaman'));
    }

    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::with('detailPinjam.alat')->findOrFail($id);
    
        // Pastikan hanya peminjaman yang statusnya dipinjam/telat yang bisa dikembalikan
        if (!in_array($peminjaman->status, ['diajukan','dipinjam','dikembalikan','telat','selesai'])) {
            return back()->with('error', 'Peminjaman ini sudah dikembalikan.');
        }
    
        DB::beginTransaction();
    
        try {
            $tanggalRencana = Carbon::parse($peminjaman->tgl_kembali_plan);
            $tanggalKembali = Carbon::today();
    
            // Hitung denda jika terlambat
            $hariTerlambat = $tanggalKembali->gt($tanggalRencana) ? $tanggalRencana->diffInDays($tanggalKembali) : 0;
            $denda = $hariTerlambat * 5000; // Denda Rp5.000 per hari
    
            // Kembalikan stok alat secara otomatis
            foreach ($peminjaman->detailPinjam as $detail) {
                if ($detail->alat) {
                    $detail->alat->increment('stok', $detail->jumlah);
                }
            }
    
            // UPDATE status jadi 'selesai' 
            $peminjaman->update([
                'status' => 'selesai',
                'denda' => $denda,
            ]);
    
            DB::commit();
    
            return redirect()->route('admin.pengembalian.index')
                ->with('success', 'Pengembalian berhasil. Stok alat dikembalikan.');
    
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Pengembalian gagal: ' . $e->getMessage());
        }
    }

    public function createPengembalian($id)
    {
        $peminjaman = Peminjaman::with([
            'user',
            'detailPinjam.alat'
        ])->findOrFail($id);

        if (!in_array($peminjaman->status, ['dipinjam', 'telat'])) {
            return back()->with('error', 'Peminjaman ini sudah selesai dikembalikan.');
        }

        return view('admin.pengembalian.create', compact('peminjaman'));
    }
}