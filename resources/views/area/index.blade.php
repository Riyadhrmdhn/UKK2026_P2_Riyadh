@extends('layout.home')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5>Data Area Parkir</h5>

        <div>
            <!-- PRINT -->
            <button onclick="printTable('areaTable')" class="btn btn-success me-2">
                <i class="bi bi-printer"></i>
            </button>

            <!-- TAMBAH -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-circle"></i>
            </button>
        </div>
    </div>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- TABLE -->
        <table class="table table-bordered" id="areaTable">
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
                                <i class="bi bi-pencil"></i>
                        </button>

                        <button class="btn btn-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#hapus{{ $a->id }}">
                                <i class="bi bi-trash"></i>
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
                <div class="modal fade" id="hapus{{ $a->id }}" 
                    tabindex="-1" 
                    aria-hidden="true">

                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <form action="{{ route('area.destroy', $a->id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <div class="modal-body text-center p-4">
                                    <i class="bi bi-exclamation-triangle text-danger fs-1"></i>

                                    <h5 class="mt-3">Yakin ingin menghapus?</h5>
                                    <p class="mb-3"><strong>{{ $a->nama_area }}</strong></p>

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
            <title>Print Area</title>
            <style>
                body { font-family: Arial; }
                table { width:100%; border-collapse: collapse; }
                table, th, td { border:1px solid black; }
                th, td { padding:8px; }
                h3 { text-align:center; }
            </style>
        </head>
        <body>
            <h3>Data Area</h3>
            ${table.outerHTML}
        </body>
        </html>
    `);
    win.document.close();
    win.print();
}
</script>

@endsection