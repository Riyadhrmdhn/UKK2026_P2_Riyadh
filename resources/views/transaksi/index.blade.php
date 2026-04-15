@extends('layout.home')

@section('content')

{{-- =========================
    INPUT TRANSAKSI
========================= --}}
<div class="card mb-4">
    <div class="card-header">
        <h5>Input Transaksi Parkir</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('transaksi.store') }}" method="POST">
            @csrf

            <div class="row">

                {{-- PILIH KENDARAAN --}}
                <div class="col-md-6">
                    <label>Pilih Kendaraan</label>
                    <select name="id_kendaraan" class="form-control" required>
                        <option value="">-- Pilih Kendaraan --</option>
                        @foreach($kendaraan as $k)
                            <option value="{{ $k->id }}">
                                {{ $k->plat_kendaraan }} - {{ $k->warna }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- PILIH AREA --}}
                <div class="col-md-6">
                    <label>Pilih Area Parkir</label>
                    <select name="id_area" class="form-control" required>
                        <option value="">-- Pilih Area --</option>
                        @foreach($area as $a)
                            <option value="{{ $a->id }}">
                                {{ $a->nama_area }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="mt-3">
                <button class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</div>


{{-- =========================
    DATA TRANSAKSI
========================= --}}
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5>Data Transaksi Parkir</h5>

       <button onclick="printTable('transaksiTable')" class="btn btn-success">
            <i class="bi bi-printer"></i>
        </button>
    </div>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered" id="transaksiTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Plat Kendaraan</th>
                    <th>Warna</th>
                    <th>Area</th>
                    <th>Waktu Masuk</th>
                    <th>Durasi</th>
                    <th>Total Bayar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($transaksi as $t)
                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $t->kendaraan->plat_kendaraan ?? '-' }}</td>
                    <td>{{ $t->kendaraan->warna ?? '-' }}</td>

                    {{-- AREA HANYA NAMA --}}
                    <td>{{ $t->area->nama_area ?? '-' }}</td>

                    {{-- WAKTU MASUK --}}
                    <td>{{ $t->waktu_masuk }}</td>

                    {{-- DURASI REALTIME --}}
                    <td class="durasi"
                        data-masuk="{{ $t->waktu_masuk }}"
                        data-status="{{ $t->status }}"
                        data-keluar="{{ $t->waktu_keluar }}">
                    </td>

                    {{-- TOTAL BAYAR REALTIME --}}
                    <td class="total-bayar"
                        data-masuk="{{ $t->waktu_masuk }}"
                        data-status="{{ $t->status }}"
                        data-tarif="{{ $t->tarif->tarif_per_jam ?? 0 }}"
                        data-total="{{ $t->biaya_total }}">
                    </td>

                    {{-- STATUS --}}
                    <td>
                        @if($t->status == 'parkir')
                            <span class="badge bg-primary">Parkir</span>
                        @else
                            <span class="badge bg-success">Selesai</span>
                        @endif
                    </td>

                    {{-- AKSI --}}
                   <td>
                        {{-- STATUS PARKIR --}}
                        @if($t->status == 'parkir')
                            <a href="{{ route('transaksi.keluar', $t->id) }}"
                            class="btn btn-success btn-sm">
                                <i class="bi bi-box-arrow-right"></i>
                            </a>
                        @endif

                        {{-- STATUS KELUAR --}}
                        @if($t->status == 'keluar')
                            <button 
                                class="btn btn-primary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalBayar"
                                data-id="{{ $t->id }}"
                                data-plat="{{ $t->kendaraan->plat_kendaraan }}"
                                data-total="{{ $t->biaya_total }}">
                                <i class="bi bi-cash"></i>
                            </button>
                        @endif
                    </td>
                </tr>
                <!-- =========================
                        MODAL PEMBAYARAN
                    ========================= -->
                    <div class="modal fade" id="modalBayar" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <form method="GET" id="formBayar">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Pembayaran Parkir</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">

                                        <p><strong>Plat Kendaraan :</strong></p>
                                        <h5 id="modalPlat"></h5>

                                        <hr>

                                        <p><strong>Total Bayar :</strong></p>
                                        <h4 class="text-success" id="modalTotal"></h4>

                                        <div class="mt-3">
                                            <label>Uang Dibayar</label>
                                            <input type="number" 
                                                class="form-control" 
                                                id="uangDibayar" 
                                                placeholder="Masukkan uang..."
                                                required>
                                        </div>

                                        <div class="mt-3">
                                            <label>Kembalian</label>
                                            <input type="text" 
                                                class="form-control" 
                                                id="kembalian"
                                                readonly>
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" 
                                                class="btn btn-secondary" 
                                                data-bs-dismiss="modal">
                                            Batal
                                        </button>

                                        <button type="submit" 
                                                class="btn btn-primary"
                                                id="btnBayar"
                                                disabled>
                                            Konfirmasi Bayar
                                        </button>
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


{{-- =========================
    SCRIPT REALTIME
========================= --}}
<script>
    function updateRealtime() {

        // DURASI
        document.querySelectorAll('.durasi').forEach(function(el) {

            let status = el.dataset.status;
            let masuk = new Date(el.dataset.masuk);
            let keluar;

            // 🔥 FIX: kalau sudah keluar, pakai waktu keluar
            if (status === 'keluar') {
                keluar = new Date(el.dataset.keluar);
            } else {
                keluar = new Date();
            }

            let selisihMenit = Math.floor((keluar - masuk) / 60000);
            let jam = Math.floor(selisihMenit / 60);
            let menit = selisihMenit % 60;

            el.innerHTML = jam + " Jam " + menit + " Menit";
        });


        // TOTAL BAYAR
        document.querySelectorAll('.total-bayar').forEach(function(el) {

            // 🔥 FIX: kalau sudah keluar, pakai total dari DB
            if (el.dataset.status === 'keluar') {
                el.innerHTML = "Rp " + parseInt(el.dataset.total).toLocaleString('id-ID');
                return;
            }

            let waktuMasuk = new Date(el.dataset.masuk);
            let sekarang = new Date();

            let selisihMenit = Math.floor((sekarang - waktuMasuk) / 60000);
            let tarifPerMenit = el.dataset.tarif / 60;

            let total = Math.round(selisihMenit * tarifPerMenit);

            el.innerHTML = "Rp " + total.toLocaleString('id-ID');
        });
    }

    setInterval(updateRealtime, 10000);
    updateRealtime();
</script>

<script>
    var modalBayar = document.getElementById('modalBayar');

    modalBayar.addEventListener('show.bs.modal', function (event) {

        var button = event.relatedTarget;

        var id = button.getAttribute('data-id');
        var plat = button.getAttribute('data-plat');
        var total = button.getAttribute('data-total');

        document.getElementById('modalPlat').innerText = plat;
        document.getElementById('modalTotal').innerText =
            "Rp " + parseInt(total).toLocaleString('id-ID');

        document.getElementById('formBayar').action =
            "/transaksi/" + id + "/bayar";
    });
</script>

<script>
    var modalBayar = document.getElementById('modalBayar');
    var totalGlobal = 0;

    modalBayar.addEventListener('show.bs.modal', function (event) {

        var button = event.relatedTarget;

        var id = button.getAttribute('data-id');
        var plat = button.getAttribute('data-plat');
        var total = button.getAttribute('data-total');

        totalGlobal = parseInt(total);

        document.getElementById('modalPlat').innerText = plat;
        document.getElementById('modalTotal').innerText =
            "Rp " + totalGlobal.toLocaleString('id-ID');

        document.getElementById('formBayar').action =
            "/transaksi/" + id + "/bayar";

        // reset input
        document.getElementById('uangDibayar').value = "";
        document.getElementById('kembalian').value = "";
        document.getElementById('btnBayar').disabled = true;
    });


    // HITUNG KEMBALIAN
    document.getElementById('uangDibayar').addEventListener('input', function() {

        let uang = parseInt(this.value);

        if(isNaN(uang)) {
            document.getElementById('kembalian').value = "";
            document.getElementById('btnBayar').disabled = true;
            return;
        }

        let kembali = uang - totalGlobal;

        if(kembali >= 0) {
            document.getElementById('kembalian').value =
                "Rp " + kembali.toLocaleString('id-ID');
            document.getElementById('btnBayar').disabled = false;
        } else {
            document.getElementById('kembalian').value =
                "Uang kurang!";
            document.getElementById('btnBayar').disabled = true;
        }
    });
</script>

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
                <title>Print Transaksi</title>
                <style>
                    body { font-family: Arial; }
                    table { width:100%; border-collapse: collapse; }
                    table, th, td { border:1px solid black; }
                    th, td { padding:8px; }
                    h3 { text-align:center; }
                </style>
            </head>
            <body>
                <h3>Data Transaksi</h3>
                ${table.outerHTML}
            </body>
            </html>
        `);
        win.document.close();
        win.print();
    }
</script>

@endsection