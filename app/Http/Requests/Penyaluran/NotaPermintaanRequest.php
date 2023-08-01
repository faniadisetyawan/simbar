<?php

namespace App\Http\Requests\Penyaluran;

use Illuminate\Foundation\Http\FormRequest;

class NotaPermintaanRequest extends FormRequest
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
            'no_dokumen' => ['required', 'max: 100'],
            'tgl_dokumen' => ['required', 'date'],
            'uraian_dokumen' => ['nullable'],
            'bidang_id' => ['required'],
            'barang_id' => ['required'],
            'jumlah_barang_permintaan' => ['required', 'gte:0'],
            'keperluan' => ['required'],
            'keterangan' => ['nullable'],
        ];
    }
}
