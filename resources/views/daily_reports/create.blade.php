@extends('layouts.app')

@section('title', 'Tambah Laporan Harian')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg p-8 border-t-4 border-emerald-500">
            <!-- Header -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Tambah Laporan Harian</h2>
                <p class="mt-2 text-sm text-gray-600">Masukkan data produksi dan keuangan harian peternakan</p>
            </div>

            <form action="{{ route('daily-reports.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kandang -->
                    <div class="md:col-span-2">
                        <label for="coop_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Kandang <span class="text-red-500">*</span>
                        </label>
                        <select name="coop_id" id="coop_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all @error('coop_id') border-red-500 @enderror">
                            <option value="">Pilih Kandang</option>
                            @foreach($coops as $coop)
                                <option value="{{ $coop->id }}" {{ old('coop_id') == $coop->id ? 'selected' : '' }}>
                                    {{ $coop->label }}
                                </option>
                            @endforeach
                        </select>
                        @error('coop_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal -->
                    <div class="md:col-span-2">
                        <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal" id="tanggal" required value="{{ old('tanggal', date('Y-m-d')) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all @error('tanggal') border-red-500 @enderror">
                        @error('tanggal')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Produksi Telur -->
                <div class="bg-emerald-50 p-6 rounded-xl border border-emerald-100 space-y-4">
                    <h3 class="text-sm font-bold text-emerald-800 uppercase tracking-wider flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Produksi Telur
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="produksi_telur_kg" class="block text-xs font-semibold text-gray-600 mb-1">Berat
                                Total (kg) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="produksi_telur_kg" id="produksi_telur_kg"
                                value="{{ old('produksi_telur_kg') }}" required placeholder="0.00"
                                class="w-full px-3 py-2 border border-emerald-200 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition-all"
                                oninput="calculateTotals()">
                            @error('produksi_telur_kg')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="jumlah_telur_butir" class="block text-xs font-semibold text-gray-600 mb-1">Jumlah
                                (Butir) <span class="text-red-500">*</span></label>
                            <input type="number" name="jumlah_telur_butir" id="jumlah_telur_butir"
                                value="{{ old('jumlah_telur_butir') }}" required placeholder="0"
                                class="w-full px-3 py-2 border border-emerald-200 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition-all"
                                oninput="calculateTotals()">
                            @error('jumlah_telur_butir')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="harga_telur_per_kg" class="block text-xs font-semibold text-gray-600 mb-1">Harga
                                Jual per kg (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="harga_telur_per_kg" id="harga_telur_per_kg"
                                value="{{ old('harga_telur_per_kg') }}" required placeholder="25000"
                                class="w-full px-3 py-2 border border-emerald-200 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition-all"
                                oninput="calculateTotals()">
                            @error('harga_telur_per_kg')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Pakan & Operasional -->
                <div class="bg-orange-50 p-6 rounded-xl border border-orange-100 space-y-4">
                    <h3 class="text-sm font-bold text-orange-800 uppercase tracking-wider flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Pakan & Kesehatan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="supply_id" class="block text-xs font-semibold text-gray-600 mb-1">
                                Jenis Pakan <span class="text-red-500">*</span>
                            </label>
                            <select name="supply_id" id="supply_id" required onchange="updatePrice()"
                                class="w-full px-3 py-2 border border-orange-200 rounded-lg focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                                <option value="">Pilih Jenis Pakan (Dari Gudang)</option>
                                @foreach($supplies as $supply)
                                    <option value="{{ $supply->id }}" 
                                            data-price="{{ $supply->harga_terakhir }}"
                                            {{ old('supply_id') == $supply->id ? 'selected' : '' }}>
                                        {{ $supply->nama_barang }} (Sisa: {{ number_format((float)$supply->stok_saat_ini, 2) }}
                                        {{ $supply->satuan_kecil }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-[10px] text-orange-600 font-bold uppercase tracking-tighter">* Stok akan
                                terpotong otomatis dari gudang</p>
                            @error('supply_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="pakan_kg" class="block text-xs font-semibold text-gray-600 mb-1">Konsumsi Pakan (kg)
                                <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="pakan_kg" id="pakan_kg" value="{{ old('pakan_kg') }}"
                                required placeholder="0.00"
                                class="w-full px-3 py-2 border border-orange-200 rounded-lg focus:ring-2 focus:ring-orange-500 outline-none transition-all"
                                oninput="calculateTotals()">
                            @error('pakan_kg')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="jumlah_kematian" class="block text-xs font-semibold text-gray-600 mb-1">Kematian
                                (Ekor) <span class="text-red-500">*</span></label>
                            <input type="number" name="jumlah_kematian" id="jumlah_kematian"
                                value="{{ old('jumlah_kematian', 0) }}" required placeholder="0"
                                class="w-full px-3 py-2 border border-orange-200 rounded-lg focus:ring-2 focus:ring-orange-500 outline-none transition-all"
                                oninput="calculateTotals()">
                            @error('jumlah_kematian')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="harga_pakan_per_kg" class="block text-xs font-semibold text-gray-600 mb-1">Harga
                                Pakan per kg (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="harga_pakan_per_kg" id="harga_pakan_per_kg"
                                value="{{ old('harga_pakan_per_kg') }}" required placeholder="8000"
                                class="w-full px-3 py-2 border border-orange-200 rounded-lg focus:ring-2 focus:ring-orange-500 outline-none transition-all"
                                oninput="calculateTotals()">
                            @error('harga_pakan_per_kg')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Biaya Lain-lain -->
                <div>
                    <label for="biaya_lain_lain" class="block text-sm font-semibold text-gray-700 mb-2">
                        Biaya Lain-lain (Rp)
                    </label>
                    <input type="number" step="0.01" name="biaya_lain_lain" id="biaya_lain_lain"
                        value="{{ old('biaya_lain_lain', 0) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all @error('biaya_lain_lain') border-red-500 @enderror"
                        oninput="calculateTotals()">
                    @error('biaya_lain_lain')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Keterangan -->
                <div>
                    <label for="keterangan" class="block text-sm font-semibold text-gray-700 mb-2">
                        Keterangan
                    </label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all @error('keterangan') border-red-500 @enderror"
                        placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preview Perhitungan -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 border-l-4 border-blue-500">
                    <h3 class="text-lg font-semibold text-blue-900 mb-4">💰 Preview Perhitungan</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-medium">Total Pendapatan Telur:</span>
                            <span class="text-lg font-bold text-emerald-600" id="preview_pendapatan">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-medium">Total Biaya Pakan:</span>
                            <span class="text-lg font-bold text-red-600" id="preview_biaya_pakan">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-medium">Biaya Lain-lain:</span>
                            <span class="text-lg font-bold text-red-600" id="preview_biaya_lain">Rp 0</span>
                        </div>
                        <hr class="border-gray-300">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-900 font-bold text-lg">Keuntungan Bersih:</span>
                            <span class="text-2xl font-bold" id="preview_keuntungan">Rp 0</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 pt-6 border-t border-gray-200">
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-lg shadow-md hover:from-emerald-700 hover:to-teal-700 transform hover:scale-105 transition-all duration-200">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Data
                    </button>
                    <a href="{{ route('daily-reports.index') }}"
                        class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-all duration-200 text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updatePrice() {
            const select = document.getElementById('supply_id');
            const selectedOption = select.options[select.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            
            if (price) {
                document.getElementById('harga_pakan_per_kg').value = parseFloat(price).toFixed(0);
                calculateTotals();
            }
        }

        function calculateTotals() {
            const produksi = parseFloat(document.getElementById('produksi_telur_kg').value) || 0;
            const hargaTelur = parseFloat(document.getElementById('harga_telur_per_kg').value) || 0;
            const pakan = parseFloat(document.getElementById('pakan_kg').value) || 0;
            const hargaPakan = parseFloat(document.getElementById('harga_pakan_per_kg').value) || 0;
            const biayaLain = parseFloat(document.getElementById('biaya_lain_lain').value) || 0;

            const totalPendapatan = produksi * hargaTelur;
            const totalBiayaPakan = pakan * hargaPakan;
            const keuntungan = totalPendapatan - (totalBiayaPakan + biayaLain);

            document.getElementById('preview_pendapatan').textContent = 'Rp ' + totalPendapatan.toLocaleString('id-ID');
            document.getElementById('preview_biaya_pakan').textContent = 'Rp ' + totalBiayaPakan.toLocaleString('id-ID');
            document.getElementById('preview_biaya_lain').textContent = 'Rp ' + biayaLain.toLocaleString('id-ID');

            const keuntunganElement = document.getElementById('preview_keuntungan');
            keuntunganElement.textContent = 'Rp ' + keuntungan.toLocaleString('id-ID');
            keuntunganElement.className = 'text-2xl font-bold ' + (keuntungan >= 0 ? 'text-emerald-700' : 'text-red-700');
        }

        // Initialize calculation on page load
        document.addEventListener('DOMContentLoaded', calculateTotals);
    </script>
@endsection