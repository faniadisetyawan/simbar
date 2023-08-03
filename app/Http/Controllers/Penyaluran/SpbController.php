<?php

namespace App\Http\Controllers\Penyaluran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Penyaluran\SpbRequest;
use App\PersediaanPenyaluran as Penyaluran;
use App\DokumenUpload;

class SpbController extends Controller
{
    private $pageTitle;

    public function __construct() 
    {
        $this->pageTitle = 'Surat Permintaan Barang';
    }

    public function index(Request $request) 
    {
        $search = $request->query('search');
        $bidangId = $request->query('bidang_id');

        return view('penyaluran.spb.index', [
            'pageTitle' => $this->pageTitle,
            'filter' => [
                'search' => $search,
                'bidang_id' => $bidangId,
            ],
        ]);
    }

    public function create() 
    {
        return view('penyaluran.spb.create', [
            'pageTitle' => $this->pageTitle,
        ]);
    }
}
