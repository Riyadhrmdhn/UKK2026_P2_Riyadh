@extends('layout.home')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5>Log Aktivitas User</h5>
    </div>

    <div class="card-body">

        <table class="table table-bordered" id="logTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama User</th>
                    <th>Aktivitas</th>
                    <th>Waktu</th>
                </tr>
            </thead>

            <tbody>
                @foreach($log as $l)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $l->user->name ?? '-' }}</td>
                    <td>{{ $l->aktivitas }}</td>
                    <td>{{ $l->waktu_aktivitas }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

{{-- SCRIPT PRINT --}}
<script>
function printTable(id) {
    let content = document.getElementById(id).outerHTML;

    let win = window.open('', '', 'width=900,height=700');
    win.document.write(`
        <html>
        <head>
            <title>Print Log Aktivitas</title>
            <style>
                table { width:100%; border-collapse: collapse; }
                table, th, td { border:1px solid black; }
                th, td { padding:8px; }
            </style>
        </head>
        <body>
            <h3>Log Aktivitas User</h3>
            ${content}
        </body>
        </html>
    `);

    win.document.close();
    win.print();
}
</script>

@endsection