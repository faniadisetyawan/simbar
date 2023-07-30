<?php

namespace App\Http\Requests\Pembukuan;

use Illuminate\Foundation\Http\FormRequest;

class PerolehanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tgl_pembukuan' => ['required', 'date'],
            'kode_jenis_dokumen' => ['required'],
            'no_dokumen' => ['required', 'max: 100'],
            'tgl_dokumen' => ['required', 'date'],
            'uraian_dokumen' => ['nullable'],
            'bidang_id' => ['required'],
            'barang_id' => ['required'],
            'jumlah_barang' => ['required', 'gte:0'],
            'nilai_perolehan' => ['required', 'gte:0'],
            'keterangan' => ['nullable'],
        ];
    }
}
