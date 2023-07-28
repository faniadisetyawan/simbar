@extends('layouts.app')

@section('page-title', 'Master - Bidang - Form')

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
            <li class="breadcrumb-item">
              <a href="{{ route('master.bidang.index') }}">Bidang</a>
            </li>
            <li class="breadcrumb-item active">Form</li>
          </ol>
        </div>
      </div>
    </div>

    <div class="col-md-6">
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
                <h5 class="card-title mb-0">Form Bidang</h5>
              </div>
            </div>

            @if (isset($props))
            <div class="col-sm-auto">
              <span class="badge rounded-pill bg-warning-subtle text-warning">Edit</span>
            </div>
            @endif
          </div>
        </div>

        <form action="{{ isset($props) ? route('master.bidang.update', $props['id']) : route('master.bidang.store') }}" method="POST">
          @csrf
          @if (isset($props))
            @method("PUT")
          @endif

          <div class="card-body">
            <div class="form-group">
              <label class="form-label">Nama Bidang <code>*</code></label>
              <input type="text" name="nama" class="form-control" value="{{ old('nama', $props['nama']) }}" />
              @error('nama')
              <div class="form-text text-danger">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="card-footer">
            <div class="d-flex flex-wrap gap-2 justify-content-end">
              <button type="submit" class="btn btn-success waves-effect">Submit</button>
              <a href="{{ route('master.bidang.index') }}" class="btn btn-light waves-effect">Kembali</a>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
@endsection