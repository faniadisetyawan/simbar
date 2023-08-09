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
              <a href="{{ route('penyaluran.sppb.index') }}">{{ "SPPB" }}</a>
            </li>
            <li class="breadcrumb-item active">Create</li>
          </ol>
        </div>
      </div>
    </div>

    @if($errors->any())
    <div class="col-12">
      {!! implode('', $errors->all('
        <div class="alert alert-danger alert-border-left alert-dismissible fade show" role="alert">
          <i class="ri-error-warning-line me-3 align-middle fs-16"></i><strong>Error</strong>
          - :message
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      ')) !!}
    </div>
    @endif

    <div class="col-12">
      <form action="{{ route('penyaluran.sppb.store') }}" method="POST">
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
                    <li>Surat Perintah Penyaluran Barang (SPPB) dari Pejabat Penatausahaan Barang.</li>
                    <li>Semua inputan dengan label <code>*</code> harus diisi.</li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-4 col-form-label">Tanggal Pembukuan <code>*</code></label>
              <div class="col-sm-8">
                <div class="input-group">
                  <span class="input-group-text">
                    <i class="ri-calendar-line"></i>
                  </span>
                  <input type="text" name="tgl_pembukuan" class="form-control" data-provider="flatpickr" data-date-format="Y-m-d" data-deafult-date="{{ old('tgl_pembukuan', $filter->tgl_pembukuan) }}" />
                </div>
                <div class="form-text">
                  <ul>
                    <li>Tanggal pembukuan secara default menampilkan tanggal hari ini. Anda bisa mengubah tanggal sesuai keperluan.</li>
                    <li>Namun perlu diketahui bahwa tanggal pembukuan ini merupakan parameter hasil pelaporan periodik.</li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-4 col-form-label">Surat Permintaan Barang <code>*</code></label>
              <div class="col-sm-8">
                <select name="usulan" class="form-control js-example-basic-single">
                  <option></option>
                  @foreach ($dokumenSumber as $item)
                  <option value="{{ $item->slug_dokumen }}" @if(isset($filter->usulan) && $filter->usulan == $item->slug_dokumen) selected @endif>
                    {{ $item->no_dokumen . ' . Tgl ' . date('d M, Y', strtotime($item->tgl_dokumen)) . ' . (' . $item->bidang->nama . ')' }}
                  </option>
                  @endforeach
                </select>
              </div>
            </div>

            @if (count($dokumenSumber) > 0 && count($dataUsulan) > 0)
            <div class="row mb-3">
              <div class="col-12">
                <div class="table-responsive">
                  <table class="table caption-top table-nowrap align-middle table-borderless mb-0">
                    <caption>
                      <div class="fw-bold">Rincian Surat Permintaan Barang</div>
                      <small class="d-block">Beri nilai <span class="text-danger">0 (nol)</span> untuk item yang <span class="text-danger">tidak disetujui</span>.</small>
                    </caption>
                    <thead class="table-light text-muted">
                      <tr>
                        <th scope="col" class="text-center">ID</th>
                        <th scope="col">Kodefikasi</th>
                        <th scope="col">Spesifikasi</th>
                        <th scope="col">Satuan</th>
                        <th scope="col" class="text-end">Jumlah Usulan (SPB)</th>
                        <th scope="col" class="text-end">Stok</th>
                        <th scope="col" class="text-end">Jumlah Disetujui</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($dataUsulan as $item)
                        <input type="hidden" name="parent_id[]" value="{{ $item->id }}" />
                        <input type="hidden" name="bidang_id[]" value="{{ $item->bidang_id }}" />
                        <input type="hidden" name="barang_id[]" value="{{ $item->barang_id }}" />
                        <input type="hidden" name="jumlah_barang_usulan[]" value="{{ $item->jumlah_barang_usulan }}" />
                        <input type="hidden" name="keperluan[]" value="{{ $item->keperluan }}" />
                        <input type="hidden" name="keterangan[]" value="{{ $item->keterangan }}" />

                        <tr>
                          <td class="text-center">{{ $item->id }}</td>
                          <td>
                            <h5 class="fs-15">{{ $item->master_persediaan->kode_barang }}</h5>
                            <p class="text-muted mb-0">NUSP: <span class="fw-medium">{{ $item->master_persediaan->kode_register }}</span></p>
                            <p class="text-muted mb-0">Nama: <span class="fw-medium">{{ $item->master_persediaan->kodefikasi->uraian }}</span></p>
                          </td>
                          <td>
                            <h5 class="fs-15">{{ $item->master_persediaan->nama_barang }}</h5>
                            <p class="text-muted mb-0">Spesifikasi: <span class="fw-medium">{{ $item->master_persediaan->spesifikasi }}</span></p>
                          </td>
                          <td>{{ $item->master_persediaan->satuan }}</td>
                          <td class="text-end">{{ $item->jumlah_barang_usulan }}</td>
                          <td class="text-end">{{ $item->jumlah_barang_sisa }}</td>
                          <td class="text-end">
                            <div class="input-step">
                              <button type="button" class="minus">–</button>
                              <input type="number" name="jumlah_barang_approve[]" class="product-quantity" value="{{ $item->jumlah_barang_usulan <= $item->jumlah_barang_sisa ? $item->jumlah_barang_usulan : $item->jumlah_barang_sisa }}" min="0" max="{{ $item->jumlah_barang_sisa }}" />
                              <button type="button" class="plus">+</button>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            @endif

            @if (isset($filter->tgl_pembukuan) && isset($filter->usulan))
              <div class="border mt-5 mb-3 border-dashed"></div>

              <div class="mb-3 alert alert-primary alert-border-left" role="alert">Kelengkapan Dokumen {{ $pageTitle }}</div>
              <div class="row mb-3">
                <label class="col-sm-4 col-form-label">Nomor Dokumen <code>*</code></label>
                <div class="col-sm-8">
                  <input type="text" name="no_dokumen" class="form-control" value="{{ old('no_dokumen') }}" required />
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
                    <input type="text" name="tgl_dokumen" class="form-control" data-provider="flatpickr" data-date-format="Y-m-d" data-deafult-date="{{ old('tgl_dokumen') }}" required />
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
            @endif
          </div>

          @if (isset($filter->tgl_pembukuan) && isset($filter->usulan))
          <div class="card-footer">
            <div class="hstack gap-2 justify-content-end">
              <a href="{{ route('penyaluran.sppb.index') }}" class="btn btn-light waves-effect">Kembali</a>
              <button type="submit" class="btn btn-success waves-effect">Submit</button>
            </div>
          </div>
          @endif
        </div>
      </form>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="{{ asset('assets/js/pages/select2.init.js') }}"></script>
  <script src="{{ asset('assets/js/pages/form-input-spin.init.js') }}"></script>
  <script>
    const setParams = (tglPembukuan, slugDokumen) => {
      window.location.href = "{{ url('penyaluran/sppb/create') }}?tgl_pembukuan=" + tglPembukuan + "&usulan=" + slugDokumen;
    }

    $(function () {
      const elemTglPembukuan = $('[name="tgl_pembukuan"]');
      const elemUsulan = $('[name="usulan"]');

      elemTglPembukuan.change(function (e) { 
        setParams(e.target.value, elemUsulan.val());
      });

      elemUsulan.change(function (e) { 
        setParams(elemTglPembukuan.val(), $(this).val());        
      });
    });
  </script>
@endpush
