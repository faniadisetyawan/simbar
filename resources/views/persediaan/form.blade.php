@extends('layouts.app')

@section('page-title', 'Master - Form Persediaan')

@push('styles')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">Form Master Persediaan</h4>
        <div class="page-title-right">
          <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
              <a href="{{ url('/master/persediaan') }}">Master Persediaan</a>
            </li>
            <li class="breadcrumb-item active">Form</li>
          </ol>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <form action="{{ url('/master/persediaan') }}" class="needs-validation" method="post" novalidate>
        @csrf

        <div class="card">
          <div class="card-header">
            <h4 class="card-title mb-0 flex-grow-1">Form Master Persediaan</h4>
          </div>
          <div class="card-body">
            <div class="form-group mb-3">
              <label class="form-label">Kode Barang</label>
              <select name="kode_barang" id="select2" required>
                <option></option>
                @foreach ($kodefikasi as $item)
                  <optgroup label="{{ $item['kode'] . ' ' . $item['uraian'] }}">
                    @foreach ($item['sub_sub_rincian_objek'] as $sub)
                      <option value="{{ $sub['kode'] }}" @if (old('kode_barang') === $sub['kode']) selected @endif>
                        {{ $sub['kode'] . ' ' . $sub['uraian'] }}
                      </option>
                    @endforeach
                  </optgroup>
                @endforeach
              </select>
            </div>
            <div class="form-group mb-3">
              <label class="form-label">Spesifikasi Nama Barang</label>
              <input type="text" name="nama_barang" class="form-control" value="{{ old('nama_barang') }}" required />
            </div>
            <div class="form-group mb-3">
              <label class="form-label">Spesifikasi</label>
              <textarea name="spesifikasi" class="form-control" rows="3">{{ old('spesifikasi') }}</textarea>
            </div>
            <div class="form-group mb-3">
              <label class="form-label">Satuan Barang</label>
              <input type="text" name="satuan" class="form-control" value="{{ old('satuan') }}" required />
            </div>
          </div>

          <div class="card-footer">
            <div class="hstack gap-2 justify-content-end">
              <button type="submit" class="btn btn-success waves-effect">Submit</button>
              <a href="{{ url('/master/persediaan') }}" class="btn btn-light waves-effect">Kembali</a>
            </div>
          </div>
        </div>
      </form>
    </div>
@endsection

@push('scripts')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    $('#select2').select2({
      placeholder: 'Select'
    });
  </script>
  <script src="{{ asset('assets/js/pages/form-validation.init.js') }}"></script>
@endpush
