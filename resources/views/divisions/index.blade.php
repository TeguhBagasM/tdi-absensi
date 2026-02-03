@extends('layouts.app')

@section('page-title', 'Manajemen Divisi')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Manajemen Divisi</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDivisionModal">
                        <i class="fas fa-plus"></i> Tambah Divisi
                    </button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th width="60">ID</th>
                                    <th>Nama Divisi</th>
                                    <th width="150" class="text-center">Jumlah Pengguna</th>
                                    <th width="180" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($divisions as $division)
                                <tr>
                                    <td>{{ $division->id }}</td>
                                    <td>{{ $division->name }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $division->users_count }} pengguna</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <button type="button" class="btn btn-sm" style="background: transparent; color: #f59e0b; border: 1px solid #fef3c7; border-radius: 6px; padding: 6px 8px;" title="Edit"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editDivisionModal{{ $division->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('divisions.destroy', $division) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  data-confirm-delete="Yakin ingin menghapus divisi {{ $division->name }}?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm" style="background: transparent; color: #ef4444; border: 1px solid #fecaca; border-radius: 6px; padding: 6px 8px;" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <x-modal-form
                                    id="editDivisionModal{{ $division->id }}"
                                    title="Edit Divisi"
                                    method="PUT"
                                    action="{{ route('divisions.update', $division) }}"
                                    submitText="Perbarui">
                                    <div class="mb-3">
                                        <label for="editName{{ $division->id }}" class="form-label">Nama Divisi</label>
                                        <input type="text"
                                               class="form-control @error('name') is-invalid @enderror"
                                               id="editName{{ $division->id }}"
                                               name="name"
                                               value="{{ old('name', $division->name) }}"
                                               required
                                               autofocus>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </x-modal-form>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data divisi.</td>
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
    id="addDivisionModal"
    title="Tambah Divisi Baru"
    action="{{ route('divisions.store') }}">
    <div class="mb-3">
        <label for="name" class="form-label">Nama Divisi</label>
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
        var addModal = new bootstrap.Modal(document.getElementById('addDivisionModal'));
        addModal.show();
    });
</script>
@endif
@endsection
