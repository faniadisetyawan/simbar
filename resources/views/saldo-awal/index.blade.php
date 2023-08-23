@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">{{ $pageTitle }}</h4>
        <div class="page-title-right">
          <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
              <a href="javascript: void(0);">Pembukuan</a>
            </li>
            <li class="breadcrumb-item">
              <a href="javascript: void(0);">Saldo Awal</a>
            </li>
            <li class="breadcrumb-item active">{{ $pageTitle }}</li>
          </ol>
        </div>
      </div>
    </div>

    <div class="col-12">
      @if (session()->has('success'))
      <div class="alert alert-success alert-border-left alert-dismissible fade show" role="alert">
        <i class="ri-check-double-line me-3 align-middle fs-16"></i><strong>Success</strong>
        - {{ session()->get('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif

      <div class="card">
        <div class="card-header">
          <div class="row g-4 align-items-center">
            <div class="col-sm">
              <div>
                <h5 class="card-title mb-0">Saldo Awal {{ $pageTitle }}</h5>
              </div>
            </div>
            @if (auth()->user()->role_id !== 4)
              <div class="col-sm-auto">
                <div class="d-flex flex-wrap align-items-start gap-2">
                  @if ($slug == 'persediaan')
                    <a href="{{ route('pembukuan.saldo-awal.create', $slug) }}" class="btn btn-success add-btn waves-effect">
                      <i class="ri-add-line align-bottom me-1"></i> Tambah
                    </a>
                  @endif

                  @if ($slug !== 'aset-lain')
                    <button type="button" class="btn btn-info waves-effect" data-bs-toggle="modal" data-bs-target="#importModal">
                      <i class="ri-upload-line align-bottom me-1"></i> Import
                    </button>
                  @endif
                </div>
              </div>
            @endif
          </div>
        </div>

        <div class="card-body bg-light-subtle">
          <form action="{{ Request::fullUrlWithQuery(['search' => $filter['search']]) }}" method="GET">
            <div class="search-box">
              <input 
                type="search" 
                name="search"
                value="{{ $filter['search'] }}"
                class="form-control search bg-light border-light" 
                placeholder="Cari berdasarkan kode barang, kode register, nama barang, spesifikasi, dan lainnya..." 
              />
              <i class="ri-search-line search-icon"></i>
            </div>
          </form>
        </div>

        <div class="card-body">
          <div class="table-responsive table-card mb-1">
            @switch($slug)
              @case("persediaan")
                @include('components.tabel-barang.persediaan', ['data' => $data])
                @break
              @case("tanah")
                @include('components.tabel-barang.tanah', ['data' => $data])
                @break
              @case("peralatan-mesin")
                @include('components.tabel-barang.peralatan-mesin', ['data' => $data])
                @break
              @case("gedung-bangunan")
                @include('components.tabel-barang.gedung-bangunan', ['data' => $data])
                @break
              @case("jij")
                @include('components.tabel-barang.jij', ['data' => $data])
                @break
              @case("atl")
                @include('components.tabel-barang.atl', ['data' => $data])
                @break
              @case("kdp")
                @include('components.tabel-barang.kdp', ['data' => $data])
                @break
              @case("atb")
                @include('components.tabel-barang.atb', ['data' => $data])
                @break
              @case("aset-lain")
                @include('components.tabel-barang.aset-lain', ['data' => $data])
                @break
              @default
                @include('components.tabel-barang.tanah', ['data' => $data])
            @endswitch

            @if (count(isset($data) ? $data : []) === 0)
              @include('components.empty-record')
            @endif
          </div>

          @if (isset($paginator))
          <div class="d-flex justify-content-end">
            {{ $paginator->withQueryString()->links() }}
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade zoomIn" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0">
        <div class="modal-header p-3 bg-info-subtle">
          <h5 class="modal-title" id="exampleModalLabel">Import Saldo Awal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>

        <form action="{{ route('pembukuan.saldo-awal.import') }}" method="post" enctype="multipart/form-data" class="tablelist-form" autocomplete="off">
          @csrf
          <input type="hidden" name="slug" value="{{ $slug }}" />

          <div class="modal-body">
            <div data-simplebar style="max-height: 250px;" class="mb-5 list-group">
              <a href="{{ asset('templates/template-persediaan-saldo_awal.xlsx') }}" target="_blank" rel="noopener noreferrer" class="list-group-item list-group-item-action list-group-item-light">
                <div class="d-flex">
                  <div class="flex-shrink-0 avatar-xs">
                    <div class="avatar-title bg-success-subtle text-success rounded">
                      <i class="ri-file-excel-2-fill"></i>
                    </div>
                  </div>
                  <div class="flex-shrink-0 ms-2">
                    <h6 class="fs-14 mb-0">Download template Persediaan</h6>
                    <small class="text-muted">File Excel</small>
                  </div>
                </div>
              </a>
              <a href="{{ asset('templates/template-tanah-saldo_awal.xlsx') }}" target="_blank" rel="noopener noreferrer" class="list-group-item list-group-item-action list-group-item-light">
                <div class="d-flex">
                  <div class="flex-shrink-0 avatar-xs">
                    <div class="avatar-title bg-success-subtle text-success rounded">
                      <i class="ri-file-excel-2-fill"></i>
                    </div>
                  </div>
                  <div class="flex-shrink-0 ms-2">
                    <h6 class="fs-14 mb-0">Download template Tanah</h6>
                    <small class="text-muted">File Excel</small>
                  </div>
                </div>
              </a>
              <a href="{{ asset('templates/template-peralatan_mesin-saldo_awal.xlsx') }}" target="_blank" rel="noopener noreferrer" class="list-group-item list-group-item-action list-group-item-light">
                <div class="d-flex">
                  <div class="flex-shrink-0 avatar-xs">
                    <div class="avatar-title bg-success-subtle text-success rounded">
                      <i class="ri-file-excel-2-fill"></i>
                    </div>
                  </div>
                  <div class="flex-shrink-0 ms-2">
                    <h6 class="fs-14 mb-0">Download template Peralatan dan Mesin</h6>
                    <small class="text-muted">File Excel</small>
                  </div>
                </div>
              </a>
              <a href="{{ asset('templates/template-gedung_bangunan-saldo_awal.xlsx') }}" target="_blank" rel="noopener noreferrer" class="list-group-item list-group-item-action list-group-item-light">
                <div class="d-flex">
                  <div class="flex-shrink-0 avatar-xs">
                    <div class="avatar-title bg-success-subtle text-success rounded">
                      <i class="ri-file-excel-2-fill"></i>
                    </div>
                  </div>
                  <div class="flex-shrink-0 ms-2">
                    <h6 class="fs-14 mb-0">Download template Gedung dan Bangunan</h6>
                    <small class="text-muted">File Excel</small>
                  </div>
                </div>
              </a>
              <a href="{{ asset('templates/template-jij-saldo_awal.xlsx') }}" target="_blank" rel="noopener noreferrer" class="list-group-item list-group-item-action list-group-item-light">
                <div class="d-flex">
                  <div class="flex-shrink-0 avatar-xs">
                    <div class="avatar-title bg-success-subtle text-success rounded">
                      <i class="ri-file-excel-2-fill"></i>
                    </div>
                  </div>
                  <div class="flex-shrink-0 ms-2">
                    <h6 class="fs-14 mb-0">Download template Jalan, Irigasi dan Jaringan</h6>
                    <small class="text-muted">File Excel</small>
                  </div>
                </div>
              </a>
              <a href="{{ asset('templates/template-atl-saldo_awal.xlsx') }}" target="_blank" rel="noopener noreferrer" class="list-group-item list-group-item-action list-group-item-light">
                <div class="d-flex">
                  <div class="flex-shrink-0 avatar-xs">
                    <div class="avatar-title bg-success-subtle text-success rounded">
                      <i class="ri-file-excel-2-fill"></i>
                    </div>
                  </div>
                  <div class="flex-shrink-0 ms-2">
                    <h6 class="fs-14 mb-0">Download template Aset Tetap Lainnya</h6>
                    <small class="text-muted">File Excel</small>
                  </div>
                </div>
              </a>
              <a href="{{ asset('templates/template-kdp-saldo_awal.xlsx') }}" target="_blank" rel="noopener noreferrer" class="list-group-item list-group-item-action list-group-item-light">
                <div class="d-flex">
                  <div class="flex-shrink-0 avatar-xs">
                    <div class="avatar-title bg-success-subtle text-success rounded">
                      <i class="ri-file-excel-2-fill"></i>
                    </div>
                  </div>
                  <div class="flex-shrink-0 ms-2">
                    <h6 class="fs-14 mb-0">Download template Konstruksi Dalam Pengerjaan</h6>
                    <small class="text-muted">File Excel</small>
                  </div>
                </div>
              </a>
              <a href="{{ asset('templates/template-atb-saldo_awal.xlsx') }}" target="_blank" rel="noopener noreferrer" class="list-group-item list-group-item-action list-group-item-light">
                <div class="d-flex">
                  <div class="flex-shrink-0 avatar-xs">
                    <div class="avatar-title bg-success-subtle text-success rounded">
                      <i class="ri-file-excel-2-fill"></i>
                    </div>
                  </div>
                  <div class="flex-shrink-0 ms-2">
                    <h6 class="fs-14 mb-0">Download template Aset Tidak Berwujud</h6>
                    <small class="text-muted">File Excel</small>
                  </div>
                </div>
              </a>
            </div>

            <div class="form-group">
              <label class="form-label">Pilih file :</label>
              <input type="file" name="document" class="form-control form-control-lg" required />
            </div>
          </div>
          <div class="modal-footer">
            <div class="hstack gap-2 justify-content-end">
              <button type="button" class="btn btn-light" id="close-modal" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection