<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class TransferController extends Controller
{
    public function index(){
        return view('laporan.rtransfer');
    }

    public function getData(Request $req){
        $query = DB::table('v_report_transfer');

        if(isset($req->datefrom) && isset($req->dateto)){
            $query->whereBetween('postdate', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('postdate', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('postdate', $req->dateto);
        }

        $query->orderBy('id');

        return DataTables::queryBuilder($query)
        ->editColumn('postdate', function ($query){
            return [
                'postdate1' => \Carbon\Carbon::parse($query->postdate)->format('d-m-Y')
             ];
        })
        ->editColumn('quantity', function ($query){
            return [
                'qty1' => number_format($query->quantity,0)
             ];
        })
        ->toJson();
    }
}
