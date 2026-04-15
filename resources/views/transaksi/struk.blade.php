<!DOCTYPE html>
<html>
<head>
    <title>Struk Parkir</title>
    <style>
        body {
            font-family: monospace;
            font-size: 14px;
            text-align: center;
        }
        .struk {
            width: 300px;
            margin: auto;
        }
        hr {
            border-top: 1px dashed black;
            margin: 8px 0;
        }
        .left {
            text-align: left;
        }
        .right {
            text-align: right;
        }
        .flex {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body onload="window.print()">

<div class="struk">

    <h3>PARKIR SYSTEM</h3>

    <hr>


    <div class="flex">
        <span>Tanggal</span>
        <span>{{ now()->format('d-m-Y H:i') }}</span>
    </div>

    <hr>

    <div class="left">
        <p>Plat : {{ $data['plat'] }}</p>
        <p>Warna: {{ $data['warna'] }}</p>
        <p>Area : {{ $data['area'] }}</p>
    </div>

    <hr>

    <div class="left">
        <p>Masuk  : {{ $data['masuk'] }}</p>
        <p>Keluar : {{ $data['keluar'] }}</p>

        @php
            $masuk = \Carbon\Carbon::parse($data['masuk']);
            $keluar = \Carbon\Carbon::parse($data['keluar']);
            $menit = $masuk->diffInMinutes($keluar);
            $jam = floor($menit / 60);
            $sisa = $menit % 60;
        @endphp

        <p>Durasi : {{ $jam }} Jam {{ $sisa }} Menit</p>
    </div>

    <hr>

    <div class="flex">
        <strong>Total</strong>
        <strong>Rp {{ number_format($data['total']) }}</strong>
    </div>

    <hr>

</div>

<script>
window.onafterprint = function() {
    window.location.href = "/transaksi";
};
</script>

</body>
</html>