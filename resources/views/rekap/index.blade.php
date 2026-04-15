@extends('layout.home')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5>Rekap Transaksi Parkir</h5>

        <button type="button" onclick="printTable()" class="btn btn-success">
            <i class="fas fa-print"></i>
        </button>
    </div>

    <div class="card-body">

        {{-- FILTER --}}
        <form method="GET" class="row mb-3">
            <div class="col-md-4">
                <input type="date" name="dari" class="form-control" value="{{ request('dari') }}">
            </div>
            <div class="col-md-4">
                <input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i>
                </button>
            </div>
        </form>

        {{-- TABEL --}}
        <table class="table table-bordered" id="rekapTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Plat</th>
                    <th>Jenis</th>
                    <th>Area</th>
                    <th>Masuk</th>
                    <th>Keluar</th>
                    <th>Durasi</th>
                    <th>Total</th>
                    <th>Metode</th>
                </tr>
            </thead>

            <tbody>
                @forelse($rekap as $i => $d)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $d->kendaraan->plat_kendaraan ?? '-' }}</td>
                    <td>{{ $d->tarif->jenis_kendaraan ?? '-' }}</td>
                    <td>{{ $d->area->nama_area ?? '-' }}</td>
                    <td>{{ $d->waktu_masuk }}</td>
                    <td>{{ $d->waktu_keluar }}</td>
                    <td>
                        {{ $d->durasi_jam ?? 0 }} Jam 
                        {{ $d->durasi_menit ?? 0 }} Menit
                    </td>
                    <td>Rp {{ number_format($d->biaya_total, 0, ',', '.') }}</td>
                    <td>{{ $d->metode_pembayaran ?? 'Cash' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">Data tidak ada</td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>

{{-- SCRIPT PRINT --}}
<script>
function printTable() {

    let table = document.getElementById("rekapTable");

    if (!table) {
        alert("Tabel tidak ditemukan!");
        return;
    }

    let win = window.open('', '', 'width=900,height=700');

    win.document.write(`
        <html>
        <head>
            <title>Print Rekap</title>
            <style>
                body { font-family: Arial; }
                table { width:100%; border-collapse: collapse; }
                table, th, td { border:1px solid black; }
                th, td { padding:8px; text-align:center; }
                h3 { text-align:center; }
            </style>
        </head>
        <body>
            <h3>Data Rekap Transaksi Parkir</h3>
            ${table.outerHTML}
        </body>
        </html>
    `);

    win.document.close();
    win.focus();
    win.print();
}
</script>

@endsection