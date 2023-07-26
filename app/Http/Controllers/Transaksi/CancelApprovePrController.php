<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class CancelApprovePrController extends Controller
{
    public function index(){
        return view('transaksi.cancelapprove.cancelpr');
    }
}
