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
            <div class="col-sm-auto">
              <div class="d-flex flex-wrap align-items-start gap-2">
                <a href="{{ route('pembukuan.saldo-awal.create', $slug) }}" class="btn btn-success add-btn waves-effect">
                  <i class="ri-add-line align-bottom me-1"></i> Tambah
                </a>
                <button type="button" class="btn btn-info waves-effect" data-bs-toggle="modal" data-bs-target="#importModal">
                  <i class="ri-upload-line align-bottom me-1"></i> Import
                </button>
              </div>
            </div>
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
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th scope="col" class="text-center">Action</th>
                  <th scope="col" class="text-center">ID</th>
                  <th scope="col">Kode Barang</th>
                  <th scope="col">Nama Barang</th>
                  <th scope="col">NUSP</th>
                  <th scope="col">Spesifikasi Nama Barang</th>
                  <th scope="col">Spesifikasi Lainnya</th>
                  <th scope="col" class="text-center">Jumlah Barang</th>
                  <th scope="col">Satuan</th>
                  <th scope="col" class="text-end">Harga Satuan</th>
                  <th scope="col" class="text-end">Nilai Perolehan</th>
                  <th scope="col">Keterangan</th>
                  <th scope="col">Tgl. Entry</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data as $item)
                  <tr>
                    <td class="text-center">
                      <div class="dropdown">
                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="ri-more-fill align-middle"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                          <li>
                            <button class="dropdown-item">
                              <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>Edit
                            </button>
                          </li>
                          <li>
                            <button class="dropdown-item">
                              <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>Remove
                            </button>
                          </li>
                        </ul>
                      </div>
                    </td>
                    <td class="text-center">{{ $item['id'] }}</td>
                    <td>{{ $item['master_persediaan']['kode_barang'] }}</td>
                    <td>{{ $item['master_persediaan']['kodefikasi']['uraian'] }}</td>
                    <td>{{ $item['master_persediaan']['kode_register'] }}</td>
                    <td>{{ $item['master_persediaan']['nama_barang'] }}</td>
                    <td>{{ $item['master_persediaan']['spesifikasi'] }}</td>
                    <td class="text-center">{{ $item['jumlah_barang'] }}</td>
                    <td>{{ $item['master_persediaan']['satuan'] }}</td>
                    <td class="text-end">{{ number_format($item['harga_satuan'], 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($item['nilai_perolehan'], 2, ',', '.') }}</td>
                    <td>{{ $item['keterangan'] }}</td>
                    <td style="white-space: nowrap;">{{ date('F d, Y H:i', strtotime($item['created_at'])) }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>

            @if (count($data) === 0)
              @include('components.empty-record')
            @endif
          </div>

          <div class="d-flex justify-content-end">
            {{ $data->withQueryString()->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection