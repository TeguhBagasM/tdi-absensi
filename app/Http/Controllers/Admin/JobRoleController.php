<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobRole;
use Illuminate\Http\Request;

class JobRoleController extends Controller
{
    public function index()
    {
        $jobRoles = JobRole::withCount('users')->orderBy('name')->paginate(15);
        return view('job-roles.index', compact('jobRoles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:job_roles,name',
        ], [
            'name.required' => 'Nama job role wajib diisi.',
            'name.max' => 'Nama job role maksimal 255 karakter.',
            'name.unique' => 'Nama job role sudah ada.',
        ]);

        JobRole::create(['name' => $request->name]);

        return redirect()->route('job-roles.index')
            ->with('success', 'Job role berhasil ditambahkan.');
    }

    public function update(Request $request, JobRole $jobRole)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:job_roles,name,' . $jobRole->id,
        ], [
            'name.required' => 'Nama job role wajib diisi.',
            'name.max' => 'Nama job role maksimal 255 karakter.',
            'name.unique' => 'Nama job role sudah ada.',
        ]);

        $jobRole->update(['name' => $request->name]);

        return redirect()->route('job-roles.index')
            ->with('success', 'Job role berhasil diperbarui.');
    }

    public function destroy(JobRole $jobRole)
    {
        if ($jobRole->users()->count() > 0) {
            return redirect()->route('job-roles.index')
                ->with('error', 'Job role tidak dapat dihapus karena masih memiliki user.');
        }

        $jobRole->delete();

        return redirect()->route('job-roles.index')
            ->with('success', 'Job role berhasil dihapus.');
    }
}
