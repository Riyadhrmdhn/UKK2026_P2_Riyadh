@extends('layout.home')

@section('content')

@php
    $admin = $users->where('role', 'admin')->values();
    $petugas = $users->where('role', 'petugas')->values();
    $owner = $users->where('role', 'owner')->values();

    // 🔥 LOGIC SHIFT 8 JAM
    $hour = now()->format('H');

    if ($hour >= 0 && $hour < 8) {
        $shiftAktif = 0;
    } elseif ($hour >= 8 && $hour < 16) {
        $shiftAktif = 1;
    } else {
        $shiftAktif = 2;
    }
@endphp

{{-- ================= HEADER GLOBAL ================= --}}
<div class="mb-3 d-flex justify-content-between">
    <h4>Kelola User</h4>

    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-circle"></i> Tambah User
    </button>
</div>

{{-- ================= ADMIN ================= --}}
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between">
        <h5>Data Admin</h5>
        <button onclick="printTable('adminTable')" class="btn btn-success">
            <i class="bi bi-printer"></i>
        </button>
    </div>

    <div class="card-body">
        <table class="table table-bordered" id="adminTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admin as $i => $user)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge {{ $user->status=='aktif'?'bg-success':'bg-danger' }}">
                            {{ $user->status }}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit{{ $user->id }}">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapus{{ $user->id }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ================= PETUGAS ================= --}}
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between">
        <h5>Data Petugas</h5>
        <button onclick="printTable('petugasTable')" class="btn btn-success">
            <i class="bi bi-printer"></i>
        </button>
    </div>

    <div class="card-body">
        <table class="table table-bordered" id="petugasTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Shift</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($petugas as $i => $user)
                @php
                    $isAktif = ($i % 3) == $shiftAktif;
                @endphp
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>

                    <td>
                        <span class="badge {{ $isAktif ? 'bg-success' : 'bg-secondary' }}">
                            {{ $isAktif ? 'Aktif' : 'Off' }}
                        </span>
                    </td>

                    <td>
                        <span class="badge {{ $isAktif ? 'bg-success' : 'bg-danger' }}">
                            {{ $isAktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>

                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit{{ $user->id }}">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapus{{ $user->id }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ================= OWNER ================= --}}
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between">
        <h5>Data Owner</h5>
        <button onclick="printTable('ownerTable')" class="btn btn-success">
            <i class="bi bi-printer"></i>
        </button>
    </div>

    <div class="card-body">
        <table class="table table-bordered" id="ownerTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($owner as $i => $user)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge {{ $user->status=='aktif'?'bg-success':'bg-danger' }}">
                            {{ $user->status }}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit{{ $user->id }}">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapus{{ $user->id }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ================= MODAL TAMBAH ================= --}}
<div class="modal fade" id="modalTambah">
    <div class="modal-dialog">
        <form action="{{ route('people.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5>Tambah User</h5>
            </div>

            <div class="modal-body">
                <input type="text" name="name" class="form-control mb-2" placeholder="Nama" required>
                <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>

                <select name="role" class="form-control mb-2" required onchange="toggleShift(this.value)">
                    <option value="">-- Pilih Role --</option>
                    <option value="admin">Admin</option>
                    <option value="petugas">Petugas</option>
                    <option value="owner">Owner</option>
                </select>

                {{-- 🔥 TAMBAHAN SHIFT --}}
                <div id="shiftField" style="display:none;">
                    <select name="shift" class="form-control mb-2">
                        <option value="">-- Pilih Shift --</option>
                        <option value="0">Shift Pagi (06:00 - 14:00)</option>
                        <option value="1">Shift Siang (14:00 - 22:00)</option>
                        <option value="2">Shift Malam (22:00 - 06:00)</option>
                    </select>
                </div>

                <select name="status" class="form-control">
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>

            <div class="modal-footer">
                <button class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ================= MODAL EDIT & HAPUS ================= --}}
@foreach($users as $user)

<div class="modal fade" id="edit{{ $user->id }}">
    <div class="modal-dialog">
        <form action="{{ route('people.update', $user->id) }}" method="POST" class="modal-content">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <h5>Edit User</h5>
            </div>

            <div class="modal-body">
                <input type="text" name="name" value="{{ $user->name }}" class="form-control mb-2" required>
                <input type="email" name="email" value="{{ $user->email }}" class="form-control mb-2" required>
                <input type="password" name="password" class="form-control mb-2" placeholder="Opsional">

                <select name="status" class="form-control">
                    <option value="aktif" {{ $user->status=='aktif'?'selected':'' }}>Aktif</option>
                    <option value="nonaktif" {{ $user->status=='nonaktif'?'selected':'' }}>Nonaktif</option>
                </select>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="hapus{{ $user->id }}">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('people.destroy', $user->id) }}" method="POST" class="modal-content">
            @csrf
            @method('DELETE')

            <div class="modal-body text-center">
                <h5>Hapus <b>{{ $user->name }}</b> ?</h5>

                <button class="btn btn-danger">Hapus</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>

@endforeach

{{-- SCRIPT --}}
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
        let rows = table.querySelectorAll("tr");
        rows.forEach(row => {
            if (row.cells.length > aksiIndex) {
                row.deleteCell(aksiIndex);
            }
        });
    }

    let win = window.open('', '', 'width=900,height=700');
    win.document.write(`
        <html>
        <head>
            <style>
                table { width:100%; border-collapse: collapse; }
                table, th, td { border:1px solid black; }
                th, td { padding:8px; }
            </style>
        </head>
        <body>${table.outerHTML}</body>
        </html>
    `);

    win.document.close();
    win.print();
}
</script>

<script>
function toggleShift(role) {
    let shift = document.getElementById('shiftField');

    if (role === 'petugas') {
        shift.style.display = 'block';
    } else {
        shift.style.display = 'none';
    }
}
</script>

<script>
function toggleShift(role) {
    let shiftField = document.getElementById('shiftField');
    let shiftSelect = document.getElementById('shiftSelect');

    if (role === 'petugas') {
        shiftField.style.display = 'block';
        shiftSelect.setAttribute('required', 'required'); // 🔥 wajib isi
    } else {
        shiftField.style.display = 'none';
        shiftSelect.removeAttribute('required');
        shiftSelect.value = ''; // reset
    }
}
</script>

@endsection