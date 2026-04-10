@extends('layout.home')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5>Data Kendaraan</h5>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            Tambah Kendaraan
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
                    <th>Plat</th>
                    <th>Warna</th>
                    <th>Jenis</th>
                    <th>Tarif / Jam</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($kendaraan as $k)
                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $k->plat_kendaraan }}</td>

                    <td>{{ $k->warna }}</td>

                    <td>{{ optional($k->tarif)->jenis_kendaraan ?? '-' }}</td>

                    <td>
                        Rp {{ number_format(optional($k->tarif)->tarif_per_jam ?? 0, 0, ',', '.') }}
                    </td>

                    <td>
                        <button class="btn btn-warning btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#edit{{ $k->id }}">
                                <i class="bi bi-pencil"></i>
                        </button>

                        <button class="btn btn-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#hapus{{ $k->id }}">
                                <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>

                <!-- MODAL EDIT -->
                <div class="modal fade" id="edit{{ $k->id }}">
                    <div class="modal-dialog">
                        <form action="{{ route('kendaraan.update', $k->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5>Edit Kendaraan</h5>
                                </div>

                                <div class="modal-body">

                                    <input type="text"
                                           name="plat_kendaraan"
                                           class="form-control mb-2"
                                           value="{{ $k->plat_kendaraan }}"
                                           required>

                                    <input type="text"
                                           name="warna"
                                           class="form-control mb-2"
                                           value="{{ $k->warna }}"
                                           required>

                                    <select name="id_tarif" class="form-control" required>
                                        @foreach($tarif as $t)
                                            <option value="{{ $t->id }}"
                                                {{ $k->id_tarif == $t->id ? 'selected' : '' }}>
                                                {{ $t->jenis_kendaraan }} - Rp {{ number_format($t->tarif_per_jam,0,',','.') }}
                                            </option>
                                        @endforeach
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
                <div class="modal fade" id="hapus{{ $k->id }}">
                    <div class="modal-dialog modal-dialog-centered">
                        <form action="{{ route('kendaraan.destroy', $k->id) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <div class="modal-content">
                                <div class="modal-body text-center">
                                    <h5>Hapus kendaraan ini?</h5>
                                    <b>{{ $k->plat_kendaraan }}</b>

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
        <form action="{{ route('kendaraan.store') }}" method="POST">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5>Tambah Kendaraan</h5>
                </div>

                <div class="modal-body">

                    <input type="text"
                           name="plat_kendaraan"
                           class="form-control mb-2"
                           placeholder="Plat kendaraan"
                           required>

                    <input type="text"
                           name="warna"
                           class="form-control mb-2"
                           placeholder="Warna"
                           required>

                    <select name="id_tarif" class="form-control" required>
                        <option value="">-- Pilih Tarif --</option>
                        @foreach($tarif as $t)
                            <option value="{{ $t->id }}">
                                {{ $t->jenis_kendaraan }} - Rp {{ number_format($t->tarif_per_jam,0,',','.') }}
                            </option>
                        @endforeach
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