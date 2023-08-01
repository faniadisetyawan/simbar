<?php

namespace App\Http\Controllers\Penyaluran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SppbController extends Controller
{
    public function index(Request $request) 
    {
        return view('penyaluran.sppb.index');
    }
}
