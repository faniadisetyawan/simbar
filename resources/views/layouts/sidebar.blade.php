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
          <a class="nav-link menu-link {{ Request::is('dashboard') ? "active" : "" }}" href="{{ url('/dashboard') }}">
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
              <li class="nav-item">
                <a href="{{ url('/master/bidang') }}" class="nav-link {{ Request::is('master/bidang') ? "active" : "" }}">Bidang</a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/users') }}" class="nav-link {{ Request::is('master/users') ? "active" : "" }}">Users</a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/persediaan') }}" class="nav-link {{ Request::is('master/persediaan') ? "active" : "" }}">Persediaan</a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/aset-tetap') }}" class="nav-link {{ Request::is('master/aset-tetap') ? "active" : "" }}">Tanah</a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/aset-lainnya') }}" class="nav-link {{ Request::is('master/aset-lainnya') ? "active" : "" }}">Peralatan dan Mesin</a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/aset-lainnya') }}" class="nav-link {{ Request::is('master/aset-lainnya') ? "active" : "" }}">Gedung dan Bangunan</a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/aset-lainnya') }}" class="nav-link {{ Request::is('master/aset-lainnya') ? "active" : "" }}">Jalan, Irigasi dan Jaringan</a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/aset-lainnya') }}" class="nav-link {{ Request::is('master/aset-lainnya') ? "active" : "" }}">Aset Tetap Lainnya</a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/aset-lainnya') }}" class="nav-link {{ Request::is('master/aset-lainnya') ? "active" : "" }}">Konstruksi Dalam Pengerjaan</a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/aset-lainnya') }}" class="nav-link {{ Request::is('master/aset-lainnya') ? "active" : "" }}">Aset Tidak Berwujud</a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/aset-lainnya') }}" class="nav-link {{ Request::is('master/aset-lainnya') ? "active" : "" }}">Aset Lain-Lain</a>
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
                <a href="{{ url('/pembukuan/saldo-awal') }}" class="nav-link {{ Request::is('pembukuan/saldo-awal') ? "active" : "" }}">Saldo Awal</a>
              </li>
              <li class="nav-item">
                <a 
                  href="#pembukuanPerolehan" 
                  class="nav-link {{ Request::is('persediaan/perolehan') ? "active" : "" }}"
                  data-bs-toggle="collapse" 
                  role="button" 
                  aria-expanded="false" 
                  aria-controls="pembukuanPerolehan"
                >Perolehan / Penerimaan</a>
                <div id="pembukuanPerolehan" class="menu-dropdown collapse">
                  <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                      <a href="{{ url('pembukuan/perolehan/pengadaan') }}" class="nav-link">Pengadaan</a>
                    </li>
                    <li class="nav-item">
                      <a href="{{ url('pembukuan/perolehan/hibah') }}" class="nav-link">Hibah</a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a href="{{ url('pembukuan/reklasifikasi') }}" class="nav-link">Reklasifikasi</a>
              </li>
              <li class="nav-item">
                <a href="{{ url('pembukuan/koreksi') }}" class="nav-link">Koreksi</a>
              </li>
              <li class="nav-item">
                <a href="{{ url('pembukuan/penghapusan') }}" class="nav-link">Penghapusan</a>
              </li>
              <li class="nav-item">
                <a href="{{ url('pembukuan/stock-opname') }}" class="nav-link {{ Request::is('pembukuan/stock-opname') ? "active" : "" }}">Stock Opname</a>
              </li>
            </ul>
          </div>
        </li>

        <li class="nav-item">
          <a 
            class="nav-link menu-link {{ Request::is('persediaan*') ? "collapsed active" : "" }}" 
            href="#persediaan" 
            data-bs-toggle="collapse" 
            role="button" 
            aria-expanded="false" 
            aria-controls="persediaan"
          >
            <i class="ri-gps-line"></i> <span>Penyaluran Persediaan</span>
          </a>
          <div id="persediaan" class="menu-dropdown collapse {{ Request::is('persediaan*') ? "show" : "" }}">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <a href="{{ url('persediaan/nota-permintaan') }}" class="nav-link {{ Request::is('persediaan/nota-permintaan') ? "active" : "" }}">Nota Permintaan</a>
              </li>
              <li class="nav-item">
                <a href="{{ url('persediaan/spb') }}" class="nav-link {{ Request::is('persediaan/spb') ? "active" : "" }}">Surat Permintaan Barang</a>
              </li>
              <li class="nav-item">
                <a href="{{ url('persediaan/sppb') }}" class="nav-link {{ Request::is('persediaan/sppb') ? "active" : "" }}">Surat Perintah Penyaluran Barang</a>
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
          <a class="nav-link menu-link" href="#report" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="report">
          <i class="ri-file-list-3-line"></i> <span>Pelaporan</span>
          </a>
          <div class="collapse menu-dropdown" id="report">
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
                <div id="laporanPerolehan" class="menu-dropdown collapse">
                  <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                      <a href="{{ url('laporan/perolehan/pengadaan') }}" class="nav-link">Pengadaan</a>
                    </li>
                    <li class="nav-item">
                      <a href="{{ url('laporan/perolehan/hibah') }}" class="nav-link">Hibah</a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a href="{{ url('/laporan/periode-sewa') }}" class="nav-link">Laporan Reklasifikasi</a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/laporan/penyewa') }}" class="nav-link">Laporan Koreksi</a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/laporan/penyewa') }}" class="nav-link">Laporan Penghapusan</a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/laporan/penyewa') }}" class="nav-link">Laporan Stock Opname</a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/laporan/penyewa') }}" class="nav-link">Laporan Penyaluran</a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/laporan/penyewa') }}" class="nav-link">Laporan Persediaan Rusak</a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/laporan/penyewa') }}" class="nav-link">Kartu Barang Persediaan</a>
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