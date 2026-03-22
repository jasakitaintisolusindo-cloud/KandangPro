@extends('layouts.app')

@section('title', 'Master Peternakan')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2 bg-white rounded-2xl shadow-sm p-6 border border-slate-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-3xl font-black text-slate-800 tracking-tight">Master Peternakan</h2>
                        <p class="text-slate-500 font-medium">Kelola lokasi peternakan Anda</p>
                    </div>
                    <button onclick="openModal('addFarmModal')"
                        class="inline-flex items-center px-6 py-3 bg-emerald-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-100 hover:bg-emerald-700 transform hover:scale-[1.02] transition-all active:scale-95">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Peternakan
                    </button>
                </div>
            </div>
        </div>

        <!-- Search & Filter Area -->
        <div class="bg-white rounded-2xl shadow-sm p-4 border border-slate-100">
            <form action="{{ route('farms.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari Nama Peternakan atau Lokasi..."
                        class="block w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-xl leading-5 bg-slate-50 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:border-transparent transition-all sm:text-sm font-medium">
                </div>
                <button type="submit"
                    class="px-6 py-2.5 bg-slate-800 text-white font-bold rounded-xl hover:bg-slate-900 transition-all active:scale-95">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('farms.index') }}"
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
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Nama
                                Peternakan</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-widest">
                                Lokasi</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-widest">
                                Telepon</th>
                            <th class="px-6 py-4 text-center text-xs font-black text-slate-500 uppercase tracking-widest">
                                Jumlah Kandang</th>
                            <th class="px-6 py-4 text-center text-xs font-black text-slate-500 uppercase tracking-widest">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($farms as $farm)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-black text-slate-700">{{ $farm->nama }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-slate-600">{{ $farm->lokasi }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-slate-600">{{ $farm->telepon ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-600">
                                        {{ $farm->coops_count }} Kandang
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center gap-2">
                                        <button onclick="openEditModal({{ $farm->toJson() }})"
                                            class="p-2 text-blue-500 hover:bg-blue-50 rounded-xl transition-all" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <form action="{{ route('farms.destroy', $farm) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data peternakan ini?');"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-red-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all"
                                                title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-3">
                                        <div class="p-4 bg-slate-50 rounded-full text-slate-300">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </div>
                                        <p class="text-slate-400 font-bold">Belum ada data peternakan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($farms->hasPages())
                <div class="bg-slate-50 px-6 py-4 border-t border-slate-100">
                    {{ $farms->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="farmModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm shadow-2xl"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/20 animate-modal-enter">
                <form id="farmForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div class="bg-white p-6 sm:p-8">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h3 class="text-2xl font-black text-slate-800 tracking-tight" id="modalTitle">Tambah
                                    Peternakan</h3>
                                <p class="text-slate-500 text-sm mt-1">Lengkapi form di bawah untuk peternakan baru.</p>
                            </div>
                            <button type="button" onclick="closeModal()"
                                class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="space-y-6">
                            <!-- Nama Peternakan -->
                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Nama
                                    Peternakan <span class="text-red-500">*</span></label>
                                <input type="text" name="nama" id="nama" required placeholder="Contoh: Peternakan A"
                                    class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:border-transparent transition-all font-bold text-slate-700">
                            </div>

                            <!-- Lokasi -->
                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Lokasi
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="lokasi" id="lokasi" required placeholder="Contoh: Yogyakarta"
                                    class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:border-transparent transition-all font-bold text-slate-700">
                            </div>

                            <!-- Telepon -->
                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Telepon</label>
                                <input type="text" name="telepon" id="telepon" placeholder="Contoh: 081234567890"
                                    class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:border-transparent transition-all font-bold text-slate-700">
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
            const modal = document.getElementById('farmModal');
            const form = document.getElementById('farmForm');
            const title = document.getElementById('modalTitle');
            const method = document.getElementById('formMethod');

            form.reset();
            method.value = 'POST';
            form.action = "{{ route('farms.store') }}";
            title.innerText = 'Tambah Peternakan';

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function openEditModal(farm) {
            const modal = document.getElementById('farmModal');
            const form = document.getElementById('farmForm');
            const title = document.getElementById('modalTitle');
            const method = document.getElementById('formMethod');

            form.action = `/farms/${farm.id}`;
            method.value = 'PUT';
            title.innerText = 'Edit Data Peternakan';

            document.getElementById('nama').value = farm.nama;
            document.getElementById('lokasi').value = farm.lokasi;
            document.getElementById('telepon').value = farm.telepon || '';

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('farmModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        window.onclick = function (event) {
            const modal = document.getElementById('farmModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
@endsection