@extends('layouts.app')

@section('page-title', 'Pelaporan - ' . $pageTitle)

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
              <a href="javascript: void(0);">Pelaporan</a>
            </li>
            <li class="breadcrumb-item active">{{ $pageTitle }}</li>
          </ol>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title mb-0">Cetak {{ $pageTitle }}</h4>
        </div>
        <div class="card-body">
          <div class="row mb-3">
            <label class="col-sm-4 col-form-label">Per Tanggal <code>*</code></label>
            <div class="col-sm-8">
              <div class="input-group">
                <span class="input-group-text">
                  <i class="ri-calendar-line"></i>
                </span>
                <input type="text" name="tgl_pembukuan" class="form-control" data-provider="flatpickr" data-date-format="Y-m-d" data-deafult-date="{{ date('Y-m-d') }}" required />
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="text-end">
            <button type="button" class="btn btn-success btn-label waves-effect" onclick="printReport()">
              <i class="ri-printer-line label-icon align-middle fs-16 me-2"></i>Cetak
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="{{ asset('assets/js/pages/select2.init.js') }}"></script>
  <script>
    const printReport = () => {
      let tglPembukuan = $('[name="tgl_pembukuan"]').val();

      if (!!tglPembukuan) {
        window.open(`{{ route('laporan.mutasi-persediaan') }}?tgl_pembukuan=${tglPembukuan}`, '_blank');
      }
    }
  </script>
@endpush
