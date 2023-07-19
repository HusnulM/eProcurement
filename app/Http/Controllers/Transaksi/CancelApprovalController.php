<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class CancelApprovalController extends Controller
{
    public function cancelApprovePO(){
        return view('transaksi.cancelapprove.cancelpo');
    }

    public function cancelApprovePR(){
        return view('transaksi.cancelapprove.cancelpr');
    }

    public function cancelApproveWO(){
        return view('transaksi.cancelapprove.cancelwo');
    }

    public function cancelApprovePBJ(){
        return view('transaksi.cancelapprove.cancelpbj');
    }
}
