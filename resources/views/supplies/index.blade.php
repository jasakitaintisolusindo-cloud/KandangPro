@extends('layouts.app')

@section('title', 'Manajemen Stok')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Manajemen Stok</h2>
                <p class="mt-1 text-sm text-gray-600">Pantau sisa pakan dan obat-obatan gudang</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('supplies.transactions') }}"
                    class="bg-white text-gray-700 px-6 py-3 rounded-xl shadow-md hover:shadow-lg transition-all font-bold flex items-center border border-gray-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Riwayat Transaksi
                </a>
                <a href="{{ route('supplies.create') }}"
                    class="bg-emerald-600 text-white px-6 py-3 rounded-xl shadow-md hover:bg-emerald-700 hover:shadow-lg transition-all font-bold flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Barang
                </a>
            </div>
        </div>

        <!-- Stock Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($supplies as $supply)
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div
                                class="p-2 rounded-lg {{ $supply->kategori == 'Pakan' ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600' }}">
                                @if($supply->kategori == 'Pakan')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                @endif
                            </div>
                            <span
                                class="px-3 py-1 rounded-full text-xs font-bold {{ $supply->stok_saat_ini <= $supply->stok_minimal ? 'bg-red-100 text-red-600 animate-pulse' : 'bg-green-100 text-green-600' }}">
                                {{ $supply->stok_saat_ini <= $supply->stok_minimal ? 'STOK RENDAH' : 'STOK AMAN' }}
                            </span>
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $supply->nama_barang }}</h3>
                        <p class="text-xs text-gray-500 uppercase font-black tracking-widest mb-4">{{ $supply->kategori }}</p>

                        <div class="flex items-end gap-2 mb-4">
                            <span
                                class="text-4xl font-black text-gray-900">{{ number_format($supply->stok_saat_ini, 0, ',', '.') }}</span>
                            <span class="text-gray-500 font-bold pb-1">{{ $supply->satuan_kecil }}</span>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-4 mb-4">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-500 font-bold">Setara:</span>
                                <span class="text-gray-900 font-black">{{ $supply->stok_formatted }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 font-bold">Stok Minimal:</span>
                                <span class="text-red-500 font-black">{{ number_format($supply->stok_minimal, 0) }}
                                    {{ $supply->satuan_kecil }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="grid grid-cols-2 gap-2 mt-4">
                            <button onclick="openModal('modal-in-{{ $supply->id }}')"
                                class="bg-gray-900 text-white py-2 rounded-lg text-sm font-bold hover:bg-black transition-all">
                                Beli Stok
                            </button>
                            <button onclick="openModal('modal-out-{{ $supply->id }}')"
                                class="bg-white border border-gray-200 text-gray-700 py-2 rounded-lg text-sm font-bold hover:bg-gray-50 transition-all">
                                Gunakan
                            </button>
                        </div>
                    </div>
                    <div class="border-t border-gray-100 px-6 py-3 bg-gray-50 flex justify-between items-center">
                        <a href="{{ route('supplies.edit', $supply) }}"
                            class="text-blue-600 text-sm font-bold hover:underline">Edit Master</a>
                        <form action="{{ route('supplies.destroy', $supply) }}" method="POST"
                            onsubmit="return confirm('Hapus master stok ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Modal Stock In -->
                <div id="modal-in-{{ $supply->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto"
                    aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                            onclick="closeModal('modal-in-{{ $supply->id }}')"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div
                            class="inline-block align-middle bg-white rounded-2xl p-8 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">Input Pembelian - {{ $supply->nama_barang }}</h3>
                            <form action="{{ route('supplies.stock-in', $supply) }}" method="POST" class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah
                                            ({{ $supply->satuan_besar }})</label>
                                        <input type="number" step="1" name="jumlah_besar" required
                                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Sisa
                                            ({{ $supply->satuan_kecil }})</label>
                                        <input type="number" step="0.01" name="jumlah_kecil" value="0"
                                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none">
                                    </div>
                                </div>
                                <p class="text-[10px] text-gray-400 italic font-bold uppercase tracking-widest">* 1
                                    {{ $supply->satuan_besar }} = {{ number_format($supply->konversi, 0) }}
                                    {{ $supply->satuan_kecil }}</p>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Pembelian</label>
                                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Total Harga Pembelian (Rp)</label>
                                    <input type="number" name="total_harga" placeholder="0" required
                                        class="w-full px-4 py-2 border border-emerald-200 bg-emerald-50 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none font-black text-emerald-900">
                                </div>
                                @if($supply->kategori == 'Obat')
                                    <div>
                                        <label
                                            class="block text-sm font-bold text-gray-700 mb-2 text-red-600 uppercase tracking-tighter">Tanggal
                                            Kadaluwarsa (FIFO)</label>
                                        <input type="date" name="expired_at"
                                            class="w-full px-4 py-2 border border-red-200 rounded-lg focus:ring-2 focus:ring-red-500 outline-none">
                                    </div>
                                @endif
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Keterangan</label>
                                    <textarea name="keterangan" rows="2"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none"></textarea>
                                </div>
                                <div class="flex gap-2 pt-4">
                                    <button type="submit"
                                        class="flex-1 bg-emerald-600 text-white font-bold py-3 rounded-xl hover:bg-emerald-700 transition-all shadow-lg">Simpan
                                        Stok Masuk</button>
                                    <button type="button" onclick="closeModal('modal-in-{{ $supply->id }}')"
                                        class="px-6 py-3 border border-gray-200 rounded-xl font-bold text-gray-500 transition-all hover:bg-gray-50">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal Stock Out -->
                <div id="modal-out-{{ $supply->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto"
                    aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                            onclick="closeModal('modal-out-{{ $supply->id }}')"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div
                            class="inline-block align-middle bg-white rounded-2xl p-8 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">Catat Pemakaian Manual -
                                {{ $supply->nama_barang }}</h3>
                            <form action="{{ route('supplies.stock-out', $supply) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Pemakaian
                                        ({{ $supply->satuan_kecil }})</label>
                                    <input type="number" step="0.01" name="jumlah" required
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Pakai</label>
                                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Tujuan / Keterangan</label>
                                    <input type="text" name="keterangan" placeholder="Contoh: Pembersihan Kandang A" required
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <div class="flex gap-2 pt-4">
                                    <button type="submit"
                                        class="flex-1 bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition-all shadow-lg">Simpan
                                        Pemakaian</button>
                                    <button type="button" onclick="closeModal('modal-out-{{ $supply->id }}')"
                                        class="px-6 py-3 border border-gray-200 rounded-xl font-bold text-gray-500 transition-all hover:bg-gray-50">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Empty State -->
        @if($supplies->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-dashed border-gray-300 p-20 text-center">
                <div class="p-4 bg-gray-50 rounded-full inline-block mb-4 text-gray-400">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Belum ada data barang</h3>
                <p class="text-gray-500 mt-2">Daftarkan pakan atau obat-obatan untuk mulai mengelola stok.</p>
                <a href="{{ route('supplies.create') }}"
                    class="mt-6 inline-block bg-emerald-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-emerald-700 transition-all">Tambah
                    Barang Sekarang</a>
            </div>
        @endif

        <div class="mt-6">
            {{ $supplies->links() }}
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    </script>
@endsection