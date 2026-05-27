@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/capture.css') }}">
@endsection

@section('content')
<div class="capture-container animate-fade-in">
    <div style="width: 100%; display: flex; justify-content: flex-start; margin-bottom: 1rem;">
        <a href="{{ route('home') }}" style="color: var(--text-muted); text-decoration: none; display: flex; align-items: center; gap: 0.5rem; font-weight: 600; transition: color 0.2s;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-muted)'">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to Home
        </a>
    </div>

    <div class="status-badge" id="statusLine">READY TO SHOOT</div>

    <div class="video-wrapper">
        <video id="webcam" autoplay playsinline></video>
        <div class="countdown-overlay" id="countdown">3</div>
        <div class="flash-overlay" id="flash"></div>
    </div>

    <div class="controls" style="display: flex; gap: 2rem; align-items: center; justify-content: center; margin-top: 1rem;">
        <button class="btn-capture" id="startBtn" title="Start Session">
            <div class="inner-circle">Click Start Foto</div>
        </button>
        <button class="btn-primary" id="uploadBtn" style="height: 50px; padding: 0 1.5rem;">
            Upload Image
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        </button>
        <input type="file" accept="image/*" id="uploadInput" style="display:none;">
    </div>

    <div class="shots-preview" id="shotsPreview">
        <div class="shot-thumb"><div class="shot-number">1</div></div>
        <div class="shot-thumb"><div class="shot-number">2</div></div>
        <div class="shot-thumb"><div class="shot-number">3</div></div>
        <div class="shot-thumb"><div class="shot-number">4</div></div>
    </div>

    <canvas id="captureCanvas" width="1280" height="960" style="display: none;"></canvas>
</div>
@endsection

@section('scripts')
<script>
    const video = document.getElementById('webcam');
    const startBtn = document.getElementById('startBtn');
    const countdownEl = document.getElementById('countdown');
    const flashEl = document.getElementById('flash');
    const statusLine = document.getElementById('statusLine');
    const previewThumbs = document.querySelectorAll('.shot-thumb');
    const canvas = document.getElementById('captureCanvas');
    const ctx = canvas.getContext('2d');

    let currentShot = 0;
    const totalShots = 4;
    
    // UUID Fallback for insecure context (HTTP)
    const generateUUID = () => {
        if (typeof crypto !== 'undefined' && crypto.randomUUID) {
            return crypto.randomUUID();
        }
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    };
    
    const sessionUuid = generateUUID();

    // Init Webcam
    async function initWebcam() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            console.error("Camera API not available. Usually due to insecure context (HTTP) or unsupported browser.");
            statusLine.innerText = "CAMERA ERROR: HTTPS REQUIRED";
            statusLine.style.background = "rgba(239, 68, 68, 0.2)";
            statusLine.style.color = "#ef4444";
            
            alert("Kamera tidak dapat diakses. Browser memerlukan koneksi aman (HTTPS) untuk mengizinkan akses kamera jika diakses melalui IP publik.\n\nSilakan gunakan 'localhost' atau pasang SSL (HTTPS).");
            return;
        }

        try {
            const stream = await navigator.mediaDevices.getUserMedia({ 
                video: { width: 1280, height: 960 }, 
                audio: false 
            });
            video.srcObject = stream;
        } catch (err) {
            console.error("Webcam access denied:", err);
            statusLine.innerText = "CAMERA ACCESS DENIED";
            statusLine.style.background = "rgba(239, 68, 68, 0.2)";
            statusLine.style.color = "#ef4444";
            alert("Harap izinkan akses kamera untuk menggunakan Photobox.");
        }
    }

    initWebcam();

    startBtn.addEventListener('click', startSession);

    async function startSession() {
        startBtn.disabled = true;
        startBtn.style.opacity = '0.5';
        
        for (let i = 1; i <= totalShots; i++) {
            await takeShot(i);
        }

        statusLine.innerText = "ALL SHOTS TAKEN! REDIRECTING...";
        setTimeout(() => {
            window.location.href = `/frames/${sessionUuid}`;
        }, 1500);
    }

    function takeShot(number) {
        return new Promise((resolve) => {
            let count = 3;
            countdownEl.style.display = 'flex';
            countdownEl.innerText = count;
            statusLine.innerText = `GET READY FOR SHOT #${number}`;

            const interval = setInterval(() => {
                count--;
                if (count > 0) {
                    countdownEl.innerText = count;
                } else {
                    clearInterval(interval);
                    countdownEl.style.display = 'none';
                    
                    // Flash effect
                    flashEl.style.animation = 'none';
                    void flashEl.offsetWidth; // trigger reflow
                    flashEl.style.animation = 'flash 0.5s ease-out';

                    // Capture image
                    captureImage(number);
                    resolve();
                }
            }, 1000);
        });
    }

    async function captureImage(sequence) {
        // Draw video to canvas
        ctx.save();
        ctx.scale(-1, 1);
        ctx.drawImage(video, -canvas.width, 0, canvas.width, canvas.height);
        ctx.restore();

        const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
        
        // Update Thumbnail
        const thumb = previewThumbs[sequence - 1];
        thumb.innerHTML = `<div class="shot-number">${sequence}</div><img src="${dataUrl}">`;

        // Send to Server
        try {
            const response = await fetch('{{ route("store.photo") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    image: dataUrl,
                    uuid: sessionUuid,
                    sequence: sequence
                })
            });
            const result = await response.json();
            console.log(`Shot ${sequence} saved:`, result);
        } catch (err) {
            console.error("Error saving photo:", err);
        }
    }

    // Upload logic
    const uploadBtn = document.getElementById('uploadBtn');
    const uploadInput = document.getElementById('uploadInput');


    uploadBtn.addEventListener('click', () => {
        uploadInput.click();
    });

    uploadInput.addEventListener('change', async (e) => {
        const file = e.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('photo', file);
        formData.append('_token', '{{ csrf_token() }}');

        statusLine.innerText = "UPLOADING IMAGE...";
        uploadBtn.disabled = true;

        try {
            const response = await fetch('{{ route("gallery.upload") }}', {
                method: 'POST',
                body: formData
            });
            if (response.redirected) {
                window.location.href = response.url;
            } else {
                window.location.href = '{{ route("gallery") }}';
            }
        } catch (err) {
            console.error("Upload error:", err);
            statusLine.innerText = "UPLOAD FAILED. TRY AGAIN.";
            uploadBtn.disabled = false;
        }
    });
</script>
@endsection
