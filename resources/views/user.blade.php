@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">Users</h4>
        <div class="page-title-right">
          <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
            <li class="breadcrumb-item active">Users</li>
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
                <h5 class="card-title mb-0">Data User</h5>
              </div>
            </div>
            <div class="col-sm-auto">
              <div class="d-flex flex-wrap align-items-start gap-2">
                <button type="button" class="btn btn-success add-btn waves-effect">
                  <i class="ri-add-line align-bottom me-1"></i> Tambah
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="card-body bg-light-subtle">
          <form action="{{ Request::fullUrlWithQuery(['search' => $filter['search']]) }}" method="GET">
            <div class="search-box">
              <input 
                type="search" 
                name="search"
                value="{{ $filter['search'] }}"
                class="form-control search bg-light border-light" 
                placeholder="Cari berdasarkan username, nama lengkap, jabatan, bidang, dan lainnya..." 
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
                <i class="ri-delete-bin-line me-1 align-bottom"></i> Trash
              </a>
            </li>
          </ul>

          <div class="table-responsive table-card mb-1">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th scope="col">Action</th>
                  <th scope="col">ID</th>
                  <th scope="col">Username</th>
                  <th scope="col">Nama Lengkap</th>
                  <th scope="col">NIP</th>
                  <th scope="col">Jabatan</th>
                  <th scope="col">Bidang</th>
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
                          <li class="dropdown-divider"></li>
                          <li>
                            <button class="dropdown-item">
                              <i class="ri-lock-fill align-bottom me-2 text-muted"></i>Ubah Password
                            </button>
                          </li>
                        </ul>
                      </div>
                    </td>
                    <td>{{ $item['id'] }}</td>
                    <td>
                      <div class="d-flex align-items-center">
                        @if (isset($item['foto']))
                          <img src="{{ asset('assets/images/users/' . $item['foto']) }}" class="avatar-xs rounded-circle me-2" alt="" />
                        @else
                          <div class="flex-shrink-0 avatar-xs me-2">
                            <div class="avatar-title bg-success-subtle text-success rounded-circle fs-13">
                              {{ strtoupper(substr($item['nama'], 0, 2)) }}
                            </div>
                          </div>
                        @endif

                        {{ $item['username'] }}
                      </div>
                    </td>
                    <td>{{ $item['nama'] }}</td>
                    <td style="white-space: nowrap;">{{ $item['nip'] }}</td>
                    <td>{{ $item['role']['nama'] }}</td>
                    <td>{{ $item['bidang']['nama'] }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          @if (count($data) === 0)
            @include('components.empty-record')
          @endif

          <div class="d-flex justify-content-end">
            {{ $data->withQueryString()->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection