@extends('layouts.app')

@section('page-title', 'Manajemen Job Role')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Manajemen Job Role</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addJobRoleModal">
                        <i class="fas fa-plus"></i> Tambah Job Role
                    </button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th width="60">ID</th>
                                    <th>Nama Job Role</th>
                                    <th width="150" class="text-center">Jumlah User</th>
                                    <th width="180" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jobRoles as $jobRole)
                                <tr>
                                    <td>{{ $jobRole->id }}</td>
                                    <td>{{ $jobRole->name }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $jobRole->users_count }} user</span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-warning"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editJobRoleModal{{ $jobRole->id }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <form action="{{ route('job-roles.destroy', $jobRole) }}"
                                              method="POST"
                                              class="d-inline"
                                              data-confirm-delete="Yakin ingin menghapus job role {{ $jobRole->name }}?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <x-modal-form
                                    id="editJobRoleModal{{ $jobRole->id }}"
                                    title="Edit Job Role"
                                    method="PUT"
                                    action="{{ route('job-roles.update', $jobRole) }}"
                                    submitText="Update">
                                    <div class="mb-3">
                                        <label for="editName{{ $jobRole->id }}" class="form-label">Nama Job Role</label>
                                        <input type="text"
                                               class="form-control @error('name') is-invalid @enderror"
                                               id="editName{{ $jobRole->id }}"
                                               name="name"
                                               value="{{ old('name', $jobRole->name) }}"
                                               required
                                               autofocus>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </x-modal-form>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data job role.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<x-modal-form
    id="addJobRoleModal"
    title="Tambah Job Role Baru"
    action="{{ route('job-roles.store') }}">
    <div class="mb-3">
        <label for="name" class="form-label">Nama Job Role</label>
        <input type="text"
               class="form-control @error('name') is-invalid @enderror"
               id="name"
               name="name"
               value="{{ old('name') }}"
               required
               autofocus>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</x-modal-form>

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var addModal = new bootstrap.Modal(document.getElementById('addJobRoleModal'));
        addModal.show();
    });
</script>
@endif
@endsection
