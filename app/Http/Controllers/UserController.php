<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Hash;
use Storage;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:1,2,3', ['only' => ['index']]);
    }
    
    public function index(Request $request)
    {
        $search = $request->query('search');
        $active = $request->boolean('active', TRUE);

        $query = User::query();
        $query->when($active === FALSE, function ($q) {
            return $q->onlyTrashed();
        });
        $query->when(!empty($search), function ($q) use ($search) {
            $q->orWhere('username', 'like', '%'.$search.'%');
            $q->orWhere('nama', 'like', '%'.$search.'%');
            $q->orWhere('nip', 'like', '%'.$search.'%');

            $q->orWhereHas('bidang', function ($qBidang) use ($search) {
                $qBidang->where('nama', 'like', '%'.$search.'%');
            });
            
            $q->orWhereHas('role', function ($qRole) use ($search) {
                $qRole->where('nama', 'like', '%'.$search.'%');
            });
        });
        $query->orderBy('id');

        $data = $query->paginate(15);

        return view('user', [
            'filter' => [
                'search' => $search,
                'active' => $active,
            ],
            'data' => $data,
        ]);
    }

    public function profile() 
    {
        $data = auth()->user();

        return view('profile', [
            'pageTitle' => 'Profile',
            'data' => $data,
        ]);
    }

    public function update(Request $request, $id) 
    {
        $validated = $request->validate([
            'username' => ['required'],
            'nama' => ['required'],
            'nip' => ['required'],
            'bidang_id' => ['required'],
            'role_id' => ['required'],
        ]);

        $data = User::findOrFail($id);
        $data->update($validated);

        return redirect()->back()->with('success', 'Profile berhasil diupdate.');
    }

    public function changePassword(Request $request, $id) 
    {
        $validated = $request->validate([
            'password' => ['required', 'min:6', 'confirmed'],
        ]);
        $validated['password'] = Hash::make($validated['password']);

        $data = User::findOrFail($id);
        $data->update($validated);

        return redirect()->back()->with('success', 'Password berhasil diubah.');
    }

    public function uploadPhoto(Request $request, $id)
    {
        $validated = $request->validate([
            'foto' => ['required', 'image'],
        ]);

        $data = User::findOrFail($id);

        if ($data) {
            $filePath = 'public/users/'.$data->foto;
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }
        }

        if ($request->hasFile('foto')) {
            $extension = $request->foto->extension();
            $validated['foto'] = time().'.'.$extension;

            $request->file('foto')->storeAs('public/users', $validated['foto']);
        }

        $data->update($validated);

        return redirect()->back()->with('success', 'Foto profile berhasil diupdate.');
    }
}
