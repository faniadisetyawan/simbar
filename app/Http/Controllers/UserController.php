<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = User::query();
        $query->when(!empty($search), function ($q) use ($search) {
            $q->orWhere('username', 'like', '%'.$search.'%');
            $q->orWhere('nama', 'like', '%'.$search.'%');
            $q->orWhere('nip', 'like', '%'.$search.'%');
        });
        $query->orderBy('id');

        $data = $query->paginate(25);

        return view('user', [
            'filter' => [
                'search' => $search,
            ],
            'data' => $data,
        ]);
    }
}
