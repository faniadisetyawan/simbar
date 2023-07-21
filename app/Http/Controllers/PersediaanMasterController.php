<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Persediaan\PersediaanMasterImport;
use App\PersediaanMaster;
use App\KodefikasiSubRincianObjek;

class PersediaanMasterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $active = $request->boolean('active', TRUE);

        $query = PersediaanMaster::query();
        $query->when($active === FALSE, function ($q) {
            return $q->onlyTrashed();
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

    public function store(Request $request) 
    {
        $validated = $request->validate([
            'kode_barang' => ['required'],
            'nama_barang' => ['required'],
            'spesifikasi' => ['nullable'],
            'satuan' => ['required'],
        ]);

        $validated['kode_register'] = '123456';
        $validated['user_id'] = auth()->id();

        return response()->json($validated);
    }

    public function import(Request $request) 
    {
        $request->validate([
            'document' => ['required', 'file'],
        ]);

        if ($request->hasFile('document')) {
            Excel::import(new PersediaanMasterImport, $request->file('document'));
        }
        
        return redirect('/master/persediaan');
    }
}
