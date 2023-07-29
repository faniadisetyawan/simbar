<?php

namespace App\Http\Controllers\Pembukuan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PerolehanController extends Controller
{
    public function index($slug) 
    {
        return view('perolehan.index', [
            'slug' => $slug,
        ]);
    }
}
