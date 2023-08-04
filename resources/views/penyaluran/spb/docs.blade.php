@extends('layouts.app')

@section('page-title', 'Penyaluran Persediaan - ' . $pageTitle . ' - Dokumen')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">Dokumen {{ $pageTitle }}</h4>
        <div class="page-title-right">
          <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
              <a href="javascript: void(0);">Penyaluran</a>
            </li>
            <li class="breadcrumb-item">
              <a href="{{ route('penyaluran.spb.index') }}">{{ $pageTitle }}</a>
            </li>
            <li class="breadcrumb-item active">Dokumen</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
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

    @if (session()->has('success'))
    <div class="col-12">
      <div class="alert alert-success alert-border-left alert-dismissible fade show" role="alert">
        <i class="ri-check-double-line me-3 align-middle fs-16"></i><strong>Success</strong>
        - {{ session()->get('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
    @endif

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

            <div class="text-muted">
              <small class="d-block fw-bold">File Upload :</small>
              @if (is_null($data['upload']))
                <p class="mb-0">-</p>
              @else
                <a href="{{ asset('storage/dokumen/' . $data['upload']['file_upload']) }}" target="_blank" rel="noopener noreferrer">
                  <i class="ri-download-2-line me-2"></i>{{ $data['upload']['file_upload'] }}
                </a>
              @endif
            </div>
            <div class="my-2 border border-dashed"></div>

            <div class="d-grid">
              <button type="button" class="btn btn-success btn-label btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#uploadModal">
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
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive table-card">
            <table class="table table-nowrap align-middle table-borderless mb-0">
              <thead class="table-light text-muted">
                <tr>
                  <th scope="col">Kodefikasi</th>
                  <th scope="col">Spesifikasi</th>
                  <th scope="col" class="text-end">Jumlah Permintaan</th>
                  <th scope="col" class="text-end">Jumlah Usulan (SPB)</th>
                  <th scope="col">Satuan</th>
                  <th scope="col">Keperluan</th>
                  <th scope="col">Tgl. Pembukuan</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data['data'] as $item)
                <tr>
                  <td>
                    <h5 class="fs-15">{{ $item['master_persediaan']['kode_barang'] }}</h5>
                    <p class="text-muted mb-0">NUSP: <span class="fw-medium">{{ $item['master_persediaan']['kode_register'] }}</span></p>
                    <p class="text-muted mb-0">Nama: {{ $item['master_persediaan']['kodefikasi']['uraian'] }}</p>
                  </td>
                  <td>
                    <h5 class="fs-15">{{ $item['master_persediaan']['nama_barang'] }}</h5>
                    <p class="text-muted mb-0">Spesifikasi: <span class="fw-medium">{{ $item['master_persediaan']['spesifikasi'] }}</span></p>
                  </td>
                  <td class="text-end">{{ $item['jumlah_barang_permintaan'] }}</td>
                  <td class="text-end table-active">{{ $item['jumlah_barang_usulan'] }}</td>
                  <td>{{ $item['master_persediaan']['satuan'] }}</td>
                  <td>{{ $item['keperluan'] }}</td>
                  <td style="white-space: nowrap;">{{ date('d M, Y', strtotime($item['tgl_pembukuan'])) }}</td>
                </tr>
                @endforeach
              </tbody>
              <tfoot class="border-top border-top-dashed">
                <tr class="border-top border-top-dashed">
                  <td colspan="2"></td>
                  <th class="text-end">Jumlah :</th>
                  <th class="text-end table-active">
                    <div class="fs-15">{{ $data['total'] }}</div>
                  </th>
                  <td colspan="3"></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  @include('components.modal-penyaluran-npb')

  <div class="modal fade zoomIn" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content border-0">
        <div class="modal-header p-3 bg-info-subtle">
          <h5 class="modal-title" id="exampleModalLabel">Upload Dokumen</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
  
        <form action="{{ route('penyaluran.spb.uploadDokumen') }}" method="post" enctype="multipart/form-data" class="tablelist-form">
          @csrf
          @method("PUT")
  
          <input type="hidden" name="slug_dokumen_tambah" value="{{ $data['slug_dokumen'] }}" />
  
          <div class="modal-body" id="modal-container">
            <div class="mb-3">
              <label class="form-label">Pilih File <code>*</code></label>
              <input type="file" name="file_upload" class="form-control" />
              <div class="form-text">Untuk menghemat resource server, upload dokumen dengan ukuran maksimal 1 (satu) MB.</div>
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
  <script src="{{ asset('assets/js/pages/form-input-spin.init.js') }}"></script>
@endpush
