@extends('layout.home')

@section('content')
<div class="row g-3 mb-3">
        <!-- Dashboard Petugas -->
            <div class="col-12">
                <div class="card h-md-100 border-0 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>
                            <h4 class="mb-1 fw-bold">Dashboard Petugas</h4>
                            <small class="text-500">
                                Monitoring kendaraan masuk dan keluar hari ini
                            </small>
                        </div>

                        <div>
                            <i class="bi bi-speedometer2 fs-1 text-primary"></i>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Masuk -->
            <div class="col-md-6 col-xxl-3">
                <div class="card h-md-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-2">Masuk Hari Ini</h6>
                            <h3 class="mb-0">{{ $masukHariIni }}</h3>
                            <small class="text-500">Dari waktu_masuk</small>
                        </div>
                        <div>
                            <i class="bi bi-box-arrow-in-right fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keluar -->
            <div class="col-md-6 col-xxl-3">
                <div class="card h-md-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-2">Keluar Hari Ini</h6>
                            <h3 class="mb-0">{{ $keluarHariIni }}</h3>
                            <small class="text-500">Dari waktu_keluar</small>
                        </div>
                        <div>
                            <i class="bi bi-box-arrow-in-left fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection