@extends('admin.layouts.app')
@section('title', 'Edit Akun')
@section('page-title', 'Edit Akun')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <div>
        <h1 style="font-size:1.5rem;font-weight:800;color:var(--text-dark);margin:0 0 0.25rem 0">Edit Akun</h1>
        <p style="color:var(--text-muted);margin:0;font-size:0.9rem">Update informasi akun {{ $user->name }}</p>
    </div>
    <a href="{{ route('admin.user-management.index') }}" class="btn">← Kembali</a>
</div>

@if($errors->any())
<div style="background:#fef2f2;padding:1rem;margin-bottom:1.5rem;border-radius:10px;color:#dc2626">
    <strong>Terjadi kesalahan:</strong>
    <ul style="margin:0.5rem 0 0 0;padding-left:1.25rem">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(session('success'))
<div style="background:#f0fdf4;padding:1rem;margin-bottom:1.5rem;border-radius:10px;color:#166534">
    {{ session('success') }}
</div>
@endif

<div class="card" style="padding:1.5rem;border-radius:12px">
    <form action="{{ route('admin.user-management.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="display:grid;gap:1.5rem;max-width:600px">
            <div>
                <label style="font-weight:600;display:block;margin-bottom:0.5rem">Nama Lengkap *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            
            <div>
                <label style="display:block;font-weight:600;margin-bottom:0.5rem;color:var(--text-dark)">Email <span style="color:#ef4444">*</span></label>
                @php
                    $emailParts = explode('@', $user->email);
                    $emailUsername = $emailParts[0] ?? '';
                @endphp
                <div style="display:flex;align-items:center;gap:0.5rem">
                    <input type="text" id="email_username" name="email_username" placeholder="username" 
                        value="{{ old('email_username', $emailUsername) }}"
                        style="flex:1;padding:0.75rem 1rem;border:2px solid #e2e8f0;border-radius:8px;font-size:0.95rem;outline:none"
                        onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#e2e8f0'" required>
                    <span style="padding:0.75rem 1rem;background:#f1f5f9;border:2px solid #e2e8f0;border-radius:8px;font-size:0.95rem;color:#64748b;font-weight:600">
                        @pkk-toba.id
                    </span>
                </div>
                <input type="hidden" name="email" id="email_full" value="{{ old('email', $user->email) }}">
                <small style="color:var(--text-muted);margin-top:0.25rem;display:block">Email otomatis: username@pkk-toba.id</small>
            </div>

            <div>
                <label style="font-weight:600;display:block;margin-bottom:0.5rem">Nomor Telepon</label>
                <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number) }}">
            </div>
            
            <div>
                <label style="font-weight:600;display:block;margin-bottom:0.5rem">Password Baru</label>
                <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah">
                <small style="color:var(--text-muted);display:block;margin-top:0.25rem">Kosongkan jika tidak ingin mengubah password</small>
            </div>
            
            <div>
                <label style="font-weight:600;display:block;margin-bottom:0.5rem">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>

            {{-- 🔹 ROLE ADMIN PANEL --}}
            <div>
                <label style="font-weight:600;display:block;margin-bottom:0.5rem">Role Admin Panel <span style="color:#ef4444">*</span></label>
                <select name="role_id" id="roleSelect" class="form-control" required onchange="togglePermissionSection()">
                    <option value="">-- Pilih Role --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                            {{ $role->display_name }} - {{ $role->description }}
                        </option>
                    @endforeach
                </select>
                <small style="color:var(--text-muted);display:block;margin-top:0.25rem">
                    Administrator: Akses penuh | Anggota: Akses terbatas sesuai permission
                </small>
            </div>

            {{-- 🔹 PERMISSION SECTION (Hanya untuk Anggota) --}}
            <div id="permissionSection" style="display:none;border:1px solid var(--border);border-radius:8px;padding:1rem;background:#f8fafc">
                <label style="font-weight:600;display:block;margin-bottom:0.5rem">Permission Akses <span style="color:#ef4444">*</span></label>
                <small style="color:var(--text-muted);display:block;margin-bottom:1rem">Pilih modul yang bisa diakses user ini</small>
                
                @foreach($permissions as $group => $perms)
                    <div style="margin-bottom:1rem;padding:0.75rem;background:#fff;border-radius:6px">
                        <h4 style="font-size:0.9rem;font-weight:700;color:var(--text-dark);margin-bottom:0.5rem;text-transform:capitalize">
                            {{ ucfirst(str_replace('-', ' ', $group)) }}
                        </h4>
                        @foreach($perms as $perm)
                            {{-- SKIP permission 'publish-berita' --}}
                            @if($perm->name !== 'publish-berita')
                                <label style="display:flex;align-items:center;gap:0.5rem;padding:0.3rem 0;cursor:pointer">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" 
                                        class="permission-checkbox" 
                                        data-group="{{ $group }}"
                                        {{ in_array($perm->id, $userPermissions) ? 'checked' : '' }}>
                                    <span style="font-size:0.9rem">{{ $perm->display_name }}</span>
                                </label>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>
            
            {{-- Aplikasi yang Diakses --}}
            <div>
                <label style="font-weight:600;display:block;margin-bottom:0.5rem">Aplikasi yang Diakses</label>
                <div style="border:1px solid var(--border);border-radius:8px;padding:1rem;max-height:200px;overflow-y:auto">
                    @forelse($applications as $app)
                    <label style="display:flex;align-items:center;gap:0.5rem;padding:0.4rem 0;cursor:pointer">
                        <input 
                            type="checkbox" 
                            name="applications[]" 
                            value="{{ $app->id }}"
                            class="application-checkbox"
                            data-app-name="{{ $app->name }}"
                            data-app-short="{{ $app->short_name }}"
                            {{ in_array($app->id, $userApplications) ? 'checked' : '' }}
                        >
                        <span>{{ $app->name }}</span>
                    </label>
                    @empty
                    <p style="color:var(--text-muted)">Belum ada aplikasi</p>
                    @endforelse
                </div>
            </div>
            
            {{-- SIDONGAN Role Selection --}}
            <div id="sidonganRoleSection" style="border:1px solid var(--border);border-radius:8px;padding:1rem;margin-top:0.5rem;background:#f8fafc">
                <label style="font-weight:600;display:block;margin-bottom:0.5rem">Peran di SIDONGAN</label>
                <select name="sidongan_role" id="sidonganRole" class="form-control">
                    <option value="">-- Pilih Peran --</option>
                    @foreach($sidonganRoles as $key => $label)
                        <option value="{{ $key }}" {{ old('sidongan_role', $user->sidongan_role) == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                <small style="color:var(--text-muted);display:block;margin-top:0.25rem">
                    Pilih peran yang sesuai untuk akses SIDONGAN
                </small>
            </div>
        </div>
        
        <div style="margin-top:1.5rem;display:flex;gap:0.75rem;justify-content:flex-end">
            <a href="{{ route('admin.user-management.index') }}" class="btn">Batal</a>
            <button type="submit" class="btn btn-primary">Update Akun</button>
        </div>
    </form>
</div>

<script>
// Auto-generate email dengan domain wajib @pkk-toba.id
function updateEmail() {
    const username = document.getElementById('email_username').value.trim();
    const fullEmail = document.getElementById('email_full');
    
    if (username) {
        fullEmail.value = username + '@pkk-toba.id';
    } else {
        fullEmail.value = '';
    }
}

// Toggle Permission Section berdasarkan Role
function togglePermissionSection() {
    const roleSelect = document.getElementById('roleSelect');
    const permissionSection = document.getElementById('permissionSection');
    const selectedOption = roleSelect.options[roleSelect.selectedIndex];
    const roleName = selectedOption ? selectedOption.text.toLowerCase() : '';
    
    if (roleName.includes('anggota')) {
        permissionSection.style.display = 'block';
    } else {
        permissionSection.style.display = 'none';
    }
}

// Init saat halaman load
document.addEventListener('DOMContentLoaded', function() {
    togglePermissionSection();
});

document.getElementById('email_username').addEventListener('input', updateEmail);

document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('input[name="applications[]"]');
    const sidonganRoleSection = document.getElementById('sidonganRoleSection');
    const sidonganRoleSelect = document.getElementById('sidonganRole');
    
    function checkSidonganStatus() {
        let isSidonganChecked = false;
        
        checkboxes.forEach(checkbox => {
            const appShort = (checkbox.dataset.appShort || '').toLowerCase();
            if (appShort === 'sidongan' && checkbox.checked) {
                isSidonganChecked = true;
            }
        });
        
        if (isSidonganChecked) {
            sidonganRoleSection.style.display = 'block';
        } else {
            sidonganRoleSection.style.display = 'none';
        }
    }
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', checkSidonganStatus);
    });
    
    checkSidonganStatus();
});
</script>

@endsection