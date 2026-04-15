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
            {{-- TOTAL KENDARAAN MASUK --}}
            <div class="col-md-6 col-xxl-3">
                <div class="card h-md-100 border-start border-primary border-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-2">Kendaraan Masuk</h6>
                            <h3 class="mb-0">{{ $masukHariIni }}</h3>
                            <small class="text-500">Hari ini</small>
                        </div>
                        <div>
                            <i class="bi bi-box-arrow-in-right fs-2 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KENDARAAN KELUAR --}}
            <div class="col-md-6 col-xxl-3">
                <div class="card h-md-100 border-start border-warning border-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-2">Kendaraan Keluar</h6>
                            <h3 class="mb-0">{{ $keluarHariIni }}</h3>
                            <small class="text-500">Hari ini</small>
                        </div>
                        <div>
                            <i class="bi bi-box-arrow-in-left fs-2 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection