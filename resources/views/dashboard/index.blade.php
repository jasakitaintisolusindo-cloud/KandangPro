@extends('layouts.app')

@section('title', 'Executive Dashboard')

@section('content')
<!-- Include Lucide Icons -->
<script src="https://unpkg.com/lucide@latest"></script>

@php
    // Calculate MoM Percentages safely
    $moMProd = $summary_prev['total_produksi'] > 0 ? (($summary['total_produksi'] - $summary_prev['total_produksi']) / $summary_prev['total_produksi']) * 100 : 0;
    $moMProfit = $summary_prev['total_keuntungan'] > 0 ? (($summary['total_keuntungan'] - $summary_prev['total_keuntungan']) / $summary_prev['total_keuntungan']) * 100 : 0;
@endphp

<div class="space-y-6 animate-fade-in pb-16">
    <!-- AI Summary Header (Glassmorphism) -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-900 via-[#1f2937] to-[#0f172a] p-1 border border-white/10 shadow-2xl shadow-emerald-900/20">
        <!-- Abstract gradient blobs -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>
        
        <div class="bg-white/10 backdrop-blur-xl rounded-[1.4rem] p-6 lg:p-8 relative z-10 text-white flex flex-col md:flex-row gap-6 md:items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-emerald-500/30 rounded-xl border border-emerald-400/30 text-emerald-300">
                        <i data-lucide="sparkles" class="w-5 h-5"></i>
                    </div>
                    <h2 class="text-xs font-black uppercase tracking-widest text-emerald-200">AI Executive Summary</h2>
                </div>
                <!-- Dynamic Sentence -->
                <p class="text-base lg:text-lg font-medium leading-relaxed text-slate-100">
                    "Halo Pak/Bu, hari ini Efisiensi Pakan (FCR) berada di angka <strong class="text-emerald-400 font-extrabold">{{ $aiSummary['fcrValue'] }} ({{ $aiSummary['fcrStatus'] }})</strong>. 
                    @if($aiSummary['highestCostCoop'] !== '-')
                        Konsumsi pakan tertinggi dalam seminggu terakhir tercatat di <strong class="text-blue-300 font-extrabold">{{ $aiSummary['highestCostCoop'] }}</strong>.
                    @endif
                    Estimasi keuntungan bulan ini diproyeksikan mencapai <strong class="text-emerald-400 font-extrabold">Rp {{ number_format($aiSummary['profitProjection'], 0, ',', '.') }}</strong>."
                </p>
            </div>
            <div class="shrink-0 flex items-center md:items-end flex-col gap-2">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ date('d M Y') }}</span>
                <div class="flex items-center gap-2 px-4 py-2 bg-white/5 border border-white/10 rounded-full backdrop-blur-md">
                    <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
                    <span class="text-xs font-bold text-slate-200">Real-time Data Active</span>
                </div>
            </div>
        </div>
    </div>

        <!-- 1. ROW ATAS: TOP PERFORMANCE CARDS (THE PULSE) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 {{ auth()->user()->canAccess('financial') ? 'xl:grid-cols-4' : 'xl:grid-cols-3' }} gap-4 lg:gap-6">
            
            <!-- Total Produksi -->
            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/40 border border-slate-100 relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-start justify-between mb-4">
                    <p class="text-[10px] font-black uppercase tracking-widest text-[#7f8c8d]">Total Produksi (30H)</p>
                    <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center">
                        <i data-lucide="egg" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-1.5 mb-2">
                    <h3 class="text-3xl font-black text-[#2c3e50] tracking-tighter">{{ number_format($summary['total_produksi'], 1) }}</h3>
                    <span class="text-xs font-bold text-slate-400 uppercase">Kg</span>
                </div>
                <div class="flex items-center gap-1.5">
                    @if($moMProd >= 0)
                        <div class="flex items-center text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">
                            <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i> +{{ number_format($moMProd, 1) }}%
                        </div>
                    @else
                        <div class="flex items-center text-[10px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded border border-red-100">
                            <i data-lucide="trending-down" class="w-3 h-3 mr-1"></i> {{ number_format($moMProd, 1) }}%
                        </div>
                    @endif
                    <span class="text-[10px] text-slate-400 font-semibold">vs Bulan Lalu</span>
                </div>
            </div>

            <!-- FCR -->
            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/40 border border-slate-100 relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-start justify-between mb-4">
                    <p class="text-[10px] font-black uppercase tracking-widest text-[#7f8c8d]">Efisiensi Pakan (FCR)</p>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center">
                        <i data-lucide="scale" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-1.5 mb-2">
                    <h3 class="text-3xl font-black text-[#2c3e50] tracking-tighter">{{ number_format($kpi['fcr'], 2) }}</h3>
                </div>
                <div class="flex items-center gap-1.5">
                    @php $fcrTarget = setting('target_fcr', 2.1); @endphp
                    @if($kpi['fcr'] <= $fcrTarget)
                        <div class="flex items-center text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">
                            <i data-lucide="check-circle-2" class="w-3 h-3 mr-1"></i> On Target (< {{ $fcrTarget }})
                        </div>
                    @else
                        <div class="flex items-center text-[10px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded border border-red-100">
                            <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i> Over Target (> {{ $fcrTarget }})
                        </div>
                    @endif
                </div>
            </div>

            <!-- Mortality -->
            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/40 border border-slate-100 relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-start justify-between mb-4">
                    <p class="text-[10px] font-black uppercase tracking-widest text-[#7f8c8d]">Tingkat Deplesi</p>
                    <div class="w-10 h-10 rounded-xl {{ $kpi['mortality'] > 2 ? 'bg-red-50 text-[#E74C3C]' : 'bg-slate-50 text-slate-500' }} flex items-center justify-center">
                        <i data-lucide="activity" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-1.5 mb-2">
                    <h3 class="text-3xl font-black {{ $kpi['mortality'] > 2 ? 'text-[#E74C3C]' : 'text-[#2c3e50]' }} tracking-tighter">{{ number_format($kpi['mortality'], 2) }}%</h3>
                </div>
                <div class="flex items-center gap-1.5">
                    @if($kpi['mortality'] > 2)
                        <div class="flex items-center text-[10px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded border border-red-100">
                            Bahaya (Di atas 2%)
                        </div>
                    @else
                        <div class="flex items-center text-[10px] font-bold text-slate-500 bg-slate-50 px-2 py-0.5 rounded border border-slate-200">
                            {{ number_format($kpi['mortality_count']) }} Ekor Mati
                        </div>
                    @endif
                </div>
            </div>

            @if(auth()->user()->canAccess('financial'))
            <!-- Net Margin -->
            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/40 border border-slate-100 relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-start justify-between mb-4">
                    <p class="text-[10px] font-black uppercase tracking-widest text-[#7f8c8d]">Estimasi Profit (Net Margin)</p>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#2ECC71] flex items-center justify-center">
                        <i data-lucide="wallet" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-1.5 mb-2">
                    <span class="text-sm font-bold text-slate-400">Rp</span>
                    <h3 class="text-3xl font-black text-[#2c3e50] tracking-tighter">{{ number_format($summary['total_keuntungan'] / 1000000, 1) }}</h3>
                    <span class="text-xs font-bold text-slate-400 uppercase">Jt</span>
                </div>
                <div class="flex items-center gap-1.5">
                    @if($moMProfit >= 0)
                        <div class="flex items-center text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">
                            <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i> +{{ number_format($moMProfit, 1) }}%
                        </div>
                    @else
                        <div class="flex items-center text-[10px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded border border-red-100">
                            <i data-lucide="trending-down" class="w-3 h-3 mr-1"></i> {{ number_format($moMProfit, 1) }}%
                        </div>
                    @endif
                    <span class="text-[10px] text-slate-400 font-semibold">vs Bulan Lalu</span>
                </div>
            </div>
            @endif
        </div>

        <!-- 2. ROW TENGAH: VISUALISASI TREN & PERBANDINGAN -->
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 lg:gap-6">
            
            <!-- Area Chart -->
            <div class="{{ auth()->user()->canAccess('financial') ? 'lg:col-span-3' : 'lg:col-span-5' }} bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/40 border border-slate-100 flex flex-col relative">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-2">
                    <div>
                        <h3 class="text-lg font-black text-[#2c3e50]">Tren Produksi vs Konsumsi Pakan</h3>
                        <p class="text-xs font-semibold text-slate-400">Analisa 30 Hari Terakhir</p>
                    </div>
                    <div class="flex items-center gap-4 text-[10px] font-black uppercase tracking-widest">
                        <div class="flex items-center gap-1.5">
                            <div class="w-3 h-3 rounded bg-emerald-400"></div> <span class="text-slate-500">Produksi (Kg)</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-3 h-3 rounded bg-blue-400"></div> <span class="text-slate-500">Pakan (Kg)</span>
                        </div>
                    </div>
                </div>
                <div class="flex-1 w-full min-h-[320px] relative">
                    <canvas id="twinChart"></canvas>
                </div>
            </div>

            @if(auth()->user()->canAccess('financial'))
            <!-- Donut Chart: Kanan 40% -->
            <div class="lg:col-span-2 bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/40 border border-slate-100 flex flex-col relative">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-black text-[#2c3e50]">Komposisi Biaya Operasional</h3>
                        <p class="text-xs font-semibold text-slate-400">Berdasarkan Est. Pengeluaran</p>
                    </div>
                    <div class="p-2 bg-slate-50 rounded-lg text-slate-400">
                        <i data-lucide="pie-chart" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="flex-1 w-full min-h-[200px] relative mb-4">
                    <canvas id="donutChart"></canvas>
                </div>
                <div class="grid grid-cols-2 gap-3 mt-auto">
                    <!-- Custom Legends based on dummy data -->
                    @foreach($operationalCostDummy['keys'] as $index => $key)
                        @php 
                            $colors = ['bg-[#E74C3C]', 'bg-[#F39C12]', 'bg-[#3498DB]', 'bg-[#2ECC71]'];
                            $colorHex = $colors[$index % count($colors)];
                            $val = $operationalCostDummy['values'][$index];
                        @endphp
                        <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50 border border-slate-100">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full {{ $colorHex }}"></div>
                                <span class="text-[10px] font-bold text-slate-600 truncate">{{ $key }}</span>
                            </div>
                            <span class="text-xs font-black text-[#2c3e50]">{{ $val }}%</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- 3. ROW BAWAH: STATUS & PROYEKSI (HEATMAP) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 items-stretch">
            
            <!-- Heatmap / Kondisi Kandang -->
            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/40 border border-slate-100 flex flex-col h-full relative">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-black text-[#2c3e50]">Heatmap Kondisi Kandang</h3>
                        <p class="text-xs font-semibold text-slate-400">Status HDP & Deplesi Hari Ini</p>
                    </div>
                    <div class="flex items-center gap-2 text-[10px] font-bold uppercase">
                        <span class="flex items-center gap-1"><div class="w-2 h-2 rounded bg-[#2ECC71]"></div>Aman</span>
                        <span class="flex items-center gap-1"><div class="w-2 h-2 rounded bg-[#F39C12]"></div>Awas</span>
                        <span class="flex items-center gap-1"><div class="w-2 h-2 rounded bg-[#E74C3C]"></div>Kritis</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @forelse($coops as $coop)
                        @php
                            $lastReport = $coop->dailyReports()->latest('tanggal')->first();
                            $hdp = ($lastReport && $coop->populasi_awal > 0) ? ($lastReport->jumlah_telur_butir / $coop->populasi_awal) * 100 : 0;
                            // Simplistic heatmap rules: HDP < (Target - 10) = Danger, HDP < Target = Warning, Else Safe
                            $diff = $hdp - $coop->target_hdp;
                            $status = 'safe'; // Green
                            $bgClass = 'bg-emerald-50 border-emerald-200 text-emerald-700';
                            $icon = 'check-circle';
                            $iconColor = 'text-emerald-500';

                            if ($diff < -15) {
                                $status = 'danger';
                                $bgClass = 'bg-red-50 border-red-200 text-red-700';
                                $icon = 'alert-octagon';
                                $iconColor = 'text-[#E74C3C]';
                            } elseif ($diff < -5) {
                                $status = 'warning';
                                $bgClass = 'bg-orange-50 border-orange-200 text-orange-700';
                                $icon = 'alert-triangle';
                                $iconColor = 'text-[#F39C12]';
                            }
                        @endphp
                        <div class="p-3 rounded-2xl border {{ $bgClass }} flex flex-col transition-transform hover:scale-105 cursor-pointer">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-black uppercase tracking-wider">{{ $coop->kode_kandang }}</span>
                                <i data-lucide="{{ $icon }}" class="w-4 h-4 {{ $iconColor }}"></i>
                            </div>
                            <div class="mt-auto">
                                <span class="text-[10px] font-bold opacity-70 block mb-0.5">Real HDP</span>
                                <span class="text-lg font-black tracking-tighter">{{ number_format($hdp, 1) }}%</span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center text-xs text-slate-400 py-4">Belum ada kandang.</div>
                    @endforelse
                </div>
            </div>

            <!-- Proyeksi Panen/Afkir -->
            <div class="bg-gradient-to-br from-[#34495E] to-[#2c3e50] rounded-3xl p-6 shadow-xl shadow-slate-900/30 border border-slate-700 flex flex-col h-full relative overflow-hidden text-white">
                <div class="absolute top-0 right-0 p-8 opacity-10 transform translate-x-4 -translate-y-4">
                    <i data-lucide="calendar-clock" class="w-32 h-32"></i>
                </div>
                <div class="relative z-10 flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-black text-white">Proyeksi Siklus Produksi</h3>
                        <p class="text-xs font-semibold text-slate-400">Estimasi waktu afkir kandang tertua</p>
                    </div>
                </div>

                @php $oldestCoop = $coops->first(); @endphp
                @if($oldestCoop)
                    <div class="relative z-10 bg-white/5 border border-white/10 p-5 rounded-2xl backdrop-blur-md">
                        <div class="flex justify-between items-center mb-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                                    <i data-lucide="home" class="w-4 h-4"></i>
                                </div>
                                <span class="font-bold text-sm">{{ $oldestCoop->kode_kandang }}</span>
                            </div>
                            <span class="text-xs font-bold text-slate-300">Phase 2</span>
                        </div>
                        <div class="mb-2 flex justify-between items-end">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Progress Umur</span>
                            <span class="text-lg font-black text-emerald-400">65%</span>
                        </div>
                        <div class="w-full bg-slate-800 rounded-full h-2.5 overflow-hidden border border-slate-700">
                            <div class="bg-gradient-to-r from-emerald-500 to-emerald-300 h-full rounded-full" style="width: 65%"></div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-3 text-center">Estimasi Afkir: <strong class="text-white">~4 Bulan Lagi</strong></p>
                    </div>
                @else
                    <div class="text-center text-slate-500 text-xs py-8">Tidak ada data umur flok.</div>
                @endif
                
                <button class="mt-auto w-full py-3 bg-white/10 hover:bg-white/20 transition-colors border border-white/10 rounded-xl text-xs font-bold uppercase tracking-widest text-slate-200 flex justify-center items-center gap-2">
                    <i data-lucide="bar-chart-2" class="w-4 h-4"></i> Lihat Master Plan
                </button>
            </div>
        </div>

</div>

@push('scripts')
    <!-- Chart.js for interactive visualizations -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Init Lucide Icons
            lucide.createIcons();

            if(document.getElementById('twinChart')) {
                // Global Chart Defaults
                Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
                Chart.defaults.color = '#94a3b8';

                fetch('{{ route("dashboard.chart-data") }}')
                    .then(response => response.json())
                    .then(data => {
                        
                        // 1. TWIN AREA CHART (Produksi vs Pakan)
                        const twinCtx = document.getElementById('twinChart').getContext('2d');
                        
                        // Gradients
                        const gradProd = twinCtx.createLinearGradient(0, 0, 0, 350);
                        gradProd.addColorStop(0, 'rgba(46, 204, 113, 0.5)'); // Emerald 500
                        gradProd.addColorStop(1, 'rgba(46, 204, 113, 0.0)');

                        const gradPakan = twinCtx.createLinearGradient(0, 0, 0, 350);
                        gradPakan.addColorStop(0, 'rgba(52, 152, 219, 0.5)'); // Blue 500
                        gradPakan.addColorStop(1, 'rgba(52, 152, 219, 0.0)');

                        new Chart(twinCtx, {
                            type: 'line',
                            data: {
                                labels: data.trend.labels,
                                datasets: [
                                    {
                                        label: 'Total Produksi (Kg)',
                                        data: data.trend.produksi,
                                        borderColor: '#2ECC71',
                                        backgroundColor: gradProd,
                                        borderWidth: 3,
                                        fill: true,
                                        tension: 0.4,
                                        yAxisID: 'y',
                                        pointRadius: 0,
                                        pointHoverRadius: 6,
                                        pointBackgroundColor: '#2ECC71'
                                    },
                                    {
                                        label: 'Total Pakan (Kg)',
                                        data: data.trend.pakan,
                                        borderColor: '#3498DB',
                                        backgroundColor: gradPakan,
                                        borderWidth: 3,
                                        fill: true,
                                        tension: 0.4,
                                        yAxisID: 'y1',
                                        pointRadius: 0,
                                        pointHoverRadius: 6,
                                        pointBackgroundColor: '#3498DB'
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: {
                                    mode: 'index',
                                    intersect: false,
                                },
                                plugins: {
                                    legend: { display: false }, // Custom legend used in HTML
                                    tooltip: {
                                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                                        titleColor: '#2c3e50',
                                        bodyColor: '#34495E',
                                        borderColor: '#e2e8f0',
                                        borderWidth: 1,
                                        padding: 12,
                                        titleFont: { size: 14, weight: 'bold', family: "'Plus Jakarta Sans'" },
                                        bodyFont: { size: 13, weight: 'bold' },
                                        cornerRadius: 12,
                                        boxPadding: 6,
                                        callbacks: {
                                            label: (ctx) => ' ' + ctx.dataset.label + ': ' + ctx.parsed.y.toLocaleString('id-ID') + ' Kg'
                                        }
                                    }
                                },
                                scales: {
                                    x: { 
                                        grid: { display: false },
                                        ticks: { font: { size: 10, weight: 'bold' }, maxTicksLimit: 10 }
                                    },
                                    y: {
                                        type: 'linear',
                                        display: true,
                                        position: 'left',
                                        grid: { color: '#f1f5f9', drawBorder: false },
                                        title: { display: true, text: 'Produksi (Kg)', font: {size: 10, weight: 'bold'}, color: '#2ECC71' }
                                    },
                                    y1: {
                                        type: 'linear',
                                        display: true,
                                        position: 'right',
                                        grid: { display: false },
                                        title: { display: true, text: 'Pakan (Kg)', font: {size: 10, weight: 'bold'}, color: '#3498DB' }
                                    }
                                }
                            }
                        });
                    });

                // 2. DONUT CHART (Operasional Dummy)
                if(document.getElementById('donutChart')) {
                    const donutCtx = document.getElementById('donutChart').getContext('2d');
                    new Chart(donutCtx, {
                        type: 'doughnut',
                        data: {
                            labels: {!! json_encode($operationalCostDummy['keys']) !!},
                            datasets: [{
                                data: {!! json_encode($operationalCostDummy['values']) !!},
                                backgroundColor: [
                                    '#E74C3C', // Pakan (Red - largest cost warning)
                                    '#F39C12', // Vitamin
                                    '#3498DB', // Listrik
                                    '#2ECC71'  // Gaji
                                ],
                                borderWidth: 0,
                                hoverOffset: 8
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '75%', // Modern thin donut
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: '#1e293b',
                                    padding: 12,
                                    cornerRadius: 10,
                                    bodyFont: { size: 14, weight: 'bold', family: "'Plus Jakarta Sans'" },
                                    callbacks: {
                                        label: (ctx) => ' ' + ctx.label + ': ' + ctx.raw + '%'
                                    }
                                }
                            }
                        }
                    });
                }
            }
        });
    </script>
@endpush
@endsection