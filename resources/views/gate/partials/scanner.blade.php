<div id="scanner-modal" class="fixed inset-0 bg-black/90 flex items-center justify-center z-50 hidden">
    <div class="relative w-full max-w-md">
        <video id="video" class="w-full rounded-xl shadow-2xl" style="touch-action: none; user-select: none;"></video></video>
        <canvas id="canvas" class="hidden"></canvas>
        <button onclick="closeScanner()" class="absolute top-4 right-4 bg-gray-800 text-gray-400 px-4 py-2 rounded-lg font-bold hover:bg-gray-700">{{ __('Close') }}</button>
        <p id="scan-result" class="mt-4 text-center text-gray-400">{{ __('Point camera at a QR code') }}</p>
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
    const resultEl = document.getElementById('scan-result');
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        resultEl.textContent = '{{ __('Camera access is unavailable. Please use serial entry.') }}';
        return;
    }
    scanning = true;
    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
        .then(function(stream) {
            video.srcObject = stream;
            video.play();
            tick();
        })
        .catch(function() {
            resultEl.textContent = '{{ __('Camera access is unavailable. Please use serial entry.') }}';
        });
}

function stopScanner() {
    scanning = false;
    if (video && video.srcObject) {
        video.srcObject.getTracks().forEach(function(track) { track.stop(); });
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
