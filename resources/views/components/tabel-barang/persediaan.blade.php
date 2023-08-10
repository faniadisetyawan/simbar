<table class="table table-hover align-middle">
  <thead class="table-light">
    <tr>
      <th scope="col" class="text-center">Action</th>
      <th scope="col">NUSP</th>
      <th scope="col">Spesifikasi Nama Barang</th>
      <th scope="col">Spesifikasi Lainnya</th>
      <th scope="col" class="text-center">Jumlah Barang</th>
      <th scope="col">Satuan</th>
      <th scope="col" class="text-end">Harga Satuan</th>
      <th scope="col" class="text-end">Nilai Perolehan</th>
      <th scope="col">Keterangan</th>
      <th scope="col">Tgl. Pembukuan</th>
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
          <td>{{ $item['master_persediaan']['kode_register'] }}</td>
          <td>{{ $item['master_persediaan']['nama_barang'] }}</td>
          <td>{{ $item['master_persediaan']['spesifikasi'] }}</td>
          <td class="text-center">{{ $item['jumlah_barang'] }}</td>
          <td>{{ $item['master_persediaan']['satuan'] }}</td>
          <td class="text-end">{{ number_format($item['harga_satuan'], 2, ',', '.') }}</td>
          <td class="text-end">{{ number_format($item['nilai_perolehan'], 2, ',', '.') }}</td>
          <td>{{ $item['keterangan'] }}</td>
          <td style="white-space: nowrap;">{{ date('d M, Y', strtotime($item['tgl_pembukuan'])) }}</td>
        </tr>
      @endforeach
    @endforeach
    @endif
  </tbody>
</table>