@extends('layout.home')

@section('content')
<div class="row g-3 mb-3">
        <!-- Dashboard Petugas -->
            <div class="col-12">
                <div class="card h-md-100 border-0 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>
                            <h4 class="mb-1 fw-bold">Dashboard Admin</h4>
                            <small class="text-500">
                                Kelola data user dan kendaraan, serta pantau aktivitas parkir secara menyeluruh
                            </small>
                        </div>

                        <div>
                            <i class="bi bi-speedometer2 fs-1 text-primary"></i>
                        </div>

                    </div>
                </div>
            </div>
              <!-- Jumlah User -->
             <div class="col-md-6 col-xxl-3">
                <div class="card h-md-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-2">Jumlah User</h6>
                            <h3 class="mb-0">{{ $totalUser }}</h3>
                            <small class="text-500">Total semua user</small>
                        </div>

                        <!-- ICON USER -->
                        <div>
                            <i class="bi bi-people fs-2"></i>
                        </div>

                    </div>
                </div>
            </div>

              <!-- Jumlah Kendaraan -->
            <div class="col-md-6 col-xxl-3">
                <div class="card h-md-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-2">Jumlah Kendaraan</h6>
                            <h3 class="mb-0">{{ $totalKendaraan }}</h3>
                            <small class="text-500">Kendaraan terdaftar</small>
                        </div>

                        <!-- ICON BARU -->
                        <div>
                            <i class="bi bi-car-front fs-2"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endsection