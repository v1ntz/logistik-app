<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Karyawan - PT PAD</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            800: '#075985',
                            900: '#0c4a6e',
                            950: '#082f49',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased bg-gray-50 flex items-center justify-center min-h-screen relative overflow-hidden">
    <!-- Abstract Background -->
    <div class="absolute inset-0 z-0 opacity-20">
        <div class="absolute -top-40 -left-40 w-96 h-96 rounded-full bg-brand-500 blur-3xl"></div>
        <div class="absolute top-40 -right-20 w-80 h-80 rounded-full bg-brand-800 blur-3xl"></div>
    </div>

    <div class="w-full max-w-md relative z-10 mx-4">
        <!-- Logo Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center space-x-2">
                @if(file_exists(public_path('logo.png')))
                    <img src="{{ asset('logo.png') }}" alt="Logo PAD" class="h-16 w-auto">
                @else
                    <div class="w-16 h-16 bg-brand-900 rounded-full flex items-center justify-center text-white font-black text-2xl shadow-lg">PAD</div>
                @endif
            </div>
            <h2 class="mt-4 text-3xl font-black text-brand-950 tracking-tight">Portal Internal</h2>
            <p class="mt-2 text-sm text-gray-600 font-medium">Masuk untuk mengelola logistik operasional</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 p-8">
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-600 rounded-xl p-4 text-sm font-medium">
                    <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Alamat Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition shadow-sm text-base" placeholder="admin@pad.com" value="{{ old('email') }}">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-sm font-bold text-gray-700 uppercase tracking-wide">Kata Sandi</label>
                    </div>
                    <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition shadow-sm text-base" placeholder="••••••••">
                </div>

                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded cursor-pointer">
                    <label for="remember" class="ml-2 block text-sm text-gray-700 cursor-pointer font-medium">
                        Ingat sesi saya
                    </label>
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold leading-6 rounded-xl text-white bg-brand-600 hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 shadow-md transition transform hover:-translate-y-0.5">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-brand-400 group-hover:text-brand-300 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        Masuk ke Sistem
                    </button>
                </div>
            </form>
        </div>

        <p class="mt-8 text-center text-xs text-gray-500">
            &copy; 2026 PT. Pratama Andal Dermaga. Hubungi IT jika lupa sandi.
        </p>
    </div>
</body>
</html>
