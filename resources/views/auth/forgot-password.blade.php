<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - KandangPRO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .bg-login {
            background-image: url('{{ asset('images/login-bg.png') }}');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body class="bg-login min-h-screen relative flex items-center justify-center p-4">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px]"></div>

    <div class="relative max-w-md w-full">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="inline-block p-4 bg-white rounded-3xl shadow-2xl mb-6 border border-white/20 overflow-hidden">
                <img src="{{ asset('images/logo.png') }}" alt="KandangPRO Logo" class="h-32 w-auto object-contain">
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight drop-shadow-md">Lupa Password?</h1>
            <p class="text-emerald-100/80 mt-2 font-medium drop-shadow-sm">Masukkan email Anda untuk reset password</p>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-[2.5rem] shadow-2xl p-8 md:p-10 border border-white/10">
            <!-- Session Status -->
            @if (session('status'))
                <div
                    class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl text-sm font-semibold">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-600 rounded-2xl text-sm font-semibold">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-2 ml-1">Email Petugas</label>
                    <div class="relative group">
                        <div
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                            </svg>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                            class="block w-full pl-11 pr-4 py-4 bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-400 font-medium"
                            placeholder="petugas@KandangPRO.com">
                    </div>
                </div>

                <button type="submit"
                    class="w-full py-4 bg-emerald-600 text-white rounded-2xl font-extrabold shadow-lg shadow-emerald-200 hover:bg-emerald-700 hover:shadow-emerald-300 active:scale-[0.98] transition-all flex items-center justify-center gap-2 group">
                    <span>Kirim Link Reset</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}"
                        class="text-sm font-bold text-slate-500 hover:text-emerald-600 transition-colors">
                        Kembali ke Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
