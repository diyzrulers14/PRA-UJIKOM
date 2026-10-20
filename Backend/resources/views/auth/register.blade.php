<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sistem Peminjaman Alat</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome untuk ikon interaktif -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4f46e5',
                        secondary: '#06b6d4',
                    }
                }
            }
        }
    </script>
    <style>
        /* Style tambahan untuk efek jejak kursor (Cursor Trail) */
        .cursor-trail {
            pointer-events: none;
            position: absolute;
            width: 12px;
            height: 12px;
            background: radial-gradient(circle, rgba(99,102,241,0.8) 0%, rgba(6,182,212,0) 70%);
            border-radius: 50%;
            transition: transform 0.1s ease-out;
            z-index: 50;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-black via-slate-900 to-slate-900 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Container untuk efek jejak kursor -->
    <div id="trail-container" class="absolute inset-0 pointer-events-none z-0"></div>

    <!-- Main Wrapper (Split Screen & Glassmorphism Effect) -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/50 shadow-2xl rounded-2xl w-full max-w-4xl overflow-hidden grid grid-cols-1 md:grid-cols-2 relative z-10">
        
        <!-- Left Side: Brand / Visual Art -->
        <div class="hidden md:flex flex-col justify-between p-10 bg-gradient-to-br from-blue-600/40 to-cyan-500/30 text-white relative">
            <div class="absolute inset-0 bg-black/20 backdrop-blur-[2px]"></div>
            <div class="relative z-10">
                <span class="px-3 py-1 text-xs font-semibold bg-cyan-500/30 border border-cyan-400/40 rounded-full text-cyan-200">Project Kr1nn</span>
                <h1 class="text-3xl font-extrabold mt-4 tracking-tight">Peminjaman Inventaris Alat </h1>
                <p class="text-sm text-indigo-200 mt-2">Kelola peminjaman alat jadi lebih cepat, aman, dan transparan dengan sistem modern.</p>
            </div>
            
            <div class="relative z-10 text-xs text-indigo-300">
                <p>&copy; 2026 Dhikri Fadillah • XII PPLG 3</p>
            </div>
        </div>

        <!-- Right Side: Form Register -->
        <div class="p-8 md:p-10 bg-white flex flex-col justify-center">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 mb-3 shadow-inner">
                    <i class="fa-solid fa-boxes-stacked text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Registrasi Akun!</h2>
                <p class="text-sm text-gray-500 mt-1">Silakan Daftarkan Akun Anda</p>
            </div>
            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r text-sm">
                    <ul class="list-disc pl-4 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-gray-700 text-xs font-bold uppercase tracking-wider mb-2"></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Nama Lengkap Anda"
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition duration-200">
                </div> 


                <label class="block text-gray-700 text-xs font-bold uppercase tracking-wider mb-2"></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fa-solid fa-envelope"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com"
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition duration-200">
                </div>

                <div>
                    <label class="block text-gray-700 text-xs font-bold uppercase tracking-wider mb-2"></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fa-solid fa-phone"></i>
                        </span>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}" required placeholder="Nomor Telepon Anda"
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition duration-200">
                </div>

                <div>
                    <label class="block text-gray-700 text-xs font-bold uppercase tracking-wider mb-2"></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fa-solid fa-location-dot"></i>
                        </span>
                        <input type="text" name="alamat" value="{{ old('alamat') }}" required placeholder="Alamat Anda"
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition duration-200">
                </div>

                <div>
                    <label class="block text-gray-700 text-xs font-bold uppercase tracking-wider mb-2"></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" name="password" id="password" required placeholder="Password Anda"
                            class="w-full pl-10 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition duration-200">
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-indigo-600 focus:outline-none">
                            <i class="fa-solid fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 text-xs font-bold uppercase tracking-wider mb-2"></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Konfirmasi Password Anda"
                            class="w-full pl-10 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition duration-200">
                    </div>
                </div>

                <!-- Tombol Submit Modern -->
                <button type="submit"
                    class="w-full mt-2 bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-semibold py-3 rounded-xl shadow-lg shadow-indigo-500/30 hover:from-indigo-700 hover:to-blue-700 hover:shadow-indigo-500/50 transform active:scale-[0.99] transition duration-200 text-sm">
                    Daftar <i class="fa-solid fa-arrow-right ml-2"></i>
                </button>

            </form>
            <p class="text-sm text-gray-500 mt-4 text-center">Sudah punya akun? <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline">Masuk -></a></p>
        </div>
    </div>

    <!-- Script untuk efek toggle password -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>

    <!-- Script untuk efek jejak kursor -->
    <script>
        const trailContainer = document.getElementById('trail-container');
        const trailCount = 10; // Jumlah jejak kursor
        const trails = [];

        for (let i = 0; i < trailCount; i++) {
            const trail = document.createElement('div');
            trail.classList.add('cursor-trail');
            trailContainer.appendChild(trail);
            trails.push(trail);
        }

        document.addEventListener('mousemove', (e) => {
            trails.forEach((trail, index) => {
                setTimeout(() => {
                    trail.style.transform = `translate(${e.clientX - 6}px, ${e.clientY - 6}px)`;
                }, index * 50); // Delay untuk efek jejak
            });
        });
    </script>
</body>
</html>