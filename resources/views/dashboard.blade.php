@extends('layouts.app')

@section('content')
  <div class="mb-3 d-flex align-items-lg-center flex-lg-row flex-column">
    <div class="flex-grow-1">
      <h4 class="fs-16 mb-1">Selamat datang, {{ auth()->user()->nama }}!</h4>
      <p class="text-muted mb-0">Berikut ringkasan nilai dan aktivitas pada aplikasi SIMBAR.</p>
    </div>
  </div>

  <div class="row h-100">
    @foreach ($rekapPerJenis as $item)
      <div class="col-lg-4 col-md-6">
        @include('components.card-widget', [
          'title' => $item['title'],
          'value' => $item['value'],
          'icon' => $item['icon'],
        ])
      </div>
    @endforeach
  </div>

  <div class="row">
    <div class="col-12">
      @include('components.recent-activity', ['data' => $recentActivity])
    </div>
  </div>
@endsection