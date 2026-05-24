@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/gallery.css') }}">
@endsection

@section('content')
<div class="gallery-container animate-fade-in">
    <div class="gallery-header">
        <h1 style="font-family: 'Outfit', sans-serif;">Photo History</h1>
        <p style="color: var(--text-muted);">Explore all moments captured in this photobox or upload your own.</p>
        @if(session('success'))
            <div style="margin-top: 1rem; padding: 0.75rem 1.5rem; background: rgba(16, 185, 129, 0.2); color: #10b981; border-radius: 1rem; display: inline-block; font-weight: 600; font-family: 'Outfit', sans-serif;">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <div class="gallery-grid">
        <!-- Upload Card -->
        <div class="gallery-item upload-card" onclick="document.getElementById('galleryUploadInput').click()">
            <div class="upload-placeholder">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary);"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                <div style="font-weight: 700; font-size: 1.1rem; color: var(--text-main); margin-top: 1rem;">Upload Photo</div>
                <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.5rem; text-align: center; padding: 0 1rem;">Pilih gambar dari HP atau laptop Anda</div>
            </div>
            <form id="galleryUploadForm" action="{{ route('gallery.upload') }}" method="POST" enctype="multipart/form-data" style="display: none;">
                @csrf
                <input type="file" id="galleryUploadInput" name="photo" accept="image/*" onchange="document.getElementById('galleryUploadForm').submit()">
            </form>
        </div>

        @foreach($results as $result)
            <div class="gallery-item">
                <img src="{{ asset('storage/' . $result->result_path) }}" class="gallery-img" alt="Photo Strip">
                <div class="gallery-info">
                    <div class="gallery-date">{{ $result->created_at->format('M d, Y - H:i') }}</div>
                    <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 10px;">
                        <a href="{{ asset('storage/' . $result->result_path) }}" download class="btn-primary" style="padding: 0.5rem 1rem; font-size: 0.8rem; width: 100%;">
                            Download
                        </a>
                        <div class="g-savetodrive"
                            data-src="{{ url('storage/' . $result->result_path) }}"
                            data-filename="photobox_{{ $result->created_at->format('Ymd_His') }}.jpg"
                            data-sitename="Photobox Experience">
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div style="text-align: center; margin-top: 4rem;">
        <a href="{{ route('home') }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">&larr; Back to Home</a>
    </div>
</div>
@endsection
