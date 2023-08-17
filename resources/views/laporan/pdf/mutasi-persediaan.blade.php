<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Cetak {{ $pageTitle }}</title>
  <style>
    html, body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      font-size: .9rem;
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
    <i>Data per {{ date('d M, Y', strtotime($tglPembukuan)) }}</i>
  </div>

  <br />
  <br />

  <table class="table table-bordered table-data">
    <thead>
      <tr>
        <th rowspan="2" class="text-center">No.</th>
        <th rowspan="2" class="text-center">Kodefikasi</th>
        <th rowspan="2" class="text-center">Spesifikasi Nama Barang</th>
        <th rowspan="2" class="text-center">Saldo Awal</th>
        <th rowspan="2" class="text-center">Penambahan</th>
        <th rowspan="2" class="text-center">Pengurangan</th>
        <th colspan="2" class="text-center">Saldo Akhir</th>
      </tr>
      <tr>
        <th class="text-center">Jumlah</th>
        <th class="text-end">Nilai Perolehan</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($data as $group)
        <tr>
          <th class="text-center">{{ $loop->iteration }}</th>
          <th colspan="7" class="text-start">{{ $group->key->kode . ' ' . $group->key->uraian }}</th>
        </tr>
        @foreach ($group->data as $item)
          <tr>
            <td></td>
            <td>{{ $item->kode_register }}</td>
            <td>{{ $item->nama_barang }}</td>
            <td class="text-center">{{ $item->saldo_awal->stok }}</td>
            <td class="text-center">{{ $item->mutasi_tambah->stok }}</td>
            <td class="text-center">{{ $item->mutasi_kurang->stok }}</td>
            <td class="text-center">{{ $item->saldo_akhir->stok }}</td>
            <td class="text-end">{{ number_format($item->saldo_akhir->nilai_perolehan, 0, ',', '.') }}</td>
          </tr>
        @endforeach
      @endforeach
    </tbody>
    <tfoot>
      <th colspan="3" class="text-center">TOTAL</th>
      <th class="text-center">{{ $total->saldo_awal->jumlah_barang }}</th>
      <th class="text-center">{{ $total->mutasi_tambah->jumlah_barang }}</th>
      <th class="text-center">{{ $total->mutasi_kurang->jumlah_barang }}</th>
      <th class="text-center">{{ $total->saldo_akhir->jumlah_barang }}</th>
      <th class="text-end">{{ number_format($total->saldo_akhir->nilai_perolehan, 0, ',', '.') }}</th>
    </tfoot>
  </table>
</body>
</html>