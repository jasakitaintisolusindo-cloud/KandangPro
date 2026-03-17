@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6 animate-fade-in">
        <!-- Top Header & Greeting -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Ringkasan Operasional</h2>
                <p class="text-slate-500 font-medium">Selamat datang kembali, peternak Kandang PRO!</p>
            </div>
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-2xl shadow-sm border border-slate-100">
                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Hari
                        Ini</span>
                    <span class="text-sm font-bold text-slate-700">{{ date('d F Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Critical Alerts (High Visibility) -->
        @if(count($alerts) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-4">
                @foreach($alerts as $alert)
                    <div
                        class="group relative overflow-hidden bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 p-1">
                        <div
                            class="absolute inset-y-0 left-0 w-1.5 {{ $alert['type'] == 'danger' ? 'bg-red-500' : 'bg-orange-500' }}">
                        </div>
                        <div class="flex items-center p-4">
                            <div
                                class="p-3 rounded-xl {{ $alert['type'] == 'danger' ? 'bg-red-50 text-red-600 animate-pulse shadow-[0_0_15px_rgba(239,68,68,0.3)]' : 'bg-orange-50 text-orange-600' }} mr-4">
                                @if(($alert['icon'] ?? '') == 'pakan')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h4
                                    class="text-xs font-bold uppercase tracking-wider {{ $alert['type'] == 'danger' ? 'text-red-400' : 'text-orange-400' }} mb-0.5">
                                    Peringatan Sistem</h4>
                                <p class="text-sm font-bold text-slate-700 leading-tight">{{ $alert['message'] }}</p>
                            </div>
                            <button class="text-slate-300 hover:text-slate-500 transition-colors p-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Main KPI Grid - 4 Columns Symmetric -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Produksi (Egg) -->
            <div
                class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-6 group hover:translate-y-[-4px] transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="p-3 bg-emerald-50 rounded-2xl text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 22C17.5228 22 20 18.5228 20 14C20 9.47715 16.5 2 12 2C7.5 2 4 9.47715 4 14C4 18.5228 6.47715 22 12 22Z" />
                        </svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Produksi (30d)</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-4xl font-extrabold text-slate-900 tracking-tight">
                        {{ number_format($summary['total_produksi'], 1) }}
                    </h3>
                    <span class="text-slate-400 font-bold text-sm">Kg</span>
                    <div
                        class="flex items-center px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-full ml-auto border border-emerald-100">
                        <svg class="w-3 h-2 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M5 15l7-7 7 7" />
                        </svg>
                        5.2%
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs font-bold text-emerald-600">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                    <span>Total Hasil Telur</span>
                </div>
            </div>

            <!-- Profit -->
            <div
                class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-6 group hover:translate-y-[-4px] transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="p-3 bg-blue-50 rounded-2xl text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Rata-rata Profit</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] font-bold text-slate-400 uppercase leading-none mb-1">IDR Per Hari</span>
                    <div class="flex items-center gap-3">
                        <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">Rp
                            {{ number_format($summary['avg_keuntungan'], 0, ',', '.') }}
                        </h3>
                        <div
                            class="flex items-center px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-full border border-emerald-100">
                            <svg class="w-3 h-2 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 15l7-7 7 7" />
                            </svg>
                            2.1%
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs font-bold text-blue-600">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                    <span>Analisa Keuntungan</span>
                </div>
            </div>

            <!-- FCR -->
            <div
                class="bg-indigo-950 rounded-[2rem] shadow-xl shadow-indigo-900/20 p-6 relative overflow-hidden group hover:scale-[1.02] transition-all duration-500 flex flex-col justify-between">
                <div class="relative z-10 flex items-start justify-between">
                    <p class="text-[10px] font-black uppercase tracking-widest text-indigo-300 opacity-80 w-1/2">Feed
                        Conversion Ratio</p>
                    <div class="inline-flex items-center px-2 py-1 bg-indigo-500/20 rounded-lg border border-indigo-400/30 text-right">
                        <span class="text-[10px] font-bold text-indigo-200">Target: <span
                                class="text-white">{{ setting('target_fcr', '2.1') }}</span></span>
                    </div>
                </div>
                <div class="relative w-full aspect-[2/1] mt-6 flex items-end justify-center">
                    <svg class="absolute inset-x-0 bottom-0 w-full h-full" viewBox="0 0 100 50">
                        <path d="M 10 50 A 40 40 0 0 1 90 50" fill="none" class="stroke-indigo-800" stroke-width="12" stroke-linecap="round"></path>
                        <path d="M 10 50 A 40 40 0 0 1 90 50" fill="none" class="stroke-indigo-400 transition-all duration-1000 ease-out" stroke-width="12" stroke-linecap="round" stroke-dasharray="125.66" stroke-dashoffset="{{ 125.66 - (125.66 * (min(100, max(0, ($kpi['fcr'] / 3.0) * 100)) / 100)) }}"></path>
                    </svg>
                    <div class="relative z-10 text-center mb-1">
                        <h3 class="text-4xl font-extrabold text-white tracking-tighter leading-none">{{ number_format($kpi['fcr'], 2) }}</h3>
                    </div>
                </div>
            </div>

            <!-- HDP -->
            <div
                class="bg-emerald-900 rounded-[2rem] shadow-xl shadow-emerald-900/20 p-6 relative overflow-hidden group hover:scale-[1.02] transition-all duration-500">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-800 to-emerald-950"></div>
                <!-- Chicken Icon Added Here -->
                <div
                    class="absolute top-0 right-0 p-8 opacity-10 text-white group-hover:rotate-12 transition-transform duration-700">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M19 6c-.6 0-1.1.2-1.6.5-.8-1.7-2.7-2.9-4.9-2.9-1.9 0-3.6.9-4.6 2.4-.2-.1-.5-.2-.8-.2-2.2 0-4 1.8-4 4 0 2.2 1.8 4 4 4h.5c.3 1.5 1.7 2.6 3.3 2.6h4.3c2.6 0 4.8-1.9 5.2-4.4.4-.1.7-.5.7-1 0-.6-.4-1.1-1-1.1h-1.1c.1-.5.1-1 .1-1.5 0-1.4-1.1-2.5-2.5-2.5-.2 0-.4.1-.6.1.4-.7.6-1.5.6-2.4 0-.4-.3-.8-.7-.8-1.1 0-2 .9-2 2v2.1c-.6.2-1.2.6-1.6 1.1-.4-.6-1-1-1.7-1H9.9c.7-1.2 2-2 3.5-2 1.6 0 2.9 1 3.5 2.4.4-.3.9-.5 1.5-.5 1.4 0 2.5 1.1 2.5 2.5 0 .2 0 .4-.1.6h.1c1.1 0 2 .9 2 2s-.9 2-2 2h-1c-.2 1.8-1.8 3.2-3.7 3.2h-4.3c-1 0-1.9-.7-2.1-1.7h-.5c-1.7 0-3-1.3-3-3 0-1.7 1.3-3 3-3 .3 0 .7 0 1 .1.2-.9.9-1.5 1.8-1.5h.3c.7 0 1.3-.4 1.5-1.1.3-.4.8-.7 1.3-.8V8c0 .6.4 1 1 1s1-.4 1-1V5.6c0 .5.3.9.8.9.3 0 .5-.1.7-.2.1.2.1.4.1.7 0 .8-.5 1.6-1.3 1.8-.4.1-.7.5-.7.9 0 .4.3.7.7.8 1.4.3 2.4 1.6 2.4 3 0 .3-.1.6-.2.8h.9c.6 0 1.1.5 1.1 1.1 0 .6-.4 1.1-1 1.1 0 .3-.1.5-.2.7-.4 1.5-1.8 2.6-3.4 2.6h-4.3c-.9 0-1.8-.6-2-1.4-.1-.3-.4-.5-.7-.5-.4 0-.7.3-.8.7-.2 1.2-1.3 2.2-2.6 2.2H6.9c-1.6 0-2.9-1.3-2.9-2.9 0-1.6 1.3-2.9 2.9-2.9h.5c.3 0 .6-.2.7-.5.2-.9.9-1.5 1.8-1.5h.2c.4 0 .7-.3.7-.7s-.3-.7-.7-.7h-.9c-1.3 0-2.4 1-2.6 2.2h-.5c-2.2 0-4 1.8-4 4 0 2.2 1.8 4 4 4h4c1.8 0 3.4-1.2 3.9-2.9h4.3c2.2 0 4-1.8 4-4 0-.1 0-.3-.1-.4.4-.1.7-.5.7-.9 0-.6-.4-1.1-0.9-1.1z" />
                    </svg>
                </div>
                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase tracking-widest text-emerald-300 mb-4 opacity-80">Hen Day
                        Production</p>
                    <div class="flex items-baseline gap-2 mb-4">
                        <h3 class="text-5xl font-extrabold text-white tracking-tighter">
                            {{ number_format($kpi['hdp_today'], 1) }}%
                        </h3>
                    </div>
                    <div class="w-full bg-emerald-800/50 rounded-full h-2 mb-2">
                        <div class="bg-emerald-400 h-full rounded-full shadow-[0_0_10px_rgba(52,211,153,0.5)]"
                            style="width: {{ $kpi['hdp_today'] }}%"></div>
                    </div>
                    <span class="text-[10px] font-bold text-emerald-300 uppercase tracking-widest">Realisasi Hari Ini</span>
                </div>
            </div>
        </div>

        <!-- Inventory and Visual Performance Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Inventory Detail -->
            <div class="lg:col-span-1 bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-8">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-lg font-bold text-slate-900 tracking-tight">Inventaris Pakan</h3>
                    <a href="{{ route('supplies.index') }}"
                        class="group flex items-center text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-xl hover:bg-emerald-600 hover:text-white transition-all">
                        <span>Detail</span>
                        <svg class="w-4 h-4 ml-1 group-hover:translate-x-0.5 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>

                <div class="space-y-6">
                    @foreach($supplySummary as $supply)
                        <div
                            class="p-5 rounded-3xl border border-slate-50 bg-slate-50/50 hover:bg-white hover:border-slate-100 hover:shadow-lg hover:shadow-slate-200/50 transition-all duration-300">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-slate-200/50 text-slate-500 rounded-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p
                                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">
                                            Nama Pakan</p>
                                        <h4 class="text-sm font-bold text-slate-800">{{ $supply['name'] }}</h4>
                                    </div>
                                </div>
                                <span
                                    class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter {{ $supply['status'] == 'low' ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-600' }}">
                                    {{ $supply['status'] == 'low' ? 'Stok Kritis' : 'Normal' }}
                                </span>
                            </div>
                            <div class="flex items-end justify-between">
                                <div class="flex items-baseline gap-1">
                                    <span
                                        class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($supply['stock'], 0) }}</span>
                                    <span class="text-xs font-bold text-slate-400 uppercase">{{ $supply['unit'] }}</span>
                                </div>
                                <div class="text-right">
                                    <div class="flex items-center justify-end gap-1 mb-1">
                                        <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Sisa
                                            Estimasi</span>
                                    </div>
                                    <p
                                        class="text-lg font-black leading-none {{ $supply['days_left'] <= 3 ? 'text-red-500' : 'text-slate-900' }} text-right mt-1">
                                        @if($supply['days_left'] == 999)
                                            ∞
                                        @else
                                            <span class="text-[10px] font-bold {{ $supply['days_left'] <= 3 ? 'text-red-400' : 'text-slate-400' }} block opacity-80 leading-tight mb-1">Cukup untuk</span>
                                            {{ $supply['days_left'] }} <span class="text-[10px] font-bold">Hari lagi</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Performance Chart -->
            <div
                class="lg:col-span-2 bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-8 flex flex-col">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 tracking-tight">Tren Profitabilitas</h3>
                        <p class="text-xs font-medium text-slate-400">Analisa keuntungan bersih 30 hari terakhir</p>
                    </div>
                    <div class="flex bg-slate-50 p-1 rounded-xl">
                        <button
                            class="px-4 py-1.5 bg-white shadow-sm rounded-lg text-xs font-bold text-emerald-600">Keuntungan</button>
                        <button
                            class="px-4 py-1.5 text-xs font-bold text-slate-400 hover:text-slate-600 transition-colors">Produksi</button>
                    </div>
                </div>
                <div class="flex-1 min-h-[350px] relative">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Coops & Analytics Bottom Layer -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Coop Comparison (HDP vs Target) -->
            <div
                class="bg-slate-900 rounded-[2.5rem] shadow-2xl shadow-slate-900/40 p-10 text-white relative overflow-hidden">
                <div
                    class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/10 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl">
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-xl font-bold tracking-tight">Komparasi Performa Kandang</h3>
                            <p class="text-xs text-slate-300 font-medium opacity-90">Monitoring HDP terhadap target masing-masing
                                kandang</p>
                        </div>
                        <div class="p-3 bg-white/5 rounded-2xl border border-white/10 text-emerald-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>

                    <div class="space-y-8">
                        @foreach($coops as $coop)
                            @php
                                $lastReport = $coop->dailyReports()->latest('tanggal')->first();
                                $realisasi = ($lastReport && $coop->populasi_awal > 0) ? ($lastReport->jumlah_telur_butir / $coop->populasi_awal) * 100 : 0;
                                $diff = $realisasi - $coop->target_hdp;
                            @endphp
                            <div class="group">
                                <div class="flex justify-between items-end mb-3">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="text-sm font-black tracking-wider uppercase">{{ $coop->kode_kandang }}</span>
                                            <span
                                                class="px-2 py-0.5 bg-white/5 border border-white/10 rounded-full text-[10px] text-slate-400 font-bold">Populasi:
                                                {{ number_format($coop->populasi_saat_ini) }}</span>
                                        </div>
                                        <p class="text-[10px] text-slate-300 font-bold uppercase mt-1">Target HDP:
                                            {{ $coop->target_hdp }}%
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-lg font-black {{ $diff >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                            {{ number_format($realisasi, 1) }}%
                                        </span>
                                        <div
                                            class="flex items-center justify-end text-[10px] font-bold {{ $diff >= 0 ? 'text-emerald-500' : 'text-red-500' }}">
                                            @if($diff >= 0)
                                                <svg class="w-2.5 h-2.5 mr-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            @else
                                                <svg class="w-2.5 h-2.5 mr-0.5 transform rotate-180" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                            {{ abs(number_format($diff, 1)) }}% vs Target
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full bg-white/5 rounded-full h-3 relative border border-white/5 overflow-hidden">
                                    <!-- Target Pin -->
                                    <div class="absolute inset-y-0 w-[2px] bg-slate-400 z-10 shadow-[0_0_5px_rgba(255,255,255,0.5)]"
                                        style="left: {{ $coop->target_hdp }}%"></div>
                                    <!-- Progress Bar -->
                                    <div class="h-full rounded-full transition-all duration-1000 shadow-inner {{ $realisasi >= $coop->target_hdp ? 'bg-gradient-to-r from-emerald-600 to-emerald-400' : 'bg-gradient-to-r from-orange-600 to-orange-400' }}"
                                        style="width: {{ $realisasi }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Profit per Coop Bar Chart -->
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-10 flex flex-col">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 tracking-tight">Akumulasi Profit</h3>
                        <p class="text-xs font-medium text-slate-400">Perbandingan keuntungan antar kandang (30d)</p>
                    </div>
                    <div class="p-4 bg-indigo-50 rounded-2xl text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-h-[300px] relative">
                    <canvas id="coopChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Global Chart Config
                Chart.defaults.font.family = "'Inter', sans-serif";
                Chart.defaults.color = '#94a3b8';

                fetch('{{ route("dashboard.chart-data") }}')
                    .then(response => response.json())
                    .then(data => {
                        // 1. Trend Line Chart (Profit)
                        const trendCtx = document.getElementById('trendChart').getContext('2d');
                        const gradient = trendCtx.createLinearGradient(0, 0, 0, 400);
                        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
                        gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

                        new Chart(trendCtx, {
                            type: 'line',
                            data: {
                                labels: data.trend.labels,
                                datasets: [{
                                    label: 'Profit Harian',
                                    data: data.trend.values,
                                    borderDash: data.trend.values.every(item => item === 0) ? [5, 5] : [],
                                    borderColor: '#10b981',
                                    borderWidth: 4,
                                    pointBackgroundColor: '#ffffff',
                                    pointBorderColor: '#10b981',
                                    pointBorderWidth: 2,
                                    pointRadius: 4,
                                    pointHoverRadius: 6,
                                    fill: true,
                                    backgroundColor: gradient,
                                    tension: 0.4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: '#0f172a',
                                        padding: 12,
                                        titleFont: { weight: 'bold', size: 14 },
                                        bodyFont: { size: 13 },
                                        cornerRadius: 12,
                                        callbacks: {
                                            label: (ctx) => ' Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                                        }
                                    }
                                },
                                scales: {
                                    x: { grid: { display: false } },
                                    y: {
                                        grid: { color: '#f1f5f9', borderDash: [5, 5] },
                                        ticks: {
                                            callback: (val) => 'Rp ' + (val / 1000000).toFixed(1) + 'jt'
                                        }
                                    }
                                }
                            }
                        });

                        // 2. Bar Chart (Coop Comparison)
                        const coopCtx = document.getElementById('coopChart').getContext('2d');
                        new Chart(coopCtx, {
                            type: 'bar',
                            data: {
                                labels: data.coops.labels,
                                datasets: [
                                    {
                                        label: 'Total Profit',
                                        data: data.coops.values,
                                        backgroundColor: '#6366f1',
                                        hoverBackgroundColor: '#4f46e5',
                                        borderRadius: 12,
                                        barThickness: 32
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: '#0f172a',
                                        callbacks: {
                                            label: (ctx) => ' Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                                        }
                                    }
                                },
                                scales: {
                                    x: { grid: { display: false } },
                                    y: {
                                        grid: { borderDash: [5, 5], color: '#f1f5f9' },
                                        ticks: {
                                            callback: (val) => 'Rp ' + (val / 1000000).toFixed(1) + 'jt'
                                        }
                                    }
                                }
                            }
                        });
                    });
            });
        </script>
    @endpush

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.6s cubic-bezier(0.2, 0, 0, 1) forwards;
        }
    </style>
@endsection