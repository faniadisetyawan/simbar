@extends('layouts.app')

@section('page-title', 'Master - Persediaan')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">Persediaan</h4>
        <div class="page-title-right">
          <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
            <li class="breadcrumb-item active">Persediaan</li>
          </ol>
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <div class="row g-4 align-items-center">
            <div class="col-sm">
              <div>
                <h5 class="card-title mb-0">Master Persediaan</h5>
              </div>
            </div>
            <div class="col-sm-auto">
              <div class="d-flex gap-1 flex-wrap">
                <button type="button" class="btn btn-success add-btn">
                  <i class="ri-add-line align-bottom me-1"></i> Tambah
                </button>
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#importModal">
                  <i class="ri-upload-line align-bottom me-1"></i> Import
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body bg-light-subtle">
          <form action="{{ Request::fullUrlWithQuery(['search' => $filter['search']]) }}" method="GET">
            <div class="search-box">
              <input 
                type="text" 
                name="search"
                value="{{ $filter['search'] }}"
                class="form-control search bg-light border-light" 
                placeholder="Cari berdasarkan kode barang, spesifikasi nama barang, NUSP, dan lainnya..." 
              />
              <i class="ri-search-line search-icon"></i>
            </div>
          </form>
        </div>
        <div class="card-body pt-0">
          <ul class="nav nav-tabs nav-tabs-custom nav-success mb-3" role="tablist">
            <li class="nav-item" role="presentation">
              <a 
                class="nav-link py-3 {{ $filter['active'] ? 'active' : '' }}" 
                href="{{ Request::fullUrlWithQuery(['active' => 'true']) }}" 
                role="tab" 
                aria-selected="{{ $filter['active'] ? 'true' : 'false' }}"
              >
                <i class="ri-checkbox-circle-line me-1 align-bottom"></i> Active
              </a>
            </li>
            <li class="nav-item" role="presentation">
              <a 
                class="nav-link py-3 {{ $filter['active'] === FALSE ? 'active' : '' }}" 
                href={{ Request::fullUrlWithQuery(['active' => 'false']) }}
                role="tab"
                aria-selected="{{ $filter['active'] === FALSE ? 'true' : 'false' }}"
              >
                <i class="ri-close-circle-line me-1 align-bottom"></i> Trash
              </a>
            </li>
          </ul>

          <div class="table-responsive table-card mb-1">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th scope="col">Action</th>
                  <th scope="col">NIBAR</th>
                  <th scope="col">Kode Barang</th>
                  <th scope="col">Nama Barang</th>
                  <th scope="col">NUSP</th>
                  <th scope="col">Spesifikasi Nama Barang</th>
                  <th scope="col">Spesifikasi Lainnya</th>
                  <th scope="col">Satuan</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data as $item)
                  <tr>
                    <td>
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
                    <td>{{ $item['id'] }}</td>
                    <td>{{ $item['kode_barang'] }}</td>
                    <td>{{ $item['kodefikasi']['uraian'] }}</td>
                    <td>{{ $item['kode_register'] }}</td>
                    <td>{{ $item['nama_barang'] }}</td>
                    <td>{{ $item['spesifikasi'] }}</td>
                    <td>{{ $item['satuan'] }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>

            @if (count($data) === 0)
              <div class="noresult my-3">
                <div class="text-center">
                  <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px"></lord-icon>
                  <h5 class="mt-2">Sorry! No Result Found</h5>
                  <p class="text-muted">We did not find any data for you search.</p>
                </div>
              </div>
            @endif
          </div>

          <div class="d-flex justify-content-end">
            {{ $data->withQueryString()->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade zoomIn" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0">
        <div class="modal-header p-3 bg-info-subtle">
          <h5 class="modal-title" id="exampleModalLabel">Import Master Persediaan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>

        <form action="{{ url('/master/persediaan/import') }}" method="post" enctype="multipart/form-data" class="tablelist-form" autocomplete="off">
          @csrf

          <div class="modal-body">
            <input type="file" name="document" class="form-control form-control-lg" />
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
