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
      <th scope="col" class="text-center">No. Sertifikat</th>
      <th scope="col" class="text-center">Tgl. Sertifikat</th>
      <th scope="col" class="text-end">Luas Tanah</th>
    </tr>
  </thead>
  <tbody>
    @if (isset($data))
    @foreach ($data as $doc)
      <tr class="table-active">
        <th colspan="10">{{ $doc['key']['kode'] . ' ' . $doc['key']['uraian'] }}</th>
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
          <td class="text-center">{{ $item['no_sertifikat'] }}</td>
          <td class="text-center">
            <div class="nowrap">{{ $item['tgl_sertifikat'] }}</div>
          </td>
          <td class="text-end">{{ $item['luas_tanah'] }}</td>
        </tr>
      @endforeach
    @endforeach
    @endif
  </tbody>
</table>