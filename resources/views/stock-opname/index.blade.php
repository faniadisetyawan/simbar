@extends('layouts.app')

@section('page-title', 'Pembukuan - ' . $pageTitle)

@section('content')
  <div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">{{ $pageTitle }}</h4>
    <div class="page-title-right">
      <ol class="breadcrumb m-0">
        <li class="breadcrumb-item">
          <a href="javascript: void(0);">Pembukuan</a>
        </li>
        <li class="breadcrumb-item active">{{ $pageTitle }}</li>
      </ol>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="row g-4 align-items-center">
        <div class="col-sm">
          <div>
            <h5 class="card-title mb-0">Daftar {{ $pageTitle }}</h5>
          </div>
        </div>
        <div class="col-sm-auto">
          <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('pembukuan.penghapusan.create') }}" class="btn btn-success add-btn waves-effect">
              <i class="ri-add-line align-bottom me-1"></i> Tambah
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="card-body bg-light-subtle">
      <form action="{{ Request::fullUrlWithQuery(['search' => $filter->search]) }}" method="GET">
        <div class="row g-3">
          <div class="col-12">
            <div class="search-box">
              <input 
                type="search" 
                name="search"
                value="{{ $filter->search }}"
                class="form-control search bg-light border-light" 
                placeholder="Cari berdasarkan kode barang, spesifikasi nama barang, NUSP, dan lainnya..." 
              />
              <i class="ri-search-line search-icon"></i>
            </div>
          </div>
          <div class="col-sm-8">
            <div class="search-box">
              <input 
                type="text" 
                name="date_range" 
                class="form-control bg-light border-light" 
                data-provider="flatpickr" 
                data-date-format="Y-m-d" 
                data-range-date="true"
                placeholder="Select date range" 
              />
              <i class="ri-calendar-line search-icon"></i>
            </div>
          </div>
          <div class="col-6 col-lg-2">
            <div>
              <button type="submit" class="btn btn-primary waves-effect w-100"> 
                <i class="ri-equalizer-fill me-1 align-bottom"></i> Filters
              </button>
            </div>
          </div>
          <div class="col-6 col-lg-2">
            <div>
              <a href="{{ route('pembukuan.penghapusan.index') }}" class="btn btn-soft-danger waves-effect w-100"> 
                <i class="ri-filter-off-fill me-1 align-bottom"></i> Reset
              </a>
            </div>
          </div>
        </div>
      </form>
    </div>

    <div class="card-body">
      <div class="table-responsive table-card mb-1">
        <table class="table align-middle">
          <thead class="table-light">
            <tr>
              <th scope="col" class="text-center">Action</th>
              <th scope="col">Kode Barang</th>
              <th scope="col">Nama Barang</th>
              <th scope="col">NUSP</th>
              <th scope="col">Spesifikasi Nama Barang</th>
              <th scope="col">Spesifikasi Lainnya</th>
              <th scope="col" class="text-end">Jumlah Barang</th>
              <th scope="col">Satuan</th>
              <th scope="col">Keterangan</th>
              <th scope="col">Tgl. Pembukuan</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>

        @if (count($data) === 0)
          @include('components.empty-record')
        @endif
      </div>
    </div>
  </div>
@endsection