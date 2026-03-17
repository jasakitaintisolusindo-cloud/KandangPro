@extends('layouts.app')

@section('title', 'Edit Master Barang')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-100 bg-gray-50/50">
                <h2 class="text-3xl font-bold text-gray-900">Edit Barang</h2>
                <p class="mt-1 text-sm text-gray-600">Perbarui informasi {{ $supply->nama_barang }}</p>
            </div>

            <form action="{{ route('supplies.update', $supply) }}" method="POST" class="p-8 space-y-6">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Barang -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Barang <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nama_barang" value="{{ old('nama_barang', $supply->nama_barang) }}"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Kategori <span
                                class="text-red-500">*</span></label>
                        <select name="kategori" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none">
                            <option value="Pakan" {{ $supply->kategori == 'Pakan' ? 'selected' : '' }}>Pakan</option>
                            <option value="Obat" {{ $supply->kategori == 'Obat' ? 'selected' : '' }}>Obat</option>
                        </select>
                    </div>

                    <!-- Stok Minimal -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Stok Minimal ({{ $supply->satuan_kecil }})
                            <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="stok_minimal"
                            value="{{ old('stok_minimal', $supply->stok_minimal) }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>

                    <!-- Unit Management Section -->
                    <div class="md:col-span-2 border-t border-gray-100 pt-6">
                        <h4 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-4 text-orange-400">
                            Peringatan: Perubahan konversi akan mempengaruhi kalkulasi stok setara!</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Satuan Besar</label>
                                <input type="text" name="satuan_besar"
                                    value="{{ old('satuan_besar', $supply->satuan_besar) }}" required
                                    class="w-full px-4 py-2 border border-blue-100 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-blue-50/30">
                            </div>
                            <div class="flex items-center justify-center pt-6">
                                <span class="text-gray-400 font-black">=</span>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Satuan Kecil</label>
                                <input type="text" name="satuan_kecil"
                                    value="{{ old('satuan_kecil', $supply->satuan_kecil) }}" required
                                    class="w-full px-4 py-2 border border-blue-100 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-blue-50/30">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Isi Satuan Kecil per Satuan Besar
                                <span class="text-red-500">*</span></label>
                            <div class="flex items-center gap-3">
                                <span class="text-sm text-gray-500 italic">1 Satuan Besar berisi</span>
                                <input type="number" step="0.01" name="konversi"
                                    value="{{ old('konversi', $supply->konversi) }}" required
                                    class="w-32 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none font-black text-blue-600">
                                <span class="text-sm text-gray-500 italic">Satuan Kecil</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 pt-6 border-t border-gray-100">
                    <button type="submit"
                        class="flex-1 bg-blue-600 text-white font-bold py-4 rounded-xl shadow-lg hover:bg-blue-700 transition-all">
                        Update Master Barang
                    </button>
                    <a href="{{ route('supplies.index') }}"
                        class="px-8 py-4 border border-gray-200 rounded-xl font-bold text-gray-500 hover:bg-gray-50 transition-all flex items-center">
                        Batal
                    </a>
                </div>
            </form>

            <div class="p-8 bg-orange-50/50 border-t border-orange-100">
                <h5 class="text-xs font-black text-orange-600 uppercase tracking-widest mb-2">Informasi Stok Saat Ini</h5>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-gray-900">{{ number_format($supply->stok_saat_ini, 2) }}</span>
                    <span class="text-gray-500 font-bold uppercase text-sm">{{ $supply->satuan_kecil }}</span>
                    <span class="text-gray-400 font-bold text-xs ml-4">({{ $supply->stok_formatted }})</span>
                </div>
            </div>
        </div>
    </div>
@endsection