<?php 
namespace App\Http\Controllers\WEB; 
 
use App\Http\Controllers\Controller; 
use App\Http\Resources\LogAktivitasResource; 
use App\Models\LogAktivitas; 
use Illuminate\Http\JsonResponse; 
 
 
class LogAktivitasController extends Controller 
{ 
    public function index(): JsonResponse 
    { 
        $total_alat = Alat::count();
        $stok_tersedia = Alat::sum('stok');

        $sedang_dipinjam = peminjaman::where('status', 'dipinjam')->count();
        $menunggu_persetujuan = peminjaman::where('status', 'diajukan')->count();
    
        $logs = LogAktivitas::with('user')->latest()->take(5)->get(); 
            return view('admin.dashboard', compact (
                'total_alat',
                'stok_tersedia',
                'sedang_dipinjam',
                'menunggu_persetujuan',
                'logs'
            ));
    } 
} 