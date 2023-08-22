<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Laporan {{ $pageTitle }}</title>
  <style>
    html, body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      font-size: .9rem;
    }
    .text-center {
      text-align: center;
    }
    .text-end {
      text-align: right;
    }
    table {
      border-collapse: collapse;
    }
    .table {
      width: 100%;
    }
    .table th,
    .table td {
      padding: 5px;
    }
  </style>
</head>
<body>
  <div class="text-center" style="margin-bottom: 3rem;">
    <h2 style="margin-bottom: 0px;">LAPORAN {{ strtoupper($pageTitle) }}</h2>
    <small>per {{ date('d M, Y', strtotime($tglPembukuan)) }}</small>
  </div>

  <table border="1" class="table">
    <thead>
      <tr>
        <th rowspan="2">No.</th>
        <th colspan="3">Dokumen</th>
        <th rowspan="2">Kode Barang</th>
        <th rowspan="2">Nama Barang</th>
        <th colspan="2">Spesifikasi Barang</th>
        <th rowspan="2">Jumlah</th>
        <th rowspan="2">Satuan Barang</th>
        <th rowspan="2">Harga Satuan (Rp)</th>
        <th rowspan="2">Nilai Total (Rp)</th>
        <th rowspan="2">Keterangan</th>
      </tr>
      <tr>
        <th>Tanggal</th>
        <th>Nomor</th>
        <th>Nama</th>
        <th>NUSP</th>
        <th>Spesifikasi Nama Barang</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($data as $group)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ date('d M, Y', strtotime($group->tgl_dokumen)) }}</td>
          <td>{{ $group->no_dokumen }}</td>
          <td>{{ $group->jenis_dokumen->nama }}</td>
          <td colspan="7"></td>
          <td class="text-end">
            <b>{{ number_format($group->total, 0, ',', '.') }}</b>
          </td>
          <td></td>
        </tr>

        @foreach ($group->data as $item)
          <tr>
            <td colspan="4"></td>
            <td>{{ $item->master_persediaan->kode_barang }}</td>
            <td>{{ $item->master_persediaan->kodefikasi->uraian }}</td>
            <td>{{ $item->master_persediaan->kode_register }}</td>
            <td>{{ $item->master_persediaan->nama_barang }}</td>
            <td class="text-center">{{ $item->jumlah_barang }}</td>
            <td>{{ $item->master_persediaan->satuan }}</td>
            <td class="text-end">{{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
            <td class="text-end">{{ number_format($item->nilai_perolehan, 0, ',', '.') }}</td>
            <td>{{ $item->keterangan }}</td>
          </tr>
        @endforeach
      @endforeach

      @if (count($data) === 0)
        <tr>
          <td colspan="13" class="text-center">
            <h3 style="letter-spacing: 1rem; padding: 3rem 0;">NIHIL</h3>
          </td>
        </tr>
      @endif
    </tbody>
    <tfoot>
      <tr>
        <th colspan="11" class="text-end">TOTAL</th>
        <th class="text-end">
          <div style="font-size: 1rem;">{{ number_format($totalNilaiPerolehan, 0, ',', '.') }}</div>
        </th>
        <th></th>
      </tr>
    </tfoot>
  </table>
</body>
</html>