<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
    /**
     * Display listing of users.
     */
    public function index()
    {
        $currentUser = auth()->user();
        
        // Query users
        $query = User::with('applications')->latest();
        
        // Jika bukan super_admin, jangan tampilkan super_admin di list
        if ($currentUser->sidongan_role !== 'super_admin') {
            $query->where('sidongan_role', '!=', 'super_admin');
        }
        
        $users = $query->paginate(10);
        
        return view('admin.user-management.index', compact('users'));
    }

    /**
     * Show form to create new user.
     */
    public function create()
    {
        $applications = Application::where('is_active', true)->orderBy('name')->get();
        $sidonganRoles = User::getSidonganRoles();
        $roles = \App\Models\Role::all();
        $permissions = \App\Models\Permission::all()->groupBy('group');
        return view('admin.user-management.create', compact('applications', 'sidonganRoles', 'roles', 'permissions'));
    }

    /**
     * Store new user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                'regex:/^[a-zA-Z0-9._%+-]+@pkk-toba\.id$/'
            ],
            'phone_number' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
            'applications' => 'array',
            'applications.*' => 'exists:applications,id',
            'sidongan_role' => 'nullable|in:bupati,ketua,sekretaris,bendahara,pokja1,pokja2,pokja3,pokja4,super_admin',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'] ?? null,
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
            'sidongan_role' => $validated['sidongan_role'] ?? null,
            'email_verified_at' => now(),
        ]);

        // Jika role adalah anggota, attach permissions yang dipilih
        $role = Role::find($validated['role_id']);
        if ($role->name === 'anggota' && isset($validated['permissions'])) {
            $user->role->permissions()->sync($validated['permissions']);
        }

        if (isset($validated['applications'])) {
            $user->applications()->sync($validated['applications']);
        }

        return redirect()->route('admin.user-management.index')
            ->with('success', 'Akun berhasil dibuat!');
    }

    /**
     * Show user details.
     */
    public function show(User $user)
    {
        $user->load(['applications', 'role.permissions']);
        return view('admin.user-management.show', compact('user'));
    }

    /**
     * Show form to edit user.
     */
    public function edit(User $user)
    {
        if ($user->sidongan_role === 'super_admin' && auth()->user()->sidongan_role !== 'super_admin') {
            abort(403, 'Akses ditolak!');
        }
        
        $applications = Application::where('is_active', true)->orderBy('name')->get();
        $userApplications = $user->applications->pluck('id')->toArray();
        $sidonganRoles = User::getSidonganRoles();
        $roles = \App\Models\Role::all();
        $permissions = \App\Models\Permission::all()->groupBy('group');
        $userPermissions = $user->role ? $user->role->permissions->pluck('id')->toArray() : [];
        
        return view('admin.user-management.edit', compact('user', 'applications', 'userApplications', 'sidonganRoles', 'roles', 'permissions', 'userPermissions'));
    }

    /**
     * Update user.
     */
    public function update(Request $request, User $user)
    {
        if ($user->sidongan_role === 'super_admin' && auth()->user()->sidongan_role !== 'super_admin') {
            return back()->with('error', 'Akses ditolak!');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
                'regex:/^[a-zA-Z0-9._%+-]+@pkk-toba\.id$/'
            ],
            'phone_number' => 'nullable|string|max:20',
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
            'applications' => 'array',
            'applications.*' => 'exists:applications,id',
            'sidongan_role' => 'nullable|in:bupati,ketua,sekretaris,bendahara,pokja1,pokja2,pokja3,pokja4,super_admin',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone_number = $validated['phone_number'] ?? null;
        $user->role_id = $validated['role_id'];
        $user->sidongan_role = $validated['sidongan_role'] ?? null;
        
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();

        // Update permissions jika role adalah anggota
        $role = Role::find($validated['role_id']);
        if ($role->name === 'anggota' && isset($validated['permissions'])) {
            $user->role->permissions()->sync($validated['permissions']);
        } elseif ($role->name === 'administrator') {
            // Administrator dapat semua permission
            $user->role->permissions()->sync(Permission::all());
        }

        if (isset($validated['applications'])) {
            $user->applications()->sync($validated['applications']);
        }

        return redirect()->route('admin.user-management.edit', $user)
            ->with('success', 'Akun berhasil diperbarui!');
    }

    /**
     * Toggle user active/inactive status.
     */
    public function toggleStatus(User $user)
    {
        // Hanya super_admin yang bisa toggle status
        if (auth()->user()->sidongan_role !== 'super_admin') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak!'], 403);
        }
        
        // Cek apakah akan menonaktifkan (email_verified_at ada → akan dihapus)
        $willDeactivate = $user->email_verified_at !== null;
        
        // Super Admin TIDAK BISA menonaktifkan dirinya sendiri (tapi BISA mengaktifkan)
        if ($user->id === auth()->id() && $willDeactivate) {
            return response()->json([
                'success' => false, 
                'message' => 'Anda tidak bisa menonaktifkan akun sendiri! Minta Super Admin lain untuk melakukannya.'
            ], 403);
        }
        
        // Toggle status
        if ($willDeactivate) {
            $user->email_verified_at = null;
            $action = 'dinonaktifkan';
        } else {
            $user->email_verified_at = now();
            $action = 'diaktifkan';
        }
        
        $user->save();
        
        return response()->json([
            'success' => true, 
            'message' => 'Akun ' . $user->name . ' telah ' . $action
        ]);
    }

    /**
     * Delete user.
     */
    public function destroy(User $user)
    {
        $currentUser = auth()->user();
        
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }
        
        if ($currentUser->sidongan_role !== 'super_admin') {
            return back()->with('error', 'Akses ditolak! Hanya Super Admin yang dapat menghapus akun.');
        }
        
        if ($user->sidongan_role === 'super_admin') {
            return back()->with('error', 'Anda tidak bisa menghapus akun Super Admin!');
        }
        
        try {
            DB::table('application_user')->where('user_id', $user->id)->delete();
            $user->delete();

            return redirect()->route('admin.user-management.index')
                ->with('success', 'Akun berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus akun: ' . $e->getMessage());
        }
    }

    /**
     * Resend verification email.
     */
    public function resendVerification(User $user)
    {
        if ($user->email_verified_at) {
            return response()->json(['success' => false, 'message' => 'Email sudah terverifikasi']);
        }
        
        $user->sendEmailVerificationNotification();
        
        return response()->json(['success' => true, 'message' => 'Email verifikasi berhasil dikirim']);
    }
}