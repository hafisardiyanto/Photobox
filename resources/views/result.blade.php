@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/result.css') }}">
@endsection

@section('content')
<div class="result-container animate-fade-in">
    <div class="success-badge">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        YOUR PHOTO IS READY!
    </div>

    <div class="result-preview">
        @if($session->result)
            <img src="{{ asset('storage/' . $session->result->result_path) }}" alt="Final Photo Strip">
        @else
            <div class="glass-card" style="padding: 4rem; text-align: center;">
                <p>Result not found. Please try again.</p>
            </div>
        @endif
    </div>

    <div class="actions">
        @if($session->result)
        <div style="display: flex; flex-direction: column; gap: 1rem; align-items: center;">
            <a href="{{ asset('storage/' . $session->result->result_path) }}" download="photobox_{{ $uuid }}.jpg" class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Download Strip
            </a>
            <div class="g-savetodrive"
                data-src="{{ url('storage/' . $session->result->result_path) }}"
                data-filename="photobox_{{ $uuid }}.jpg"
                data-sitename="Photobox Experience">
            </div>
        </div>
        @endif
        
        <a href="{{ route('home') }}" class="btn-primary" style="background: rgba(255,255,255,0.1); box-shadow: none;">
            Take Another One
        </a>
    </div>
</div>
@endsection
