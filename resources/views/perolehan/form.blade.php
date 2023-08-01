@extends('layouts.app')

@section('page-title', 'Pembukuan - Perolehan - ' . $pageTitle)

@push('styles')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">Form {{ $pageTitle }}</h4>
        <div class="page-title-right">
          <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
              <a href="javascript: void(0);">Pembukuan</a>
            </li>
            <li class="breadcrumb-item">
              <a href="javascript: void(0);">Perolehan</a>
            </li>
            <li class="breadcrumb-item">
              <a href="{{ route('pembukuan.perolehan.index', $slug) }}">{{ $pageTitle }}</a>
            </li>
            <li class="breadcrumb-item active">Form</li>
          </ol>
        </div>
      </div>
    </div>

    <div class="col-12">
      <form action="{{ route('pembukuan.perolehan.store', $slug) }}" method="POST">
        @csrf
        <div class="card">
          <div class="card-header">
            <h4 class="card-title mb-0 flex-grow-1">Form {{ $pageTitle }}</h4>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <div class="mb-5 alert alert-primary alert-border-left" role="alert">
                  <ul class="mb-0">
                    <li>Sumber data bisa diperoleh dari Nota Pembelian, Surat Pesanan, Kwitansi, Berita Acara Serah Terima (BAST), atau Surat Perintah Kerja (SPK)</li>
                    <li>Semua inputan dengan label <code>*</code> harus diisi.</li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="mb-3">
              <div class="row mb-3">
                <label class="col-sm-4 col-form-label">Pilih Bidang <code>*</code></label>
                <div class="col-sm-8">
                  <select name="bidang_id" class="form-control js-example-basic-single">
                    <option></option>
                    @foreach ($appBidang as $item)
                      <option value="{{ $item['id'] }}" @if(old('bidang_id', auth()->user()->bidang_id) == $item['id']) selected @endif>{{ $item['id']. '. ' . $item['nama'] }}</option>
                    @endforeach
                  </select>
                </div>
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

            <div class="mb-3 alert alert-primary alert-border-left" role="alert">Informasi Rincian Belanja</div>
            <div class="row mb-3">
              <label class="col-sm-4 col-form-label">Pilih Barang <code>*</code></label>
              <div class="col-sm-8">
                <select name="barang_id" class="form-control js-example-basic-single">
                  <option></option>
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
            </div>
            <div class="row mb-3">
              <label class="col-sm-4 col-form-label">Jumlah Barang <code>*</code></label>
              <div class="col-sm-8">
                <input type="number" name="jumlah_barang" class="form-control" value="{{ old('jumlah_barang') }}" min="0" />
                @error('jumlah_barang')
                <div class="form-text text-danger">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-4 col-form-label">Nilai Perolehan <code>*</code></label>
              <div class="col-sm-8">
                <div class="input-group">
                  <span class="input-group-text">Rp</span>
                  <input type="text" id="cleaveNilaiPerolehan" class="form-control" value="{{ old('nilai_perolehan') }}" min="0" />
                  <input type="hidden" name="nilai_perolehan" class="form-control" />
                </div>
                @error('nilai_perolehan')
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
              <button type="submit" class="btn btn-success waves-effect">Submit</button>
              <a href="{{ route('pembukuan.perolehan.index', $slug) }}" class="btn btn-light waves-effect">Kembali</a>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="{{ asset('assets/js/pages/select2.init.js') }}"></script>
  <script src="{{ asset('assets/libs/cleave.js/cleave.min.js') }}"></script>
  <script src="{{ asset('assets/js/pages/form-masks.init.js') }}"></script>
  <script>
    $(function () {
      let cleaveNilaiPerolehan = new Cleave('#cleaveNilaiPerolehan', {
        numeral: true,
        numeralDecimalMark: ",",
        delimiter: ".",
      });

      $('#cleaveNilaiPerolehan').change(function (e) {
        $('[name="nilai_perolehan"]').val(cleaveNilaiPerolehan.getRawValue());
      });
    });
  </script>
@endpush
