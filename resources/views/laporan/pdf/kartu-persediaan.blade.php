<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Cetak {{ $pageTitle }} - {{ '#' . $master->id }}</title>
  <style>
    html, body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      font-size: 1rem;
    }
    .text-start {
      text-align: left;
    }
    .text-center {
      text-align: center;
    }
    .text-end {
      text-align: right;
    }
    .w-100 {
      width: 100%;
    }
    .w-60 {
      width: 60%;
    }
    .w-40 {
      width: 40%;
    }
    .ps15 {
      padding-left: 15px;
    }
    .mb5 {
      margin-bottom: 5px;
    }
    .table-title th,
    .table-title td {
      vertical-align: top;
    }
    .table {
      width: 100%;
      border-collapse: collapse;
    }
    .table th,
    .table td {
      padding: 5px;
    }
    .table-bordered th {
      border: thin solid black;
    }
    .table-bordered td {
      border-left: thin solid black;
      border-right: thin solid black;
    }
    .table-bordered tr:last-child {
      border-bottom: thin solid black;
    }
    .table-data tbody td:nth-last-child(1),
    .table-data tbody td:nth-last-child(2) {
      background-color: #daf4f0;
      color: black;
    }
    td.empty {
      text-align: center;
      padding: 5rem auto;
      background-color: #fde8e4 !important;
      letter-spacing: 5px;
    }
  </style>
</head>
<body>
  <div class="text-center">
    <h2 class="mb5">{{ strtoupper($pageTitle) }}</h2>
    <small>per {{ date('d M, Y', strtotime($tglPembukuan)) }}</small>
  </div>

  <br />

  <table class="w-100 table-title">
    <tr>
      <td class="w-60">
        <table>
          <tr>
            <th class="text-start">Kode Barang</th>
            <th class="ps15">:</th>
            <td>{{ $master->kode_barang }}</td>
          </tr>
          <tr>
            <th class="text-start">Nama Barang</th>
            <th class="ps15">:</th>
            <td>{{ $master->kodefikasi->uraian }}</td>
          </tr>
          <tr>
            <th class="text-start">Satuan</th>
            <th class="ps15">:</th>
            <td>{{ $master->satuan }}</td>
          </tr>
        </table>
      </td>
      <td class="w-40">
        <table>
          <tr>
            <th class="text-start">NUSP</th>
            <th class="ps15">:</th>
            <td>{{ $master->kode_register }}</td>
          </tr>
          <tr>
            <th class="text-start">Spesifikasi</th>
            <th class="ps15">:</th>
            <td>
              {{ $master->nama_barang }} - <small>{{ $master->spesifikasi }}</small>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <br />

  <table class="table table-bordered table-data">
    <thead>
      <tr>
        <th rowspan="2" class="text-center">Tanggal</th>
        <th rowspan="2" class="text-center">Pembukuan</th>
        <th colspan="2" class="text-center">Masuk</th>
        <th colspan="2" class="text-center">Keluar</th>
        <th colspan="2" class="text-center">Sisa / Saldo</th>
      </tr>
      <tr>
        <th class="text-center">Jumlah</th>
        <th class="text-center">Nilai (Rp)</th>
        <th class="text-center">Jumlah</th>
        <th class="text-center">Nilai (Rp)</th>
        <th class="text-center">Jumlah</th>
        <th class="text-center">Nilai (Rp)</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($data as $item)
        @if ($item->is_tambah)
          <tr>
            <td class="text-center">{{ date('d M, Y', strtotime($item->tgl_pembukuan)) }}</td>
            <td>{{ $item->pembukuan['nama'] }}</td>
            <td class="text-center">{{ $item->jumlah_barang }}</td>
            <td class="text-end">{{ number_format($item->nilai_perolehan, 0, ',', '.') }}</td>
            <td class="text-center">0</td>
            <td class="text-end">0</td>
            <td class="text-center">{{ $item->stok }}</td>
            <td class="text-end">{{ number_format($item->nilai_akhir, 0, ',', '.') }}</td>
          </tr>
        @else
          <tr>
            <td class="text-center">{{ date('d M, Y', strtotime($item->tgl_pembukuan)) }}</td>
            <td>{{ $item->pembukuan['nama'] }}</td>
            <td class="text-center">0</td>
            <td class="text-end">0</td>
            <td class="text-center">{{ $item->jumlah_barang }}</td>
            <td class="text-end">{{ number_format($item->nilai_perolehan, 0, ',', '.') }}</td>
            <td class="text-center">{{ $item->stok }}</td>
            <td class="text-end">{{ number_format($item->nilai_akhir, 0, ',', '.') }}</td>
          </tr>
        @endif
      @endforeach

      @if (count($data) === 0)
        <tr>
          <td colspan="8" class="empty">NIHIL</td>
        </tr>
      @endif
    </tbody>
  </table>
</body>
</html>