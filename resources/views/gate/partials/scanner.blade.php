@php
    $stats = [
        'total' => \App\Models\Ticket::count(),
        'active' => \App\Models\Ticket::where('status', 'active')->count(),
        'used' => \App\Models\Ticket::where('status', 'used')->count(),
        'cancelled' => \App\Models\Ticket::where('status', 'cancelled')->count(),
    ];
@endphp
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8 max-w-4xl mx-auto">
    <div class="bg-black rounded-xl p-4 text-center shadow">
        <p class="text-sm text-gray-500">{{ __('Total') }}</p>
        <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
    </div>
    <div class="bg-green-50 rounded-xl p-4 text-center shadow">
        <p class="text-sm text-green-600">{{ __('Active') }}</p>
        <p class="text-2xl font-bold text-green-800">{{ $stats['active'] }}</p>
    </div>
    <div class="bg-gray-200 rounded-xl p-4 text-center shadow">
        <p class="text-sm text-gray-500">{{ __('Used') }}</p>
        <p class="text-2xl font-bold">{{ $stats['used'] }}</p>
    </div>
    <div class="bg-red-50 rounded-xl p-4 text-center shadow">
        <p class="text-sm text-red-600">{{ __('Cancelled') }}</p>
        <p class="text-2xl font-bold text-red-800">{{ $stats['cancelled'] }}</p>
    </div>
</div>

<div id="scanner-modal" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50 hidden">
    <div class="relative w-full max-w-md">
        <video id="video" class="w-full rounded-xl shadow-2xl"></video>
        <canvas id="canvas" class="hidden"></canvas>
        <button onclick="closeScanner()" class="absolute top-4 right-4 bg-black text-gray-500 px-4 py-2 rounded-lg font-bold">{{ __('Close') }}</button>
        <p id="scan-result" class="mt-4 text-center text-white">{{ __('Point camera at a QR code') }}</p>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
<script>
let video, canvas, context, scanning = false;

function openScanner() {
    document.getElementById('scanner-modal').classList.remove('hidden');
    video = document.getElementById('video');
    canvas = document.getElementById('canvas');
    startScanner();
}

function closeScanner() {
    document.getElementById('scanner-modal').classList.add('hidden');
    stopScanner();
}

function startScanner() {
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        document.getElementById('scan-result').textContent = '{{ __('Camera access is unavailable. Please use serial entry.') }}';
        return;
    }
    scanning = true;
    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
        .then(function(stream) {
            video.srcObject = stream;
            video.play();
            tick();
        })
        .catch(function(err) {
            document.getElementById('scan-result').textContent = '{{ __('Camera access is unavailable. Please use serial entry.') }}';
        });
}

function stopScanner() {
    scanning = false;
    if (video && video.srcObject) {
        video.srcObject.getTracks().forEach(track => track.stop());
    }
}

function tick() {
    if (!scanning) return;
    if (video.readyState === video.HAVE_ENOUGH_DATA) {
        canvas.height = video.videoHeight;
        canvas.width = video.videoWidth;
        context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
        const code = jsQR(imageData.data, imageData.width, imageData.height);
        if (code) {
            window.location.href = '{{ url('gate/ticket/lookup') }}/' + encodeURIComponent(code.data);
        }
    }
    if (scanning) requestAnimationFrame(tick);
}
</script>
@endpush
