  @extends('layout.home')

@section('content')
  <div class="row g-3 mb-3">

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

            <!-- Masuk -->
            <div class="col-md-6 col-xxl-3">
                <div class="card h-md-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-2">Masuk Hari Ini</h6>
                            <h3 class="mb-0">{{ $masuk }}</h3>
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
                            <h3 class="mb-0">{{ $keluar }}</h3>
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