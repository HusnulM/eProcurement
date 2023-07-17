<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Mail\NotifApprovePbjMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class PbjController extends Controller
{
    public function index(){
        // $mekanik    = DB::table('t_mekanik')->get();
        // $department = DB::table('t_department')->get();
        // return view('transaksi.pbj.index', ['mekanik' => $mekanik, 'department' => $department]);
        // return view('transaksi.pbj.ceklisttidaklayak');
        return view('transaksi.pbj.openwo');
    }

    public function create($id){
        $wodata     = DB::table('v_wo_to_pbj')->where('id', $id)->where('wo_status', 'A')->first();
        if($wodata){
            $woitems    = DB::table('t_wo02')->where('wonum', $wodata->wonum)->get();
            $mekanik    = DB::table('t_mekanik')->get();
            $department = DB::table('t_department')->get();
            $cklist     = DB::table('v_checklist_kendaraan')->where('no_checklist', $wodata->cheklistnumber)->first();
            $kendaraan  = DB::table('t_kendaraan')->where('id', $cklist->no_plat)->first();
            $warehouse  = DB::table('t_warehouse')->where('id', $wodata->whscode)->first();
            return view('transaksi.pbj.create', 
                [
                    'wodata'     => $wodata,
                    'mekanik'    => $mekanik, 
                    'department' => $department, 
                    'cklist'     => $cklist, 
                    'kendaraan'  => $kendaraan,
                    'woitems'    => $woitems,
                    'warehouse'  => $warehouse
                ]);
        }else{
            return Redirect::to("/transaction/pbj")->withError('Data SPK/Work Order tidak ditemukan');
        }
    }

    public function createWithoueWO(){        
        $mekanik    = DB::table('t_mekanik')->get();
        $department = DB::table('t_department')->get();
        $kendaraan  = DB::table('t_kendaraan')->get();
        return view('transaksi.pbj.createpbj', 
            [
                'mekanik'    => $mekanik, 
                'department' => $department,
                'kendaraan'  => $kendaraan
            ]);
    }

    public function duedatepbj(){
        return view('transaksi.pbj.duedatepbj');
    }

    public function detailWO($id){
        $wohdr = DB::table('v_spk01')->where('id', $id)->first();
        if($wohdr){
            // $mekanik    = DB::table('t_mekanik')->where('id', $wohdr->mekanik)->first();
            $warehouse  = DB::table('t_warehouse')->where('id', $wohdr->whscode)->first();
            // $kendaraan  = DB::table('t_kendaraan')->where('id', $wohdr->license_number)->first();
            $woitem     = DB::table('t_wo02')->where('wonum', $wohdr->wonum)->get();
            $attachments = DB::table('t_attachments')->where('doc_object','SPK')->where('doc_number', $wohdr->wonum)->get();
            // $attachments = DB::table('t_attachments')->where('doc_object','SPK')->where('doc_number', $prhdr->wonum)->get();
            $approvals  = DB::table('v_wo_approval')->where('wonum', $wohdr->wonum)->get();
            // $department = DB::table('v_wo_approval')->where('wonum', $prhdr->wonum)->first();
            // return $woitem;
            // $kendaraan  = DB::table('t_kendaraan')->where('no_kendaraan', $pbjhdr->unit_desc)->first();

            return view('transaksi.pbj.detailwo', 
                [
                    'prhdr'       => $wohdr, 
                    'pritem'      => $woitem,
                    // 'mekanik'     => $mekanik,
                    'warehouse'   => $warehouse,
                    // 'kendaraan'   => $kendaraan,
                    'attachments' => $attachments,
                    'approvals'   => $approvals, 
                ]);
        }else{
            return Redirect::to("/transaction/pbj")->withError('Data SPK/Work Order tidak ditemukan');
        }
    }

    public function changePBJ($id){
        $pbjhdr = DB::table('t_pbj01')->where('id', $id)->first();
        if($pbjhdr){
            if($pbjhdr->pbjtype == 1){
                $pbjitem     = DB::table('t_pbj02')->where('pbjnumber', $pbjhdr->pbjnumber)->get();
                $department  = DB::table('t_department')->get();
                $attachments = DB::table('t_attachments')->where('doc_object','PBJ')->where('doc_number', $pbjhdr->pbjnumber)->get();
                $mekanik     = DB::table('t_mekanik')->get();
                $cklist      = DB::table('v_checklist_kendaraan')->where('no_checklist', $pbjhdr->cheklistnumber)->first();
                $kendaraan   = DB::table('t_kendaraan')->where('id', $cklist->no_plat)->first();
                $project     = DB::table('t_projects')->where('idproject', $pbjhdr->idproject);
                if(!$project){
                    $project = null;
                }
                // $pbjdept   = DB::table('t_department')->where()->firts();
                $pbjwhs    = DB::table('t_warehouse')->where('id', $pbjitem[0]->whscode)->first();
    
                $approvals   = DB::table('v_pbj_approval')
                ->where('pbjnumber', $pbjhdr->pbjnumber)
                ->orderBy('approver_level','asc')
                ->orderBy('pbjitem', 'asc')
                ->get();
    
                // return $pbjhdr;
                return view('transaksi.pbj.change', 
                    [
                        'department'  => $department, 
                        'pbjhdr'      => $pbjhdr, 
                        'pbjitem'     => $pbjitem,
                        'attachments' => $attachments, 
                        'approvals'   => $approvals,
                        'mekanik'     => $mekanik,
                        'kendaraan'   => $kendaraan,
                        'project'     => $project,
                        'pbjwhs'      => $pbjwhs,
                    ]);
            }else{
                $pbjitem     = DB::table('t_pbj02')->where('pbjnumber', $pbjhdr->pbjnumber)->get();
                $department  = DB::table('t_department')->get();
                $attachments = DB::table('t_attachments')->where('doc_object','PBJ')->where('doc_number', $pbjhdr->pbjnumber)->get();
                $mekanik     = DB::table('t_mekanik')->get();
                // $cklist     = DB::table('v_checklist_kendaraan')->where('no_checklist', $pbjhdr->cheklistnumber)->first();
                $kendaraan  = DB::table('t_kendaraan')->where('no_kendaraan', $pbjhdr->unit_desc)->first();
    
                $pbjdept   = DB::table('t_department')->where('deptid', $pbjhdr->deptid)->first();
                $pbjwhs    = DB::table('t_warehouse')->where('id', $pbjitem[0]->whscode)->first();
                
                $project     = DB::table('t_projects')->where('idproject', $pbjhdr->idproject);
                if(!$project){
                    $project = null;
                }

                $approvals   = DB::table('v_pbj_approval')
                ->where('pbjnumber', $pbjhdr->pbjnumber)
                ->orderBy('approver_level','asc')
                ->orderBy('pbjitem', 'asc')
                ->get();
    
                // return $pbjhdr;
                return view('transaksi.pbj.changePbj', 
                    [
                        'department'  => $department, 
                        'pbjhdr'      => $pbjhdr, 
                        'pbjitem'     => $pbjitem,
                        'attachments' => $attachments, 
                        'approvals'   => $approvals,
                        'mekanik'     => $mekanik,
                        'kendaraan'   => $kendaraan,
                        'pbjdept'     => $pbjdept,
                        'pbjwhs'      => $pbjwhs,
                        'project'     => $project
                    ]);
            }
        }else{
            return Redirect::to("/transaction/pbj")->withError('Dokumen PBJ tidak ditemukan');
        }
    }

    public function list(){
        $department = DB::table('t_department')->get();
        return view('transaksi.pbj.list', ['department' => $department]);
    }

    public function listOpenWO(){
        $query = DB::table('v_wo_to_pbj')
                 ->where('wo_status', 'A')
                 ->orderBy('id');
        return DataTables::queryBuilder($query)->toJson();
    }

    public function dataCekListTidakLayak(Request $request){
        if(isset($request->params)){
            $params = $request->params;        
            $whereClause = $params['sac'];
        }
        $query = DB::table('v_checklist_kendaraan')->where('hasil_pemeriksaan','TIDAK LAYAK')->where('pbj_created', 'N')
                 ->orderBy('id');
        return DataTables::queryBuilder($query)->toJson();
    }

    public function listPBJ(Request $request){
        if(isset($request->params)){
            $params = $request->params;        
            $whereClause = $params['sac'];
        }

        $checkObjAuth = DB::table('user_object_auth')
                        ->where('object_name', 'ALLOW_DISPLAY_ALL_DEPT')
                        ->where('object_val', 'Y')
                        ->where('userid', Auth::user()->id)
                        ->first();
        if($checkObjAuth){
            $query = DB::table('v_pbj01')
            // ->where('createdby',Auth::user()->email)
           //  ->where('is_active','Y')
           //  ->where('approval_status','N')
            ->orderBy('id');
        }else{
            $query = DB::table('v_pbj01')
                     ->where('createdby',Auth::user()->email)
                    //  ->where('is_active','Y')
                    //  ->where('approval_status','N')
                     ->orderBy('id');
        }
        return DataTables::queryBuilder($query)
        // ->editColumn('amount', function ($query){
        //     return [
        //         'amount1' => number_format($query->amount,0)
        //      ];
        // })->editColumn('approved_amount', function ($query){
        //     return [
        //         'amount2' => number_format($query->approved_amount,0)
        //      ];
        // })
        ->toJson();
    }

    public function listDuedatePBJ(Request $request){
        if(isset($request->params)){
            $params = $request->params;        
            $whereClause = $params['sac'];
        }
        $query = DB::table('v_duedate_pbj')
                //  ->where('createdby',Auth::user()->email)
                 ->where('duedate','>','3')
                 ->where('prcreated','N')
                 ->orderBy('id');
        return DataTables::queryBuilder($query)
        // ->editColumn('amount', function ($query){
        //     return [
        //         'amount1' => number_format($query->amount,0)
        //      ];
        // })->editColumn('approved_amount', function ($query){
        //     return [
        //         'amount2' => number_format($query->approved_amount,0)
        //      ];
        // })
        ->toJson();
    }

    public function detailPBJ($id){
        $pbjhdr = DB::table('t_pbj01')->where('id', $id)->first();
        if($pbjhdr){
            $pbjitem     = DB::table('t_pbj02')->where('pbjnumber', $pbjhdr->pbjnumber)->get();
            $department  = DB::table('t_department')->get();
            $attachments = DB::table('t_attachments')->where('doc_object','PBJ')->where('doc_number', $pbjhdr->pbjnumber)->get();

            $pbjProject = DB::table('t_projects')->where('idproject', $pbjhdr->idproject)->first();
            if(!$pbjProject){
                $pbjProject = null;
            }

            $approvals   = DB::table('v_pbj_approval')
            ->where('pbjnumber', $pbjhdr->pbjnumber)
            ->orderBy('approver_level','asc')
            ->orderBy('pbjitem', 'asc')
            ->get();
            return view('transaksi.pbj.pbjdetail', 
                [
                    'department'    => $department, 
                    'pbjhdr'        => $pbjhdr, 
                    'pbjitem'       => $pbjitem,
                    'attachments'   => $attachments, 
                    'approvals'     => $approvals,
                    'project'       => $pbjProject
                ]);
        }else{
            return Redirect::to("/transaction/pbj")->withError('Dokumen PBJ tidak ditemukan');
        }
    }

    public function budgetLists(Request $request){
        $params = $request->params;        
        $whereClause = $params['sac'];
        $query = DB::table('t_budget')->orderBy('id');
        return DataTables::queryBuilder($query)
        ->editColumn('amount', function ($query){
            return [
                'amount1' => number_format($query->amount,0)
             ];
        })->editColumn('approved_amount', function ($query){
            return [
                'amount2' => number_format($query->approved_amount,0)
             ];
        })
        ->toJson();
    }

    public function save(Request $req){
        // return $req;
        DB::beginTransaction();
        try{
            if(isset($req['parts'])){
                $tgl   = substr($req['tglpbj'], 8, 2);
                $bulan = substr($req['tglpbj'], 5, 2);
                $tahun = substr($req['tglpbj'], 0, 4);
                // return $tgl . ' - ' . $bulan . ' - ' . $tahun;
                $department = DB::table('t_department')->where('department', $req['kepada'])->first();

                // $ptaNumber = generatePbjNumber($tahun, $department->deptid, $tgl);
                $ptaNumber = generatePbjNumber($tahun, Auth::user()->deptid, $tgl);
    
                // return $ptaNumber;
    
                // $amount = $req['nominal'];
                // $amount = str_replace(',','',$amount);
                $PBJid = DB::table('t_pbj01')->insertGetId([
                    'pbjnumber'         => $ptaNumber,
                    'pbjtype'           => $req['pbjTYpe'],
                    'deptid'            => Auth::user()->deptid,
                    'tgl_pbj'           => $req['tglpbj'],
                    'tujuan_permintaan' => $req['requestto'],
                    'kepada'            => $req['kepada'],
                    'unit_desc'         => $req['unitdesc'],
                    'engine_model'      => $req['engine'],
                    'chassis_sn'        => $req['chassis'],
                    'reference'         => $req['refrensi'],
                    'requestor'         => $req['requestor'],
                    'type_model'        => $req['typeModel'],
                    'user'              => $req['user'],
                    'kode_brg_jasa'     => $req['kodeJasa'],
                    'engine_sn'         => $req['nginesn'],
                    'hm_km'             => $req['hmkm'] ?? 0,
                    'km'                => $req['km'] ?? 0,
                    'budget_cost_code'  => $req['budgetcode'],
                    'cheklistnumber'    => $req['checklistnum'] ?? null,
                    'idproject'         => $req['project'] ?? null,
                    'remark'            => $req['remark'],
                    'periode'           => $req['periode'],
                    'createdon'         => getLocalDatabaseDateTime(),
                    'createdby'         => Auth::user()->email ?? Auth::user()->username
                ]);
    
                $parts    = $req['parts'];
                $partdsc  = $req['partdesc'];
                $quantity = $req['quantity'];
                $uom      = $req['uoms'];
                $figure   = $req['figures'];
                $remark   = $req['remarks'];
                $wonum    = $req['wonum'];
                $woitem   = $req['woitem'];
                $whscode  = $req['warehouse'];
    
                $insertData = array();
                $pbjItems   = array();
                $count = 0;
                for($i = 0; $i < sizeof($parts); $i++){
                    $qty    = $quantity[$i];
                    $qty    = str_replace(',','',$qty);
                    
                    $count = $count + 1;
                    $data = array(
                        'pbjnumber'    => $ptaNumber,
                        'pbjitem'      => $count,
                        'partnumber'   => $parts[$i],
                        'description'  => $partdsc[$i],
                        'quantity'     => $qty,
                        'unit'         => $uom[$i],
                        'figure'       => $figure[$i],
                        'remark'       => $remark[$i],
                        'wonum'        => $wonum[$i] ?? null,
                        'woitem'       => $woitem[$i] ?? 0,
                        'whscode'      => $whscode[$i],
                        'createdon'    => getLocalDatabaseDateTime(),
                        'createdby'    => Auth::user()->email ?? Auth::user()->username
                    );
                    array_push($insertData, $data);
                    array_push($pbjItems, $data);
                }
                insertOrUpdate($insertData,'t_pbj02');

                DB::table('t_wo01')->where('wonum', $req['woNumber'])->update([
                    'pbj_created' => 'Y'
                ]);

                DB::table('t_wo02')->where('wonum', $req['woNumber'])->update([
                    'pbj_created' => 'Y'
                ]);
                
                // return $pbjItems;
                //Insert Attachments | t_attachments
                if(isset($req['efile'])){
                    $files = $req['efile'];
                    $insertFiles = array();
        
                    foreach ($files as $efile) {
                        $filename = $efile->getClientOriginalName();
                        $upfiles = array(
                            'doc_object' => 'PBJ',
                            'doc_number' => $ptaNumber,
                            'efile'      => $filename,
                            'pathfile'   => '/files/PBJ/'. $filename,
                            'createdon'  => getLocalDatabaseDateTime(),
                            'createdby'  => Auth::user()->username ?? Auth::user()->email
                        );
                        array_push($insertFiles, $upfiles);
        
                        // $efile->move(public_path().'/files/PBJ/', $filename);  
                        $efile->move('files/PBJ/', $filename);  
                    }
                    if(sizeof($insertFiles) > 0){
                        insertOrUpdate($insertFiles,'t_attachments');
                    }
                }
                // insertOrUpdate($insertFiles,'t_attachments');
    
                //Set Approval
                $approval = DB::table('v_workflow_budget')->where('object', 'PBJ')->where('requester', Auth::user()->id)->get();
                if(sizeof($approval) > 0){
                    // foreach($pbjItems as $pbitem){
                    for($a = 0; $a < sizeof($pbjItems); $a++){
                        $insertApproval = array();
                        foreach($approval as $row){
                            $is_active = 'N';
                            if($row->approver_level == 1){
                                $is_active = 'Y';
                            }
                            $approvals = array(
                                'pbjnumber'         => $ptaNumber,
                                'pbjitem'           => $pbjItems[$a]['pbjitem'],
                                'approver_level'    => $row->approver_level,
                                'approver'          => $row->approver,
                                'requester'         => Auth::user()->id,
                                'is_active'         => $is_active,
                                'createdon'         => getLocalDatabaseDateTime()
                            );
                            array_push($insertApproval, $approvals);
                        }
                        insertOrUpdate($insertApproval,'t_pbj_approval');
                    }
                }    

                DB::table('t_checklist_kendaraan')->where('no_checklist',$req['checklistnum'])->update([
                    'pbj_created' => 'Y',
                    'pbjnumber'   => $ptaNumber
                ]);
                DB::commit();

                $approverId = DB::table('v_workflow_budget')->where('object', 'PBJ')
                            ->where('requester', Auth::user()->id)
                            ->where('approver_level', '1')
                            ->pluck('approver');

                $mailto = DB::table('users')
                    ->whereIn('id', $approverId)
                    ->pluck('email');   

                $dataApprovePBJ = DB::table('v_duedate_pbj')
                    ->where('pbjnumber', $ptaNumber)
                    ->orderBy('id')->get();

                Mail::to($mailto)->queue(new NotifApprovePbjMail($dataApprovePBJ, $PBJid, $ptaNumber));

                if($req['pbjTYpe'] === "1"){
                    return Redirect::to("/transaction/pbj")->withSuccess('PBJ Berhasil dibuat dengan Nomor : '. $ptaNumber);
                }else{
                    return Redirect::to("/transaction/pbjtanpawo")->withSuccess('PBJ Berhasil dibuat dengan Nomor : '. $ptaNumber);
                }
            }else{
                if($req['pbjTYpe'] === "1"){
                    return Redirect::to("/transaction/pbj")->withError('PBJ Item Belum di Pilih');
                }else{
                    return Redirect::to("/transaction/pbjtanpawo")->withError('PBJ Item Belum di Pilih');
                }
            }
        } catch(\Exception $e){
            DB::rollBack();
            if($req['pbjTYpe'] === "1"){
                return Redirect::to("/transaction/pbj")->withError($e->getMessage());
            }else{
                return Redirect::to("/transaction/pbjtanpawo")->withError($e->getMessage());
            }
        }
    }

    public function update(Request $req){
        // return $req;
        DB::beginTransaction();
        try{
            if(isset($req['parts'])){
               
                $ptaNumber = $req['pbjnumber'];
    
                // return $ptaNumber;
    
                // $amount = $req['nominal'];
                // $amount = str_replace(',','',$amount);
                DB::table('t_pbj01')->where('pbjnumber', $ptaNumber)->update([                    
                    'deptid'            => Auth::user()->deptid,
                    'tgl_pbj'           => $req['tglpbj'],
                    'tujuan_permintaan' => $req['requestto'],
                    'kepada'            => $req['kepada'],
                    'unit_desc'         => $req['unitdesc'],
                    'engine_model'      => $req['engine'],
                    'chassis_sn'        => $req['chassis'],
                    'reference'         => $req['refrensi'],
                    'requestor'         => $req['requestor'],
                    'type_model'        => $req['typeModel'],
                    'user'              => $req['user'],
                    'kode_brg_jasa'     => $req['kodeJasa'],
                    'engine_sn'         => $req['nginesn'],
                    'hm_km'             => $req['hmkm'] ?? 0,
                    'km'                => $req['km'] ?? 0,
                    'budget_cost_code'  => $req['budgetcode'],
                    'remark'            => $req['remark'],
                    'periode'           => $req['periode'],
                ]);
    
                $parts    = $req['parts'];
                $partdsc  = $req['partdesc'];
                $quantity = $req['quantity'];
                $uom      = $req['uoms'];
                $figure   = $req['figures'];
                $remark   = $req['remarks'];
                $wonum    = $req['wonum'];
                $woitem   = $req['woitem'];
                $whscode  = $req['warehouse'];
                $pbjitem  = $req['pbjitem'];

                $insertData = array();
                $pbjItems   = array();
                $count = 0;
                for($i = 0; $i < sizeof($parts); $i++){
                    $qty    = $quantity[$i];
                    $qty    = str_replace(',','',$qty);
                    
                    if($pbjitem[$i]){
                        $count = $pbjitem[$i];
                    }else{
                        $count += 1;
                    }

                    $data = array(
                        'pbjnumber'    => $ptaNumber,
                        'pbjitem'      => $count,
                        'partnumber'   => $parts[$i],
                        'description'  => $partdsc[$i],
                        'quantity'     => $qty,
                        'unit'         => $uom[$i],
                        'figure'       => $figure[$i],
                        'remark'       => $remark[$i],
                        'wonum'        => $wonum[$i] ?? null,
                        'woitem'       => $woitem[$i] ?? 0,
                        'whscode'      => $whscode[$i] ?? $req['whscode'],
                        'createdon'    => getLocalDatabaseDateTime(),
                        'createdby'    => Auth::user()->email ?? Auth::user()->username
                    );
                    array_push($insertData, $data);
                    array_push($pbjItems, $data);
                }
                insertOrUpdate($insertData,'t_pbj02');
                
                // return $pbjItems;
                //Insert Attachments | t_attachments
                if(isset($req['efile'])){
                    $files = $req['efile'];
                    $insertFiles = array();
        
                    foreach ($files as $efile) {
                        $filename = $efile->getClientOriginalName();
                        $upfiles = array(
                            'doc_object' => 'PBJ',
                            'doc_number' => $ptaNumber,
                            'efile'      => $filename,
                            'pathfile'   => '/files/PBJ/'. $filename,
                            'createdon'  => getLocalDatabaseDateTime(),
                            'createdby'  => Auth::user()->username ?? Auth::user()->email
                        );
                        array_push($insertFiles, $upfiles);
        
                        // $efile->move(public_path().'/files/PBJ/', $filename);  
                        $efile->move('files/PBJ/', $filename);  
                    }
                    if(sizeof($insertFiles) > 0){
                        insertOrUpdate($insertFiles,'t_attachments');
                    }
                }

                

                // insertOrUpdate($insertFiles,'t_attachments');
    
                //Set Approval
                $approval = DB::table('v_workflow_budget')->where('object', 'PBJ')->where('requester', Auth::user()->id)->get();
                if(sizeof($approval) > 0){
                    DB::table('t_pbj_approval')->where('pbjnumber', $ptaNumber)->delete();
                    // foreach($pbjItems as $pbitem){
                    for($a = 0; $a < sizeof($pbjItems); $a++){
                        $insertApproval = array();
                        foreach($approval as $row){
                            $is_active = 'N';
                            if($row->approver_level == 1){
                                $is_active = 'Y';
                            }
                            $approvals = array(
                                'pbjnumber'         => $ptaNumber,
                                'pbjitem'           => $pbjItems[$a]['pbjitem'],
                                'approver_level'    => $row->approver_level,
                                'approver'          => $row->approver,
                                'requester'         => Auth::user()->id,
                                'is_active'         => $is_active,
                                'createdon'         => getLocalDatabaseDateTime()
                            );
                            array_push($insertApproval, $approvals);
                        }
                        insertOrUpdate($insertApproval,'t_pbj_approval');
                    }
                }    

                // DB::table('t_checklist_kendaraan')->where('no_checklist',$req['checklistnum'])->update([
                //     'pbj_created' => 'Y',
                //     'pbjnumber'   => $ptaNumber
                // ]);
                DB::commit();
                return Redirect::to("/transaction/pbj/list")->withSuccess('PBJ '. $ptaNumber .' Berhasil diupdate');
                // if($req['pbjTYpe'] === "1"){
                //     return Redirect::to("/transaction/pbj")->withSuccess('PBJ '. $ptaNumber .' Berhasil diupdate');
                // }else{
                //     return Redirect::to("/transaction/pbjtanpawo")->withSuccess('PBJ Berhasil dibuat dengan Nomor : '. $ptaNumber);
                // }
            }else{
                // if($req['pbjTYpe'] === "1"){
                    return Redirect::to("/transaction/pbj/list")->withError('PBJ Item Belum di Pilih');
                // }else{
                //     return Redirect::to("/transaction/pbjtanpawo")->withError('PBJ Item Belum di Pilih');
                // }
                
            }
        } catch(\Exception $e){
            DB::rollBack();
            dd($e);
            return Redirect::to("/transaction/pbj/list")->withError($e->getMessage());
        }
    }

    public function deletePBJ(Request $req){
        DB::beginTransaction();
        try{

        }catch(\Exception $e){
            DB::rollBack();
            $result = array(
                'msgtype' => '500',
                'message' => $e->getMessage()
            );
            return $result;
        }
    }

    public function deletePBJItem(Request $req){
        DB::beginTransaction();
        try{
            $checkApproval = DB::table('v_pbj_approval')
                ->where('pbjnumber', $req['pbjnumber'])
                ->where('pbjitem', $req['pbjitem'])
                ->where('approval_status', 'A')->first();
            
            if($checkApproval){
                $result = array(
                    'msgtype' => '500',
                    'message' => 'PBJ : '. $req['pbjnumber'] . ' sudah di approve, data tidak bisa dihapus'
                );
                return $result;
            }

            $checkBAST = DB::table('t_pbj02')
                ->where('pbjnumber', $req['pbjnumber'])
                ->where('pbjitem', $req['pbjitem'])
                ->where('bast_created', 'N')->first();

            if($checkBAST){
                DB::table('t_pbj02')->where('pbjnumber', $req['pbjnumber'])->where('pbjitem', $req['pbjitem'])->delete();
                DB::table('t_pbj_approval')->where('pbjnumber', $req['pbjnumber'])
                                           ->where('pbjitem', $req['pbjitem'])->delete();
    
                DB::commit();
                $result = array(
                    'msgtype' => '200',
                    'message' => 'Item PBJ : '. $req['pbjnumber'] . ' - ' . $req['pbjitem'] . ' berhasil dihapus'
                );

            }else{
                $result = array(
                    'msgtype' => '500',
                    'message' => 'Item PBJ : '. $req['prnum'] . ' - ' . $req['pbjitem'] . ' sudah dibuat BAST'
                );
            }
            
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
