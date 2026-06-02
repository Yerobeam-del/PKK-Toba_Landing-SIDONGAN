@extends('sidongan.layouts.app')
@section('title', 'Notifikasi - SIDONGAN')

@section('content')
@php
    $currentUser = auth()->guard('sidongan')->user();
    
    $totalNotifications = $notifications->total() ?? 0;
    $unreadCount = $notifications->where('read_at', null)->count();
    $readCount = $totalNotifications - $unreadCount;
@endphp

<div>
    {{-- Header Section --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 0;">Notifikasi</h1>
            <p style="font-size: 0.875rem; color: #64748b; margin-top: 0.25rem;">Pusat informasi dan pemberitahuan aktivitas sistem</p>
        </div>
        @if($unreadCount > 0)
        <button onclick="markAllAsRead()" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: white; border: 1px solid #e2e8f0; color: #64748b; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.borderColor='#cbd5e1'; this.style.color='#334155'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.color='#64748b'">
            <i class="fas fa-check-double" style="color: #3b82f6;"></i>
            <span>Tandai Semua Dibaca</span>
        </button>
        @endif
    </div>

    {{-- Stats Cards --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        <div style="background: linear-gradient(135deg, #6366f1, #4f46e5); border-radius: 0.75rem; padding: 1.25rem; color: white;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.2); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-bell" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.9; margin: 0;">Total Notifikasi</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $totalNotifications }}</p>
                </div>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #f97316, #ea580c); border-radius: 0.75rem; padding: 1.25rem; color: white;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.2); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-envelope-open-text" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.9; margin: 0;">Belum Dibaca</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $unreadCount }}</p>
                </div>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #22c55e, #16a34a); border-radius: 0.75rem; padding: 1.25rem; color: white;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.2); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-envelope-open" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.9; margin: 0;">Sudah Dibaca</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $readCount }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Notifications List --}}
    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0;">
        
        {{-- Filter Tabs --}}
        <div style="padding: 1rem 1.25rem; border-bottom: 1px solid #e2e8f0; display: flex; gap: 1rem;">
            <button style="padding: 0.5rem 1rem; background: #eff6ff; color: #2563eb; border: none; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 600; cursor: default;">Semua</button>
            <button style="padding: 0.5rem 1rem; background: transparent; color: #64748b; border: none; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500; cursor: pointer;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">Belum Dibaca</button>
            <button style="padding: 0.5rem 1rem; background: transparent; color: #64748b; border: none; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500; cursor: pointer;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">Sudah Dibaca</button>
        </div>

        {{-- List Notifikasi --}}
        @forelse($notifications as $notif)
        <div style="display: flex; gap: 1rem; padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; background: {{ $notif->read_at ? '#ffffff' : '#eff6ff' }}; transition: all 0.2s; cursor: pointer;" 
             onmouseover="this.style.background='{{ $notif->read_at ? '#f8fafc' : '#dbeafe' }}'" 
             onmouseout="this.style.background='{{ $notif->read_at ? '#ffffff' : '#eff6ff' }}'"
             @if(!$notif->read_at && $notif->related_id) onclick="window.location.href='{{ route('sidongan.documents.show', $notif->related_id) }}'; markAsRead({{ $notif->id }})" @endif>
            
            {{-- Icon Bell --}}
            <div style="flex-shrink: 0; width: 2.5rem; height: 2.5rem; background: {{ $notif->read_at ? '#f1f5f9' : '#dbeafe' }}; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-bell" style="color: {{ $notif->read_at ? '#94a3b8' : '#3b82f6' }}; font-size: 1rem;"></i>
            </div>
            
            {{-- Content --}}
            <div style="flex: 1; min-width: 0;">
                {{-- TAMPILKAN MESSAGE SEBAGAI TEKS UTAMA --}}
                <p style="font-size: 0.9rem; font-weight: 500; color: #0f172a; margin: 0; line-height: 1.5;">
                    {{ $notif->message }}
                </p>
                {{-- TANGGAL FORMAT INDONESIA: 27 Mei 2026, 15.30 --}}
                <span style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem; display: block;">
                    {{ \Carbon\Carbon::parse($notif->created_at)->locale('id')->translatedFormat('d F Y, H.i') }}
                </span>
            </div>
            
            {{-- Unread Indicator (Blue Dot) --}}
            @if(!$notif->read_at)
            <div style="flex-shrink: 0; width: 0.5rem; height: 0.5rem; background: #3b82f6; border-radius: 50%; margin-top: 0.6rem;"></div>
            @endif
        </div>
        @empty
        {{-- Empty State --}}
        <div style="padding: 4rem 2rem; text-align: center;">
            <div style="width: 96px; height: 96px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <i class="fas fa-bell-slash" style="color: #94a3b8; font-size: 3rem;"></i>
            </div>
            <h3 style="font-size: 1.125rem; font-weight: 700; color: #1e293b; margin: 0 0 0.5rem 0;">Tidak Ada Notifikasi</h3>
            <p style="font-size: 0.875rem; color: #64748b; margin: 0;">Anda telah membaca semua notifikasi atau belum ada aktivitas terbaru.</p>
        </div>
        @endforelse

        {{-- Pagination --}}
        @if($notifications->hasPages())
        <div style="padding: 1rem 1.25rem; border-top: 1px solid #e2e8f0;">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Script untuk mark as read --}}
<script>
function markAsRead(id) {
    fetch(`/sidongan/notifications/${id}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(err => {
        console.error('Error marking notification as read:', err);
    });
}

function markAllAsRead() {
    if (confirm('Tandai semua notifikasi sebagai sudah dibaca?')) {
        fetch('/sidongan/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal menandai notifikasi sebagai dibaca');
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Terjadi kesalahan saat memproses permintaan');
        });
    }
}
</script>
@endsection