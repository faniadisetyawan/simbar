@extends('layouts.app')

@section('page-title', 'Pembukuan - Saldo Awal - ' . $pageTitle . ' - Form')

@push('styles')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">{{ $pageTitle }}</h4>
        <div class="page-title-right">
          <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
              <a href="javascript: void(0);">Saldo Awal</a>
            </li>
            <li class="breadcrumb-item">
              <a href="{{ route('pembukuan.saldo-awal.index', $slug) }}">{{ $pageTitle }}</a>
            </li>
            <li class="breadcrumb-item active">Form</li>
          </ol>
        </div>
      </div>
    </div>

    <div class="col-6">
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
                <h5 class="card-title mb-0">Form Saldo Awal {{ $pageTitle }}</h5>
              </div>
            </div>
          </div>
        </div>

        <form action="{{ route('pembukuan.saldo-awal.store') }}" method="post">
          @csrf

          <div class="card-body">
            <div class="form-group mb-3">
              <label class="form-label">Pilih Barang <code>*</code></label>
              <select name="barang_id" class="form-control js-example-basic-single">
                @foreach ($appMasterPersediaan as $group)
                <optgroup label="{{ $group['key'] }}">
                  @foreach ($group['data'] as $item)
                  <option value="{{ $item['id'] }}" @if(old('barang_id', $props['barang_id']) === $item['id']) @endif>
                    {{ $item['kode_barang'] . '.' . $item['kode_register']. ' ' . $item['nama_barang'] . ' ' . $item['spesifikasi'] }}
                  </option>
                  @endforeach
                </optgroup>
                @endforeach
              </select>
              @error('barang_id')
              <div class="form-text text-danger">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-group mb-3">
              <label class="form-label">Jumlah Barang <code>*</code></label>
              <input type="number" name="jumlah_barang" class="form-control" value="{{ old('jumlah_barang', $props['jumlah_barang']) }}" />
              @error('jumlah_barang')
              <div class="form-text text-danger">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-group mb-3">
              <label class="form-label">Nilai Perolehan <code>*</code></label>
              <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="number" name="nilai_perolehan" class="form-control" value="{{ old('nilai_perolehan', $props['nilai_perolehan']) }}" />
              </div>
              @error('nilai_perolehan')
              <div class="form-text text-danger">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-group mb-3">
              <label class="form-label">Keterangan</label>
              <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $props['keterangan']) }}</textarea>
            </div>
          </div>

          <div class="card-footer">
            <div class="d-flex flex-wrap gap-2 justify-content-end">
              <button type="submit" class="btn btn-success waves-effect">Submit</button>
              <a href="{{ route('pembukuan.saldo-awal.index', $slug) }}" class="btn btn-light waves-effect">Kembali</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="{{ asset('assets/js/pages/select2.init.js') }}"></script>
@endpush