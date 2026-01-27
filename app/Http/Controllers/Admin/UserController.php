<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        $pendingCount = User::where('is_approved', false)
            ->whereHas('role', fn($q) => $q->where('name', '!=', 'admin'))
            ->count();

        return view('users.index', compact('users', 'pendingCount'));
    }

    public function approvals()
    {
        $pendingUsers = User::with('role', 'division', 'jobRole')
            ->where('is_approved', false)
            ->whereHas('role', fn($q) => $q->where('name', '!=', 'admin'))
            ->get();

        return view('users.approvals', compact('pendingUsers'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }

    public function approve(User $user)
    {
        if ($user->role && $user->role->name === 'admin') {
            return redirect()->back()->with('error', 'Tidak dapat memproses user admin.');
        }

        $user->update(['is_approved' => true]);

        return redirect()->back()->with('success', 'User disetujui.');
    }

    public function reject(User $user)
    {
        if ($user->role && $user->role->name === 'admin') {
            return redirect()->back()->with('error', 'Tidak dapat memproses user admin.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'User ditolak dan dihapus.');
    }
}
