<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Mail\NotifApprovePrMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use DataTables, Auth, DB;
use Validator,Redirect,Response;
use PDF;

class PurchaseRequestController extends Controller
{
    public function index(){
        $proyek = DB::table('t_projects')->get();
        return view('transaksi.pr.index', ['proyek' => $proyek]);
    }

    public function listPR(){
        $proyek = DB::table('t_projects')->get();
        return view('transaksi.pr.printlist', ['proyek' => $proyek]);
    }

    public function duedate(){
        return view('transaksi.pr.duedatepr');
    }

    public function getListPR(Request $req)
    {
        $query = DB::table('t_pr01')
            ->join('t_projects', 't_pr01.idproject', '=', 't_projects.id')
            ->select('t_pr01.*','t_projects.kode_project', 't_projects.nama_project', 't_projects.project_manager')->distinct();

        if(isset($req->approvalstat)){
            if($req->approvalstat === "O"){
                $query->where('t_pr01.approvestat', 'O');
            }elseif($req->approvalstat === "A"){
                $query->where('t_pr01.approvestat', 'A');
            }elseif($req->approvalstat === "R"){
                $query->where('t_pr01.approvestat', 'R');
            }
        }

        if(isset($req->datefrom) && isset($req->dateto)){
            $query->whereBetween('t_pr01.prdate', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('t_pr01.prdate', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('t_pr01.prdate', '<=', $req->dateto);
        }

        if(isset($req->project)){
            if($req->project !== 'All'){
                $query->where('idproject', $req->project);
            }
        }

        $query->orderBy('t_pr01.id');

        return DataTables::queryBuilder($query)
        ->editColumn('prdate', function ($query){
            return [
                'prdate1' => \Carbon\Carbon::parse($query->prdate)->format('d-m-Y')
             ];
        })
        ->toJson();
    }

    public function listDuedatePR(Request $request){
        if(isset($request->params)){
            $params = $request->params;
            $whereClause = $params['sac'];
        }
        $query = DB::table('v_pr_duedate')
                ->where('duedate','>','3')
                ->where('pocreated','N')
                 ->orderBy('id');
        return DataTables::queryBuilder($query)
        ->editColumn('quantity', function ($query){
            return [
                'quantity1' => number_format($query->quantity,0)
             ];
        })
        ->editColumn('prdate', function ($query){
            return [
                'prdate1' => \Carbon\Carbon::parse($query->prdate)->format('d-m-Y')
            ];
        })
        ->toJson();
    }

    public function prdetail($id){
        $prhdr = DB::table('t_pr01')->where('id', $id)->first();
        $prdtl = DB::table('t_pr02')
                ->join('t_material', 't_pr02.material', '=', 't_material.material')
                ->select('t_pr02.*','t_material.matspec')
                ->where('t_pr02.prnum', $prhdr->prnum)
                ->where('t_pr02.isdeleted', 'N')
                ->get();

        $attachments = DB::table('t_attachments')
                        ->where('doc_object','PR')
                        ->where('doc_number', $prhdr->prnum)
                        ->get();

        $proyek = DB::table('t_projects')->get();
        $proyekx = DB::table('t_projects')->where('id', $prhdr->idproject)->first();

        $approvals   = DB::table('v_pr_approval01')->where('prnum', $prhdr->prnum)->get();
        // return $attachments;
        return view('transaksi.pr.prdetail',
            [
                'prhdr'          => $prhdr,
                'pritem'         => $prdtl,
                'attachments'    => $attachments,
                'proyek'         => $proyek,
                'proyekx'        => $proyekx,
                'approvals'      => $approvals
            ]);
    }

    public function changePR($id){
        $prhdr = DB::table('t_pr01')->where('id', $id)->first();
        $prdtl = DB::table('t_pr02')
                ->join('t_material', 't_pr02.material', '=', 't_material.material')
                ->select('t_pr02.*','t_material.matspec')
                ->where('t_pr02.prnum', $prhdr->prnum)
                ->where('t_pr02.isdeleted', 'N')
                ->get();

        $attachments = DB::table('t_attachments')
                        ->where('doc_object','PR')
                        ->where('doc_number', $prhdr->prnum)
                        ->get();

        $proyek = DB::table('t_projects')->get();
        $proyekx = DB::table('t_projects')->where('id', $prhdr->idproject)->first();

        $approvals   = DB::table('v_pr_approval01')->where('prnum', $prhdr->prnum)->get();
        // return $attachments;
        return view('transaksi.pr.change',
            [
                // 'department'    => $department,
                'prhdr'         => $prhdr,
                'pritem'        => $prdtl,
                'attachments'   => $attachments,
                'proyek'        => $proyek,
                'proyekx'       => $proyekx,
                'approvals'     => $approvals
            ]);
    }

    public function save(Request $req){
        // return $req;
        DB::beginTransaction();
        try{
            if(!isset($req['parts'])){
                // return Redirect::to("/proc/pr")->withError('Item PR Blum di isi');
                $result = array(
                    'msgtype' => '500',
                    'message' => 'Item PR Blum di isi'
                );
                return $result;
            }
            $tgl   = substr($req['tglreq'], 8, 2);
            $bulan = substr($req['tglreq'], 5, 2);
            $tahun = substr($req['tglreq'], 2, 2);

            $kodeProject = null;
            $project = DB::table('t_projects')->where('id', $req['project'])->first();
            if($project){
                $kodeProject = $project->kode_project;
            }
            if($req['prtype'] === "AA"){
                $kodeProject = 'AA';
            }

            $ptaNumber = generatePRNumber($tahun, $bulan, $req['prtype'], $kodeProject);

            $prID = DB::table('t_pr01')->insertGetId([
                'prnum'             => $ptaNumber,
                'typepr'            => $req['prtype'],
                'prdate'            => $req['tglreq'],
                'idproject'         => $req['project'],
                'requestby'         => $req['requestor'],
                'remark'            => $req['remark'],
                'createdon'         => getLocalDatabaseDateTime(),
                'createdby'         => Auth::user()->username
            ]);

            $parts    = $req['parts'];
            $partdsc  = $req['partdesc'];
            $quantity = $req['quantity'];
            $uom      = $req['uoms'];
            $itemtext = $req['remarks'];

            $insertData = array();
            $count = 0;

            $prItems = array();

            for($i = 0; $i < sizeof($parts); $i++){
                $qty    = $quantity[$i];
                $qty    = str_replace(',','',$qty);

                $count = $count + 1;
                $data = array(
                    'prnum'        => $ptaNumber,
                    'pritem'       => $count,
                    'material'     => $parts[$i],
                    'matdesc'      => $partdsc[$i],
                    'quantity'     => $qty,
                    'unit'         => $uom[$i],
                    'remark'       => $itemtext[$i],
                    'idproject'    => $req['project'],
                    'createdon'    => getLocalDatabaseDateTime(),
                    'createdby'    => Auth::user()->username
                );

                array_push($insertData, $data);
                array_push($prItems, $data);
            }
            // return $insertData;
            insertOrUpdate($insertData,'t_pr02');

            //Insert Attachments | t_attachments
            if(isset($req['efile'])){
                $files = $req['efile'];
                $insertFiles = array();

                foreach ($files as $efile) {
                    $filename = $efile->getClientOriginalName();
                    $upfiles = array(
                        'doc_object' => 'PR',
                        'doc_number' => $ptaNumber,
                        'efile'      => $filename,
                        'pathfile'   => '/files/PR/'. $filename,
                        'createdon'  => getLocalDatabaseDateTime(),
                        'createdby'  => Auth::user()->username
                    );
                    array_push($insertFiles, $upfiles);

                    $efile->move('files/PR/', $filename);
                }
                if(sizeof($insertFiles) > 0){
                    insertOrUpdate($insertFiles,'t_attachments');
                }
            }
            // insertOrUpdate($insertFiles,'t_attachments');

            //Set Approval
            $approvalActive = DB::table('general_setting')->where('setting_name', 'WORKFLOW_PR_ACTIVE')->first();
            if($approvalActive->setting_value === "Y"){
                $approval = DB::table('v_workflows')->where('object', 'PR')->where('requester', Auth::user()->id)->get();
                if(sizeof($approval) > 0){
                    $insertApproval = array();
                    foreach($approval as $row){
                        $is_active = 'N';
                        if($row->approver_level == 1){
                            $is_active = 'Y';
                        }
                        $approvals = array(
                            'prnum'             => $ptaNumber,
                            // 'pritem'            => $prItems[$a]['pritem'],
                            'approver_level'    => $row->approver_level,
                            'approver'          => $row->approver,
                            'requester'         => Auth::user()->id,
                            'is_active'         => $is_active,
                            'createdon'         => getLocalDatabaseDateTime()
                        );
                        array_push($insertApproval, $approvals);
                    }
                    insertOrUpdate($insertApproval,'t_pr_approval');
                }else{
                    DB::rollBack();
                    $result = array(
                        'msgtype' => '500',
                        'message' => 'Approval belum di tambahkan untuk user '. Auth::user()->name
                    );
                    return $result;
                }
            }

            DB::commit();

            $result = array(
                'msgtype' => '200',
                'message' => 'PR Berhasil dibuat dengan Nomor : '. $ptaNumber
            );
            return $result;
        } catch(\Exception $e){
            DB::rollBack();
            $result = array(
                'msgtype' => '500',
                'message' => $e->getMessage()
            );
            return $result;
        }
    }

    public function update(Request $req, $prid){
        DB::beginTransaction();
        try{
            if(!isset($req['material'])){
                $result = array(
                    'msgtype' => '500',
                    'message' => 'PR : '. $ptaNumber . ' - Item PR Blum di isi'
                );
                return $result;
            }

            $prhdr = DB::table('t_pr01')->where('id', $prid)->first();

            $ptaNumber = $prhdr->prnum;

            $checkApproval = DB::table('t_pr_approval')
                ->where('prnum', $ptaNumber)->where('approval_status', 'A')->first();

            if($checkApproval){
                $result = array(
                    'msgtype' => '500',
                    'message' => 'PR : '. $ptaNumber . ' sudah di approve, data tidak bisa diupdate'
                );
                return $result;
            }

            DB::table('t_pr01')->where('id', $prid)->update([
                'prnum'             => $ptaNumber,
                'prdate'            => $req['tglreq'],
                'requestby'         => $req['requestor'],
                'remark'            => $req['remark'],
                'idproject'         => $req['project'],
                'changedon'         => getLocalDatabaseDateTime(),
                'changedby'         => Auth::user()->username
            ]);

            $parts    = $req['material'];
            $partdsc  = $req['matdesc'];
            $quantity = $req['quantity'];
            $uom      = $req['unit'];
            $itemtext = $req['remarks'];
            $pritem   = $req['pritem'];

            $insertData = array();
            $prItems    = array();
            $count      = 0;

            for($i = 0; $i < sizeof($parts); $i++){
                $qty    = $quantity[$i];
                $qty    = str_replace(',','',$qty);

                if($pritem[$i]){
                    $count = $pritem[$i];
                    $data = array(
                        'prnum'        => $ptaNumber,
                        'pritem'       => $count,
                        'material'     => $parts[$i],
                        'matdesc'      => $partdsc[$i],
                        'quantity'     => $qty,
                        'unit'         => $uom[$i],
                        'remark'       => $itemtext[$i],
                        'createdon'    => $prhdr->createdon,
                        'createdby'    => $prhdr->createdby,
                        'changedon'    => getLocalDatabaseDateTime(),
                        'changedby'    => Auth::user()->username
                    );
                }else{
                    $count += 1;
                    $data = array(
                        'prnum'        => $ptaNumber,
                        'pritem'       => $count,
                        'material'     => $parts[$i],
                        'matdesc'      => $partdsc[$i],
                        'quantity'     => $qty,
                        'unit'         => $uom[$i],
                        'remark'       => $itemtext[$i],
                        'createdon'    => getLocalDatabaseDateTime(),
                        'createdby'    => Auth::user()->username,
                        'changedon'    => getLocalDatabaseDateTime(),
                        'changedby'    => Auth::user()->username
                    );
                }

                array_push($insertData, $data);
                array_push($prItems, $data);
            }

            // return $insertData;
            insertOrUpdate($insertData,'t_pr02');

            //Insert Attachments | t_attachments
            if(isset($req['efile']) && $req['efile'][0] != null){
                $files = $req['efile'];
                $insertFiles = array();

                foreach ($files as $efile) {
                    $filename = $efile->getClientOriginalName();
                    $upfiles = array(
                        'doc_object' => 'PR',
                        'doc_number' => $ptaNumber,
                        'efile'      => $filename,
                        'pathfile'   => '/files/PR/'. $filename,
                        'createdon'  => getLocalDatabaseDateTime(),
                        'createdby'  => Auth::user()->username
                    );
                    array_push($insertFiles, $upfiles);

                    $efile->move('files/PR/', $filename);
                    // $efile->move(public_path().'/files/PR/', $filename);
                }
                if(sizeof($insertFiles) > 0){
                    insertOrUpdate($insertFiles,'t_attachments');
                }
            }

            //Set Approval
            $approvalActive = DB::table('general_setting')->where('setting_name', 'WORKFLOW_PR_ACTIVE')->first();
            if($approvalActive->setting_value === "Y"){
                $approval = DB::table('v_workflows')->where('object', 'PR')->where('requester', Auth::user()->id)->get();
                if(sizeof($approval) > 0){
                    DB::table('t_pr_approval')->where('prnum', $ptaNumber)->delete();
                    $insertApproval = array();
                    foreach($approval as $row){
                        $is_active = 'N';
                        if($row->approver_level == 1){
                            $is_active = 'Y';
                        }
                        $approvals = array(
                            'prnum'             => $ptaNumber,
                            // 'pritem'            => $prItems[$a]['pritem'],
                            'approver_level'    => $row->approver_level,
                            'approver'          => $row->approver,
                            'requester'         => Auth::user()->id,
                            'is_active'         => $is_active,
                            'createdon'         => getLocalDatabaseDateTime()
                        );
                        array_push($insertApproval, $approvals);
                    }
                    insertOrUpdate($insertApproval,'t_pr_approval');
                }else{
                    DB::rollBack();
                    $result = array(
                        'msgtype' => '500',
                        'message' => 'Approval belum di tambahkan untuk user '. Auth::user()->name
                    );
                    return $result;
                }
            }

            DB::commit();
            // return Redirect::to("/proc/pr/change/".$prid)->withSuccess('PR '. $ptaNumber .'Berhasil di update');
            $result = array(
                'msgtype' => '200',
                'message' => 'PR : '. $ptaNumber . ' Berhasil di update'
            );
            return $result;
        } catch(\Exception $e){
            DB::rollBack();
            $result = array(
                'msgtype' => '500',
                'message' => $e->getMessage()
            );
            return $result;
            // return Redirect::to("/proc/pr/change/".$prid)->withError($e->getMessage());
            // dd($e->getMessage());
        }
    }

    public function deletePR($id){
        DB::beginTransaction();
        try{
            $prhdr = DB::table('t_pr01')->where('id', $id)->first();
            $pbjdoc = DB::table('t_pr02')
                        ->where('prnum', $prhdr->prnum)->get();

            $checkApproval = DB::table('v_pr_approval_v2')
                        ->where('prnum', $prhdr->prnum)->where('approval_status', 'A')->first();

            if($checkApproval){
                return Redirect::to("/proc/pr/listpr")->withError('PR : '. $prhdr->prnum . ' sudah di approve, data tidak bisa dihapus');
            }

            DB::table('t_pr01')->where('id', $id)->delete();
            DB::table('t_attachments')->where('doc_object', 'PR')->where('doc_number',$prhdr->prnum)->delete();
            DB::table('t_pr_approval')->where('prnum', $prhdr->prnum)->delete();

            // return $pbjdoc;
            foreach($pbjdoc as $row){
                DB::table('t_pbj02')
                    ->where('pbjnumber', $row->pbjnumber)
                    ->where('pbjitem', $row->pbjitem)->update([
                        'prcreated' => 'N'
                ]);

                // DB::commit();
            }

            DB::commit();
            return Redirect::to("/proc/pr/listpr")->withSuccess('PR '. $prhdr->prnum .' Berhasil dihapus');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/proc/pr/listpr")->withError($e->getMessage());
            // dd($e->getMessage());
        }
    }

    public function deletePRItem(Request $req){
        DB::beginTransaction();
        try{
            $checkApproval = DB::table('v_pr_approval_v2')
                ->where('prnum', $req['prnum'])
                ->where('approval_status', 'A')
                ->first();

            if($checkApproval){
                $result = array(
                    'msgtype' => '500',
                    'message' => 'PR : '. $req['prnum'] . ' sudah di approve, data tidak bisa dihapus'
                );
                return $result;
            }

            $pbjdoc = DB::table('t_pr02')
                        ->where('prnum', $req['prnum'])
                        ->where('pritem', $req['pritem'])->get();

            // $prhdr = DB::table('t_pr01')->where('prnum', $req['prnum'])->first();
            // DB::table('t_pr02')->where('prnum', $req['prnum'])->where('pritem', $req['pritem'])->update([
            //     'isdeleted' => 'Y'
            // ]);
            DB::table('t_pr02')->where('prnum', $req['prnum'])->where('pritem', $req['pritem'])->delete();
            // DB::table('t_pr_approvalv2')->where('prnum', $req['prnum']); //->where('pritem', $req['pritem'])->delete();
            // DB::table('t_pr_approvalv2')->where('prnum', $prhdr->prnum)->delete();

            foreach($pbjdoc as $row){
                DB::table('t_pbj02')
                    ->where('pbjnumber', $row->pbjnumber)
                    ->where('pbjitem', $row->pbjitem)->update([
                        'prcreated' => 'N'
                ]);
            }

            DB::commit();

            $result = array(
                'msgtype' => '200',
                'message' => 'Item PR : '. $req['prnum'] . ' - ' . $req['pritem'] . ' berhasil dihapus'
            );
            // return Redirect::to("/approve/pr")->withSuccess('PR dengan Nomor : '. $ptaNumber . ' berhasil di approve');
            return $result;
        } catch(\Exception $e){
            DB::rollBack();
            $result = array(
                'msgtype' => '500',
                'message' => $e->getMessage()
            );
            return $result;
            // return Redirect::to("/proc/pr")->withError($e->getMessage());
            // dd($e->getMessage());
        }
    }
}
