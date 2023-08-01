<?php

namespace App\Http\Controllers\Penyaluran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PersediaanPenyaluran as Penyaluran;
use App\Http\Requests\Penyaluran\NotaPermintaanRequest;

class NotaPermintaanController extends Controller
{
    private $pageTitle;

    public function __construct() 
    {
        $this->pageTitle = 'Nota Permintaan Barang';
    }

    public function index(Request $request) 
    {
        $search = $request->query('search');
        $bidangId = $request->query('bidang_id');

        return view('penyaluran.nota-permintaan.index', [
            'pageTitle' => $this->pageTitle,
            'filter' => [
                'search' => $search,
                'bidang_id' => $bidangId,
            ],
        ]);
    }

    public function create() 
    {
        return view('penyaluran.nota-permintaan.create', [
            'pageTitle' => $this->pageTitle,
        ]);
    }

    public function store(NotaPermintaanRequest $request) 
    {
        $validated = $request->validated();
        $validated['kode_pembukuan'] = '31';
        $validated['kode_jenis_dokumen'] = '08';
        $validated['slug_dokumen'] = Str::slug($validated['no_dokumen'], '-');
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        $data = new Penyaluran($validated);
        $data->save();

        return redirect()->back()->with('success', 'Data berhasil ditambahkan.');
    }
}
