<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KandangPro') - Manajemen Peternakan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b-4 border-emerald-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Left: Logo & Title -->
                <div class="flex items-center flex-shrink-0">
                    <div class="flex-shrink-0 flex items-center space-x-3">
                        <img src="{{ asset('images/logo.png') }}" alt="KandangPRO Logo"
                            class="w-auto h-12 object-contain bg-white rounded-lg shadow-sm">
                        <div class="flex flex-col leading-tight">
                            <h1
                                class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                                {{ setting('farm_name', 'KandangPRO') }}
                            </h1>
                            <span class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">
                                Peternakan Modern
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Center: Navigation Links -->
                <div class="flex items-center justify-center flex-1 px-4 overflow-x-auto whitespace-nowrap hide-scroll">
                    <div class="flex space-x-4 lg:space-x-6">
                        <a href="{{ route('dashboard.index') }}"
                            class="{{ request()->routeIs('dashboard.index') ? 'border-emerald-500 text-emerald-700' : 'border-transparent text-gray-600 hover:border-emerald-500 hover:text-emerald-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold transition-all duration-200 whitespace-nowrap">
                            Dashboard
                        </a>
                        <a href="{{ route('daily-reports.index') }}"
                            class="{{ request()->routeIs('daily-reports.*') ? 'border-emerald-500 text-emerald-700' : 'border-transparent text-gray-600 hover:border-emerald-500 hover:text-emerald-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold transition-all duration-200 whitespace-nowrap">
                            Laporan Harian
                        </a>
                        <a href="{{ route('farms.index') }}"
                            class="{{ request()->routeIs('farms.*') ? 'border-emerald-500 text-emerald-700' : 'border-transparent text-gray-600 hover:border-emerald-500 hover:text-emerald-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold transition-all duration-200 whitespace-nowrap">
                            Master Peternakan
                        </a>
                        <a href="{{ route('coops.index') }}"
                            class="{{ request()->routeIs('coops.*') ? 'border-emerald-500 text-emerald-700' : 'border-transparent text-gray-600 hover:border-emerald-500 hover:text-emerald-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold transition-all duration-200 whitespace-nowrap">
                            Master Kandang
                        </a>
                        <a href="{{ route('supplies.index') }}"
                            class="{{ request()->routeIs('supplies.*') ? 'border-emerald-500 text-emerald-700' : 'border-transparent text-gray-600 hover:border-emerald-500 hover:text-emerald-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold transition-all duration-200 whitespace-nowrap">
                            Manajemen Stok
                        </a>
                        <a href="{{ route('settings.index') }}"
                            class="{{ request()->routeIs('settings.*') ? 'border-emerald-500 text-emerald-700' : 'border-transparent text-gray-600 hover:border-emerald-500 hover:text-emerald-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold transition-all duration-200 whitespace-nowrap">
                            Pengaturan
                        </a>
                    </div>
                </div>

                <!-- Right: Date & Profile -->
                <div class="flex items-center justify-end space-x-1 sm:space-x-3 lg:space-x-4 flex-shrink-0">
                    <span
                        class="text-[10px] font-black uppercase tracking-widest text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-100 hidden 2xl:inline-block whitespace-nowrap">
                        {{ date('d F Y') }}
                    </span>

                    @auth
                        <div class="flex items-center space-x-3 pl-4 border-l border-slate-200 whitespace-nowrap">
                            <div class="flex flex-col text-right hidden sm:block">
                                <span class="text-xs font-black text-slate-700 leading-none">{{ Auth::user()->name }}</span>
                                <span
                                    class="text-[8px] font-bold text-emerald-600 uppercase tracking-tighter mt-0.5">Petugas
                                    Kandang</span>
                            </div>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all duration-200 group"
                                    title="Keluar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-5 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-100 hover:bg-emerald-700 hover:shadow-emerald-200 transition-all active:scale-95">
                            Masuk
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Alert Messages -->
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-md animate-fade-in">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-md animate-fade-in">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terjadi Kesalahan</h3>
                        <p class="text-sm text-red-700 mt-1">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat beberapa kesalahan:</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <p class="text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} {{ setting('farm_name', 'Jasfarm') }} - Sistem Manajemen Peternakan Ayam Petelur
            </p>
        </div>
    </footer>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }

        /* Hide scrollbar for nav container */
        .hide-scroll::-webkit-scrollbar {
            display: none;
        }
        .hide-scroll {
            -ms-overflow-style: none; /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
    @stack('scripts')
</body>

</html>