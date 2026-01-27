@extends('layouts.app')

@section('page-title', 'Manajemen Pengguna')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Manajemen Pengguna</h4>
                            <div class="d-flex gap-2">
                                <a href="{{ route('users.approvals') }}" class="btn btn-outline-secondary position-relative">
                                    <i class="fas fa-user-check"></i> Persetujuan Pengguna
                                    @if(isset($pendingCount) && $pendingCount > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            {{ $pendingCount }}
                                        </span>
                                    @endif
                                </a>
                                <a href="{{ route('users.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Pengguna
                                </a>
                            </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Peran</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->isAdmin() ? 'danger' : 'primary' }}">
                                            {{ str_replace('_', ' ', $user->role->name ?? 'Tidak ada peran') }}
                                        </span>
                                    </td>
                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            @if($user->id !== auth()->id())
                                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline"
                                                      data-confirm-delete="Yakin ingin menghapus pengguna {{ $user->name }}?">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data pengguna.</td>
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
@endsection
