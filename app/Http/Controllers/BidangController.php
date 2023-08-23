<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bidang;

class BidangController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:1,2,3');
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $active = $request->boolean('active', TRUE);

        $query = Bidang::query();
        $query->when($active === FALSE, function ($q) {
            return $q->onlyTrashed();
        });
        $query->orderBy('id');

        $data = $query->get();

        return view('bidang.index', [
            'filter' => [
                'search' => $search,
                'active' => $active,
            ],
            'data' => $data,
        ]);
    }

    public function create() 
    {
        return view('bidang.form', [
            'props' => NULL,
        ]);
    }

    public function store(Request $request) 
    {
        $validated = $request->validate([
            'nama' => ['required'],
        ]);

        $data = new Bidang($validated);
        $data->save();

        return redirect()->back()->withInput()->with('success', 'Item berhasil ditambahkan.');
    }

    public function edit($id) 
    {
        $props = Bidang::findOrFail($id);
        
        return view('bidang.form', [
            'props' => $props,
        ]);
    }

    public function update(Request $request, $id) 
    {
        $validated = $request->validate([
            'nama' => ['required'],
        ]);

        $data = Bidang::findOrFail($id);
        $data->update($validated);

        return redirect()->route('master.bidang.index')->withInput()->with('success', 'Item berhasil diupdate.');
    }

    public function destroy($id) 
    {
        $data = Bidang::onlyTrashed()->findOrFail($id);
        $data->forceDelete();

        return redirect()->back()->with('success', 'Item berhasil dihapus secara permanen.');
    }

    public function restore($id) 
    {
        $data = Bidang::onlyTrashed()->findOrFail($id);
        $data->restore();

        return redirect()->back()->with('success', 'Item berhasil direstore.');
    }

    public function trash($id) 
    {
        $data = Bidang::findOrFail($id);
        $data->delete();

        return redirect()->back()->with('success', 'Item berhasil diarsipkan.');
    }
}
