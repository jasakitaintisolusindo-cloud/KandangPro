@extends('layouts.app')

@section('title', 'Riwayat Transaksi Stok')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Riwayat Transaksi</h2>
                <p class="mt-1 text-sm text-gray-600">Log setiap pergerakan pakan dan obat-obatan</p>
            </div>
            <a href="{{ route('supplies.index') }}" class="text-emerald-600 font-bold flex items-center hover:underline">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar Stok
            </a>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Tanggal</th>
                            <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Barang</th>
                            <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Tipe</th>
                            <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest text-right">
                                Jumlah</th>
                            <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Keterangan</th>
                            <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Exp. Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($transactions as $transaction)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <span
                                        class="text-sm font-bold text-gray-900">{{ $transaction->tanggal->format('d M Y') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <span
                                            class="w-2 h-2 rounded-full mr-3 {{ $transaction->supply->kategori == 'Pakan' ? 'bg-orange-400' : 'bg-blue-400' }}"></span>
                                        <div>
                                            <p class="text-sm font-black text-gray-900 leading-none">
                                                {{ $transaction->supply->nama_barang }}</p>
                                            <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold tracking-tighter">
                                                {{ $transaction->supply->kategori }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $transaction->tipe == 'Masuk' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $transaction->tipe }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span
                                        class="text-sm font-black {{ $transaction->tipe == 'Masuk' ? 'text-emerald-600' : 'text-red-500' }}">
                                        {{ $transaction->tipe == 'Masuk' ? '+' : '-' }}
                                        {{ number_format($transaction->jumlah, 2) }}
                                    </span>
                                    <span
                                        class="text-[10px] font-bold text-gray-400 uppercase ml-1">{{ $transaction->supply->satuan_kecil }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs text-gray-600 font-medium">{{ $transaction->keterangan }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if($transaction->expired_at)
                                        <span
                                            class="text-xs font-bold {{ $transaction->expired_at->isPast() ? 'text-red-500' : 'text-gray-500' }}">
                                            {{ $transaction->expired_at->format('d M Y') }}
                                        </span>
                                    @else
                                        <span class="text-gray-300">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <p class="text-gray-400 font-bold uppercase tracking-widest">Belum ada riwayat transaksi</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-gray-50">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
@endsection