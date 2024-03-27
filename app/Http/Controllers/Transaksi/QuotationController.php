<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;


class QuotationController extends Controller
{
    public function index()
    {
        $proyek = getAuthorizedProject();
        $doctyp = getAuthorizedPOType('PR', 'ALLOW_PRTYPE');
        return view('transaksi.quotation.index', ['proyek' => $proyek, 'doctyp' => $doctyp]);
    }
}
