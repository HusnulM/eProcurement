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
        if(!$prhdr){
            return Redirect::to("/proc/pr/listpr")->withError("PR tidak ditemukan!");
        }else{
            $prdtl = DB::table('t_pr02')->where('prnum', $prhdr->prnum)->get();
            $attachments = DB::table('t_attachments')->where('doc_object','PR')->where('doc_number', $prhdr->prnum)->get();
            $approvals   = DB::table('v_pr_approval_v2')
                           ->where('prnum', $prhdr->prnum)
                           ->orderBy('approver_level', 'ASC')
                           ->orderBy('pritem', 'ASC')
                           ->get();

            $pbjNumber = DB::table('t_pr02')->where('prnum', $prhdr->prnum)->pluck('pbjnumber');
            $pbjAttachments = DB::table('t_attachments')->where('doc_object','PBJ')
                              ->whereIn('doc_number', $pbjNumber)->get();
            // return $attachments;
            return view('transaksi.pr.prdetail',
                [
                    'department'     => $department,
                    'prhdr'          => $prhdr,
                    'pritem'         => $prdtl,
                    'attachments'    => $attachments,
                    'approvals'      => $approvals,
                    'pbjAttachments' => $pbjAttachments
                ]);
        }
    }

    public function printprlist(Request $req){
        $query = DB::table('v_pr01')->select('id','prnum','prdate','approvestat','requestby','remark','deptname','createdby')->distinct();

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

        $checkObjAuth = DB::table('user_object_auth')
                        ->where('object_name', 'ALLOW_DISPLAY_ALL_DEPT')
                        ->where('object_val', 'Y')
                        ->where('userid', Auth::user()->id)
                        ->first();
        if($checkObjAuth){

        }else{
            $query->where('createdby', Auth::user()->email);
        }
        // $query->where('createdby', Auth::user()->email);

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
        $prhdr    = DB::table('t_pr01')->where('id', $id)->first();
        $prdtl    = DB::table('v_pr01')->where('prnum', $prhdr->prnum)->get();
        $proyek   = DB::table('t_projects')->where('id', $prhdr->idproject)->first();

        $disetjui = DB::table('t_master_approval')->where('id', $prhdr->disetujui)->first();
        $diktahui = DB::table('t_master_approval')->where('id', $prhdr->diketahui)->first();

        $dtlGroup = DB::table('v_pr01')
                    ->select('prnum','itemtext')
                    ->distinct()
                    ->where('prnum', $prhdr->prnum)
                    ->get();

        $pdf = PDF::loadview('transaksi.pr.printpr',
            [
                'prhdr'       => $prhdr,
                'pritem'      => $prdtl,
                'dtlGroup'    => $dtlGroup,
                'project'     => $proyek->nama_project,
                'disetjui'      => $disetjui,
                'diktahui'      => $diktahui,
                'proyek' => $proyek
            ]);
        // $pdf->setOptions(['isPhpEnabled' => true]);
        $pdf->set_option("enable_php", true);
        return $pdf->stream();
    }

    public function pbjlist(){
        return view('transaksi.pbj.list');
    }

    public function polist(){
        $department = DB::table('t_department')->get();
        return view('transaksi.po.printpolist', ['department' => $department]);
    }

    public function printpo($id){
        $pohdr = DB::table('v_po01')->where('id', $id)->first();
        if($pohdr){
            $podtl = DB::table('t_po02')
                    ->join('t_material', 't_po02.material', '=', 't_material.material')
                    ->leftJoin('v_cost_master', 't_po02.cost_code', '=', 'v_cost_master.id')
                    ->select('t_po02.*','t_material.matspec', 'v_cost_master.cost_code as costcd',
                            'v_cost_master.cost_desc', 'v_cost_master.cost_group_desc')
                    ->where('t_po02.ponum', $pohdr->ponum)
                    ->get();

            $vendor = DB::table('t_vendor')->where('id', $pohdr->vendor)->first();
            $userPO = DB::table('users')->where('username', $pohdr->createdby)->first();

            $totalPrice = DB::table('v_po02')
                    ->where('ponum', $pohdr->ponum)
                    ->sum('totalprice');
            // dd($totalPrice);
            $proyek   = DB::table('t_projects')->where('id', $podtl[0]->idproject)->first();

            $pdf = PDF::loadview('transaksi.po.formpo',
            [
                'pohdr'          => $pohdr,
                'poitem'         => $podtl,
                'vendor'         => $vendor,
                'totalPrice'     => $totalPrice,
                'proyek'         => $proyek
            ]);
            // $pdf->setOptions(['isPhpEnabled' => true]);
            $pdf->set_option("enable_php", true);
            return $pdf->stream();
        }else{
            return 'PO tidak ditemukan';
        }

        // if($pohdr->is_posolar === 'Y'){
        //     $pdf = PDF::loadview('transaksi.po.formposolar',
        //     [
        //         'pohdr'          => $pohdr,
        //         'poitem'         => $podtl,
        //         'vendor'         => $vendor,
        //         'firstApprover'  => $firstApprover ?? null,
        //         'secondApprover' => $secondApprover ?? null,
        //         'lastApprover'   => $lastApprover ?? null,
        //         'firstApprovalDate'  => $firstApprovalDate ?? null,
        //         'secondApprovalDate' => $secondApprovalDate ?? null,
        //         'lastApprovalDate'   => $lastApprovalDate ?? null
        //     ]);
        // }else{
        // }

    }

    public function wolist(Request $req){
        return view('transaksi.spk.printlist');
    }

    public function wodetail($id){
        // return view('transaksi.spk.printlist');
        $wohdr = DB::table('v_spk01')->where('id', $id)->first();
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
