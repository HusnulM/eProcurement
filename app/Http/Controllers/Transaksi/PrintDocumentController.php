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
        $attachments = DB::table('t_attachments')->where('doc_object','PR')->where('doc_number', $prhdr->prnum)->get();
        $approvals   = DB::table('v_pr_approval')->where('prnum', $prhdr->prnum)->get();
        // return $attachments;
        return view('transaksi.pr.prdetail', 
            [
                'department'    => $department, 
                'prhdr'         => $prhdr, 
                'pritem'        => $prdtl, 
                'attachments'   => $attachments, 
                'approvals'     => $approvals
            ]);
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
        return view('transaksi.pbj.list');
    }

    public function printpbj($id){
        $prhdr = DB::table('t_pbj01')->where('id', $id)->first();
        $prdtl = DB::table('t_pbj02')->where('pbjnumber', $prhdr->pbjnumber)->get();
        $logo = DB::table('general_setting')->where('setting_name', 'COMPANY_LOGO')->first();
        // $customPaper = array(0,0,567.00,283.80);
        $pdf = PDF::loadview('transaksi.pbj.printpbj', ['hdr' => $prhdr, 'item' => $prdtl, 'logo' => $logo])->setPaper('A5','landscape');
        // $pdf = ('P','mm','A5');
        $pdf->render();
        return $pdf->stream();
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
        $department  = DB::table('t_department')->get();
        $pohdr       = DB::table('v_rpo')->where('id', $id)->first();
        $podtl       = DB::table('t_po02')->where('ponum', $pohdr->ponum)->get();
        $costs       = DB::table('t_po03')->where('ponum', $pohdr->ponum)->get();
        $attachments = DB::table('t_attachments')->where('doc_object','PO')->where('doc_number', $pohdr->ponum)->get();
        $approvals   = DB::table('v_po_approval')->where('ponum', $pohdr->ponum)->get();


        return view('transaksi.po.podetail', 
            [
                'department'    => $department, 
                'pohdr'         => $pohdr, 
                'poitem'        => $podtl,
                'costs'         => $costs,
                'attachments'   => $attachments, 
                'approvals'     => $approvals
            ]);
    }

    public function printpo($id){
        $pohdr = DB::table('v_rpo')->where('id', $id)->first();
        $podtl = DB::table('t_po02')->where('ponum', $pohdr->ponum)->get();

        $pdf = PDF::loadview('transaksi.po.formpo', ['pohdr' => $pohdr, 'poitem' => $podtl]);
        $pdf->setOptions(['isRemoteEnabled' => true]);
        $pdf->setProtocol($_SERVER['DOCUMENT_ROOT']);
        // $pdf = PDF::loadview('transaksi.po.printpo', ['pohdr' => $pohdr, 'poitem' => $podtl]);
        return $pdf->stream();
    }

    public function wolist(Request $req){
        return view('transaksi.spk.printlist');
    }

    public function wodetail($id){
        // return view('transaksi.spk.printlist');
        $wohdr = DB::table('t_wo01')->where('id', $id)->first();
        if($wohdr){
            $woitem     = DB::table('t_wo02')->where('wonum', $wohdr->wonum)->get();
            $mekanik    = DB::table('t_mekanik')->where('id', $wohdr->mekanik)->first();
            $warehouse  = DB::table('t_warehouse')->where('id', $wohdr->whscode)->first();
            $kendaraan  = DB::table('t_kendaraan')->where('id', $wohdr->license_number)->first();
            $attachments = DB::table('t_attachments')->where('doc_object', 'SPK')->where('doc_number', $wohdr->wonum)->get();
            // return $woitem;

            return view('transaksi.spk.printdetail', 
                [
                    'spkhdr'      => $wohdr, 
                    'spkitems'    => $woitem,
                    'mekanik'     => $mekanik,
                    'warehouse'   => $warehouse,
                    'kendaraan'   => $kendaraan,
                    'attachments' => $attachments
                ]);
        }else{
            return Redirect::to("/printdoc/wo")->withError('Data SPK/Work Order tidak ditemukan');
        }
    }

    public function printwo($id){
        $prhdr = DB::table('t_wo01')->where('id', $id)->first();
        $prdtl = DB::table('t_wo02')->where('wonum', $prhdr->wonum)->get();

        $pdf = PDF::loadview('transaksi.spk.printspk', ['prhdr' => $prhdr, 'pritem' => $prdtl]);
        return $pdf->stream();
    }

    public function grpo(){
        return view('transaksi.movement.grpolist');
    }

    public function grpolist(){
        $query = DB::table('v_rgrpo')->select('id','docnum','postdate','received_by','vendor_name','remark')->distinct();

        if(isset($req->datefrom) && isset($req->dateto)){
            $query->whereBetween('postdate', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('postdate', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('postdate', $req->dateto);
        }

        // $query->where('createdby', Auth::user()->email);

        $query->orderBy('id');

        return DataTables::queryBuilder($query)
        ->editColumn('postdate', function ($query){
            return [
                'postdate1' => \Carbon\Carbon::parse($query->postdate)->format('d-m-Y')
             ];
        })
        ->toJson();
    }

    public function printgrpo($id){
        $pohdr = DB::table('v_rgrpo')->select('id','docnum','postdate','received_by','vendor_name','remark')->where('id', $id)->first();
        $podtl = DB::table('v_rgrpo')->where('docnum', $pohdr->docnum)->get();

        $pdf = PDF::loadview('transaksi.movement.printgrpo', ['pohdr' => $pohdr, 'poitem' => $podtl]);
        return $pdf->stream();
    }

    public function grpodetail($id){
        $pohdr = DB::table('v_rgrpo')->select('id','docnum','postdate','received_by','vendor_name','remark')->where('id', $id)->first();
        $podtl = DB::table('v_rgrpo')->where('docnum', $pohdr->docnum)->get();
        return view('transaksi.movement.grpodetail', ['pohdr' => $pohdr, 'poitem' => $podtl]);
    }

    public function issuedlist(){

    }

    public function printissued(){

    }
}
