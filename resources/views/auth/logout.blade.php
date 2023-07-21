@extends('layouts.auth')

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
      <div class="card mt-4">
        <div class="card-body p-4 text-center">
          <lord-icon src="https://cdn.lordicon.com/hzomhqxz.json" trigger="loop" colors="primary:#405189,secondary:#08a88a" style="width:180px;height:180px"></lord-icon>
          <div class="mt-4 pt-2">
            <h5>You are Logged Out</h5>
            <p class="text-muted">Terimakasih sudah menggunakan <span class="fw-semibold">SIMBAR</span></p>
            <div class="mt-4">
              <a href="{{ url('auth/login') }}" class="btn btn-success w-100">Sign In</a>
            </div>
          </div>
        </div>
        <!-- end card body -->
      </div>
      <!-- end card -->
    </div>
    <!-- end col -->
  </div>
@endsection