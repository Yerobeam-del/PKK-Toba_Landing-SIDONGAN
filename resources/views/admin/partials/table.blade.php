{{-- resources/views/admin/partials/table.blade.php --}}
@props(['data' => [], 'emptyMessage' => 'Belum ada data', 'editRoute' => null, 'deleteRoute' => null])

<div class="table-container" style="padding:1rem">
    @if(count($data) > 0)
    <table style="width:100%;border-collapse:collapse">
        <thead>
            <tr style="text-align:left;border-bottom:1px solid rgba(0,0,0,0.06)">
                <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Foto</th>
                <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Nama</th>
                <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Jabatan</th>
                <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;text-align:right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
            <tr style="border-bottom:1px solid rgba(0,0,0,0.04)">
                <td style="padding:1rem">
                    @if($item->photo_path)
                    <img src="{{ asset('storage/'.$item->photo_path) }}" alt="{{ $item->name }}" style="width:40px;height:40px;border-radius:8px;object-fit:cover;background:#f8fafc">
                    @else
                    <div style="width:40px;height:40px;border-radius:8px;background:#f8fafc;display:flex;align-items:center;justify-content:center;color:#94a3b8">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    @endif
                </td>
                <td style="padding:1rem;font-weight:600">{{ $item->name }}</td>
                <td style="padding:1rem">
                    <span style="background:rgba(20,184,166,0.1);color:var(--primary);padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600">
                        {{ $item->position }}
                    </span>
                </td>
                <td style="padding:1rem;text-align:right">
                    <div class="actions" style="justify-content:flex-end">
                        @if($editRoute)
                        <a href="{{ route($editRoute, $item) }}" class="btn-edit" title="Edit">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </a>
                        @endif
                        @if($deleteRoute)
                        <button type="button" onclick="confirmDeleteItem({{ $item->id }}, '{{ addslashes($item->name) }}')" class="btn-del" title="Hapus">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                        </button>
                        <form id="delete-form-{{ $item->id }}" action="{{ route($deleteRoute, $item) }}" method="POST" style="display:none">
                            @csrf 
                            @method('DELETE')
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    {{-- Empty State Visual --}}
    <div style="text-align:center;padding:3rem 1rem;color:var(--text-muted)">
        <div style="width:64px;height:64px;background:#f8fafc;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <h3 style="font-size:1rem;font-weight:700;color:var(--text-dark);margin:0 0 0.5rem">Belum Ada Data</h3>
        <p style="font-size:0.9rem;margin:0">{{ $emptyMessage }}</p>
    </div>
    @endif
</div>

{{-- Toast Confirm Script --}}
<script>
// Global function untuk delete confirmation dengan Toast
if (typeof window.confirmDeleteItem === 'undefined') {
    window.confirmDeleteItem = function(id, name) {
        // Cek apakah Toast tersedia
        if (typeof Toast !== 'undefined' && typeof Toast.confirm === 'function') {
            Toast.confirm(
                `Data <strong>"${name}"</strong> akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.`,
                {
                    title: 'Hapus Data?',
                    confirmText: 'Ya, Hapus',
                    cancelText: 'Batal',
                    type: 'danger'
                }
            ).then(function(confirmed) {
                if (confirmed) {
                    const form = document.getElementById('delete-form-' + id);
                    if (form) form.submit();
                }
            }).catch(function(error) {
                console.error('Error:', error);
                if (confirm(`Hapus data "${name}"?`)) {
                    const form = document.getElementById('delete-form-' + id);
                    if (form) form.submit();
                }
            });
        } else {
            // Fallback ke browser confirm
            if (confirm(`Hapus data "${name}"?`)) {
                const form = document.getElementById('delete-form-' + id);
                if (form) form.submit();
            }
        }
    };
}
</script>