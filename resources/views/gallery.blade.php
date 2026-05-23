@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/gallery.css') }}">
@endsection

@section('content')
<div class="gallery-container animate-fade-in">
    <div class="gallery-header">
        <h1 style="font-family: 'Outfit', sans-serif;">Photo History</h1>
        <p style="color: var(--text-muted);">Explore all moments captured in this photobox.</p>
    </div>

    @if($results->isEmpty())
        <div class="empty-gallery glass-card">
            <p>No photos captured yet.</p>
            <a href="{{ route('home') }}" class="btn-primary" style="margin-top: 1rem;">Start First Session</a>
        </div>
    @else
        <div class="gallery-grid">
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
    @endif

    <div style="text-align: center; margin-top: 4rem;">
        <a href="{{ route('home') }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">&larr; Back to Home</a>
    </div>
</div>
@endsection
