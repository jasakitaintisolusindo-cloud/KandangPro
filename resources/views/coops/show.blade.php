@extends('layouts.app')

@section('title', 'Monitoring CCTV - ' . $coop->kode_kandang)

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-emerald-500">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <nav class="flex text-sm text-gray-500 mb-1" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-2">
                            <li><a href="{{ route('coops.index') }}" class="hover:text-emerald-600">Master Kandang</a></li>
                            <li><span class="mx-2">/</span></li>
                            <li class="text-gray-900 font-medium">Monitoring CCTV</li>
                        </ol>
                    </nav>
                    <h2 class="text-3xl font-bold text-gray-900">{{ $coop->label }}</h2>
                    <div class="flex items-center mt-1 text-sm">
                        <span class="flex h-2 w-2 relative mr-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <p class="text-gray-600">Live Monitoring aktif</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('coops.edit', $coop) }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Setup URL
                    </a>
                </div>
            </div>
        </div>

        @if($coop->cctv_url)
            <!-- Player and Snapshot Row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Video Player -->
                <div class="lg:col-span-2 bg-black rounded-xl shadow-2xl overflow-hidden relative group">
                    <div class="aspect-video relative">
                        <video id="videoPlayer" class="w-full h-full" controls playsinline></video>
                        <canvas id="snapshotCanvas" class="hidden"></canvas>
                    </div>

                    <!-- Overlay Controls -->
                    <div class="absolute bottom-6 right-6 flex gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button onclick="takeSnapshot()"
                            class="flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg shadow-lg hover:bg-emerald-700 transition-all font-semibold">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Ambil Snapshot
                        </button>
                    </div>
                </div>

                <!-- Snapshot History / Info -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Informasi Monitoring</h3>
                    <div class="space-y-4">
                        <div class="p-4 bg-emerald-50 rounded-lg border border-emerald-100">
                            <p class="text-sm font-semibold text-emerald-800 mb-1">Status Koneksi</p>
                            <p class="text-xs text-emerald-600" id="connectionStatus">Menghubungkan ke stream...</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Snapshot Terakhir</h4>
                            <div id="snapshotPreview"
                                class="aspect-video bg-gray-100 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300 overflow-hidden">
                                <p class="text-xs text-gray-400">Belum ada snapshot yang diambil</p>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100">
                            <p class="text-xs text-gray-500">
                                * Fitur snapshot akan menyimpan gambar langsung ke server Laravel sebagai bukti dokumentasi
                                kondisi kandang.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">URL stream belum dikonfigurasi</h3>
                <p class="text-gray-600 mb-6">Silakan atur URL stream CCTV (HLS/RTSP) di Master Kandang untuk melihat live
                    monitoring.</p>
                <a href="{{ route('coops.edit', $coop) }}"
                    class="inline-flex items-center px-6 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 shadow-md">
                    Konfigurasi Sekarang
                </a>
            </div>
        @endif
    </div>

    @push('scripts')
        @if($coop->cctv_url)
            <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
            <script>
                const video = document.getElementById('videoPlayer');
                const videoSrc = '{{ $coop->cctv_url }}';
                const connectionStatus = document.getElementById('connectionStatus');

                if (Hls.isSupported()) {
                    const hls = new Hls();
                    hls.loadSource(videoSrc);
                    hls.attachMedia(video);
                    hls.on(Hls.Events.MANIFEST_PARSED, function () {
                        video.play();
                        connectionStatus.textContent = "Terhubung - Streaming sedang berjalan";
                        connectionStatus.parentElement.className = "p-4 bg-emerald-50 rounded-lg border border-emerald-100";
                        connectionStatus.className = "text-xs text-emerald-600";
                    });
                    hls.on(Hls.Events.ERROR, function (event, data) {
                        if (data.fatal) {
                            connectionStatus.textContent = "Error: Gagal memuat stream. Pastikan URL valid.";
                            connectionStatus.parentElement.className = "p-4 bg-red-50 rounded-lg border border-red-100";
                            connectionStatus.className = "text-xs text-red-600";
                        }
                    });
                } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                    video.src = videoSrc;
                    video.addEventListener('loadedmetadata', function () {
                        video.play();
                        connectionStatus.textContent = "Terhubung (Native)";
                    });
                }

                function takeSnapshot() {
                    const canvas = document.getElementById('snapshotCanvas');
                    const preview = document.getElementById('snapshotPreview');
                    const context = canvas.getContext('2d');

                    // Set canvas dimensions to match video
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;

                    // Draw the current frame
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);

                    // Convert to base64
                    const imageData = canvas.toDataURL('image/jpeg', 0.8);

                    // Show preview
                    preview.innerHTML = `<img src="${imageData}" class="w-full h-full object-cover">`;

                    // Send to server
                    fetch('{{ route("coops.snapshot", $coop) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ image: imageData })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Snapshot berhasil disimpan ke storage!');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Gagal menyimpan snapshot.');
                        });
                }
            </script>
        @endif
    @endpush
@endsection