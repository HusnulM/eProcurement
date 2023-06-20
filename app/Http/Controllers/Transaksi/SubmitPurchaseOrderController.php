<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class SubmitPurchaseOrderController extends Controller
{
    public function index(){
        $department = DB::table('t_department')->get();
        return view('transaksi.po.submitlist', ['department' => $department]);
    }

    public function approvedPOList(Request $req){
        $query = DB::table('v_submit_po');

        if(isset($req->department)){
            if($req->department !== 'All'){
                $query->where('deptid', $req->department);
            }
        }

        if(isset($req->datefrom) && isset($req->dateto)){
            $query->whereBetween('podat', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('podat', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('podat', $req->dateto);
        }

        $query->orderBy('id');

        return DataTables::queryBuilder($query)
        // ->editColumn('quantity', function ($query){
        //     return [
        //         'qty1' => number_format($query->quantity,0)
        //     ];
        // })->editColumn('grqty', function ($query){
        //     return [
        //         'qty2' => number_format($query->grqty,0)
        //     ];
        // })->editColumn('openqty', function ($query){
        //     return [
        //         'qty3' => number_format($query->openqty,0)
        //     ];
        // })->editColumn('price', function ($query){
        //     return [
        //         'price1' => number_format($query->price,0)
        //     ];
        // })
        ->editColumn('podat', function ($query){
            return [
                'podat1' => \Carbon\Carbon::parse($query->podat)->format('d-m-Y')
             ];
        })
        ->toJson();
    }

    public function getPoItems(Request $req){
        $data = DB::table('t_po02')->where('ponum', $req['ponum'])->get();
        return $data;
    }

    public function submitDatatoApi(Request $req){
        $poNumber = $req['ponum'];
        $result   = sendPurchaseOrder($poNumber);

        return $result;
    }
}
