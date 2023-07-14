<div class="card card-height-100">
  <div class="card-header align-items-center d-flex">
    <h4 class="card-title mb-0 flex-grow-1">Recent Activity</h4>
    <div class="flex-shrink-0">
      <div class="dropdown card-header-dropdown">
        <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="fw-semibold text-uppercase fs-12">Sort by: </span><span class="text-muted">Minggu ini<i class="mdi mdi-chevron-down ms-1"></i></span>
        </a>
        <div class="dropdown-menu dropdown-menu-end">
          <a class="dropdown-item" href="#">Hari ini</a>
          <a class="dropdown-item" href="#">Minggu ini</a>
          <a class="dropdown-item" href="#">Bulan ini</a>
        </div>
      </div>
    </div>
  </div>
  <div class="card-body p-0">
    <div data-simplebar style="height: 390px;">
      <div class="p-3">
        @foreach ($data as $item)
          <h6 class="text-muted text-uppercase mb-3 @if ($loop->index > 0) mt-4 @endif fs-11">{{ $item['periode'] }}</h6>
          @foreach ($item['data'] as $activity)
            <div class="d-flex align-items-center @if ($loop->index > 0) mt-3 @endif">
              <div class="avatar-xs flex-shrink-0">
                <span class="avatar-title bg-light rounded-circle">
                  @if (isset($activity['is_penyaluran']))
                    <i data-feather="send" class="icon-dual-warning icon-sm"></i>
                  @else
                    <i data-feather="{{ $activity['is_perolehan'] ? "arrow-up-circle" : "arrow-down-circle" }}" class="icon-dual-{{ $activity['is_perolehan'] ? "success" : "danger" }} icon-sm"></i>
                  @endif
                </span>
              </div>
              <div class="flex-grow-1 ms-3">
                <h6 class="fs-14 mb-1">{{ $activity['pembukuan'] }}</h6>
                <p class="text-muted fs-12 mb-0">
                  @if (isset($activity['is_penyaluran']))
                    <i class="mdi mdi-circle-medium text-warning fs-15 align-middle"></i> {{ $activity['bidang'] }}
                  @else
                    <i class="mdi mdi-circle-medium text-{{ $activity['is_perolehan'] ? "success" : "danger" }} fs-15 align-middle"></i> {{ $activity['bidang'] }}
                  @endif
                </p>
              </div>
              <div class="flex-shrink-0 text-end">
                @if (isset($activity['is_penyaluran']))
                  <h6 class="mb-1 text-warning">{{ $activity['nilai'] }}</h6>
                @else
                  <h6 class="mb-1 text-{{ $activity['is_perolehan'] ? "success" : "danger" }}">
                    {{ $activity['is_perolehan'] ? "+" : "-" }} Rp{{ $activity['nilai'] }}
                  </h6>
                @endif
              </div>
            </div>
          @endforeach
        @endforeach
        {{-- <h6 class="text-muted text-uppercase mb-3 fs-11">14 July 2023</h6>
        <div class="d-flex align-items-center">
          <div class="avatar-xs flex-shrink-0">
            <span class="avatar-title bg-light rounded-circle">
            <i data-feather="arrow-down-circle" class="icon-dual-success icon-sm"></i>
            </span>
          </div>
          <div class="flex-grow-1 ms-3">
            <h6 class="fs-14 mb-1">Bought Bitcoin</h6>
            <p class="text-muted fs-12 mb-0">
              <i class="mdi mdi-circle-medium text-success fs-15 align-middle"></i> Visa Debit Card ***6
            </p>
          </div>
          <div class="flex-shrink-0 text-end">
            <h6 class="mb-1 text-success">+0.04025745<span class="text-uppercase ms-1">Btc</span></h6>
            <p class="text-muted fs-13 mb-0">+878.52 USD</p>
          </div>
        </div>
        <div class="d-flex align-items-center mt-3">
          <div class="avatar-xs flex-shrink-0">
            <span class="avatar-title bg-light rounded-circle">
            <i data-feather="send" class="icon-dual-warning icon-sm"></i>
            </span>
          </div>
          <div class="flex-grow-1 ms-3">
            <h6 class="fs-14 mb-1">Sent Ethereum</h6>
            <p class="text-muted fs-12 mb-0">
              <i class="mdi mdi-circle-medium text-warning fs-15 align-middle"></i> Sofia Cunha
            </p>
          </div>
          <div class="flex-shrink-0 text-end">
            <h6 class="mb-1 text-muted">-0.09025182<span class="text-uppercase ms-1">Eth</span></h6>
            <p class="text-muted fs-13 mb-0">-659.35 USD</p>
          </div>
        </div>

        <h6 class="text-muted text-uppercase mb-3 mt-4 fs-11">24 Dec 2021</h6>
        <div class="d-flex align-items-center">
          <div class="avatar-xs flex-shrink-0">
            <span class="avatar-title bg-light rounded-circle">
            <i data-feather="arrow-up-circle" class="icon-dual-danger icon-sm"></i>
            </span>
          </div>
          <div class="flex-grow-1 ms-3">
            <h6 class="fs-14 mb-1">Sell Dash</h6>
            <p class="text-muted fs-12 mb-0">
              <i class="mdi mdi-circle-medium text-danger fs-15 align-middle"></i> www.cryptomarket.com
            </p>
          </div>
          <div class="flex-shrink-0 text-end">
            <h6 class="mb-1 text-danger">-98.6025422<span class="text-uppercase ms-1">Dash</span></h6>
            <p class="text-muted fs-13 mb-0">-1508.98 USD</p>
          </div>
        </div>
        <div class="d-flex align-items-center mt-3">
          <div class="avatar-xs flex-shrink-0">
            <span class="avatar-title bg-light rounded-circle">
            <i data-feather="arrow-up-circle" class="icon-dual-danger icon-sm"></i>
            </span>
          </div>
          <div class="flex-grow-1 ms-3">
            <h6 class="fs-14 mb-1">Sell Dogecoin</h6>
            <p class="text-muted fs-12 mb-0">
              <i class="mdi mdi-circle-medium text-success fs-15 align-middle"></i> www.coinmarket.com
            </p>
          </div>
          <div class="flex-shrink-0 text-end">
            <h6 class="mb-1 text-danger">-1058.08025142<span class="text-uppercase ms-1">Doge</span></h6>
            <p class="text-muted fs-13 mb-0">-89.36 USD</p>
          </div>
        </div>
        <div class="d-flex align-items-center mt-3">
          <div class="avatar-xs flex-shrink-0">
            <span class="avatar-title bg-light rounded-circle">
            <i data-feather="arrow-up-circle" class="icon-dual-success icon-sm"></i>
            </span>
          </div>
          <div class="flex-grow-1 ms-3">
            <h6 class="fs-14 mb-1">Bought Litecoin</h6>
            <p class="text-muted fs-12 mb-0">
              <i class="mdi mdi-circle-medium text-warning fs-15 align-middle"></i> Payment via Wallet
            </p>
          </div>
          <div class="flex-shrink-0 text-end">
            <h6 class="mb-1 text-success">+0.07225912<span class="text-uppercase ms-1">Ltc</span></h6>
            <p class="text-muted fs-13 mb-0">+759.45 USD</p>
          </div>
        </div> --}}
      </div>
    </div>
  </div>
</div>