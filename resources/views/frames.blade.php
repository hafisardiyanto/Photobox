@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/frames.css') }}">
@endsection

@section('content')
<div class="frames-container animate-fade-in">
    <!-- Left: Preview -->
    <div class="preview-box">
        <div id="photoStrip" class="photo-strip-preview">
            @foreach($session->photos->sortBy('sequence_number') as $photo)
            <div class="preview-photo">
                <img src="{{ asset('storage/' . $photo->image_path) }}" class="photo-img">
            </div>
            @endforeach
            <div style="text-align: center; margin-top: 10px; font-family: 'Outfit', sans-serif; opacity: 0.3; font-weight: 700;">
                PHOTOBOX
            </div>
        </div>
    </div>

    <!-- Right: Settings -->
    <div class="settings-panel">
        <div class="glass-card">
            <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                Choose Frame
            </div>
            <div class="frame-grid">
                @foreach($frames as $frame)
                @php
                    $slug = strtolower(str_replace(' ', '-', $frame->name));
                    $colorClass = explode('-', $slug)[0];
                @endphp
                <div class="frame-option {{ $loop->first ? 'active' : '' }}" 
                     onclick="selectFrame('{{ $slug }}', this)">
                    <div class="frame-preview-circle preview-{{ $colorClass }}"></div>
                    <span>{{ $frame->name }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="glass-card">
            <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 0-10 10c0 5.5 4.5 10 10 10s10-4.5 10-10c0-2-1.5-3.5-3.5-3.5h-1.3l-1.6-1.6a1 1 0 0 0-.7-.3H12z"/><circle cx="7.5" cy="10.5" r="1.5"/><circle cx="12" cy="7.5" r="1.5"/><circle cx="16.5" cy="10.5" r="1.5"/></svg>
                Apply Filter
            </div>
            <div class="filter-grid">
                <div class="filter-option active" onclick="applyFilter('', this)">None</div>
                <div class="filter-option" onclick="applyFilter('filter-bw', this)">B&W</div>
                <div class="filter-option" onclick="applyFilter('filter-sepia', this)">Sepia</div>
                <div class="filter-option" onclick="applyFilter('filter-vintage', this)">Vintage</div>
                <div class="filter-option" onclick="applyFilter('filter-clarendon', this)">Clarendon</div>
                <div class="filter-option" onclick="applyFilter('filter-gingham', this)">Gingham</div>
                <div class="filter-option" onclick="applyFilter('filter-moon', this)">Moon</div>
                <div class="filter-option" onclick="applyFilter('filter-lark', this)">Lark</div>
                <div class="filter-option" onclick="applyFilter('filter-reyes', this)">Reyes</div>
                <div class="filter-option" onclick="applyFilter('filter-juno', this)">Juno</div>
                <div class="filter-option" onclick="applyFilter('filter-slumber', this)">Slumber</div>
                <div class="filter-option" onclick="applyFilter('filter-glossier', this)">Glossier</div>
            </div>
        </div>

        <button class="btn-primary" id="generateBtn" style="width: 100%; justify-content: center;">
            Generate & Finish
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
        </button>
    </div>
</div>

<canvas id="resultCanvas" width="1200" height="4000" style="display: none;"></canvas>
@endsection

@section('scripts')
<script>
    function selectFrame(name, el) {
        document.querySelectorAll('.frame-option').forEach(opt => opt.classList.remove('active'));
        el.classList.add('active');
        
        const preview = document.getElementById('photoStrip');
        preview.className = 'photo-strip-preview ' + name.split('-')[0];
    }

    function applyFilter(filterClass, el) {
        document.querySelectorAll('.filter-option').forEach(opt => opt.classList.remove('active'));
        el.classList.add('active');
        
        document.querySelectorAll('.photo-img').forEach(img => {
            img.className = 'photo-img ' + filterClass;
        });
    }

    const generateBtn = document.getElementById('generateBtn');
    generateBtn.addEventListener('click', async () => {
        generateBtn.disabled = true;
        generateBtn.innerText = "Processing...";

        // Process images with Canvas
        const canvas = document.getElementById('resultCanvas');
        const ctx = canvas.getContext('2d');
        const images = document.querySelectorAll('.photo-img');
        const previewStrip = document.getElementById('photoStrip');
        
        // Match canvas to strip aspect
        const stripWidth = 600; 
        const padding = 30;
        const gap = 20;
        const imgWidth = stripWidth - (padding * 2);
        const imgHeight = (imgWidth / 4) * 3;
        const footerSpace = 100;
        const stripHeight = (padding * 2) + (images.length * imgHeight) + ((images.length - 1) * gap) + footerSpace;

        canvas.width = stripWidth;
        canvas.height = stripHeight;

        // Draw Background (Frame)
        const bgColor = window.getComputedStyle(previewStrip).backgroundColor;
        ctx.fillStyle = bgColor;
        ctx.fillRect(0, 0, stripWidth, stripHeight);

        // Draw Images
        let currentY = padding;
        for (let img of images) {
            // Apply filter manually in canvas if needed, but for now we'll just draw
            // To apply CSS filters to canvas we use ctx.filter
            ctx.filter = window.getComputedStyle(img).filter;
            
            // Draw image maintaining aspect ratio and cropping center
            ctx.drawImage(img, padding, currentY, imgWidth, imgHeight);
            currentY += imgHeight + gap;
        }

        // Draw Footer Text
        ctx.filter = 'none';
        ctx.fillStyle = 'rgba(0,0,0,0.3)';
        ctx.font = 'bold 30px Outfit';
        ctx.textAlign = 'center';
        ctx.fillText('PHOTOBOX.AI', stripWidth / 2, stripHeight - 50);

        const resultDataUrl = canvas.toDataURL('image/jpeg', 0.9);

        // Send to Server
        try {
            const response = await fetch('{{ route("store.result") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    uuid: '{{ $uuid }}',
                    image: resultDataUrl
                })
            });
            const resultList = await response.json();
            window.location.href = `/result/{{ $uuid }}`;
        } catch (err) {
            console.error("Error generating result:", err);
            generateBtn.disabled = false;
            generateBtn.innerText = "Try Again";
        }
    });

    // Handle store.result route on the fly
</script>
@endsection
