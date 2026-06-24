<?php

namespace App\Http\Controllers;

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
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

/**
 * Role access management (superadmin only).
 *
 * Allows the superadmin to view, edit, and delete user roles.
 */
class HakaksesController extends Controller
{
    /**
     * Display a listing of users with their roles.
     */
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

    /**
     * Show the form for editing the specified user's role.
     */
    public function edit(int $id): View
    {
        $hakakses = User::findOrFail($id);

        return view('hakakses.edit', compact('hakakses'));
    }

    /**
     * Update the specified user's role.
     */
    public function update(UpdateUserRoleRequest $request, int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->syncRoles([$request->role]);

        // Delete existing profiles to ensure only one profile type is active
        $user->studentProfile()->delete();
        $user->teacherProfile()->delete();
        $user->teknisiProfile()->delete();

        // Menyimpan data profile sesuai dengan role
        switch ($request->role) {
            case 'student':
                $user->studentProfile()->updateOrCreate(
                    ['user_id' => $user->id],
                    ['nik' => $request->nik]
                );
                break;
            case 'teacher':
                $user->teacherProfile()->updateOrCreate(
                    ['user_id' => $user->id],
                    ['nuptk' => $request->nuptk]
                );
                break;
            case 'technician':
                $user->teknisiProfile()->updateOrCreate(
                    ['user_id' => $user->id],
                    ['id_teknisi' => $request->id_technician]
                );
                break;
        }
        $user->save(); 

        ActivityLog::log(
            "Role updated for {$user->name} to {$request->role}",
            'Role Access',
            'updated',
            $user
        );

        return redirect()->route('hakakses.index')
            ->with('success', 'User role updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        // Prevent deleting yourself
        if ($user->id === Auth::id()) {        
            return redirect()->route('hakakses.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $userName = $user->name;
        $user->delete();

        ActivityLog::log(
            "User deleted: {$userName}",
            'Role Access',
            'deleted'
        );

        return redirect()->route('hakakses.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('hakakses.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        // Langsung menangkap input password dan melakukan hashing
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'role' => $request->role,
        ];

        try {
            $user = User::create($userData);
            $user->assignRole($request->role);

            switch ($request->role) {
                case 'student':
                    $user->studentProfile()->create([
                        'nik' => $request->nik,
                    ]);
                    break;
                case 'teacher':
                    $user->teacherProfile()->create([
                        'nuptk' => $request->nuptk,
                    ]);
                    break;
                case 'technician':
                    $user->teknisiProfile()->create([
                        'id_teknisi' => $request->id_technician,
                    ]);
                    break;
            }

        } catch (\Exception $e) {
            Log::error("Error creating user: " . $e->getMessage(), [
                'userData' => $userData,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create user. Please check logs for details.']);
        }

        ActivityLog::log(
            "New user created: {$user->name} with role {$request->role}",
            'Role Access',
            'created',
            $user
        );

        return redirect()->route('hakakses.index')
            ->with('success', 'User created successfully.');
    }
}