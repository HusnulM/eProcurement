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

    public function create($prID)
    {
        $proyek = getAuthorizedProject();
        $prdata = DB::table('v_pr01')->where('id', $prID)->get();
        return view('transaksi.quotation.create', ['proyek' => $proyek, 'prdata' => $prdata]);
    }
}
