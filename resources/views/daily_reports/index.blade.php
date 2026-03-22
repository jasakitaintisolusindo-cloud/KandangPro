@extends('layouts.app')

@section('title', 'Laporan Harian')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-emerald-500">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Laporan Harian</h2>
                    <p class="mt-1 text-sm text-gray-600">Kelola data produksi dan keuangan harian peternakan</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('daily-reports.export', request()->all()) }}"
                        class="inline-flex items-center px-6 py-3 bg-white border-2 border-emerald-600 text-emerald-600 font-semibold rounded-lg shadow-md hover:bg-emerald-50 transform hover:scale-105 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export Excel
                    </a>
                    <a href="{{ route('daily-reports.create') }}"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-lg shadow-md hover:from-emerald-700 hover:to-teal-700 transform hover:scale-105 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Data
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <form method="GET" action="{{ route('daily-reports.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                            Mulai</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                    </div>
                    <div>
                        <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                            Akhir</label>
                        <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                    </div>
                    <div>
                        <label for="coop_id" class="block text-sm font-medium text-gray-700 mb-2">Kandang</label>
                        <select name="coop_id" id="coop_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                            <option value="">Semua Kandang</option>
                            @foreach($coops as $coop)
                                <option value="{{ $coop->id }}" {{ request('coop_id') == $coop->id ? 'selected' : '' }}>
                                    {{ $coop->kode_kandang }} - {{ $coop->farm->nama_peternakan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button type="submit"
                        class="px-6 py-2 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('daily-reports.index') }}"
                        class="px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-all duration-200">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-emerald-600 to-teal-600">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Kandang</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">
                                Produksi (kg)</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">
                                Harga/kg</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">
                                Pendapatan</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">Biaya
                                Pakan</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">Biaya
                                Lain</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">
                                Keuntungan</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($reports as $report)
                            <tr class="hover:bg-emerald-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $report->tanggal->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $report->coop->kode_kandang }}</div>
                                    <div class="text-xs text-gray-500">{{ $report->coop->farm->nama_peternakan }}</div>
                                    <div class="mt-1 text-[10px] text-gray-400">
                                        <i class="fas fa-pencil-alt mr-1"></i>Input: <span class="font-semibold">{{ $report->creator->name ?? 'Sistem' }}</span>
                                        @if($report->verifiedBy)
                                        | <i class="fas fa-check-double ml-1 mr-1 text-emerald-500"></i>Approve: <span class="font-semibold">{{ $report->verifiedBy->name }}</span>
                                        @endif
                                    </div>
                                    @if($report->status == 'rejected')
                                        <div class="mt-1 flex items-center text-[10px] text-red-600 max-w-[150px]" title="{{ $report->rejection_note }}">
                                            <i class="fas fa-info-circle mr-1"></i> <span class="truncate font-medium">{{ $report->rejection_note }}</span>
                                        </div>
                                    @elseif($report->status == 'approved')
                                        <div class="mt-1 text-[10px] text-emerald-600 font-bold"><i class="fas fa-lock mr-1"></i>Data Terkunci</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($report->status == 'approved')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">Approved</span>
                                    @elseif($report->status == 'draft')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Draft</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                                    {{ number_format($report->produksi_telur_kg, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                                    Rp {{ number_format($report->harga_telur_per_kg, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-emerald-600">
                                    Rp {{ number_format($report->total_pendapatan_telur, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-600">
                                    Rp {{ number_format($report->total_biaya_pakan, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-600">
                                    Rp {{ number_format($report->biaya_lain_lain, 0, ',', '.') }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold {{ $report->keuntungan_bersih >= 0 ? 'text-emerald-700' : 'text-red-700' }}">
                                    Rp {{ number_format($report->keuntungan_bersih, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center gap-2">
                                        @if(auth()->user()->isManager() && ($report->status == 'draft' || $report->status == 'rejected'))
                                            <!-- Approve Button (SweetAlert) -->
                                            <button type="button" onclick="confirmApprove('{{ route('daily-reports.approve', $report) }}')" class="text-emerald-500 hover:text-emerald-800 transition-colors" title="Approve Laporan">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            </button>
                                            <!-- Reject Button (SweetAlert) -->
                                            <button type="button" onclick="confirmReject('{{ route('daily-reports.reject', $report) }}')" class="text-orange-500 hover:text-orange-800 transition-colors" title="Tolak Laporan">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            </button>
                                        @endif
                                        
                                        @if($report->status !== 'approved')
                                            <a href="{{ route('daily-reports.edit', $report) }}" class="text-blue-600 hover:text-blue-900 transition-colors" title="Lihat/Edit & Bukti">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                            </a>
                                            <button type="button" onclick="confirmDelete('{{ route('daily-reports.destroy', $report) }}')" class="text-red-600 hover:text-red-900 transition-colors" title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-lg font-medium">Belum ada data laporan</p>
                                        <p class="text-sm mt-1">Klik tombol "Tambah Data" untuk memulai</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                        @if($reports->count() > 0)
                            <tr class="bg-gradient-to-r from-emerald-100 to-teal-100 font-bold">
                                <td colspan="5" class="px-6 py-4 text-right text-sm uppercase">TOTAL:</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-emerald-700">
                                    Rp {{ number_format($reports->sum('total_pendapatan_telur'), 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-700">
                                    Rp {{ number_format($reports->sum('total_biaya_pakan'), 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-700">
                                    Rp {{ number_format($reports->sum('biaya_lain_lain'), 0, ',', '.') }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-right {{ $reports->sum('keuntungan_bersih') >= 0 ? 'text-emerald-800' : 'text-red-800' }}">
                                    Rp {{ number_format($reports->sum('keuntungan_bersih'), 0, ',', '.') }}
                                </td>
                                <td></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($reports->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Hidden form for SweetAlert Actions -->
    <form id="actionForm" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="_method" id="actionMethod" value="PUT">
        <input type="hidden" name="rejection_note" id="actionRejectionNote" value="">
    </form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const actionForm = document.getElementById('actionForm');
    const methodInput = document.getElementById('actionMethod');
    const noteInput = document.getElementById('actionRejectionNote');

    function confirmApprove(url) {
        Swal.fire({
            title: 'Approve Laporan?',
            html: '<p class="text-slate-500 leading-relaxed font-medium">Data yang disetujui akan <strong class="text-slate-800 font-bold">mengunci laporan stok pakan</strong> di gudang dan masuk ke <strong class="text-slate-800 font-bold">Executive Dashboard</strong>.</p>',
            icon: 'question',
            iconColor: '#10B981',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-check-circle mr-2"></i> Ya, Approve!',
            cancelButtonText: 'Batal',
            buttonsStyling: false,
            customClass: {
                popup: 'rounded-3xl shadow-2xl p-4 border border-slate-100',
                title: 'text-2xl font-black text-slate-700 mt-2 mb-1',
                htmlContainer: 'text-sm mt-2 mb-4',
                icon: 'border-0 bg-emerald-50 rounded-full scale-[1.15] p-2 mt-4',
                confirmButton: 'bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-emerald-500/30 transform hover:-translate-y-0.5 transition-all duration-300',
                cancelButton: 'bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-3 px-6 rounded-xl shadow-sm transition-colors ml-3',
                actions: 'mt-6 w-full flex justify-center gap-3'
            },
            backdrop: `rgba(15, 23, 42, 0.7)`,
            didOpen: () => {
                const backdrop = document.querySelector('.swal2-container');
                if(backdrop) backdrop.style.backdropFilter = 'blur(5px)';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                actionForm.action = url;
                methodInput.value = 'PUT';
                actionForm.submit();
            }
        });
    }

    function confirmReject(url) {
        Swal.fire({
            title: 'Kembalikan Laporan?',
            input: 'textarea',
            inputLabel: 'Berikan alasan penolakan untuk dikoreksi pekerja:',
            inputPlaceholder: 'Contoh: Bukti foto timbangan kurang jelas...',
            inputAttributes: {
                'aria-label': 'Alasan penolakan'
            },
            icon: 'warning',
            iconColor: '#F59E0B',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-undo mr-2"></i> Kembalikan Laporan',
            cancelButtonText: 'Batal',
            buttonsStyling: false,
            customClass: {
                popup: 'rounded-3xl shadow-2xl p-4 border border-slate-100',
                title: 'text-2xl font-black text-slate-700 mt-2 mb-1',
                htmlContainer: 'text-sm mt-2',
                input: 'w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-slate-50 font-medium',
                inputLabel: 'text-sm font-bold text-slate-600 text-left mb-2',
                icon: 'border-0 bg-orange-50 rounded-full scale-[1.15] p-2 mt-4',
                confirmButton: 'bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-orange-500/30 transform hover:-translate-y-0.5 transition-all duration-300',
                cancelButton: 'bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-3 px-6 rounded-xl shadow-sm transition-colors ml-3',
                actions: 'mt-6 w-full flex justify-center gap-3'
            },
            backdrop: `rgba(15, 23, 42, 0.7)`,
            didOpen: () => {
                const backdrop = document.querySelector('.swal2-container');
                if(backdrop) backdrop.style.backdropFilter = 'blur(5px)';
            },
            inputValidator: (value) => {
                if (!value) {
                    return 'Alasan revisi wajib diisi agar pekerja bisa memperbaiki!'
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                actionForm.action = url;
                methodInput.value = 'PUT';
                noteInput.value = result.value;
                actionForm.submit();
            }
        });
    }

    function confirmDelete(url) {
        Swal.fire({
            title: 'Hapus Laporan?',
            html: '<p class="text-slate-500 font-medium">Data yang dihapus (beserta gambar bukti fisiknya) <strong class="text-red-500">tidak dapat dikembalikan!</strong></p>',
            icon: 'error',
            iconColor: '#EF4444',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-trash-alt mr-2"></i> Ya, Hapus!',
            cancelButtonText: 'Batal',
            buttonsStyling: false,
            customClass: {
                popup: 'rounded-3xl shadow-2xl p-4 border border-slate-100',
                title: 'text-2xl font-black text-slate-700 mt-2 mb-1',
                htmlContainer: 'text-sm mt-2 mb-4',
                icon: 'border-0 bg-red-50 rounded-full scale-[1.15] p-2 mt-4',
                confirmButton: 'bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-red-500/30 transform hover:-translate-y-0.5 transition-all duration-300',
                cancelButton: 'bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-3 px-6 rounded-xl shadow-sm transition-colors ml-3',
                actions: 'mt-6 w-full flex justify-center gap-3'
            },
            backdrop: `rgba(15, 23, 42, 0.7)`,
            didOpen: () => {
                const backdrop = document.querySelector('.swal2-container');
                if(backdrop) backdrop.style.backdropFilter = 'blur(5px)';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                actionForm.action = url;
                methodInput.value = 'DELETE';
                actionForm.submit();
            }
        });
    }
</script>
@endpush