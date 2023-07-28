<?php

namespace App\Http\Requests\SaldoAwal;

use Illuminate\Foundation\Http\FormRequest;

class PersediaanRequest extends FormRequest
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
            'barang_id' => ['required'],
            'jumlah_barang' => ['required'],
            'nilai_perolehan' => ['required'],
            'keterangan' => ['nullable'],
        ];
    }
}
