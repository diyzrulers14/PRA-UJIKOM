<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Alat - Sistem Peminjaman</title>
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
        /* Card Produk Ala Shopee */
        .product-card {
            border: 1px solid #eaeaea;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            background: #fff;
            border-radius: 8px;
            position: relative;
        }
        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border-color: var(--shopee-orange);
        }
        .product-img-top {
            height: 140px;
            background-color: #fafafa;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ccc;
            font-size: 3rem;
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
                        <a class="nav-link active" href="{{ route('peminjam.katalog') }}">
                            <i class="fa-solid fa-store me-2"></i> Katalog Alat
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('peminjam.riwayat') }}">
                            <i class="fa-solid fa-clock-rotate-left me-2"></i> Riwayat Peminjaman
                        </a>
                    </li>
                </ul>

                <hr class="text-muted mt-4">

                <div class="d-grid">
                    <button class="btn btn-outline-danger fw-bold" onclick="confirmLogout()">
                        <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
                    </button>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
            </div>
        </nav>

        <!-- MAIN CONTENT -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            <!-- HEADER UTAMA (Judul & Profil Sejajar) -->
            <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded shadow-sm mb-4">
                <h4 class="mb-0 fw-bold text-dark">
                    <i class="fa-solid fa-basket-shopping text-shopee me-2"></i> Katalog Peminjaman Alat
                </h4>
                
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

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- KATALOG KONTEN -->
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form id="formAjukan" action="{{ route('peminjam.ajukan') }}" method="POST">
                        @csrf

                        <!-- BARIS SEJAJAR: SEARCH BAR & TANGGAL KEMBALI (DITUKAR POSISI) -->
                        <div class="row align-items-end mb-4 bg-light p-3 rounded border">
                            <div class="col-md-7 mb-2 mb-md-0">
                                <label class="form-label fw-bold text-secondary small">
                                    <i class="fa-solid fa-magnifying-glass me-1 text-shopee"></i> CARI ALAT
                                </label>
                                <div class="input-group shadow-sm">
                                    <input type="text" id="searchInput" class="form-control border-end-0 bg-white" placeholder="Cari nama alat yang mau dipinjam...">
                                    <span class="input-group-text bg-white text-muted border-start-0">
                                        <i class="fa-solid fa-search"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-bold text-secondary small">
                                    <i class="fa-solid fa-calendar-days me-1 text-shopee"></i> RENCANA TANGGAL KEMBALI
                                </label>
                                <input type="date" name="tgl_kembali_plan" class="form-control shadow-sm bg-white" required min="{{ date('Y-m-d') }}">
                            </div>

                            <!-- TOMBOL SUBMIT -->
                            <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                                <button type="submit" class="btn btn-shopee px-5 py-2 shadow-sm fw-bold">
                                    <i class="fa-solid fa-paper-plane me-2"></i> Ajukan Peminjaman
                                </button>
                            </div>
                        </div>

                        <!-- GRID ALAT -->
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3" id="productGrid">
                            @forelse($alats as $alat)
                                <div class="col product-item" data-name="{{ strtolower($alat->nama_alat) }}">
                                    <div class="product-card h-100 p-3 d-flex flex-column justify-content-between">
                                        <div>
                                            <!-- Foto Alat -->
                                            <div class="product-img-top mb-3 rounded text-center bg-light d-flex align-items-center justify-content-center" style="height: 160px; overflow: hidden;">
                                                @if($alat->gambar)
                                                    <img src="{{ asset('storage/' . $alat->gambar) }}" alt="{{ $alat->nama_alat }}" class="w-100 h-100 object-fit-cover">
                                                @else
                                                    <div class="text-secondary opacity-50">
                                                        <i class="fa-solid fa-screwdriver-wrench fa-2x"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            
                                            
                                            <!-- Kategori & Nama -->
                                            <div class="mb-2">
                                                <span class="badge bg-light text-dark border mb-1" style="font-size: 0.75rem;">
                                                    {{ $alat->kategori->nama_kategori ?? 'Umum' }}
                                                </span>
                                                <h6 class="fw-bold text-dark text-truncate-2 mb-1" style="min-height: 2.4em;">
                                                    {{ $alat->nama_alat }}
                                                </h6>
                                            </div>

                                            <!-- Stok Info -->
                                            <div class="mb-3">
                                                @if($alat->stok > 0)
                                                    <span class="text-success small fw-semibold">
                                                        <i class="fa-solid fa-check-circle me-1"></i> Stok: {{ $alat->stok }} Unit
                                                    </span>
                                                @else
                                                    <span class="text-danger small fw-semibold">
                                                        <i class="fa-solid fa-xmark-circle me-1"></i> Stok Habis
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Aksi Input Jumlah & Checkbox -->
                                        <div class="border-top pt-3 mt-2">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <label class="small text-muted fw-bold mb-0">Jumlah Pinjam:</label>
                                                <!-- Ubah name menjadi array asosiatif dengan key ID alat -->
                                                <input type="number" name="jumlah[{{ $alat->id }}]" class="form-control form-control-sm text-center shadow-sm" style="width: 80px;" value="1" min="1" max="{{ $alat->stok }}">
                                            </div>

                                            <div class="form-check bg-light p-2 rounded text-center border">
                                                <input class="form-check-input float-none me-1" type="checkbox" name="alat_id[]" value="{{ $alat->id }}" id="alat_{{ $alat->id }}">
                                                <label class="form-check-label fw-bold text-primary small cursor-pointer" for="alat_{{ $alat->id }}">
                                                    Pilih Alat Ini
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5 text-muted">
                                    <i class="fa-solid fa-box-open fa-3x mb-3 text-secondary opacity-50"></i>
                                    <p class="mb-0">Belum ada alat tersedia di katalog.</p>
                                </div>
                            @endforelse
                        </div>
                    </form>
                </div>
            </div>

        </main>
    </div>
</div>


<!-- Script JavaScript untuk Konfirmasi Logout SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Apakah Anda yakin ingin logout?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }

    document.getElementById('formAjukan').addEventListener('submit', function (event) {
        const alatDipilih = document.querySelectorAll('input[name="alat_id[]"]:checked');

        if (alatDipilih.length === 0) {
            event.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Alat belum dipilih',
                text: 'Pilih minimal satu alat sebelum mengajukan peminjaman.'
            });
        }
    });
</script>

<!-- Script Live Search Card Produk -->
<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let keyword = this.value.toLowerCase();
        let items = document.querySelectorAll('.product-item');

        items.forEach(item => {
            let name = item.getAttribute('data-name');
            if (name.includes(keyword)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
</script>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>