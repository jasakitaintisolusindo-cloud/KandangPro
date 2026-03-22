<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KandangPro') - Manajemen Peternakan</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F4F6F9;
        }
        
        .sidebar {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        @media (max-width: 1024px) {
            .sidebar-collapsed .sidebar {
                transform: translateX(-100%);
            }
        }
    </style>
</head>

<body class="text-slate-700 antialiased min-h-screen flex sidebar-collapsed" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div class="fixed inset-0 bg-slate-900/50 z-40 lg:hidden transition-opacity" 
         x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         style="display: none;"></div>

    <!-- Main Sidebar -->
    <aside class="sidebar fixed lg:sticky top-0 inset-y-0 left-0 z-50 w-72 bg-[#34495E] text-white flex flex-col h-screen shadow-2xl lg:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
        
        <!-- Sidebar Header (Brand) -->
        <div class="h-16 flex items-center px-6 bg-[#2c3e50] border-b border-white/10 shrink-0">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8 rounded bg-white p-1 mr-3 object-contain">
            <div class="flex flex-col leading-tight">
                <span class="text-lg font-extrabold tracking-tight text-white nav-text">
                    {{ setting('farm_name', 'Kandang PRO') }}
                </span>
            </div>
            <!-- Close button for mobile -->
            <button @click="sidebarOpen = false" class="lg:hidden ml-auto text-slate-300 hover:text-white">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Sidebar Navigation -->
        <div class="flex-1 overflow-y-auto py-4 px-3 custom-scrollbar">
            <nav class="space-y-1">
                <p class="px-3 text-[10px] font-black uppercase tracking-widest text-[#7f8c8d] mb-2 mt-4 nav-text">Navigasi Utama</p>
                
                <a href="{{ route('dashboard.index') }}" 
                   class="{{ request()->routeIs('dashboard.index') ? 'bg-[#2ECC71] text-white shadow-lg shadow-emerald-500/30' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-bold rounded-xl transition-all duration-200">
                    <i class="fas fa-tachometer-alt w-6 text-center text-lg {{ request()->routeIs('dashboard.index') ? 'text-white' : 'text-[#7f8c8d] group-hover:text-white' }} mr-2"></i>
                    <span class="nav-text">Dashboard</span>
                </a>

                <a href="{{ route('daily-reports.index') }}" 
                   class="{{ request()->routeIs('daily-reports.*') ? 'bg-[#2ECC71] text-white shadow-lg shadow-emerald-500/30' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} group flex items-center justify-between px-3 py-2.5 text-sm font-bold rounded-xl transition-all duration-200 mt-1">
                    <div class="flex items-center">
                        <i class="fas fa-clipboard-list w-6 text-center text-lg {{ request()->routeIs('daily-reports.*') ? 'text-white' : 'text-[#7f8c8d] group-hover:text-white' }} mr-2"></i>
                        <span class="nav-text">Laporan Harian</span>
                    </div>
                    @if(isset($pendingReportsCount) && $pendingReportsCount > 0)
                        <span class="nav-text bg-red-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full shadow-md animate-pulse">{{ $pendingReportsCount }}</span>
                    @endif
                </a>

                @if(auth()->user()->canAccess('farms') || auth()->user()->canAccess('coops') || auth()->user()->canAccess('supplies'))
                <p class="px-3 text-[10px] font-black uppercase tracking-widest text-[#7f8c8d] mb-2 mt-6 nav-text">Manajemen Data</p>
                @endif

                @if(auth()->user()->canAccess('farms'))
                <a href="{{ route('farms.index') }}" 
                   class="{{ request()->routeIs('farms.*') ? 'bg-[#2ECC71] text-white shadow-lg shadow-emerald-500/30' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-bold rounded-xl transition-all duration-200">
                    <i class="fas fa-tractor w-6 text-center text-lg {{ request()->routeIs('farms.*') ? 'text-white' : 'text-[#7f8c8d] group-hover:text-white' }} mr-2"></i>
                    <span class="nav-text">Peternakan</span>
                </a>
                @endif

                @if(auth()->user()->canAccess('coops'))
                <a href="{{ route('coops.index') }}" 
                   class="{{ request()->routeIs('coops.*') ? 'bg-[#2ECC71] text-white shadow-lg shadow-emerald-500/30' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-bold rounded-xl transition-all duration-200 mt-1">
                    <i class="fas fa-home w-6 text-center text-lg {{ request()->routeIs('coops.*') ? 'text-white' : 'text-[#7f8c8d] group-hover:text-white' }} mr-2"></i>
                    <span class="nav-text">Kandang</span>
                </a>
                @endif

                @if(auth()->user()->canAccess('supplies'))
                <a href="{{ route('supplies.index') }}" 
                   class="{{ request()->routeIs('supplies.*') ? 'bg-[#2ECC71] text-white shadow-lg shadow-emerald-500/30' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-bold rounded-xl transition-all duration-200 mt-1">
                    <i class="fas fa-boxes w-6 text-center text-lg {{ request()->routeIs('supplies.*') ? 'text-white' : 'text-[#7f8c8d] group-hover:text-white' }} mr-2"></i>
                    <span class="nav-text">Inventaris & Stok</span>
                </a>
                @endif

                @if(auth()->user()->isManager())
                <p class="px-3 text-[10px] font-black uppercase tracking-widest text-[#7f8c8d] mb-2 mt-6 nav-text">Sistem</p>

                <a href="{{ route('settings.index') }}" 
                   class="{{ request()->routeIs('settings.*') ? 'bg-[#2ECC71] text-white shadow-lg shadow-emerald-500/30' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-bold rounded-xl transition-all duration-200">
                    <i class="fas fa-cog w-6 text-center text-lg {{ request()->routeIs('settings.*') ? 'text-white' : 'text-[#7f8c8d] group-hover:text-white' }} mr-2"></i>
                    <span class="nav-text">Pengaturan</span>
                </a>
                @endif
            </nav>
        </div>
        
        <!-- Sidebar User Panel & Footer -->
        <div class="mt-auto shrink-0 bg-slate-900/20 border-t border-white/5">
            @auth
            <div class="px-5 py-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center text-white font-bold text-lg shadow-inner shrink-0">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="flex flex-col overflow-hidden nav-text">
                    <span class="text-sm font-bold truncate text-white">{{ Auth::user()->name }}</span>
                    <span class="text-[10px] text-emerald-400 font-bold uppercase tracking-wider"><i class="fas fa-circle text-[8px] mr-1"></i> Online</span>
                </div>
            </div>
            @endauth
            <!-- Sidebar Footer -->
            <div class="p-3 border-t border-white/5 text-center nav-text">
                <p class="text-[10px] text-slate-400 font-medium tracking-wide">KandangPRO v2.0</p>
                <p class="text-[9px] text-emerald-500/70 font-bold tracking-widest uppercase mt-0.5"><i class="fas fa-check-circle mr-1"></i>Server Stable</p>
            </div>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-h-screen min-w-0 bg-[#F4F6F9]">
        
        <!-- Top Navbar -->
        <header class="h-16 bg-white border-b border-gray-200 shadow-sm flex items-center justify-between px-4 sm:px-6 lg:px-8 shrink-0 z-30 sticky top-0">
            
            <!-- Left Navbar (Toggle & Actions) -->
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 hover:text-emerald-600 focus:outline-none transition-colors lg:hidden">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <button class="hidden lg:block text-slate-500 hover:text-emerald-600 focus:outline-none transition-colors" onclick="document.body.classList.toggle('lg:sidebar-collapse')">
                    <i class="fas fa-bars text-xl"></i>
                </button>

                <!-- Quick Action Button -->
                <a href="{{ route('daily-reports.create') }}" class="hidden sm:inline-flex items-center px-4 py-2 bg-[#2ECC71] text-white text-sm font-bold rounded-lg shadow-md hover:bg-[#27ae60] transition-colors">
                    <i class="fas fa-plus mr-2"></i> Input Laporan
                </a>
            </div>

            <!-- Right Navbar (User Menu) -->
            <div class="flex items-center gap-4">
                
                <!-- Date/Weather Mini Widget -->
                <div class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-slate-50 rounded-lg border border-slate-100 text-sm font-bold text-slate-600">
                    <i class="fas fa-cloud-sun text-yellow-500 text-lg"></i>
                    <span>28&deg;C</span>
                    <span class="text-slate-300 mx-1">|</span>
                    <span class="text-xs">{{ date('d M Y') }}</span>
                </div>

                @auth
                <!-- User Dropdown (Simple logout for now) -->
                <div class="pl-4 border-l border-slate-200">
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center justify-center p-2 text-slate-500 hover:text-[#E74C3C] hover:bg-red-50 rounded-lg transition-colors" title="Keluar">
                            <i class="fas fa-sign-out-alt text-lg"></i>
                        </button>
                    </form>
                </div>
                @endauth
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 sm:p-6 lg:p-8">
            
            <!-- Alerts -->
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-[#2ECC71] p-4 rounded-r-lg shadow-sm animate-fade-in flex items-start">
                    <i class="fas fa-check-circle text-[#2ECC71] text-xl mr-3 mt-0.5"></i>
                    <p class="text-sm font-bold text-emerald-900">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-[#E74C3C] p-4 rounded-r-lg shadow-sm animate-fade-in flex items-start">
                    <i class="fas fa-exclamation-circle text-[#E74C3C] text-xl mr-3 mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-bold text-red-900">Terjadi Kesalahan</h3>
                        <p class="text-sm text-red-700 font-medium mt-1">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-[#E74C3C] p-4 rounded-r-lg shadow-sm flex items-start">
                    <i class="fas fa-times-circle text-[#E74C3C] text-xl mr-3 mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-bold text-red-900 mb-2">Terdapat beberapa kesalahan:</h3>
                        <ul class="text-sm text-red-700 font-medium list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
            
        </main>
        
        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-auto shrink-0 z-10 relative">
            <div class="px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row justify-between items-center gap-2">
                <p class="text-xs font-semibold text-slate-500">
                    &copy; {{ date('Y') }} <span class="text-slate-700">{{ setting('farm_name', 'KandangPRO') }}</span>. All rights reserved.
                </p>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    Versi 2.0
                </p>
            </div>
        </footer>
    </div>

    <!-- Alpine.js for interactive bits (using unpkg CDN for simplicity if not built-in) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }
        .custom-scrollbar:hover::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
        }
        
        /* Optional: simple collapse implementation for lg screens */
        body.lg\:sidebar-collapse .sidebar {
            width: 5rem; /* 80px */
        }
        body.lg\:sidebar-collapse .sidebar .nav-text {
            display: none !important;
        }
        body.lg\:sidebar-collapse .sidebar i {
            margin-right: 0;
            font-size: 1.25rem;
        }
        body.lg\:sidebar-collapse .sidebar a, 
        body.lg\:sidebar-collapse .sidebar .flex {
            justify-content: center;
        }
        body.lg\:sidebar-collapse .sidebar a {
            padding: 0.75rem 0;
        }
    </style>

    @stack('scripts')
</body>
</html>