@extends('layout.home')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5>Data Tarif Parkir</h5>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            Tambah Tarif
        </button>
    </div>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis Kendaraan</th>
                    <th>Tarif / Jam</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($tarif as $t)
                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $t->jenis_kendaraan }}</td>

                    <td>Rp {{ number_format($t->tarif_per_jam, 0, ',', '.') }}</td>

                    <td>
                        <button class="btn btn-warning btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#edit{{ $t->id }}">
                                <i class="bi bi-pencil"></i>
                        </button>

                        <button class="btn btn-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#hapus{{ $t->id }}">
                                <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>

                <!-- MODAL EDIT -->
                <div class="modal fade" id="edit{{ $t->id }}">
                    <div class="modal-dialog">
                        <form action="{{ route('tarif.update', $t->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5>Edit Tarif</h5>
                                </div>

                                <div class="modal-body">

                                    <select name="jenis_kendaraan" class="form-control mb-2" required>
                                        <option value="Motor" {{ $t->jenis_kendaraan == 'Motor' ? 'selected' : '' }}>Motor</option>
                                        <option value="Mobil" {{ $t->jenis_kendaraan == 'Mobil' ? 'selected' : '' }}>Mobil</option>
                                    </select>

                                    <input type="number"
                                           name="tarif_per_jam"
                                           class="form-control"
                                           value="{{ $t->tarif_per_jam }}"
                                           min="100"
                                           required>

                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- MODAL HAPUS -->
                <div class="modal fade" id="hapus{{ $t->id }}">
                    <div class="modal-dialog modal-dialog-centered">
                        <form action="{{ route('tarif.destroy', $t->id) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <div class="modal-content">
                                <div class="modal-body text-center">
                                    <h5>Yakin hapus?</h5>
                                    <b>{{ $t->jenis_kendaraan }}</b>

                                    <br><br>

                                    <button class="btn btn-danger">Hapus</button>
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

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambah">
    <div class="modal-dialog">
        <form action="{{ route('tarif.store') }}" method="POST">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5>Tambah Tarif</h5>
                </div>

                <div class="modal-body">

                    <select name="jenis_kendaraan" class="form-control mb-2" required>
                        <option value="">-- Pilih Kendaraan --</option>
                        <option value="Motor">Motor</option>
                        <option value="Mobil">Mobil</option>
                    </select>

                    <input type="number"
                           name="tarif_per_jam"
                           class="form-control"
                           min="100"
                           placeholder="Minimal 100"
                           required>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection