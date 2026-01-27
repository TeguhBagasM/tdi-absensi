@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Detail User</h4>
                    <div>
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>ID:</strong></div>
                        <div class="col-md-9">{{ $user->id }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Nama:</strong></div>
                        <div class="col-md-9">{{ $user->name }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Email:</strong></div>
                        <div class="col-md-9">{{ $user->email }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Role:</strong></div>
                        <div class="col-md-9">
                            <span class="badge bg-{{ $user->isAdmin() ? 'danger' : 'primary' }} fs-6">
                                {{ $user->role->name ?? 'No Role' }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Tanggal Daftar:</strong></div>
                        <div class="col-md-9">{{ $user->created_at->format('d F Y, H:i') }} WIB</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Terakhir Update:</strong></div>
                        <div class="col-md-9">{{ $user->updated_at->format('d F Y, H:i') }} WIB</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Email Verified:</strong></div>
                        <div class="col-md-9">
                            @if($user->email_verified_at)
                                <span class="badge bg-success">Terverifikasi</span>
                                <small class="text-muted">({{ $user->email_verified_at->format('d/m/Y H:i') }})</small>
                            @else
                                <span class="badge bg-warning">Belum Terverifikasi</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
