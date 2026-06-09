@extends('sidongan.layouts.app')
@section('title', 'Notifikasi - SIDONGAN')

@section('content')
<style>
    .notif-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
    }
    .notif-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1);
        border-color: #f97316;
    }
    .stats-card {
        transition: all 0.3s;
    }
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-in {
        animation: slideIn 0.4s ease-out;
    }
</style>

<div style="max-width: 900px; margin: 0 auto;">
    {{-- Header --}}
    <div style="margin-bottom: 1.5rem;" class="animate-slide-in">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 0;">Notifikasi</h1>
        <p style="font-size: 0.875rem; color: #64748b; margin-top: 0.25rem;">Pusat informasi dan pemberitahuan aktivitas sistem</p>
    </div>

    {{-- Stats Card --}}
    <div class="stats-card" style="background: linear-gradient(135deg, #f97316, #ea580c); border-radius: 0.75rem; padding: 1.25rem; color: white; position: relative; overflow: hidden; margin-bottom: 1.5rem;">
        <div style="position: absolute; top: -10px; right: -10px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="display: flex; align-items: center; justify-content: space-between; position: relative; z-index: 1;">
            <div>
                <p style="font-size: 0.875rem; opacity: 0.95; margin: 0;">Notifikasi Baru</p>
                <p style="font-size: 2rem; font-weight: 800; margin: 0.25rem 0 0 0;" id="unreadCount">{{ $unreadCount }}</p>
            </div>
            <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.25); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                <svg style="width: 1.75rem; height: 1.75rem; stroke: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    @if($unreadCount > 0)
    <div style="display: flex; justify-content: flex-end; margin-bottom: 1rem;">
        <button onclick="markAllAsRead()" 
                style="background: none; border: 1px solid #ea580c; color: #ea580c; padding: 0.5rem 1rem; border-radius: 0.5rem; cursor: pointer; font-size: 0.85rem; font-weight: 600; transition: all 0.2s;"
                onmouseover="this.style.background='#ea580c'; this.style.color='white'; this.style.transform='scale(1.05)'"
                onmouseout="this.style.background='none'; this.style.color='#ea580c'; this.style.transform='scale(1)'">
            Tandai Semua Sudah Dibaca
        </button>
    </div>
    @endif

    {{-- List --}}
    <div style="background: white; border-radius: 0.75rem; border: 1px solid #e5e7eb; overflow: hidden;">
        @forelse($notifications as $notification)
        <div class="notif-card animate-slide-in" 
             style="padding: 1rem 1.25rem; border-bottom: 1px solid #f3f4f6; display: flex; gap: 1rem; align-items: flex-start; cursor: pointer; transition: background 0.2s; animation-delay: {{ $loop->index * 0.1 }}s;"
             onmouseover="this.style.background='#fff7ed'"
             onmouseout="this.style.background='white'"
             onclick="markAsRead({{ $notification->id }})">
            
            {{-- Icon --}}
            <div style="flex-shrink: 0;">
                <div style="width: 40px; height: 40px; background: #ffedd5; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 20px; height: 20px; stroke: #ea580c;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </div>
            </div>
            
            {{-- Content --}}
            <div style="flex: 1;">
                <p style="font-size: 0.95rem; font-weight: 600; color: #111827; margin: 0 0 0.25rem 0;">
                    {{ $notification->title }}
                </p>
                <p style="font-size: 0.85rem; color: #4b5563; margin: 0 0 0.5rem 0; line-height: 1.5;">
                    {{ $notification->message }}
                </p>
                <p style="font-size: 0.75rem; color: #9ca3af; margin: 0; font-weight: 500;">
                    {{ $notification->created_at->format('d M Y, H:i') }}
                </p>
            </div>
            
            {{-- Unread Indicator --}}
            <div style="flex-shrink: 0;">
                <span style="display: inline-flex; width: 8px; height: 8px; background: #ea580c; border-radius: 50%; margin-top: 8px; animation: pulse 2s infinite;"></span>
            </div>
        </div>
        @empty
        {{-- Empty State --}}
        <div style="text-align: center; padding: 3rem 1.25rem;">
            <div style="width: 64px; height: 64px; background: #ecfdf5; border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center;">
                <svg style="width: 32px; height: 32px; stroke: #10b981;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin: 0 0 0.5rem 0;">Semua Notifikasi Sudah Dibaca</h3>
            <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Tidak ada notifikasi baru saat ini.</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($notifications->hasPages())
    <div style="margin-top: 1.5rem;">
        {{ $notifications->links() }}
    </div>
    @endif
</div>

<script>
function markAsRead(notificationId) {
    const item = event.currentTarget;
    item.style.opacity = '0.5';
    
    fetch(`/sidongan/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            item.style.transition = 'all 0.3s';
            item.style.transform = 'translateX(20px)';
            setTimeout(() => {
                item.remove();
                let el = document.getElementById('unreadCount');
                if(el) el.textContent = Math.max(0, parseInt(el.textContent) - 1);
            }, 300);
        }
    });
}

function markAllAsRead() {
    if(confirm('Hapus semua notifikasi?')) {
        fetch('/sidongan/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        }).then(res => res.json()).then(data => {
            if(data.success) location.reload();
        });
    }
}
</script>

<style>
@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.2); opacity: 0.7; }
}
</style>
@endsection