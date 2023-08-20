@extends('layouts.app')

@section('page-title', 'Pembukuan - ' . $pageTitle)

@push('styles')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
  <div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">{{ $pageTitle }}</h4>
    <div class="page-title-right">
      <ol class="breadcrumb m-0">
        <li class="breadcrumb-item">
          <a href="javascript: void(0);">Pembukuan</a>
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('pembukuan.penghapusan.index') }}">{{ $pageTitle }}</a>
        </li>
        <li class="breadcrumb-item active">Create</li>
      </ol>
    </div>
  </div>

  @if($errors->any())
    {!! implode('', $errors->all('
      <div class="alert alert-danger alert-border-left alert-dismissible fade show" role="alert">
        <i class="ri-error-warning-line me-3 align-middle fs-16"></i><strong>Error</strong>
        - :message
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    ')) !!}
  @endif

  <form action="{{ route('pembukuan.penghapusan.store') }}" method="post">
    @csrf

    <div class="card">
      <div class="card-header">
        <h4 class="card-title mb-0 flex-grow-1">Form {{ $pageTitle }}</h4>
      </div>
      <div class="card-body">
        <div class="mb-5 alert alert-primary alert-border-left" role="alert">
          <ul class="mb-0">
            <li>Semua inputan dengan label <code>*</code> harus diisi.</li>
          </ul>
        </div>
  
        <div class="row mb-3">
          <label class="col-sm-4 col-form-label">Tanggal Pembukuan <code>*</code></label>
          <div class="col-sm-8">
            <div class="input-group">
              <span class="input-group-text">
                <i class="ri-calendar-line"></i>
              </span>
              <input type="text" name="tgl_pembukuan" class="form-control" data-provider="flatpickr" data-date-format="Y-m-d" data-deafult-date="{{ old('tgl_pembukuan', date('Y-m-d')) }}" />
            </div>
            <div class="form-text">
              <ul>
                <li>Tanggal pembukuan secara default menampilkan tanggal hari ini. Anda bisa mengubah tanggal sesuai keperluan.</li>
                <li>Namun perlu diketahui bahwa tanggal pembukuan ini merupakan parameter hasil pelaporan periodik.</li>
              </ul>
            </div>
          </div>
        </div>
  
        <div class="border mt-5 mb-3 border-dashed"></div>
  
        <div class="mb-3 alert alert-primary alert-border-left" role="alert">Informasi Dokumen Sumber</div>
        <div class="row mb-3">
          <label class="col-sm-4 col-form-label">Jenis Dokumen <code>*</code></label>
          <div class="col-sm-8">
            <select name="kode_jenis_dokumen" class="form-control js-example-basic-single">
              <option></option>
              @foreach ($appJenisDokumen as $item)
                <option value="{{ $item['kode'] }}" @if(old('kode_jenis_dokumen') == $item['kode']) selected @endif>{{ $item['kode']. '. ' . $item['nama'] }}</option>
              @endforeach
            </select>
            @error('kode_jenis_dokumen')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-sm-4 col-form-label">Nomor Dokumen <code>*</code></label>
          <div class="col-sm-8">
            <input type="text" name="no_dokumen" class="form-control" value="{{ old('no_dokumen') }}" />
            @error('no_dokumen')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-sm-4 col-form-label">Tanggal Dokumen <code>*</code></label>
          <div class="col-sm-8">
            <div class="input-group">
              <span class="input-group-text">
                <i class="ri-calendar-line"></i>
              </span>
              <input type="text" name="tgl_dokumen" class="form-control" data-provider="flatpickr" data-date-format="Y-m-d" data-deafult-date="{{ old('tgl_dokumen') }}" />
            </div>
            @error('tgl_dokumen')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-sm-4 col-form-label">Uraian Dokumen</label>
          <div class="col-sm-8">
            <textarea name="uraian_dokumen" class="form-control" rows="3">{{ old('uraian_dokumen') }}</textarea>
          </div>
        </div>
  
        <div class="border mt-5 mb-3 border-dashed"></div>
  
        <div class="mb-3 alert alert-primary alert-border-left" role="alert">Informasi Barang</div>
        <div class="row mb-3">
          <label class="col-sm-4 col-form-label">Pilih Barang <code>*</code></label>
          <div class="col-sm-8">
            <select name="barang_id" class="form-control js-example-basic-single">
              <option></option>
              @foreach ($appPersediaanHasStok as $group)
              <optgroup label="{{ $group->key->kode . ' ' . $group->key->uraian }}">
                @foreach ($group->data as $item)
                <option value="{{ $item->id }}" @if(old('barang_id', $props['barang_id']) == $item->id) selected @endif>
                  {{ $item->kode_register . ' ' . $item->nama_barang . ' ' . $item->spesifikasi . ', Stok : ' . $item->stok . ' ' . $item->satuan }}
                </option>
                @endforeach
              </optgroup>
              @endforeach
            </select>
            @error('barang_id')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-sm-4 col-form-label">Jumlah Barang <code>*</code></label>
          <div class="col-sm-8">
            <input type="number" name="jumlah_barang" class="form-control" value="{{ old('jumlah_barang') }}" min="1" />
            <div class="form-text">Masukkan jumlah barang yang dihapus.</div>
            @error('jumlah_barang')
              <div class="form-text text-danger">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-sm-4 col-form-label">Keterangan</label>
          <div class="col-sm-8">
            <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
          </div>
        </div>
      </div>
      <div class="card-footer">
        <div class="hstack gap-2 justify-content-end">
          <a href="{{ route('pembukuan.penghapusan.index') }}" class="btn btn-light waves-effect">Kembali</a>
          <button type="submit" class="btn btn-success waves-effect">Submit</button>
        </div>
      </div>
    </div>
  </form>
@endsection

@push('scripts')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="{{ asset('assets/js/pages/select2.init.js') }}"></script>
@endpush