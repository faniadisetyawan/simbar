@extends('layouts.app')

@section('page-title', 'Pembukuan - Perolehan - ' . $pageTitle)

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">{{ $pageTitle }}</h4>
        <div class="page-title-right">
          <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
              <a href="javascript: void(0);">Pembukuan</a>
            </li>
            <li class="breadcrumb-item">
              <a href="javascript: void(0);">Perolehan</a>
            </li>
            <li class="breadcrumb-item active">{{ $pageTitle }}</li>
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
                <h5 class="card-title mb-0">Daftar {{ $pageTitle }}</h5>
              </div>
            </div>
            <div class="col-sm-auto">
              <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('pembukuan.perolehan.create', $slug) }}" class="btn btn-success add-btn waves-effect">
                  <i class="ri-add-line align-bottom me-1"></i> Tambah
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body bg-light-subtle">
          <form action="{{ Request::fullUrlWithQuery(['search' => $filter['search']]) }}" method="GET">
            <div class="row g-3">
              <div class="col-12">
                <div class="search-box">
                  <input 
                    type="search" 
                    name="search"
                    value="{{ $filter['search'] }}"
                    class="form-control search bg-light border-light" 
                    placeholder="Cari berdasarkan kode barang, spesifikasi nama barang, NUSP, dan lainnya..." 
                  />
                  <i class="ri-search-line search-icon"></i>
                </div>
              </div>
              <div class="col-sm-4">
                <div>
                  <input 
                    type="text" 
                    name="date_range" 
                    class="form-control bg-light border-light" 
                    data-provider="flatpickr" 
                    data-date-format="Y-m-d" 
                    data-range-date="true"
                    {{-- data-deafult-date="{{ '2023-01-01 to ' . date('Y-m-d') }}"  --}}
                    placeholder="Select date" 
                  />
                </div>
              </div>
              <div class="col-sm-4">
                <div>
                  <select name="bidang_id" class="form-select bg-light border-light">
                    <option value="">Select</option>
                    @foreach ($appBidang as $item)
                    <option value="{{ $item->id }}" @if($filter['bidang_id'] == $item['id']) selected @endif>{{ $item->id . '. ' . $item->nama }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-6 col-lg-2">
                <div>
                  <button type="submit" class="btn btn-primary waves-effect w-100"> 
                    <i class="ri-equalizer-fill me-1 align-bottom"></i> Filters
                  </button>
                </div>
              </div>
              <div class="col-6 col-lg-2">
                <div>
                  <a href="{{ route('pembukuan.perolehan.index', $slug) }}" class="btn btn-soft-danger waves-effect w-100"> 
                    <i class="ri-filter-off-fill me-1 align-bottom"></i> Reset
                  </a>
                </div>
              </div>
            </div>
          </form>
        </div>

        <div class="card-body">
          <div class="table-responsive table-card mb-1">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th scope="col" class="text-center">Action</th>
                  <th scope="col">Kode Barang</th>
                  <th scope="col">Nama Barang</th>
                  <th scope="col">NUSP</th>
                  <th scope="col">Spesifikasi Nama Barang</th>
                  <th scope="col">Spesifikasi Lainnya</th>
                  <th scope="col" class="text-end">Jumlah Barang</th>
                  <th scope="col">Satuan</th>
                  <th scope="col" class="text-end">Harga Satuan</th>
                  <th scope="col" class="text-end">Nilai Perolehan</th>
                  <th scope="col">Tgl. Pembukuan</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data as $doc)
                  @php
                    $detailUrl = route('pembukuan.perolehan.showByDocs', [$slug, $doc['slug_dokumen']]);
                  @endphp

                  <tr class="table-active fw-bold">
                    <td class="text-center">
                      <a href="{{ $detailUrl }}" class="link-primary">
                        <i class="ri-arrow-right-line align-middle"></i>
                      </a>
                    </td>
                    <td colspan="8">
                      <a href="{{ $detailUrl }}" class="link-primary">
                        <div class="d-flex align-items-center">
                          <h6 class="my-0">{{ $doc['no_dokumen'] . ' - ' }}</h6>
                          <span class="ms-2 d-flex align-items-center">
                            <i class="ri-calendar-line me-2"></i>{{ date('M d, Y', strtotime($doc['tgl_dokumen'])) }}
                          </span>
                        </div>
                        <p class="text-muted fs-12 mb-0">
                          <i class="mdi mdi-circle-medium text-success fs-15 align-middle"></i>{{ $doc['bidang']['nama'] }}
                        </p>
                      </a>
                    </td>
                    <td class="text-end">
                      <h6 class="my-0">{{ number_format($doc['total'], 2, ',', '.') }}</h6>
                    </td>
                    <td></td>
                  </tr>

                  @foreach ($doc['data'] as $item)
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
                    <td>{{ $item['master_persediaan']['kode_barang'] }}</td>
                    <td>{{ $item['master_persediaan']['kodefikasi']['uraian'] }}</td>
                    <td>{{ $item['master_persediaan']['kode_register'] }}</td>
                    <td>{{ $item['master_persediaan']['nama_barang'] }}</td>
                    <td>{{ $item['master_persediaan']['spesifikasi'] }}</td>
                    <td class="text-end">{{ $item['jumlah_barang'] }}</td>
                    <td>{{ $item['master_persediaan']['satuan'] }}</td>
                    <td class="text-end">{{ number_format($item['harga_satuan'], 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($item['nilai_perolehan'], 2, ',', '.') }}</td>
                    <td style="white-space: nowrap;">{{ date('d M, Y', strtotime($item['tgl_pembukuan'])) }}</td>
                  </tr>
                  @endforeach
                @endforeach
              </tbody>
            </table>

            @if (count($data) === 0)
              @include('components.empty-record')
            @endif
          </div>

          <div class="d-flex justify-content-end">
            {{ $paginator->withQueryString()->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection