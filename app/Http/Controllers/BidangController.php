<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bidang;

class BidangController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = Bidang::orderBy('id')->get();

        return view('bidang', [
            'data' => $data,
        ]);
    }
}
