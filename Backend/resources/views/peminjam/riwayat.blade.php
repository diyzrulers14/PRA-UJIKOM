<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjaman - Sistem Peminjaman</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --shopee-orange: #ee4d2d;
            --shopee-dark: #d73211;
        }
        body {
            background-color: #f5f5f5;
        }
        .bg-shopee {
            background-color: var(--shopee-orange) !important;
        }
        .btn-shopee {
            background-color: var(--shopee-orange);
            color: white;
        }
        .btn-shopee:hover {
            background-color: var(--shopee-dark);
            color: white;
        }
        .sidebar {
            min-height: 100vh;
            background: white;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
        }
        .sidebar .nav-link {
            color: #333;
            padding: 12px 20px;
            font-weight: 500;
            border-radius: 8px;
            margin-bottom: 5px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: #fff2ef;
            color: var(--shopee-orange);
        }
        .stat-card {
            border: none;
            border-radius: 8px;
            transition: transform 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">
        
        <!-- SIDEBAR -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-3">
            <div class="position-sticky pt-3">
                <div class="text-center mb-4">
                    <h5 class="fw-bold text-dark m-0">
                        <i class="fa-solid fa-boxes-stacked text-shopee me-2"></i>PinjamAlat
                    </h5>
                    <small class="text-muted">Panel Peminjam</small>
                </div>

                <hr class="text-muted">

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('peminjam.katalog') }}">
                            <i class="fa-solid fa-store me-2"></i> Katalog Alat
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('peminjam.riwayat') }}">
                            <i class="fa-solid fa-clock-rotate-left me-2"></i> Riwayat Peminjaman
                        </a>
                    </li>
                </ul>

                <hr class="text-muted mt-4">

                <div class="px-2">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-outline-danger w-100 btn-sm fw-bold">
                            <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- MAIN CONTENT -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            <!-- TOPBAR / HEADER KANAN ATAS -->
            <div class="d-flex justify-content-end align-items-center bg-white p-3 rounded shadow-sm mb-4">
                <div class="d-flex align-items-center">
                    <span class="fw-semibold text-secondary me-2">
                        Halo, <span class="text-dark">{{ auth()->user()->name }}</span>
                    </span>
                    <div class="bg-shopee text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 35px; height: 35px;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </div>

            <!-- ALERT NOTIFIKASI -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- KONTEN UTAMA RIWAYAT -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fa-solid fa-clock-rotate-left text-shopee me-2"></i> Riwayat Transaksi Peminjaman
                    </h5>
                    <a href="{{ route('peminjam.katalog') }}" class="btn btn-sm btn-shopee fw-bold">
                        <i class="fa-solid fa-plus me-1"></i> Pinjam Alat Lagi
                    </a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light text-secondary small">
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Rencana Kembali</th>
                                    <th>Daftar Alat & Jumlah</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($peminjaman as $item)
                                    <tr>
                                        <td class="fw-bold text-muted">{{ $loop->iteration }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d/m/Y H:i') }}</td>
                                        <td><span class="badge bg-light text-dark border">{{ $item->tgl_kembali_plan }}</span></td>
                                        <td>
                                            <ul class="list-unstyled mb-0 small">
                                                @foreach($item->detailPinjams as $detail)
                                                    <li>
                                                        <i class="fa-solid fa-angle-right text-shopee me-1"></i>
                                                        <strong>{{ $detail->alat->nama_alat ?? 'Alat Dihapus' }}</strong> 
                                                        <span class="text-muted">({{ $detail->jumlah }} Unit)</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            @if($item->status == 'diajukan')
                                                <span class="badge bg-warning text-dark px-2 py-1">Menunggu Persetujuan</span>
                                            @elseif($item->status == 'dipinjam')
                                                <span class="badge bg-primary px-2 py-1">Sedang Dipinjam</span>
                                            @elseif($item->status == 'selesai')
                                                <span class="badge bg-success px-2 py-1">Selesai</span>
                                            @else
                                                <span class="badge bg-secondary px-2 py-1">{{ ucfirst($item->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fa-solid fa-folder-open fa-3x mb-3 text-secondary opacity-50"></i>
                                            <p class="mb-0">Kamu belum pernah melakukan transaksi peminjaman alat.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>