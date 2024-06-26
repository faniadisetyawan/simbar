<div class="modal fade zoomIn" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content border-0">
      <div class="modal-header p-3 bg-info-subtle">
        <h5 class="modal-title" id="exampleModalLabel">Form Barang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
      </div>

      <form action="{{ route('pembukuan.reklasifikasi.store') }}" method="post" class="tablelist-form">
        @csrf

        <input type="hidden" name="_method" value="POST" />
        <input type="hidden" name="kode_jenis_dokumen" />
        <input type="hidden" name="no_dokumen" />
        <input type="hidden" name="tgl_dokumen" />
        <input type="hidden" name="uraian_dokumen" />

        <div class="modal-body" id="modal-container">
          <div class="row mb-3">
            <label class="col-sm-4 col-form-label">Tanggal Pembukuan <code>*</code></label>
            <div class="col-sm-8">
              <div class="input-group">
                <span class="input-group-text">
                  <i class="ri-calendar-line"></i>
                </span>
                <input type="text" name="tgl_pembukuan" class="form-control" data-provider="flatpickr" data-date-format="Y-m-d" data-deafult-date="{{ old('tgl_pembukuan', date('Y-m-d')) }}" />
              </div>
              <div class="form-text">
                <ul>
                  <li>Tanggal pembukuan bisa diubah sesuai keperluan.</li>
                  <li>Namun perlu diketahui bahwa tanggal pembukuan ini merupakan parameter hasil pelaporan periodik.</li>
                </ul>
              </div>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-4 col-form-label">Pilih Barang <code>*</code></label>
            <div class="col-sm-8">
              <select name="barang_id" class="form-control js-example-basic-single-modal">
                <option></option>
                @foreach ($appMasterPersediaanGroupMutasi as $group)
                <optgroup label="{{ $group->key->kode . ' ' . $group->key->uraian }}">
                  @foreach ($group->data as $item)
                  <option value="{{ $item->id }}" @if(old('barang_id') == $item->id) selected @endif>
                    {{ $item->kode_register . ' ' . $item->nama_barang . ' ' . $item->spesifikasi . ', Stok : ' . $item->stok . ' ' . $item->satuan }}
                  </option>
                  @endforeach
                </optgroup>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-4 col-form-label">Jumlah Barang <code>*</code></label>
            <div class="col-sm-8">
              <input type="number" name="jumlah_barang" class="form-control" value="{{ old('jumlah_barang') }}" min="0" />
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-4 col-form-label">Keterangan</label>
            <div class="col-sm-8">
              <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="hstack gap-2 justify-content-end">
            <button type="button" class="btn btn-light" id="close-modal" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts-modal-reklasifikasi')
<script>
  const formElem = jQuery('#formModal');

  const openFormModal = ({ data, doc }) => {
    formElem.find('[name="kode_jenis_dokumen"]').val(doc.kode_jenis_dokumen);
    formElem.find('[name="no_dokumen"]').val(doc.no_dokumen);
    formElem.find('[name="tgl_dokumen"]').val(doc.tgl_dokumen);
    formElem.find('[name="uraian_dokumen"]').val(doc.uraian_dokumen);

    if (!!data) {
      formElem.find('[name="_method"]').val('PUT');
      formElem.find('[name="tgl_pembukuan"]').val(data.tgl_pembukuan);
      formElem.find('[name="tgl_pembukuan"]').attr('data-deafult-date', data.tgl_pembukuan);
      formElem.find('[name="barang_id"]').val(data.barang_id).change();
      formElem.find('[name="jumlah_barang"]').val(data.jumlah_barang);
      formElem.find('[name="keterangan"]').val(data.keterangan);
      formElem.find('[type="submit"]').html('Update');
    } else {
      formElem.find('[name="tgl_pembukuan"]').val(doc.data[0].tgl_pembukuan);
      formElem.find('[name="tgl_pembukuan"]').attr('data-deafult-date', doc.data[0].tgl_pembukuan);
    }

    let url = !!data ? 
      `{{ url('pembukuan/reklasifikasi/barang/${data.id}') }}` 
      : 
      `{{ route('pembukuan.reklasifikasi.store') }}`;
    formElem.find('form').attr('action', url);

    formElem.modal('show');
  }

  $(function () {
    formElem.on('hidden.bs.modal', () => {
      jQuery(this).find('form').trigger('reset');
      jQuery(this).find('[name="_method"]').val('POST');
      jQuery(this).find('[name="barang_id"]').val('').change();
      jQuery(this).find('[type="submit"]').html('Submit');
    });
  });
</script>
@endpush