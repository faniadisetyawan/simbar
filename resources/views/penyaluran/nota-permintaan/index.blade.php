@extends('layouts.app')

@section('page-title', 'Penyaluran Persediaan - ' . $pageTitle)

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">{{ $pageTitle }}</h4>
        <div class="page-title-right">
          <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
              <a href="javascript: void(0);">Penyaluran</a>
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
                <h5 class="card-title mb-0">Daftar {{ $pageTitle }}</h5>
              </div>
            </div>
            <div class="col-sm-auto">
              <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('penyaluran.nota-permintaan.create') }}" class="btn btn-success add-btn waves-effect">
                  <i class="ri-add-line align-bottom me-1"></i> Tambah
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body bg-light-subtle">
          <form action="{{ Request::fullUrlWithQuery(['search' => $filter['search']]) }}" method="GET">
            <div class="row g-3">
              <div class="col-12">
                <div class="search-box">
                  <input 
                    type="search" 
                    name="search"
                    value="{{ $filter['search'] }}"
                    class="form-control search bg-light border-light" 
                    placeholder="Cari berdasarkan kode barang, spesifikasi nama barang, NUSP, dan lainnya..." 
                  />
                  <i class="ri-search-line search-icon"></i>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="search-box">
                  <input 
                    type="text" 
                    name="date_range" 
                    class="form-control bg-light border-light" 
                    data-provider="flatpickr" 
                    data-date-format="Y-m-d" 
                    data-range-date="true"
                    {{-- data-deafult-date="{{ '2023-01-01 to ' . date('Y-m-d') }}"  --}}
                    placeholder="Select date range" 
                  />
                  <i class="ri-calendar-line search-icon"></i>
                </div>
              </div>
              <div class="col-sm-4">
                <div>
                  <select name="bidang_id" class="form-select bg-light border-light">
                    <option value="">Select</option>
                    @foreach ($appBidang as $item)
                    <option value="{{ $item->id }}" @if($filter['bidang_id'] == $item['id']) selected @endif>{{ $item->id . '. ' . $item->nama }}</option>
                    @endforeach
                  </select>
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
                  <a href="{{ route('penyaluran.nota-permintaan.index') }}" class="btn btn-soft-danger waves-effect w-100"> 
                    <i class="ri-filter-off-fill me-1 align-bottom"></i> Reset
                  </a>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection