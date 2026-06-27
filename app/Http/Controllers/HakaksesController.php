<?php

namespace App\Http\Controllers;

// PENTING: Kita panggil lagi file request kustom Anda
use App\Http\Requests\Roles\UpdateUserRoleRequest;
use App\Http\Requests\Roles\StoreUserRequest;
use App\Models\ActivityLog;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\TeknisiProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class HakaksesController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::with('roles');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $hakakses = $query->orderBy('name')->get();
        return view('hakakses.index', compact('hakakses'));
    }

    public function create(): View
    {
        return view('hakakses.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'role' => $request->role,
        ];

        try {
            DB::transaction(function () use ($userData, $request) {
                $user = User::create($userData);
                
                if (method_exists($user, 'assignRole')) {
                    $user->assignRole($request->role);
                }

                switch ($request->role) {
                    case 'student':
                        $user->studentProfile()->create(['nik' => $request->nik]);
                        break;
                    case 'teacher':
                        $user->teacherProfile()->create(['nuptk' => $request->nuptk]);
                        break;
                    case 'technician':
                        $user->teknisiProfile()->create(['id_teknisi' => $request->id_technician]);
                        break;
                }

                ActivityLog::log(
                    "New user created: {$user->name} with role {$request->role}",
                    'Role Access',
                    'created',
                    $user
                );
            });

        } catch (\Exception $e) {
            Log::error("Error creating user: " . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal Simpan! Pesan Sistem: ' . $e->getMessage()]);
        }

        return redirect()->route('hakakses.index')->with('success', 'Berhasil membuat & menambahkan user.');
    }

    public function edit(int $id): View
    {
        $hakakses = User::findOrFail($id);
        return view('hakakses.edit', compact('hakakses'));
    }

    public function update(UpdateUserRoleRequest $request, int $id): RedirectResponse
    {
        try {
            $user = User::findOrFail($id);

            DB::transaction(function () use ($user, $request) {
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'role' => $request->role 
                ]);

                if (method_exists($user, 'syncRoles')) {
                    $user->syncRoles([$request->role]);
                }

                $user->studentProfile()->delete();
                $user->teacherProfile()->delete();
                $user->teknisiProfile()->delete();

                switch ($request->role) {
                    case 'student':
                        $user->studentProfile()->create(['nik' => $request->nik]);
                        break;
                    case 'teacher':
                        $user->teacherProfile()->create(['nuptk' => $request->nuptk]);
                        break;
                    case 'technician':
                        $user->teknisiProfile()->create(['id_teknisi' => $request->id_technician]);
                        break;
                }

                ActivityLog::log(
                    "Role updated for {$user->name} to {$request->role}",
                    'Role Access',
                    'updated',
                    $user
                );
            });

        } catch (\Exception $e) {
            Log::error("Error updating user: " . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal Update! Pesan Sistem: ' . $e->getMessage()]);
        }

        return redirect()->route('hakakses.index')->with('success', 'Role user berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {        
            return redirect()->route('hakakses.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $userName = $user->name;
        $user->delete();

        ActivityLog::log("User deleted: {$userName}", 'Role Access', 'deleted');

        return redirect()->route('hakakses.index')->with('success', 'User berhasil dihapus.');
    }
}