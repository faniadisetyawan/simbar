<?php

namespace App\Http\Controllers\Penyaluran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SpbController extends Controller
{
    public function index(Request $request) 
    {
        return view('penyaluran.spb.index');
    }
}
