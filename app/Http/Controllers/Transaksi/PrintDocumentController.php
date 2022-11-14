<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables, Auth, DB;
use Validator,Redirect,Response;
use PDF;

class PrintDocumentController extends Controller
{
    public function prlist(){
        $department = DB::table('t_department')->get();
        return view('transaksi.pr.printlist', ['department' => $department]);
    }   

    public function prdetail($id){
        $department = DB::table('t_department')->get();
        $prhdr = DB::table('t_pr01')->where('id', $id)->first();
        $prdtl = DB::table('t_pr02')->where('prnum', $prhdr->prnum)->get();

        return view('transaksi.pr.prdetail', ['department' => $department, 'prhdr' => $prhdr, 'pritem' => $prdtl]);
    }

    public function printprlist(Request $req){
        $query = DB::table('v_rpr')->select('id','prnum','prdate','approvestat','requestby','remark','deptname','createdby')->distinct();

        if(isset($req->department)){
            if($req->department !== 'All'){
                $query->where('deptid', $req->department);
            }
        }

        if(isset($req->approvalstat)){
            if($req->approvalstat === "O"){
                $query->where('approvestat', 'O');
            }elseif($req->approvalstat === "A"){
                $query->where('approvestat', 'A');                
            }elseif($req->approvalstat === "R"){
                $query->where('approvestat', 'R');         
            }
        }

        if(isset($req->datefrom) && isset($req->dateto)){
            $query->whereBetween('prdate', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('prdate', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('prdate', $req->dateto);
        }

        $query->where('createdby', Auth::user()->email);

        $query->orderBy('id');

        return DataTables::queryBuilder($query)
        ->editColumn('prdate', function ($query){
            return [
                'prdate1' => \Carbon\Carbon::parse($query->prdate)->format('d-m-Y')
             ];
        })
        ->toJson();
    }

    public function printpr($id){
        $prhdr = DB::table('t_pr01')->where('id', $id)->first();
        $prdtl = DB::table('t_pr02')->where('prnum', $prhdr->prnum)->get();

        $pdf = PDF::loadview('transaksi.pr.printpr', ['prhdr' => $prhdr, 'pritem' => $prdtl]);
        return $pdf->stream();
    }

    public function pbjlist(){

    }

    public function printpbj(){

    }

    public function polist(){
        $department = DB::table('t_department')->get();
        return view('transaksi.po.printpolist', ['department' => $department]);
    }

    public function printpolist(Request $req){
        $query = DB::table('v_rpo')->select('id','ponum','podat','approvestat','vendor','note','vendor_name','deptname','createdby')->distinct();

        if(isset($req->department)){
            if($req->department !== 'All'){
                $query->where('deptid', $req->department);
            }
        }

        if(isset($req->approvalstat)){
            if($req->approvalstat === "O"){
                $query->where('approvestat', 'O');
            }elseif($req->approvalstat === "A"){
                $query->where('approvestat', 'A');                
            }elseif($req->approvalstat === "R"){
                $query->where('approvestat', 'R');                
            }
        }

        if(isset($req->datefrom) && isset($req->dateto)){
            $query->whereBetween('podat', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('podat', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('podat', $req->dateto);
        }

        $query->where('createdby', Auth::user()->email);

        $query->orderBy('id');

        return DataTables::queryBuilder($query)
        ->editColumn('podat', function ($query){
            return [
                'podat1' => \Carbon\Carbon::parse($query->podat)->format('d-m-Y')
             ];
        })
        ->toJson();
    }

    public function podetail($id){
        $department = DB::table('t_department')->get();
        $pohdr = DB::table('v_rpo')->where('id', $id)->first();
        $podtl = DB::table('t_po02')->where('ponum', $pohdr->ponum)->get();

        return view('transaksi.po.podetail', ['department' => $department, 'pohdr' => $pohdr, 'poitem' => $podtl]);
    }

    public function printpo($id){
        $pohdr = DB::table('v_rpo')->where('id', $id)->first();
        $podtl = DB::table('t_po02')->where('ponum', $pohdr->ponum)->get();

        $pdf = PDF::loadview('transaksi.po.printpo', ['pohdr' => $pohdr, 'poitem' => $podtl]);
        return $pdf->stream();
    }

    public function wolist(){

    }

    public function printwo(){

    }

    public function grpolist(){

    }

    public function printgrpo(){

    }

    public function issuedlist(){

    }

    public function printissued(){

    }
}
