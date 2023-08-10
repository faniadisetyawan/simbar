<table class="table table-hover align-middle">
  <thead class="table-light">
    <tr>
      <th scope="col" class="text-center">NIBAR</th>
      <th scope="col">Kode Register</th>
      <th scope="col">Spesifikasi Nama Barang</th>
      <th scope="col">Alamat</th>
      <th scope="col" class="text-center">Tahun Pengadaan</th>
      <th scope="col" class="text-end">Nilai Perolehan</th>
      <th scope="col">Keterangan</th>
    </tr>
  </thead>
  <tbody>
    @if (isset($data))
    @foreach ($data as $doc)
      <tr class="table-active">
        <th colspan="7">{{ $doc['key']['kode'] . ' ' . $doc['key']['uraian'] }}</th>
      </tr>
      @foreach ($doc['data'] as $item)
        <tr>
          <td class="text-center">{{ $item['id'] }}</td>
          <td>{{ $item['kode_register'] }}</td>
          <td>{{ $item['nama_barang'] }}</td>
          <td>{{ $item['spesifikasi'] }}</td>
          <td class="text-center">{{ $item['tahun_pengadaan'] }}</td>
          <td class="text-end">{{ number_format($item['nilai_perolehan'], 2, ',', '.') }}</td>
          <td>{{ $item['keterangan'] }}</td>
        </tr>
      @endforeach
    @endforeach
    @endif
  </tbody>
</table>