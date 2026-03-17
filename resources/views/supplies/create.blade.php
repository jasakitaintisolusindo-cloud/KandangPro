@extends('layouts.app')

@section('title', 'Tambah Barang Baru')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-100 bg-gray-50/50">
                <h2 class="text-3xl font-bold text-gray-900">Tambah Barang</h2>
                <p class="mt-1 text-sm text-gray-600">Daftarkan item pakan atau obat-obatan baru</p>
            </div>

            <form action="{{ route('supplies.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Barang -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Barang <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nama_barang" required placeholder="Contoh: Pakan Layer Super"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Kategori <span
                                class="text-red-500">*</span></label>
                        <select name="kategori" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none transition-all appearance-none">
                            <option value="Pakan">Pakan</option>
                            <option value="Obat">Obat</option>
                        </select>
                    </div>

                    <!-- Stok Minimal -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Stok Minimal <span
                                class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="stok_minimal" required placeholder="100.00"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                    </div>

                    <!-- Unit Management Section -->
                    <div class="md:col-span-2 border-t border-gray-100 pt-6">
                        <h4 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-4">Pengaturan Satuan &
                            Konversi</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Satuan Besar
                                    (Beli)</label>
                                <input type="text" name="satuan_besar" required placeholder="Sak / Box"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none bg-gray-50">
                            </div>
                            <div class="flex items-center justify-center pt-6">
                                <span class="text-gray-400 font-black">=</span>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Satuan Kecil
                                    (Pakai)</label>
                                <input type="text" name="satuan_kecil" required placeholder="Kg / Botol"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none bg-gray-50">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Isi Satuan Kecil per Satuan Besar
                                <span class="text-red-500">*</span></label>
                            <div class="flex items-center gap-3">
                                <span class="text-sm text-gray-500 italic">1 Satuan Besar berisi</span>
                                <input type="number" step="0.01" name="konversi" required placeholder="50.00"
                                    class="w-32 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none font-black text-emerald-600">
                                <span class="text-sm text-gray-500 italic">Satuan Kecil</span>
                            </div>
                            <p class="mt-2 text-[10px] text-gray-400 font-bold uppercase tracking-tight">Contoh: 1 Sak
                                berisi 50 Kg. Masukkan 50.</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 pt-6 border-t border-gray-100">
                    <button type="submit"
                        class="flex-1 bg-emerald-600 text-white font-bold py-4 rounded-xl shadow-lg hover:bg-emerald-700 transition-all transform hover:-translate-y-1">
                        Simpan Master Barang
                    </button>
                    <a href="{{ route('supplies.index') }}"
                        class="px-8 py-4 border border-gray-200 rounded-xl font-bold text-gray-500 hover:bg-gray-50 transition-all flex items-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection