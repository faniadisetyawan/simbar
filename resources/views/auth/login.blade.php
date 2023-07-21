@extends('layouts.auth')

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
      <div class="card mt-4">
        <div class="card-body p-4">
          <div class="text-center mt-2">
            <h5 class="text-primary">Welcome Back !</h5>
            <p class="text-muted">Sign in to continue to SIMBAR.</p>
          </div>
          <div class="p-2 mt-4">
            <form action="{{ url('/auth/login') }}" method="POST">
              @csrf
              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Enter username" value="{{ old('username') }}" />
              </div>

              <div class="mb-3">
                <label class="form-label" for="password-input">Password</label>
                <div class="position-relative auth-pass-inputgroup mb-3">
                  <input type="password" class="form-control pe-5 password-input" name="password" placeholder="Enter password" id="password-input" value="{{ old('password') }}" />
                  <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                </div>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="auth-remember-check">
                <label class="form-check-label" for="auth-remember-check">Remember me</label>
              </div>
              <div class="mt-4">
                <button class="btn btn-success w-100" type="submit">Sign In</button>
              </div>
            </form>
          </div>
        </div>
        <!-- end card body -->
      </div>
      <!-- end card -->
    </div>
  </div>
@endsection