@extends('layout.home')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5>Data Area Parkir</h5>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            Tambah Area
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
                    <th>Nama Area</th>
                    <th>Kapasitas</th>
                    <th>Terisi</th>
                    <th>Sisa</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($area as $a)
                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $a->nama_area }}</td>

                    <td>{{ $a->kapasitas }}</td>

                    <td>{{ $a->terisi }}</td>

                    <td>{{ $a->kapasitas - $a->terisi }}</td>

                    <td>
                        <button class="btn btn-warning btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#edit{{ $a->id }}">
                            Edit
                        </button>

                        <button class="btn btn-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#hapus{{ $a->id }}">
                            Hapus
                        </button>
                    </td>
                </tr>

                <!-- MODAL EDIT -->
                <div class="modal fade" id="edit{{ $a->id }}">
                    <div class="modal-dialog">
                        <form action="{{ route('area.update', $a->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5>Edit Area</h5>
                                </div>

                                <div class="modal-body">

                                    <input type="text"
                                           name="nama_area"
                                           class="form-control mb-2"
                                           value="{{ $a->nama_area }}"
                                           required>

                                    <input type="number"
                                           name="kapasitas"
                                           class="form-control mb-2"
                                           value="{{ $a->kapasitas }}"
                                           min="1"
                                           required>

                                    <input type="number"
                                           name="terisi"
                                           class="form-control"
                                           value="{{ $a->terisi }}"
                                           min="0"
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
                <div class="modal fade" id="hapus{{ $a->id }}">
                    <div class="modal-dialog modal-dialog-centered">
                        <form action="{{ route('area.destroy', $a->id) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <div class="modal-content">
                                <div class="modal-body text-center">
                                    <h5>Hapus area ini?</h5>
                                    <b>{{ $a->nama_area }}</b>

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
        <form action="{{ route('area.store') }}" method="POST">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5>Tambah Area</h5>
                </div>

                <div class="modal-body">

                    <input type="text"
                        name="nama_area"
                        class="form-control mb-2"
                        placeholder="Nama area"
                        required>

                    <input type="number"
                        name="kapasitas"
                        class="form-control"
                        placeholder="Kapasitas"
                        min="1"
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