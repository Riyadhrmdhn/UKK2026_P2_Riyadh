@extends('layout.home')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5>Data Kendaraan</h5>

        <div>
            <!-- PRINT -->
            <button onclick="printTable('kendaraanTable')" class="btn btn-success me-2">
                <i class="bi bi-printer"></i>
            </button>

            <!-- TAMBAH -->
            <button class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalTambah">
                <i class="bi bi-plus-circle"></i>
            </button>
        </div>
    </div>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- TABLE -->
        <table class="table table-bordered" id="kendaraanTable">
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
                <div class="modal fade" id="hapus{{ $k->id }}" 
                    tabindex="-1" 
                    aria-hidden="true">

                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <form action="{{ route('kendaraan.destroy', $k->id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <div class="modal-body text-center p-4">
                                    <i class="bi bi-exclamation-triangle text-danger fs-1"></i>

                                    <h5 class="mt-3">Yakin ingin menghapus?</h5>
                                    <p class="mb-3"><strong>{{ $k->plat_kendaraan }}</strong></p>

                                    <div class="d-flex justify-content-center gap-2">
                                        <button type="button" 
                                                class="btn btn-secondary"
                                                data-bs-dismiss="modal">
                                            Batal
                                        </button>

                                        <button type="submit" 
                                                class="btn btn-danger">
                                            Hapus
                                        </button>
                                    </div>
                                </div>

                            </form>

                        </div>
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

{{-- SCRIPT PRINT --}}
<script>
function printTable(id) {
    let table = document.getElementById(id).cloneNode(true);
    let headers = table.querySelectorAll("thead th");
    let aksiIndex = -1;

    headers.forEach((th, index) => {
        if (th.innerText.trim().toLowerCase() === 'aksi') {
            aksiIndex = index;
        }
    });

    if (aksiIndex !== -1) {
        table.querySelectorAll("tr").forEach(row => {
            if (row.cells.length > aksiIndex) {
                row.deleteCell(aksiIndex);
            }
        });
    }

    let win = window.open('', '', 'width=900,height=700');
    win.document.write(`
        <html>
        <head>
            <title>Print Data User</title>
            <style>
                body { font-family: Arial; }
                table { width:100%; border-collapse: collapse; }
                table, th, td { border:1px solid black; }
                th, td { padding:8px; }
                h3 { text-align:center; }
            </style>
        </head>
        <body>
            <h3>Data User</h3>
            ${table.outerHTML}
        </body>
        </html>
    `);
    win.document.close();
    win.print();
}
</script>

@endsection