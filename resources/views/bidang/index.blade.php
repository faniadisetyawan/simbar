@extends('layouts.app')

@section('page-title', 'Master - Bidang')

@push('styles')
  <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">Bidang</h4>
        <div class="page-title-right">
          <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
              <a href="javascript: void(0);">Master</a>
            </li>
            <li class="breadcrumb-item active">Bidang</li>
          </ol>
        </div>
      </div>
    </div>

    <div class="col-12">
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
                <h5 class="card-title mb-0">Data Bidang</h5>
              </div>
            </div>
            <div class="col-sm-auto">
              <div class="d-flex flex-wrap align-items-start gap-2">
                <a href="{{ route('master.bidang.create') }}" class="btn btn-success add-btn waves-effect">
                  <i class="ri-add-line align-bottom me-1"></i> Tambah
                </a>
              </div>
            </div>
          </div>
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
                <i class="ri-delete-bin-line me-1 align-bottom"></i> Trash
              </a>
            </li>
          </ul>

          <div class="table-responsive table-card">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th scope="col" class="text-center">Action</th>
                  <th scope="col" class="text-center">ID</th>
                  <th scope="col">Nama Bidang</th>
                  <th scope="col">Tgl. Entry</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data as $item)
                  <tr>
                    <td class="text-center">
                      <div class="dropdown dropend">
                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="ri-more-fill align-middle"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                          @if ($filter['active'])
                            <li>
                              <a href="{{ route('master.bidang.edit', $item['id']) }}" class="dropdown-item">
                                <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>Edit
                              </a>
                            </li>
                            <li>
                              <button class="dropdown-item" onclick="confirmTrash({{ $item }})">
                                <i class="ri-archive-fill align-bottom me-2 text-muted"></i>Arsipkan
                              </button>
                              <form id="form-trash-{{ $item['id'] }}" action="{{ route('master.bidang.trash', $item['id']) }}" method="POST">
                                @csrf
                                @method("DELETE")
                              </form>
                            </li>
                          @else
                            <li>
                              <button class="dropdown-item" onclick="confirmRestore({{ $item }})">
                                <i class="ri-restart-line align-bottom me-2 text-muted"></i>Restore
                              </button>
                              <form id="form-restore-{{ $item['id'] }}" action="{{ route('master.bidang.restore', $item['id']) }}" method="POST">
                                @csrf
                                @method("PUT")
                              </form>
                            </li>
                            <li>
                              <button class="dropdown-item" onclick="confirmDestroy({{ $item }})">
                                <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>Hapus
                              </button>
                              <form id="form-destroy-{{ $item['id'] }}" action="{{ route('master.bidang.destroy', $item['id']) }}" method="POST">
                                @csrf
                                @method("DELETE")
                              </form>
                            </li>
                          @endif
                        </ul>
                      </div>
                    </td>
                    <td class="text-center">{{ $item['id'] }}</td>
                    <td>{{ $item['nama'] }}</td>
                    <td>{{ date('F d, Y H:i', strtotime($item['created_at'])) }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>

            @if (count($data) === 0)
              @include('components.empty-record')
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
      const confirmTrash = (data) => {
        Swal.fire({
          title: "Konfirmasi.",
          html: `<small>Apakah anda yakin mengarsipkan <b>"${data.nama}"</b> ?</small>`,
          icon: "question",
          showCancelButton: true,
          confirmButtonText: "Arsipkan",
          cancelButtonText: "Batal",
        }).then((result) => {
          if (result.isConfirmed) {
            document.getElementById('form-trash-' + data.id).submit();
          }
        });
      }

      const confirmRestore = (data) => {
        Swal.fire({
          title: "Konfirmasi.",
          html: `<small>Apakah anda yakin melakukan restore data <b>"${data.nama}"</b> ?</small>`,
          icon: "question",
          showCancelButton: true,
          confirmButtonText: "Restore",
          cancelButtonText: "Batal",
        }).then((result) => {
          if (result.isConfirmed) {
            document.getElementById('form-restore-' + data.id).submit();
          }
        });
      }

      const confirmDestroy = (data) => {
        Swal.fire({
          title: "Konfirmasi.",
          html: `<small>Apakah anda yakin menghapus <b>"${data.nama}"</b> ?</small>`,
          icon: "question",
          showCancelButton: true,
          confirmButtonText: "Hapus",
          cancelButtonText: "Batal",
        }).then((result) => {
          if (result.isConfirmed) {
            document.getElementById('form-destroy-' + data.id).submit();
          }
        });
      }
    </script>
  @endpush
@endsection