@extends('layouts.app')

@section('page-title', 'Penyaluran Persediaan - ' . $pageTitle . ' - Form')

@push('styles')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">Form - {{ $pageTitle }}</h4>
        <div class="page-title-right">
          <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
              <a href="javascript: void(0);">Penyaluran Persediaan</a>
            </li>
            <li class="breadcrumb-item">
              <a href="{{ route('penyaluran.nota-permintaan.index') }}">{{ $pageTitle }}</a>
            </li>
            <li class="breadcrumb-item active">Create</li>
          </ol>
        </div>
      </div>
    </div>

    <div class="col-12">
      <form action="{{ route('penyaluran.nota-permintaan.store') }}" method="POST">
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
                    <li>Surat Permintaan Barang (SPB) ini menjadi dasar pembuatan Surat Perintah Penyaluran Barang (SPPB) dari Pejabat Penatausahaan Barang.</li>
                    <li>Semua inputan dengan label <code>*</code> harus diisi.</li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-4 col-form-label">Pilih Nota Permintaan <code>*</code></label>
              <div class="col-sm-8">
                <select name="bidang_id" class="form-control js-example-basic-single">
                  <option></option>
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

            <div class="border mt-5 mb-3 border-dashed"></div>

            <div class="mb-3 alert alert-primary alert-border-left" role="alert">Kelengkapan Dokumen {{ $pageTitle }}</div>
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

            <div class="mb-3 alert alert-primary alert-border-left" role="alert">Rincian {{ $pageTitle }}</div>
            <div class="row mb-3">
              <label class="col-sm-4 col-form-label">Pilih Barang <code>*</code></label>
              <div class="col-sm-8">
                <select name="barang_id" class="form-select">
                  <option></option>
                </select>
                @error('barang_id')
                <div class="form-text text-danger">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-4 col-form-label">Jumlah Barang <code>*</code></label>
              <div class="col-sm-8">
                <input type="number" name="jumlah_barang_permintaan" class="form-control" value="{{ old('jumlah_barang_permintaan') }}" min="0" />
                @error('jumlah_barang_permintaan')
                <div class="form-text text-danger">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-4 col-form-label">Keperluan <code>*</code></label>
              <div class="col-sm-8">
                <textarea name="keperluan" class="form-control" rows="3">{{ old('keperluan') }}</textarea>
                @error('keperluan')
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
              <a href="{{ route('penyaluran.nota-permintaan.index') }}" class="btn btn-light waves-effect">Kembali</a>
              <button type="submit" class="btn btn-success waves-effect">Submit</button>
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
  <script src="{{ asset('assets/js/pages/form-masks.init.js') }}"></script>
  <script>
    jQuery(function () {
      const elemTglPembukuan = jQuery('[name="tgl_pembukuan"]');
      const elemSelectBarang = jQuery('select[name="barang_id"]');

      let filter = elemTglPembukuan.val();
      elemTglPembukuan.change(function (e) {
        elemSelectBarang.val(null).change();
        filter = e.target.value;
      });

      elemSelectBarang.select2({
        placeholder: "Select...",
        ajax: {
          url: '/api/master/persediaan/available-stock',
          dataType: 'json',
          data: (params) => {
            return {
              search: params.term,
              tgl_pembukuan: filter
            }
          },
          processResults: (response) => {
            response.map((item) => Object.assign(item, { text: `${item.kode_barang}.${item.kode_register} ${item.nama_barang} ${item.spesifikasi || ''}, Stok: ${item.jumlah_barang} ${item.satuan}` }));

            return {
              results: response
            }
          },
        },
      });
    });
  </script>
@endpush
