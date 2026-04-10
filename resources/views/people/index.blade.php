@extends('layout.home')

@section('content')

<div class="card mb-3">

    <!-- HEADER -->
    <div class="card-header d-flex justify-content-between">
        <h5>Kelola User</h5>

        <!-- BUTTON TAMBAH -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle"></i> Tambah User
        </button>
    </div>

    <div class="card-body">

        <!-- ALERT -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- TABLE -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $i => $user)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td>
                            <span class="badge {{ $user->status == 'aktif' ? 'bg-success' : 'bg-danger' }}">
                                {{ $user->status }}
                            </span>
                        </td>
                        <td>

                            <!-- EDIT BUTTON -->
                            <button class="btn btn-warning btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEdit{{ $user->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <!-- DELETE -->
                            <button class="btn btn-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalHapus{{ $user->id }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- MODAL EDIT -->
                    <div class="modal fade" id="modalEdit{{ $user->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('user.update', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5>Edit User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">

                                        <input type="text" name="name" value="{{ $user->name }}" class="form-control mb-2" placeholder="Nama">
                                        <input type="email" name="email" value="{{ $user->email }}" class="form-control mb-2" placeholder="Email">
                                        <input type="password" name="password" class="form-control mb-2" placeholder="Password (opsional)">

                                        <select name="role" class="form-control mb-2">
                                            <option value="admin" {{ $user->role=='admin'?'selected':'' }}>Admin</option>
                                            <option value="petugas" {{ $user->role=='petugas'?'selected':'' }}>Petugas</option>
                                            <option value="owner" {{ $user->role=='owner'?'selected':'' }}>Owner</option>
                                        </select>

                                        <select name="status" class="form-control">
                                            <option value="aktif" {{ $user->status=='aktif'?'selected':'' }}>Aktif</option>
                                            <option value="nonaktif" {{ $user->status=='nonaktif'?'selected':'' }}>Nonaktif</option>
                                        </select>

                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-primary">Update</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                        <!-- MODAL HAPUS -->
                        <div class="modal fade" id="modalHapus{{ $user->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <form action="{{ route('user.destroy', $user->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <div class="modal-content">
                                        <div class="modal-body text-center">
                                            Yakin hapus <b>{{ $user->name }}</b> ?
                                            <br><br>

                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    @endforeach
                </tbody>

            </table>
        </div>

    </div>
</div>


<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('user.store') }}" method="POST">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5>Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="text" name="name" class="form-control mb-2" placeholder="Nama">
                    <input type="email" name="email" class="form-control mb-2" placeholder="Email">
                    <input type="password" name="password" class="form-control mb-2" placeholder="Password">

                    <select name="role" class="form-control mb-2">
                        <option value="admin">Admin</option>
                        <option value="petugas">Petugas</option>
                        <option value="owner">Owner</option>
                    </select>

                    <select name="status" class="form-control">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">Simpan</button>
                </div>
            </div>

        </form>
    </div>
</div>

@endsection