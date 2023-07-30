@extends('layouts.app')

@section('page-title', 'Pembukuan - Perolehan - Dokumen')

@push('styles')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">Dokumen {{ $pageTitle }}</h4>
        <div class="page-title-right">
          <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
              <a href="javascript: void(0);">Perolehan</a>
            </li>
            <li class="breadcrumb-item">
              <a href="{{ route('pembukuan.perolehan.index', $slug) }}">Pengadaan</a>
            </li>
            <li class="breadcrumb-item active">Dokumen</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-3 col-md-8 mx-auto">
      <div class="card sticky-side-div">
        <div class="card-header">
          <div class="d-flex">
            <h5 class="card-title flex-grow-1 mb-0">
              <i class="mdi mdi-file-document-outline align-middle me-1 text-muted"></i> Dokumen
            </h5>
            <div class="flex-shrink-0">
              <a href="javascript:void(0);" class="badge bg-primary-subtle text-primary fs-11">Edit</a>
              <a href="javascript:void(0);" class="badge bg-primary-subtle text-primary fs-11">Hapus</a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="text-center">
            <lord-icon
              src="https://cdn.lordicon.com/ggqtvqxi.json"
              trigger="loop"
              colors="primary:#405189,secondary:#0ab39c"
              style="width:80px;height:80px">
            </lord-icon>
          </div>

          <div class="mt-2">
            <div class="text-muted">
              <small class="d-block fw-bold">Bidang :</small>
              <p class="mb-0">{{ $data['bidang']['nama'] }}</p>
            </div>
            <div class="my-2 border border-dashed"></div>
            <div class="text-muted">
              <small class="d-block fw-bold">No. Dokumen :</small>
              <p class="mb-0">{{ $data['no_dokumen'] }}</p>
            </div>
            <div class="my-2 border border-dashed"></div>
            <div class="text-muted">
              <small class="d-block fw-bold">Tgl. Dokumen :</small>
              <p class="mb-0">{{ date('d M, Y', strtotime($data['tgl_dokumen'])) }}</p>
            </div>
            <div class="my-2 border border-dashed"></div>
            <div class="text-muted">
              <small class="d-block fw-bold">Uraian Dokumen :</small>
              <p class="mb-0">{{ $data['uraian_dokumen'] ? $data['uraian_dokumen'] : '-' }}</p>
            </div>
            <div class="my-2 border border-dashed"></div>
            <div class="d-grid">
              <button type="button" class="btn btn-success btn-label btn-sm waves-effect">
                <div class="d-flex">
                  <div class="flex-shrink-0">
                    <i class="ri-upload-line label-icon align-middle fs-16 me-2"></i>
                  </div>
                  <div class="flex-grow-1">Upload Dokumen</div>
                </div>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-9">
      <div class="card">
        <div class="card-header">
          <div class="d-flex align-items-center">
            <h5 class="card-title flex-grow-1 mb-0">Daftar Barang</h5>
            <div class="flex-shrink-0">
              <button type="button" class="btn btn-success btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#formModal">
                <i class="ri-add-fill align-middle me-1"></i> Tambah
              </button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive table-card">
            <table class="table table-nowrap align-middle table-borderless mb-0">
              <thead class="table-light text-muted">
                <tr>
                  <th scope="col" class="text-center">Action</th>
                  <th scope="col">Kodefikasi</th>
                  <th scope="col">Spesifikasi Nama Barang</th>
                  <th scope="col" class="text-end">Harga Satuan</th>
                  <th scope="col" class="text-end">Jumlah Barang</th>
                  <th scope="col" class="text-end">Nilai Perolehan</th>
                  <th scope="col">Tgl. Pembukuan</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data['data'] as $item)
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
                  <td>
                    <h5 class="fs-15">{{ $item['master_persediaan']['kode_barang'] }}</h5>
                    <p class="text-muted mb-0">NUSP: <span class="fw-medium">{{ $item['master_persediaan']['kode_register'] }}</span></p>
                    <p class="text-muted mb-0">Nama: {{ $item['master_persediaan']['kodefikasi']['uraian'] }}</p>
                  </td>
                  <td>
                    <h5 class="fs-15">{{ $item['master_persediaan']['nama_barang'] }}</h5>
                    <p class="text-muted mb-0">Spesifikasi: <span class="fw-medium">{{ $item['master_persediaan']['spesifikasi'] }}</span></p>
                  </td>
                  <td class="fw-medium text-end">{{ number_format($item['harga_satuan'], 2, ',', '.') }}</td>
                  <td class="text-end">{{ $item['jumlah_barang'] . ' ' . $item['master_persediaan']['satuan'] }}</td>
                  <td class="fw-medium text-end">{{ number_format($item['nilai_perolehan'], 2, ',', '.') }}</td>
                  <td style="white-space: nowrap;">{{ date('d M, Y', strtotime($item['tgl_pembukuan'])) }}</td>
                </tr>
                @endforeach
              </tbody>
              <tfoot class="border-top border-top-dashed">
                <tr class="border-top border-top-dashed">
                  <td colspan="4"></td>
                  <th class="text-end">Total :</th>
                  <th class="text-end">
                    <div class="fs-15">{{ number_format($data['total'], 2, ',', '.') }}</div>
                  </th>
                  <td></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade zoomIn" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content border-0">
        <div class="modal-header p-3 bg-info-subtle">
          <h5 class="modal-title" id="exampleModalLabel">Form Barang</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>

        <form action="{{ route('pembukuan.perolehan.store', $slug) }}" method="post" class="tablelist-form">
          @csrf

          <div class="modal-body" id="modal-container">
            <div class="mb-3">
              <label class="form-label">Pilih Barang <code>*</code></label>
              <select name="barang_id" class="form-control js-example-basic-single">
                <option></option>
                @foreach ($appMasterPersediaan as $group)
                <optgroup label="{{ $group['key'] }}">
                  @foreach ($group['data'] as $item)
                  <option value="{{ $item['id'] }}" @if(old('barang_id') === $item['id']) @endif>
                    {{ $item['kode_barang'] . '.' . $item['kode_register']. ' ' . $item['nama_barang'] . ' ' . $item['spesifikasi'] . ', @' . $item['satuan'] }}
                  </option>
                  @endforeach
                </optgroup>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Jumlah Barang <code>*</code></label>
              <input type="number" name="jumlah_barang" class="form-control" value="{{ old('jumlah_barang') }}" min="0" />
            </div>
            <div class="mb-3">
              <label class="form-label">Nilai Perolehan <code>*</code></label>
              <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="text" id="cleaveNilaiPerolehan" class="form-control" value="{{ old('nilai_perolehan') }}" min="0" />
                <input type="hidden" name="nilai_perolehan" class="form-control" />
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Keterangan</label>
              <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
            </div>
          </div>
          <div class="modal-footer">
            <div class="hstack gap-2 justify-content-end">
              <button type="button" class="btn btn-light" id="close-modal" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success">Submit</button>
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