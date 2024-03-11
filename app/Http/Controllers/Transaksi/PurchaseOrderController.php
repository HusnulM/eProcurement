<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Mail\NotifApprovePoMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class PurchaseOrderController extends Controller
{
    public function index(){
        $proyek = getAuthorizedProject();
        return view('transaksi.po.index', ['proyek' => $proyek]);
    }

    public function duedate(){
        return view('transaksi.po.duedatepo');
    }

    public function listPO(){
        $proyek = getAuthorizedProject();
        return view('transaksi.po.printpolist', ['proyek' => $proyek]);
    }

    public function getListPO(Request $req){

        $query = DB::table('v_po01');

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
            $query->where('podat', '<=', $req->dateto);
        }

        $checkObjAuth = DB::table('user_object_auth')
        ->where('object_name', 'ALLOW_DISPLAY_PROJECT')
        ->where('object_val', '*')
        ->where('userid', Auth::user()->id)
        ->first();
        if($checkObjAuth){

        }else{
            if(isset($req->project)){
                if($req->project !== 'All'){
                    $query->where('idproject', $req->project);
                }
            }
        }

        $query->orderBy('id');

        return DataTables::queryBuilder($query)
        ->editColumn('podat', function ($query){
            return [
                'podat1' => \Carbon\Carbon::parse($query->podat)->format('d-m-Y')
             ];
        })
        ->toJson();
    }

    public function poDetail($id){
        $pohdr       = DB::table('t_po01')->where('id', $id)->first();
        $podtl       = DB::table('t_po02')
                        ->join('t_material', 't_po02.material', '=', 't_material.material')
                        ->leftJoin('v_cost_master', 't_po02.cost_code', '=', 'v_cost_master.id')
                        ->select('t_po02.*','t_material.matspec', 'v_cost_master.cost_code as costcd',
                                 'v_cost_master.cost_desc', 'v_cost_master.cost_group_desc')
                        ->where('t_po02.ponum', $pohdr->ponum)
                        ->get();
        // dd($podtl);
        // DB::table('t_po02')->where('ponum', $pohdr->ponum)->get();
        $costs       = DB::table('t_po03')->where('ponum', $pohdr->ponum)->get();
        $attachments = DB::table('t_attachments')->where('doc_object','PO')->where('doc_number', $pohdr->ponum)->get();
        $approvals   = DB::table('v_po_approval')->where('ponum', $pohdr->ponum)->get();
        $vendor      = DB::table('t_vendor')->where('id', $pohdr->vendor)->first();
        // $sdepartment = DB::table('t_department')->where('deptid', $pohdr->deptid)->first();
        $proyek = getAuthorizedProject();
        $sproyek = DB::table('t_projects')->where('id', $pohdr->idproject)->first();

        return view('transaksi.po.podetail',
            [
                // 'department'    => $department,
                'pohdr'         => $pohdr,
                'poitem'        => $podtl,
                'costs'         => $costs,
                'attachments'   => $attachments,
                'approvals'     => $approvals,
                'vendor'        => $vendor,
                'proyek'        => $proyek,
                'sproyek'       => $sproyek
                // 'sdepartment'   => $sdepartment
            ]);
    }

    public function changePO($id){

        $pohdr       = DB::table('t_po01')->where('id', $id)->first();
        $podtl       = DB::table('t_po02')
                        ->join('t_material', 't_po02.material', '=', 't_material.material')
                        ->leftJoin('v_cost_master', 't_po02.cost_code', '=', 'v_cost_master.id')
                        ->select('t_po02.*','t_material.matspec', 'v_cost_master.cost_code as costcd',
                                 'v_cost_master.cost_desc', 'v_cost_master.cost_group_desc')
                        ->where('t_po02.ponum', $pohdr->ponum)
                        ->get();
        // dd($podtl);
        // DB::table('t_po02')->where('ponum', $pohdr->ponum)->get();
        $costs       = DB::table('t_po03')->where('ponum', $pohdr->ponum)->get();
        $attachments = DB::table('t_attachments')->where('doc_object','PO')->where('doc_number', $pohdr->ponum)->get();
        $approvals   = DB::table('v_po_approval')->where('ponum', $pohdr->ponum)->get();
        $vendor      = DB::table('t_vendor')->where('id', $pohdr->vendor)->first();
        // $sdepartment = DB::table('t_department')->where('deptid', $pohdr->deptid)->first();
        $proyek = getAuthorizedProject();
        $sproyek = DB::table('t_projects')->where('id', $pohdr->idproject)->first();

        return view('transaksi.po.change',
            [
                // 'department'    => $department,
                'pohdr'         => $pohdr,
                'poitem'        => $podtl,
                'costs'         => $costs,
                'attachments'   => $attachments,
                'approvals'     => $approvals,
                'vendor'        => $vendor,
                'proyek'        => $proyek,
                'sproyek'       => $sproyek
                // 'sdepartment'   => $sdepartment
            ]);
    }

    public function getApprovedPR(Request $request){
        $params = $request->params;
        if(isset($request->params)){
            $whereClause = $params['sac'];
        }

        $query = DB::table('v_approved_prv2')
                 ->join('t_material', 'v_approved_prv2.material', '=', 't_material.material')
                 ->select('v_approved_prv2.*','t_material.matspec')
                 ->whereIn('v_approved_prv2.pocreated', ['N','P'])
                 ->where('v_approved_prv2.openqty', '>', 0)
                 ->orderBy('v_approved_prv2.id');
        return DataTables::queryBuilder($query)
        ->editColumn('poqty', function ($query){
            return [
                'poqty1' => number_format($query->poqty,0)
             ];
        })->editColumn('openqty', function ($query){
            return [
                'openqty1' => number_format($query->openqty,0)
             ];
        })->editColumn('quantity', function ($query){
            return [
                'quantity1' => number_format($query->quantity,0)
             ];
        })->editColumn('last_purchase_price', function ($query){
            return [
                'last_price' => number_format($query->last_purchase_price,0)
             ];
        })
        ->toJson();
    }

    public function save(Request $req){
        // return $req;
        DB::beginTransaction();
        try{
            if(!isset($req['parts'])){
                return Redirect::to("/proc/po")->withError('Item PO Blum di isi');
            }
            $tgl   = substr($req['tglreq'], 8, 2);
            $bulan = substr($req['tglreq'], 5, 2);
            $tahun = substr($req['tglreq'], 2, 2);

            // return $tgl . ' - ' . $bulan . ' - ' . $tahun;
            $prefix = null;
            if($req['prtype'] === "AA"){
                $prefix = 'AA';
            }else{
                $project = DB::table('t_projects')->where('id', $req['project'])->first();
                $prefix = $project->kode_project;;
            }
            $ptaNumber = generatePONumber($tahun, $bulan, $prefix);
            // dd($ptaNumber);
            if($req['poSolarInd'] === 'Y'){
                $poID = DB::table('t_po01')->insertGetId([
                    'ponum'             => $ptaNumber,
                    'ext_ponum'         => $ptaNumber,
                    'potype'            => $req['prtype'],
                    'podat'             => $req['tglreq'],
                    'delivery_date'     => $req['deldate'],
                    'vendor'            => $req['vendor'],
                    'note'              => $req['remark'],
                    'idproject'         => $req['project'],
                    'ppn'               => $req['ppn'] ?? 0,
                    'currency'          => $req['currency'],
                    'tf_top'            => $req['termofpayment'] ?? null,
                    'approvestat'       => 'O',
                    'is_posolar'        => $req['poSolarInd'],
                    'solar_pbbkb'       => $req['solarpbbkb'],
                    'solar_oat'         => $req['solaroat'],
                    'solar_ppn_oat'     => $req['ppnoat'],
                    'createdon'         => getLocalDatabaseDateTime(),
                    'createdby'         => Auth::user()->username
                ]);
            }else{
                $poID = DB::table('t_po01')->insertGetId([
                    'ponum'             => $ptaNumber,
                    'ext_ponum'         => $ptaNumber,
                    'potype'            => $req['prtype'],
                    'podat'             => $req['tglreq'],
                    'delivery_date'     => $req['deldate'],
                    'vendor'            => $req['vendor'],
                    'note'              => $req['remark'],
                    'idproject'         => $req['project'],
                    'ppn'               => $req['ppn'] ?? 0,
                    'currency'          => $req['currency'],
                    'tf_top'            => $req['termofpayment'] ?? null,
                    'approvestat'       => 'O',
                    'is_posolar'        => $req['poSolarInd'],
                    'createdon'         => getLocalDatabaseDateTime(),
                    'createdby'         => Auth::user()->username
                ]);
            }

            $parts    = $req['parts'];
            $partdsc  = $req['partdesc'];
            $quantity = $req['quantity'];
            $uom      = $req['uoms'];
            $price    = $req['unitprice'];
            $prnum    = $req['prnum'];
            $pritem   = $req['pritem'];
            $costcode = $req['costcode'];

            $insertData = array();
            $poItems    = array();
            $count = 0;

            $budgetCode  = "0";
            $budgetPriod = null;
            $IDProject   = 0;
            for($i = 0; $i < sizeof($parts); $i++){
                $qty    = $quantity[$i];
                $qty    = str_replace(',','',$qty);

                $uprice = $price[$i];
                $uprice = str_replace(',','',$uprice);

                $count = $count + 1;
                $data = array(
                    'ponum'        => $ptaNumber,
                    'poitem'       => $count,
                    'material'     => $parts[$i],
                    'matdesc'      => $partdsc[$i],
                    'quantity'     => $qty,
                    'unit'         => $uom[$i],
                    'price'        => $uprice,
                    'prnum'        => $prnum[$i] ?? 0,
                    'pritem'       => $pritem[$i] ?? 0,
                    'idproject'    => $req['project'],
                    'cost_code'    => $costcode[$i],
                    'createdon'    => getLocalDatabaseDateTime(),
                    'createdby'    => Auth::user()->username
                );
                array_push($insertData, $data);
                array_push($poItems, $data);

                if($prnum[$i]){
                    $poQty = getTotalPOCreated($prnum[$i] ?? 0, $pritem[$i] ?? 0);
                    if($poQty == '1'){
                        DB::table('t_pr02')->where('prnum', $prnum[$i])->where('pritem', $pritem[$i])
                        ->update([
                            'pocreated' => 'Y'
                        ]);
                    }else{
                        DB::table('t_pr02')->where('prnum', $prnum[$i])->where('pritem', $pritem[$i])
                        ->update([
                            'pocreated' => 'P'
                        ]);
                    }
                }

                DB::table('t_material')->where('material', $parts[$i])->update([
                    'last_purchase_price' => $uprice
                ]);
            }
            // return $insertData;
            insertOrUpdate($insertData,'t_po02');

            if(isset($req['costname'])){
                $costname  = $req['costname'];
                $costvalue = $req['costvalue'];

                $insertData = array();
                for($i = 0; $i < sizeof($costname); $i++){
                    $costVal = $costvalue[$i];
                    $costVal = str_replace(',','',$costVal);
                    $costdata = array(
                        'ponum'        => $ptaNumber,
                        'costname'     => $costname[$i],
                        'costvalue'    => $costVal,
                        'createdon'    => getLocalDatabaseDateTime(),
                        'createdby'    => Auth::user()->username
                    );
                    array_push($insertData, $costdata);
                }
                insertOrUpdate($insertData,'t_po03');
            }

            //Insert Attachments | t_attachments
            if(isset($req['efile'])){
                $files = $req['efile'];
                $insertFiles = array();

                foreach ($files as $efile) {
                    $filename = $efile->getClientOriginalName();
                    $upfiles = array(
                        'doc_object' => 'PO',
                        'doc_number' => $ptaNumber,
                        'efile'      => $filename,
                        'pathfile'   => '/files/PO/'. $filename,
                        'createdon'  => getLocalDatabaseDateTime(),
                        'createdby'  => Auth::user()->username
                    );
                    array_push($insertFiles, $upfiles);

                    // $efile->move(public_path().'/files/PO/', $filename);
                    $efile->move('files/PO/', $filename);
                }
                if(sizeof($insertFiles) > 0){
                    insertOrUpdate($insertFiles,'t_attachments');
                }
            }
            // insertOrUpdate($insertFiles,'t_attachments');

            //Set Approval
            $approvalActive = DB::table('general_setting')->where('setting_name', 'WORKFLOW_PR_ACTIVE')->first();
            if($approvalActive){
                $approval = DB::table('v_workflows')->where('object', 'PO')->where('requester', Auth::user()->id)->get();
                if(sizeof($approval) > 0){
                    $insertApproval = array();
                    foreach($approval as $row){
                        $is_active = 'N';
                        if($row->approver_level == 1){
                            $is_active = 'Y';
                        }
                        $approvals = array(
                            'ponum'             => $ptaNumber,
                            'approver_level'    => $row->approver_level,
                            'approver'          => $row->approver,
                            'requester'         => Auth::user()->id,
                            'is_active'         => $is_active,
                            'createdon'         => getLocalDatabaseDateTime()
                        );
                        array_push($insertApproval, $approvals);
                    }
                    insertOrUpdate($insertApproval,'t_po_approval');
                }else{
                    DB::rollBack();
                    return Redirect::to("/proc/po")->withError('Approval belum di tambahkan untuk user '. Auth::user()->name);
                }
            }

            DB::commit();

            if(!$approvalActive){
                DB::table('t_po01')->where('ponum', $ptaNumber)->update([
                    'approvestat' => 'A'
                ]);

                DB::table('t_po02')->where('ponum', $ptaNumber)->update([
                    'approvestat' => 'A'
                ]);
            }

            return Redirect::to("/proc/po")->withSuccess('PO Berhasil dibuat dengan Nomor : '. $ptaNumber);
        } catch(\Exception $e){
            DB::rollBack();
            dd($e);
            // return $e;
            // return Redirect::to("/proc/po")->withError($e->getMessage());
        }
    }

    public function update(Request $req, $poid){
        // return $req;
        DB::beginTransaction();
        try{
            if(!isset($req['parts'])){
                // return Redirect::to("/proc/po")->withError('Item PO Blum di isi');
                $result = array(
                    'msgtype' => '500',
                    'message' => 'Item PO Blum di isi PO : '. $ptaNumber . ' masih kosong'
                );
                return $result;
            }

            $pohdr = DB::table('t_po01')->where('id', $poid)->first();
            $ptaNumber = $pohdr->ponum;

            $approvalActive = DB::table('general_setting')->where('setting_name', 'WORKFLOW_PR_ACTIVE')->first();
            if($approvalActive){
                $checkApproval = DB::table('v_po_approval')
                    ->where('ponum', $ptaNumber)->where('approval_status', 'A')->first();

                if($checkApproval){
                    $result = array(
                        'msgtype' => '500',
                        'message' => 'PO : '. $ptaNumber . ' sudah di approve, data tidak bisa diupdate'
                    );
                    return $result;
                }
            }

            if($req['poSolarInd'] === 'Y'){
                DB::table('t_po01')->where('id', $poid)->update([
                    'podat'             => $req['tglreq'],
                    'delivery_date'     => $req['deldate'],
                    'vendor'            => $req['vendor'],
                    'note'              => $req['remark'],
                    'idproject'         => $req['project'],
                    'ppn'               => $req['ppn'] ?? 0,
                    'currency'          => $req['currency'],
                    'tf_top'            => $req['termofpayment'] ?? null,
                    'is_posolar'        => $req['poSolarInd'],
                    'solar_pbbkb'       => $req['solarpbbkb'],
                    'solar_oat'         => $req['solaroat'],
                    'solar_ppn_oat'     => $req['ppnoat'],
                    'changedon'         => getLocalDatabaseDateTime(),
                    'changedby'         => Auth::user()->username
                ]);
            }else{
                DB::table('t_po01')->where('id', $poid)->update([
                    'podat'             => $req['tglreq'],
                    'delivery_date'     => $req['deldate'],
                    'vendor'            => $req['vendor'],
                    'idproject'         => $req['project'],
                    'note'              => $req['remark'],
                    'ppn'               => $req['ppn'] ?? 0,
                    'currency'          => $req['currency'],
                    'tf_top'            => $req['termofpayment'] ?? null,
                    'is_posolar'        => $req['poSolarInd'],
                    'solar_pbbkb'       => 0,
                    'solar_oat'         => 0,
                    'solar_ppn_oat'     => 0,
                    'changedon'         => getLocalDatabaseDateTime(),
                    'changedby'         => Auth::user()->username
                ]);
            }

            $parts    = $req['parts'];
            $partdsc  = $req['partdesc'];
            $quantity = $req['quantity'];
            $uom      = $req['uoms'];
            $price    = $req['unitprice'];
            $prnum    = $req['prnum'];
            $pritem   = $req['pritem'];
            $poitem   = $req['poitem'];
            $costcode = $req['costcode'];

            $insertData = array();
            $poItems    = array();
            $count = 0;

            for($i = 0; $i < sizeof($parts); $i++){
                $qty    = $quantity[$i];
                $qty    = str_replace(',','',$qty);

                $uprice = $price[$i];
                $uprice = str_replace(',','',$uprice);

                if($poitem[$i]){
                    $count = $poitem[$i];
                }else{
                    $count += 1;
                }

                $data = array(
                    'ponum'        => $ptaNumber,
                    'poitem'       => $count,
                    'material'     => $parts[$i],
                    'matdesc'      => $partdsc[$i],
                    'quantity'     => $qty,
                    'unit'         => $uom[$i],
                    'price'        => $uprice,
                    'prnum'        => $prnum[$i] ?? null,
                    'pritem'       => $pritem[$i] ?? 0,
                    'idproject'    => $req['project'],
                    'cost_code'    => $costcode[$i],
                    'createdon'    => $pohdr->createdon,
                    'createdby'    => $pohdr->createdby,
                    'changedon'    => getLocalDatabaseDateTime(),
                    'changedby'    => Auth::user()->username
                );
                array_push($insertData, $data);
                array_push($poItems, $data);

                if($prnum[$i]){
                    $poQty = getTotalPOCreated($prnum[$i] ?? 0, $pritem[$i] ?? 0);
                    if($poQty == '1'){
                        DB::table('t_pr02')->where('prnum', $prnum[$i])->where('pritem', $pritem[$i])
                        ->update([
                            'pocreated' => 'Y'
                        ]);
                    }else{
                        DB::table('t_pr02')->where('prnum', $prnum[$i])->where('pritem', $pritem[$i])
                        ->update([
                            'pocreated' => 'P'
                        ]);
                    }
                }

                DB::table('t_material')->where('material', $parts[$i])->update([
                    'last_purchase_price' => $uprice
                ]);
            }

            // return($insertData);
            insertOrUpdate($insertData,'t_po02');

            if(isset($req['costname'])){
                $costname  = $req['costname'];
                $costvalue = $req['costvalue'];

                $insertData = array();
                for($i = 0; $i < sizeof($costname); $i++){
                    $costVal = $costvalue[$i];
                    $costVal = str_replace(',','',$costVal);
                    $check = DB::table('t_po03')->where('ponum', $ptaNumber)->where('costname', $costname[$i])->first();
                    if($check){
                        DB::table('t_po03')->where('ponum', $ptaNumber)->where('costname', $costname[$i])->update([
                            'ponum'        => $ptaNumber,
                            'costname'     => $costname[$i],
                            'costvalue'    => $costVal,
                        ]);
                    }else{
                        $costdata = array(
                            'ponum'        => $ptaNumber,
                            'costname'     => $costname[$i],
                            'costvalue'    => $costVal,
                            'createdon'    => date('Y-m-d H:m:s'),
                            'createdby'    => Auth::user()->email ?? Auth::user()->username
                        );
                        array_push($insertData, $costdata);
                    }
                }
                if(sizeof($insertData) > 0){
                    insertOrUpdate($insertData,'t_po03');
                }
            }

            //Insert Attachments | t_attachments
            if(isset($req['efile'])){
                $files = $req['efile'];
                $insertFiles = array();

                foreach ($files as $efile) {
                    $filename = $efile->getClientOriginalName();
                    $upfiles = array(
                        'doc_object' => 'PO',
                        'doc_number' => $ptaNumber,
                        'efile'      => $filename,
                        'pathfile'   => '/files/PO/'. $filename,
                        'createdon'  => getLocalDatabaseDateTime(),
                        'createdby'  => Auth::user()->username
                    );
                    array_push($insertFiles, $upfiles);

                    // $efile->move(public_path().'/files/PO/', $filename);
                    $efile->move('files/PO/', $filename);
                }
                if(sizeof($insertFiles) > 0){
                    insertOrUpdate($insertFiles,'t_attachments');
                }
            }

            //Set Approval
            $approvalActive = DB::table('general_setting')->where('setting_name', 'WORKFLOW_PR_ACTIVE')->first();
            if($approvalActive){
                $creator = DB::table('users')->where('username', $pohdr->createdby)->first();
                $approval = DB::table('v_workflows')->where('object', 'PO')->where('requester', $creator->id)->get();
                if(sizeof($approval) > 0){
                    DB::table('t_po_approval')->where('ponum', $ptaNumber)->delete();
                    $insertApproval = array();
                    foreach($approval as $row){
                        $is_active = 'N';
                        if($row->approver_level == 1){
                            $is_active = 'Y';
                        }
                        $approvals = array(
                            'ponum'             => $ptaNumber,
                            'approver_level'    => $row->approver_level,
                            'approver'          => $row->approver,
                            'requester'         => $creator->id,
                            'is_active'         => $is_active,
                            'createdon'         => getLocalDatabaseDateTime()
                        );
                        array_push($insertApproval, $approvals);
                    }
                    insertOrUpdate($insertApproval,'t_po_approval');
                }else{
                    DB::rollBack();
                    $result = array(
                        'msgtype' => '500',
                        'message' => 'Gagal Update PO, Approval belum di tambahkan untuk user '. Auth::user()->name
                    );

                    return $result;
                    // return Redirect::to("/proc/po")->withError('Approval belum di tambahkan untuk user '. Auth::user()->name);
                }
            }

            DB::commit();

            if(!$approvalActive){
                DB::table('t_po01')->where('ponum', $ptaNumber)->update([
                    'approvestat' => 'A'
                ]);

                DB::table('t_po02')->where('ponum', $ptaNumber)->update([
                    'approvestat' => 'A'
                ]);
            }

            $result = array(
                'msgtype' => '200',
                'message' => 'PO : '. $ptaNumber . ' Berhasil di update'
            );
            return $result;
        } catch(\Exception $e){
            DB::rollBack();
            $result = array(
                'msgtype' => '500',
                'message' => $e->getMessage()
            );
            return $result;
            // return Redirect::to("/proc/po")->withError($e->getMessage());
        }
    }

    public function deletePOItem(Request $req){
        DB::beginTransaction();
        try{
            $checkApproval = DB::table('t_po_approval')
                            ->where('ponum', $req['ponum'])
                            ->where('approval_status', 'A')
                            ->first();
            if($checkApproval){
                $result = array(
                    'msgtype' => '400',
                    'message' => 'PO : '. $req['ponum'] . ' sudah di approve, tidak bisa dihapus!'
                );
            }else{
                $pbjdoc = DB::table('t_po02')
                            ->where('ponum', $req['ponum'])
                            ->where('poitem', $req['poitem'])->get();

                DB::table('t_po02')->where('ponum', $req['ponum'])->where('poitem', $req['poitem'])->delete();
                // DB::table('t_pr_approval')->where('prnum', $prhdr->prnum)->delete();

                foreach($pbjdoc as $row){
                    DB::table('t_pr02')
                        ->where('prnum', $row->prnum)
                        ->where('pritem', $row->pritem)->update([
                            'pocreated' => 'N'
                    ]);
                }

                DB::commit();

                $result = array(
                    'msgtype' => '200',
                    'message' => 'Item PO : '. $req['ponum'] . ' - ' . $req['poitem'] . ' berhasil dihapus'
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

    public function deletePO($id){
        DB::beginTransaction();
        try{
            $prhdr = DB::table('t_po01')->where('id', $id)->first();

            $checkApproval = DB::table('t_po_approval')
                            ->where('ponum', $prhdr->ponum)
                            ->where('approval_status', 'A')
                            ->first();
            if($checkApproval){
                return Redirect::to("/proc/po/listpo")->withError('PO '. $prhdr->ponum .' sudah di approve, tidak bisa dihapus!');
            }else{
                $pbjdoc = DB::table('t_po02')
                            ->where('ponum', $prhdr->ponum)->get();

                DB::table('t_po01')->where('id', $id)->delete();
                DB::table('t_attachments')->where('doc_object', 'PO')->where('doc_number',$prhdr->ponum)->delete();
                DB::table('t_po_approval')->where('ponum', $prhdr->ponum)->delete();

                foreach($pbjdoc as $row){
                    DB::table('t_pr02')
                        ->where('prnum', $row->prnum)
                        ->where('pritem', $row->pritem)->update([
                            'pocreated' => 'N'
                    ]);
                }

                DB::commit();
                return Redirect::to("/proc/po/listpo")->withSuccess('PO '. $prhdr->ponum .' Berhasil dihapus');
            }
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/proc/po/listpo")->withError($e->getMessage());
            // dd($e->getMessage());
        }
    }

    public function deleteAttachment($poID, $attachmentID){
        DB::beginTransaction();
        try{
            $prhdr = DB::table('t_po01')->where('id', $poID)->first();
            DB::table('t_attachments')->where('doc_object', 'PO')
                    ->where('id',$attachmentID)
                    ->where('doc_number',$prhdr->ponum)
                    ->delete();
            DB::commit();
            return Redirect::to("/proc/po/change/".$poID)->withSuccess('Attachment PO '. $prhdr->ponum .' Berhasil dihapus');

        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/proc/po/change/".$poID)->withError($e->getMessage());
            // dd($e->getMessage());
        }
    }

    public function sendPO(){
        $poNumber = 'PO/20230612000001';
        $result   = sendPurchaseOrder($poNumber);

        return $result;
    }
}
