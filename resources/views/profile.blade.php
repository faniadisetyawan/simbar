@extends('layouts.app')

@section('page-title', $pageTitle)

@push('styles')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
  <div class="position-relative mx-n4 mt-n4">
    <div class="profile-wid-bg profile-setting-img">
      <img src="{{ asset('assets/images/profile-bg.jpg') }}" class="profile-wid-img" alt="" />
    </div>
  </div>

  <div class="card mt-n5">
    <div class="card-body p-4">
      <div class="text-center">
        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
          <img src="{{ is_null($data->foto) ? asset('assets/images/users/avatar-3.jpg') : asset('storage/users/' . $data->foto) }}" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image" />
          <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
            <form form-upload-photo action="{{ route('master.users.upload-photo', $data->id) }}" method="post" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <input id="profile-img-file-input" type="file" input-upload-photo name="foto" class="profile-img-file-input" />
            </form>
            <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
              <span class="avatar-title rounded-circle bg-light text-body">
                <i class="ri-camera-fill"></i>
              </span>
            </label>
          </div>
        </div>
        <h5 class="fs-16 mb-1">{{ $data->nama }}</h5>
        <p class="text-muted mb-0">{{ $data->role->nama }} / {{ $data->bidang->nama }}</p>
      </div>
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

  @if (session()->has('success'))
    <div class="alert alert-success alert-border-left alert-dismissible fade show" role="alert">
      <i class="ri-check-double-line me-3 align-middle fs-16"></i><strong>Success</strong>
      - {{ session()->get('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card">
    <div class="card-header">
      <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
            <i class="fas fa-home"></i> Personal Details
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab">
            <i class="far fa-user"></i> Ubah Password
          </a>
        </li>
      </ul>
    </div>
    <div class="card-body p-4">
      <div class="tab-content">
        <div class="tab-pane active" id="personalDetails" role="tabpanel">
          <form action="{{ route('master.users.update', $data->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
              <div class="col-12">
                <div class="mb-3">
                  <label class="form-label">Username <code>*</code></label>
                  <input type="text" name="username" class="form-control" value="{{ $data->username }}" />
                </div>
              </div>

              <div class="col-lg-6">
                <div class="mb-3">
                  <label class="form-label">Nama Lengkap <code>*</code></label>
                  <input type="text" name="nama" class="form-control" value="{{ $data->nama }}" />
                </div>
              </div>
              <div class="col-lg-6">
                <div class="mb-3">
                  <label class="form-label">NIP</label>
                  <input type="text" name="nip" cleave-nip class="form-control" placeholder="xxxxxxxx xxxxxx x xxx" value="{{ $data->nip }}" />
                </div>
              </div>

              <div class="col-lg-6">
                <div class="mb-3">
                  <label class="form-label">Bidang <code>*</code></label>
                  <select name="bidang_id" class="form-select js-example-basic-single">
                    <option></option>
                    @foreach ($appBidang as $item)
                      <option value="{{ $item->id }}" @if($data->bidang_id == $item->id) selected @endif>
                        {{ $item->id . '. ' . $item->nama }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="mb-3">
                  <label class="form-label">Role <code>*</code></label>
                  <select name="role_id" class="form-select js-example-basic-single">
                    <option></option>
                    @foreach ($appUserRoles as $item)
                      <option value="{{ $item->id }}" @if($data->role_id == $item->id) selected @endif>
                        {{ $item->id . '. ' . $item->nama }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="col-lg-12">
                <div class="hstack gap-2 justify-content-end mt-3">
                  <button type="submit" class="btn btn-primary">Update</button>
                </div>
              </div>
            </div>
          </form>
        </div>

        <div class="tab-pane" id="changePassword" role="tabpanel">
          <form action="{{ route('master.users.change-password', $data->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-2">
              <div class="col-sm-6">
                <div>
                  <label class="form-label">New Password <code>*</code></label>
                  <input type="password" name="password" class="form-control" placeholder="Enter new password" />
                </div>
              </div>
              <div class="col-sm-6">
                <div>
                  <label class="form-label">Confirm Password <code>*</code></label>
                  <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" />
                </div>
              </div>
              <div class="col-lg-12">
                <div class="text-end mt-3">
                  <button type="submit" class="btn btn-success">Change Password</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="{{ asset('assets/js/pages/select2.init.js') }}"></script>
  <script src="{{ asset('assets/libs/cleave.js/cleave.min.js') }}"></script>
  <script>
    $(function () {
      $('[name="username"]').change(function () {
        let generate = $(this).val().replace(/\s/g, '').toLowerCase();
        $(this).val(generate);
      });

      new Cleave('[cleave-nip]', {
        blocks: [8, 6, 1, 3],
      });

      $('[input-upload-photo]').change(function (e) {
        let fileUpload = e.target.files[0];
        if (!!fileUpload) {
          $('[form-upload-photo]').submit();          
        }
      });
    });
  </script>
@endpush