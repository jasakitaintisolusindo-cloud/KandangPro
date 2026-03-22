@extends('layouts.app')

@section('title', 'Master Kandang')

@section('content')
    <div class="space-y-6">
        <!-- Header & Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Title Section -->
            <div class="md:col-span-3 bg-white rounded-2xl shadow-sm p-6 border border-slate-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-3xl font-black text-slate-800 tracking-tight">Master Kandang</h2>
                        <p class="text-slate-500 font-medium">Kelola kapasitas dan status operasional kandang</p>
                    </div>
                    <button onclick="openModal('addCoopModal')"
                        class="inline-flex items-center px-6 py-3 bg-emerald-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-100 hover:bg-emerald-700 transform hover:scale-[1.02] transition-all active:scale-95">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Kandang
                    </button>
                </div>
            </div>

            <!-- Summary: Kandang Aktif -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-100 border-l-4 border-l-emerald-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-slate-400 uppercase tracking-wider">Kandang Aktif</p>
                        <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $summary['total_active_coops'] }}</h3>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-2xl text-emerald-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Summary: Total Kapasitas -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-100 border-l-4 border-l-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-slate-400 uppercase tracking-wider">Total Kapasitas</p>
                        <h3 class="text-3xl font-black text-slate-800 mt-1">{{ number_format($summary['total_capacity'], 0, ',', '.') }}</h3>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-2xl text-blue-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Summary: Populasi Saat Ini -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-100 border-l-4 border-l-amber-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-slate-400 uppercase tracking-wider">Populasi Saat Ini</p>
                        <h3 class="text-3xl font-black text-slate-800 mt-1">{{ number_format($summary['total_current_population'], 0, ',', '.') }}</h3>
                    </div>
                    <div class="p-3 bg-amber-50 rounded-2xl text-amber-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filter Area -->
        <div class="bg-white rounded-2xl shadow-sm p-4 border border-slate-100">
            <form action="{{ route('coops.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari Kode Kandang (contoh: K-001)..."
                        class="block w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-xl leading-5 bg-slate-50 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:border-transparent transition-all sm:text-sm font-medium">
                </div>
                <button type="submit"
                    class="px-6 py-2.5 bg-slate-800 text-white font-bold rounded-xl hover:bg-slate-900 transition-all active:scale-95">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('coops.index') }}"
                        class="px-6 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all text-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Kode Kandang</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Peternakan</th>
                            <th class="px-6 py-4 text-right text-xs font-black text-slate-500 uppercase tracking-widest">Populasi Awal</th>
                            <th class="px-6 py-4 text-right text-xs font-black text-slate-500 uppercase tracking-widest">Pop. Saat Ini</th>
                            <th class="px-6 py-4 text-center text-xs font-black text-slate-500 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-black text-slate-500 uppercase tracking-widest">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($coops as $coop)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-black text-slate-700 uppercase tracking-tight">{{ $coop->kode_kandang }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-slate-600">{{ $coop->farm->nama }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span class="text-sm font-black text-slate-700">{{ number_format($coop->populasi_awal, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span class="text-sm font-black text-emerald-600">{{ number_format($coop->populasi_saat_ini, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($coop->status)
                                        <span class="px-3 py-1 inline-flex text-[10px] leading-5 font-black rounded-full bg-emerald-100 text-emerald-700 uppercase tracking-widest">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-[10px] leading-5 font-black rounded-full bg-slate-100 text-slate-500 uppercase tracking-widest">
                                            Non-Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('coops.show', $coop) }}"
                                            class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all" title="Lihat CCTV & Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                        </a>
                                        <button onclick="openEditModal({{ $coop->toJson() }})"
                                            class="p-2 text-blue-500 hover:bg-blue-50 rounded-xl transition-all" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <form action="{{ route('coops.destroy', $coop) }}" method="POST"
                                            onsubmit="return confirm('PENTING: Menghapus kandang ini akan menghilangkan akses ke data laporan harian terkait. Kami sarankan hanya menonaktifkan status jika data ingin tetap tersimpan.\n\nApakah Anda yakin ingin menghapus?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all" title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-3">
                                        <div class="p-4 bg-slate-50 rounded-full text-slate-300">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </div>
                                        <p class="text-slate-400 font-bold">Belum ada data kandang.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($coops->hasPages())
                <div class="bg-slate-50 px-6 py-4 border-t border-slate-100">
                    {{ $coops->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="coopModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm shadow-2xl"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/20 animate-modal-enter">
                <form id="coopForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    
                    <div class="bg-white p-6 sm:p-8">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h3 class="text-2xl font-black text-slate-800 tracking-tight" id="modalTitle">Tambah Master Kandang</h3>
                                <p class="text-slate-500 text-sm mt-1">Lengkapi form di bawah untuk kandang baru.</p>
                            </div>
                            <button type="button" onclick="closeModal()" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="space-y-6">
                            <!-- Farm ID -->
                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Peternakan <span class="text-red-500">*</span></label>
                                <select name="farm_id" id="farm_id" required
                                    class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:border-transparent transition-all font-bold text-slate-700">
                                    <option value="">Pilih Peternakan</option>
                                    @php $farms = \App\Models\Farm::all(); @endphp
                                    @foreach($farms as $farm)
                                        <option value="{{ $farm->id }}">{{ $farm->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Kode Kandang -->
                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Kode Kandang <span class="text-red-500">*</span></label>
                                <input type="text" name="kode_kandang" id="kode_kandang" required placeholder="CONTOH: K-001"
                                    class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:border-transparent transition-all font-bold text-slate-700 uppercase">
                            </div>

                            <!-- Populasi Awal -->
                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Populasi <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="number" name="populasi_awal" id="populasi_awal" required min="1" placeholder="0"
                                        class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:border-transparent transition-all font-bold text-slate-700">
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <span class="text-slate-400 font-bold text-sm">Ekor</span>
                                    </div>
                                </div>
                            </div>

                            <!-- CCTV URL -->
                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">URL CCTV (HLS/RTSP)</label>
                                <input type="url" name="cctv_url" id="cctv_url" placeholder="https://example.com/stream.m3u8"
                                    class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:border-transparent transition-all font-bold text-slate-700">
                                <p class="mt-1 text-xs text-slate-400">Opsional. Masukkan URL stream untuk live monitoring.</p>
                            </div>

                            <!-- Status Toggle -->
                            <div class="flex items-center justify-between p-4 bg-emerald-50 rounded-2xl border border-emerald-100">
                                <div>
                                    <p class="text-sm font-black text-emerald-800 uppercase tracking-tight">Status Kandang</p>
                                    <p class="text-[10px] text-emerald-600 font-bold uppercase tracking-widest">Aktifkan untuk operasional</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="status" id="status" value="1" class="sr-only peer" checked>
                                    <div class="w-14 h-8 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-6 peer-checked:after:border-white after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-emerald-500"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-8 py-6 flex flex-row-reverse gap-3">
                        <button type="submit"
                            class="flex-1 px-6 py-3 bg-emerald-600 text-white font-black rounded-2xl shadow-lg shadow-emerald-100 hover:bg-emerald-700 transform hover:scale-[1.02] transition-all active:scale-95">
                            Simpan Perubahan
                        </button>
                        <button type="button" onclick="closeModal()"
                            class="flex-1 px-6 py-3 bg-white text-slate-500 font-black rounded-2xl border border-slate-200 hover:bg-slate-100 transition-all">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(type) {
            const modal = document.getElementById('coopModal');
            const form = document.getElementById('coopForm');
            const title = document.getElementById('modalTitle');
            const method = document.getElementById('formMethod');
            
            form.reset();
            method.value = 'POST';
            form.action = "{{ route('coops.store') }}";
            title.innerText = 'Tambah Master Kandang';
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function openEditModal(coop) {
            const modal = document.getElementById('coopModal');
            const form = document.getElementById('coopForm');
            const title = document.getElementById('modalTitle');
            const method = document.getElementById('formMethod');
            
            form.action = `/coops/${coop.id}`;
            method.value = 'PUT';
            title.innerText = 'Edit Data Kandang';
            
            document.getElementById('farm_id').value = coop.farm_id;
            document.getElementById('kode_kandang').value = coop.kode_kandang;
            document.getElementById('populasi_awal').value = coop.populasi_awal;
            document.getElementById('cctv_url').value = coop.cctv_url || '';
            document.getElementById('status').checked = !!coop.status;
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('coopModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('coopModal');
            if (event.target == modal) {
                closeModal();
            }
        }

        @if(session('edit_coop_data'))
            document.addEventListener("DOMContentLoaded", function() {
                const coopData = {!! session('edit_coop_data') !!};
                openEditModal(coopData);
            });
        @endif
    </script>

    <style>
        .animate-modal-enter {
            animation: modal-enter 0.3s ease-out;
        }
        @keyframes modal-enter {
            from { opacity: 0; transform: scale(0.95) translateY(20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
    </style>
@endsection
