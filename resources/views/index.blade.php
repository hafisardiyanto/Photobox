@extends('layouts.app')

@section('content')
<div class="container animate-fade-in" style="max-width: 800px; text-align: center; margin-top: 4rem;">
    <h1 style="font-family: 'Outfit', sans-serif; font-size: 4rem; margin-bottom: 1.5rem; font-weight: 800; line-height: 1.1;">
        Foto  <br> 
        <span style="color: var(--primary);"></span>
    </h1>
    <p style="color: var(--text-muted); font-size: 1.25rem; margin-bottom: 3rem; max-width: 600px; margin-left: auto; margin-right: auto;">
       
    </p>

    <div style="display: flex; justify-content: center; gap: 1.5rem;">
        <a href="{{ route('capture') }}" class="btn-primary">
            Mulai Baru Sesi 
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
        </a>
        <a href="{{ route('gallery') }}" class="btn-primary" style="background: rgba(255,255,255,0.1); box-shadow: none;">
            Melihat Foto
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
        </a>
    </div>

    <!-- Feature Preview Grid -->
    <div style="margin-top: 6rem; display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem;">
        <div class="glass-card" style="padding: 1.5rem;">
            <div style="width: 48px; height: 48px; background: rgba(99, 102, 241, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; color: var(--primary);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            </div>
            <h3 style="margin-bottom: 0.5rem;">Auto Foto</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem;">4 automatic shots with countdown timer for the perfect pose every time.</p>
        </div>
        <div class="glass-card" style="padding: 1.5rem;">
            <div style="width: 48px; height: 48px; background: rgba(168, 85, 247, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; color: #a855f7;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
            </div>
            <h3 style="margin-bottom: 0.5rem;">Pilih Frame </h3>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Choose from dozens of premium frames designed by professional</p>
        </div>
        <div class="glass-card" style="padding: 1.5rem;">
            <div style="width: 48px; height: 48px; background: rgba(16, 185, 129, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; color: #10b981;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            </div>
            <h3 style="margin-bottom: 0.5rem;">Instant Export</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Download your photo strip immediately in high resolution (4K ready).</p>
        </div>
    </div>
</div>
@endsection
