<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\ProviderTraits;
use App\Traits\MutasiTraits;
use App\Http\Requests\PersediaanMasterRequest;
use App\Imports\Persediaan\PersediaanMasterImport;
use App\PersediaanMaster;
use App\KodefikasiSubRincianObjek;
use App\MutasiTambah;

class PersediaanMasterController extends Controller
{
    use ProviderTraits, MutasiTraits;

    private $pageTitle;

    public function __construct()
    {
        $this->middleware('auth');
        $this->pageTitle = 'Kartu Barang Persediaan';
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $active = $request->boolean('active', TRUE);

        $query = PersediaanMaster::query();
        $query->when($active === FALSE, function ($q) {
            return $q->onlyTrashed();
        });
        $query->where(function ($q) use ($search) {
            $q->orWhere('kode_barang', 'like', '%'.$search.'%');
            $q->orWhere('kode_register', 'like', '%'.$search.'%');
            $q->orWhere('nama_barang', 'like', '%'.$search.'%');
            $q->orWhere('spesifikasi', 'like', '%'.$search.'%');
        });

        $data = $query->paginate(25);

        return view('persediaan.master', [
            'filter' => [
                'search' => $search,
                'active' => $active,
            ],
            'data' => $data,
        ]);
    }

    public function create() 
    {
        $queryKodefikasiSubRincianObjek = KodefikasiSubRincianObjek::query();
        $queryKodefikasiSubRincianObjek->where(DB::raw('LEFT(kode, 5)'), '1.1.7');
        $queryKodefikasiSubRincianObjek->orderBy('kode');
        $kodefikasiSubRincianObjek = $queryKodefikasiSubRincianObjek->get();

        return view('persediaan.form', [
            'kodefikasi' => $kodefikasiSubRincianObjek,
        ]);
    }

    public function store(PersediaanMasterRequest $request) 
    {
        $validated = $request->validated();
        $validated['kode_register'] = $this->_generateNUSP($validated['kode_barang']);
        $validated['user_id'] = auth()->id();

        $data = new PersediaanMaster($validated);
        $data->save();

        return redirect()->back()->with('success', 'Item berhasil ditambahkan.');
    }

    public function update(PersediaanMasterRequest $request, $id) 
    {
        $validated = $request->validated();

        $data = PersediaanMaster::findOrFail($id);
        if ($data['kode_barang'] !== $validated['kode_barang']) {
            $validated['kode_register'] = $this->_generateNUSP($validated['kode_barang']);
        }
        $data->update($validated);

        return redirect()->route('master.persediaan.index')->with('success', 'Item berhasil diupdate.');
    }

    public function import(Request $request) 
    {
        $request->validate([
            'document' => ['required', 'file'],
        ]);

        if ($request->hasFile('document')) {
            Excel::import(new PersediaanMasterImport, $request->file('document'));
        }
        
        return redirect()->back()->with('success', 'Semua data berhasil diimport.');
    }

    public function kartuPersediaan(Request $request) 
    {
        $tglPembukuan = $request->query('tgl_pembukuan');
        $barangId = $request->query('barang_id');
        
        if (!empty($tglPembukuan) && !empty($barangId)) {
            $master = PersediaanMaster::findOrFail($barangId);
            $data = $this->logMutasiTrait($tglPembukuan, $barangId);

            return Pdf::loadView('laporan.pdf.kartu-persediaan', [
                'pageTitle' => $this->pageTitle,
                'tglPembukuan' => $tglPembukuan,
                'master' => $master,
                'data' => $data,
            ])->setPaper('a4', 'landscape')->stream();
        }

        return view('laporan.kartu-persediaan', [
            'pageTitle' => $this->pageTitle,
        ]);
    }
}
