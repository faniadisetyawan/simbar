<?php

namespace App\Http\Controllers\Pembukuan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StockOpnameController extends Controller
{
    private $pageTitle;

    public function __construct() 
    {
        $this->pageTitle = 'Stock Opname';
    }

    public function index(Request $request) 
    {
        $filter = (object)[
            'search' => $request->query('search'),
        ];

        $data = [];

        return view('stock-opname.index', [
            'pageTitle' => $this->pageTitle,
            'filter' => $filter,
            'data' => $data,
        ]);
    }
}
