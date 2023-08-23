<div class="app-menu navbar-menu">
  <!-- LOGO -->
  <div class="navbar-brand-box">
    <!-- Dark Logo-->
    <a href="{{ url('/') }}" class="logo logo-dark">
      <span class="logo-sm">
        <img src="{{ asset('assets/images/logo-sm.png') }}" alt="LogoSM" height="35">
      </span>
      <span class="logo-lg">
        <img src="{{ asset('assets/images/logo-dark.png') }}" alt="LogoDark" height="20">
      </span>
    </a>
    <!-- Light Logo-->
    <a href="{{ url('/') }}" class="logo logo-light">
      <span class="logo-sm">
        <img src="{{ asset('assets/images/logo-sm.png') }}" alt="LogoSM" height="35">
      </span>
      <span class="logo-lg">
        <img src="{{ asset('assets/images/logo-light.png') }}" alt="LogoLight" height="20">
      </span>
    </a>
    <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
      <i class="ri-record-circle-line"></i>
    </button>
  </div>
  <div id="scrollbar">
    <div class="container-fluid">
      <div id="two-column-menu"></div>
      
      <ul class="navbar-nav" id="navbar-nav">
        <li class="menu-title"><span>Menu</span></li>
        <li class="nav-item">
          <a class="nav-link menu-link {{ Request::is('dashboard') ? "active" : "" }}" href="{{ route('dashboard') }}">
            <i class="ri-dashboard-2-line"></i> <span>Dashboards</span>
          </a>
        </li>

        <li class="nav-item">
          <a 
            class="nav-link menu-link {{ Request::is('master*') ? "collapsed active" : "" }}" 
            href="#masterData" 
            data-bs-toggle="collapse" 
            role="button" 
            aria-expanded="false" 
            aria-controls="masterData"
          >
            <i class="ri-apps-2-line"></i> <span>Master Data</span>
          </a>
          <div class="collapse menu-dropdown {{ Request::is('master*') ? "show" : "" }}" id="masterData">
            <ul class="nav nav-sm flex-column">
              @if (auth()->user()->role_id !== 4)
                <li class="nav-item">
                  <a href="{{ route('master.bidang.index') }}" class="nav-link {{ Request::is('master/bidang*') ? "active" : "" }}">Bidang</a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('master.users.index') }}" class="nav-link {{ Request::is('master/users*') ? "active" : "" }}">Users</a>
                </li>
              @endif

              <li class="nav-item">
                <a href="{{ route('master.persediaan.index') }}" class="nav-link {{ Request::is('master/persediaan*') ? "active" : "" }}">Persediaan</a>
              </li>
            </ul>
          </div>
        </li>

        <li class="nav-item">
          <a 
            class="nav-link menu-link {{ Request::is('pembukuan*') ? "collapsed active" : "" }}" 
            href="#pembukuan" 
            data-bs-toggle="collapse" 
            role="button" 
            aria-expanded="false" 
            aria-controls="pembukuan"
          >
            <i class="ri-pencil-ruler-2-line"></i> <span>Pembukuan</span>
          </a>
          <div id="pembukuan" class="menu-dropdown collapse {{ Request::is('pembukuan*') ? "show" : "" }}">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <a 
                  href="#pembukuanSaldoAwal" 
                  class="nav-link {{ Request::is('pembukuan/saldo-awal*') ? "active" : "" }}"
                  data-bs-toggle="collapse" 
                  role="button" 
                  aria-expanded="{{ Request::is('pembukuan/saldo-awal*') ? true : false }}" 
                  aria-controls="pembukuanSaldoAwal"
                >Saldo Awal</a>
                <div id="pembukuanSaldoAwal" class="menu-dropdown collapse {{ Request::is('pembukuan/saldo-awal*') ? "show" : "" }}">
                  <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                      <a href="{{ route('pembukuan.saldo-awal.index', 'persediaan') }}" class="nav-link {{ Request::is('pembukuan/saldo-awal/persediaan*') ? "active" : "" }}">Persediaan</a>
                    </li>
                    <li class="nav-item">
                      <a href="{{ route('pembukuan.saldo-awal.index', 'tanah') }}" class="nav-link {{ Request::is('pembukuan/saldo-awal/tanah*') ? "active" : "" }}">Tanah</a>
                    </li>
                    <li class="nav-item">
                      <a href="{{ route('pembukuan.saldo-awal.index', 'peralatan-mesin') }}" class="nav-link {{ Request::is('pembukuan/saldo-awal/peralatan-mesin*') ? "active" : "" }}">Peralatan dan Mesin</a>
                    </li>
                    <li class="nav-item">
                      <a href="{{ route('pembukuan.saldo-awal.index', 'gedung-bangunan') }}" class="nav-link {{ Request::is('pembukuan/saldo-awal/gedung-bangunan*') ? "active" : "" }}">Gedung dan Bangunan</a>
                    </li>
                    <li class="nav-item">
                      <a href="{{ route('pembukuan.saldo-awal.index', 'jij') }}" class="nav-link {{ Request::is('pembukuan/saldo-awal/jij*') ? "active" : "" }}">Jalan, Irigasi dan Jaringan</a>
                    </li>
                    <li class="nav-item">
                      <a href="{{ route('pembukuan.saldo-awal.index', 'atl') }}" class="nav-link {{ Request::is('pembukuan/saldo-awal/atl*') ? "active" : "" }}">Aset Tetap Lainnya</a>
                    </li>
                    <li class="nav-item">
                      <a href="{{ route('pembukuan.saldo-awal.index', 'kdp') }}" class="nav-link {{ Request::is('pembukuan/saldo-awal/kdp*') ? "active" : "" }}">Konstruksi Dalam Pengerjaan</a>
                    </li>
                    <li class="nav-item">
                      <a href="{{ route('pembukuan.saldo-awal.index', 'atb') }}" class="nav-link {{ Request::is('pembukuan/saldo-awal/atb*') ? "active" : "" }}">Aset Tidak Berwujud</a>
                    </li>
                    <li class="nav-item">
                      <a href="{{ route('pembukuan.saldo-awal.index', 'aset-lain') }}" class="nav-link {{ Request::is('pembukuan/saldo-awal/aset-lain*') ? "active" : "" }}">Aset Lain-Lain</a>
                    </li>
                  </ul>
                </div>
              </li>

              <li class="nav-item">
                <a 
                  href="#pembukuanPerolehan" 
                  class="nav-link {{ Request::is('persediaan/perolehan*') ? "active" : "" }}"
                  data-bs-toggle="collapse" 
                  role="button" 
                  aria-expanded="false" 
                  aria-controls="pembukuanPerolehan"
                >Perolehan / Penerimaan</a>
                <div id="pembukuanPerolehan" class="menu-dropdown collapse {{ Request::is('pembukuan/perolehan*') ? "show" : "" }}">
                  <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                      <a href="{{ route('pembukuan.perolehan.index', 'pengadaan') }}" class="nav-link {{ Request::is('pembukuan/perolehan/pengadaan*') ? "active" : "" }}">Pengadaan</a>
                    </li>
                    <li class="nav-item">
                      <a href="{{ route('pembukuan.perolehan.index', 'hibah') }}" class="nav-link {{ Request::is('pembukuan/perolehan/hibah*') ? "active" : "" }}">Hibah</a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a href="{{ route('pembukuan.reklasifikasi.index') }}" class="nav-link {{ Request::is('pembukuan/reklasifikasi*') ? "active" : "" }}">Reklasifikasi</a>
              </li>
              <li class="nav-item">
                <a href="{{ route('pembukuan.penghapusan.index') }}" class="nav-link {{ Request::is('pembukuan/penghapusan*') ? "active" : "" }}">Penghapusan</a>
              </li>
              <li class="nav-item">
                <a href="{{ route('pembukuan.stock-opname.index') }}" class="nav-link {{ Request::is('pembukuan/stock-opname*') ? "active" : "" }}">Stock Opname</a>
              </li>
            </ul>
          </div>
        </li>

        <li class="nav-item">
          <a 
            class="nav-link menu-link {{ Request::is('penyaluran*') ? "collapsed active" : "" }}" 
            href="#penyaluran" 
            data-bs-toggle="collapse" 
            role="button" 
            aria-expanded="false" 
            aria-controls="penyaluran"
          >
            <i class="ri-gps-line"></i> <span>Penyaluran Persediaan</span>
          </a>
          <div id="penyaluran" class="menu-dropdown collapse {{ Request::is('penyaluran*') ? "show" : "" }}">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <a href="{{ route('penyaluran.nota-permintaan.index') }}" class="nav-link {{ Request::is('penyaluran/nota-permintaan*') ? "active" : "" }}">Nota Permintaan</a>
              </li>
              <li class="nav-item">
                <a href="{{ route('penyaluran.spb.index') }}" class="nav-link {{ Request::is('penyaluran/spb*') ? "active" : "" }}">Surat Permintaan Barang</a>
              </li>
              <li class="nav-item">
                <a href="{{ route('penyaluran.sppb.index') }}" class="nav-link {{ Request::is('penyaluran/sppb*') ? "active" : "" }}">Surat Perintah Penyaluran Barang</a>
              </li>
            </ul>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link menu-link {{ Request::is('usulan') ? "active" : "" }}" href="{{ url('/usulan') }}">
            <i class="ri-rocket-line"></i> <span>Usulan</span>
          </a>
        </li>

        <li class="menu-title"><i class="ri-more-fill"></i> <span>Output</span></li>
        <li class="nav-item">
          <a 
            class="nav-link menu-link {{ Request::is('laporan*') ? "collapsed active" : "" }}" 
            href="#report" 
            data-bs-toggle="collapse" 
            role="button" 
            aria-expanded="false" 
            aria-controls="report"
          >
          <i class="ri-file-list-3-line"></i> <span>Pelaporan</span>
          </a>
          <div class="collapse menu-dropdown {{ Request::is('laporan*') ? "show" : "" }}" id="report">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <a 
                  href="#laporanPerolehan" 
                  class="nav-link {{ Request::is('laporan/perolehan') ? "active" : "" }}"
                  data-bs-toggle="collapse" 
                  role="button" 
                  aria-expanded="false" 
                  aria-controls="laporanPerolehan"
                >Laporan Perolehan</a>
                <div id="laporanPerolehan" class="menu-dropdown collapse {{ Request::is('laporan/perolehan*') ? "show" : "" }}">
                  <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                      <a href="{{ route('laporan.perolehan', 'pengadaan') }}" class="nav-link {{ Request::is('laporan/perolehan/pengadaan*') ? "active" : "" }}">Pengadaan</a>
                    </li>
                    <li class="nav-item">
                      <a href="{{ route('laporan.perolehan', 'hibah') }}" class="nav-link {{ Request::is('laporan/perolehan/hibah*') ? "active" : "" }}">Hibah</a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a href="{{ route('laporan.reklasifikasi') }}" class="nav-link {{ Request::is('laporan/reklasifikasi*') ? "active" : "" }}">Laporan Reklasifikasi</a>
              </li>
              <li class="nav-item">
                <a href="{{ route('laporan.penghapusan') }}" class="nav-link {{ Request::is('laporan/penghapusan*') ? "active" : "" }}">Laporan Penghapusan</a>
              </li>
              <li class="nav-item">
                <a href="{{ route('laporan.stock-opname') }}" class="nav-link {{ Request::is('laporan/stock-opname*') ? "active" : "" }}">Laporan Stock Opname</a>
              </li>
              <li class="nav-item">
                <a href="{{ route('laporan.penyaluran') }}" class="nav-link {{ Request::is('laporan/penyaluran*') ? "active" : "" }}">Laporan Penyaluran</a>
              </li>
              <li class="nav-item">
                <a href="{{ route('laporan.kartu-persediaan') }}" class="nav-link {{ Request::is('laporan/kartu-persediaan*') ? "active" : "" }}">Kartu Barang Persediaan</a>
              </li>
              <li class="nav-item">
                <a href="{{ route('laporan.mutasi-persediaan') }}" class="nav-link {{ Request::is('laporan/mutasi-persediaan*') ? "active" : "" }}">Mutasi Persediaan</a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/laporan/usulan') }}" class="nav-link">Laporan Usulan</a>
              </li>
            </ul>
          </div>
        </li>
      </ul>
    </div>
    <!-- Sidebar -->
  </div>
  <div class="sidebar-background"></div>
</div>