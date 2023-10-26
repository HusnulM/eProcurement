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
        $department = DB::table('t_department')->get();
        return view('transaksi.po.index', ['department' => $department]);
    }

    public function duedate(){
        return view('transaksi.po.duedatepo');
    }

    public function listDuedatePO(Request $request){
        $params = $request->params;
        if(isset($request->params)){
            $whereClause = $params['sac'];
        }
        $query = DB::table('v_po_duedate')
                 ->where('duedate','>','3')
                 ->where('openqty', '>', 0)
                 ->orderBy('id');
        return DataTables::queryBuilder($query)
        ->editColumn('quantity', function ($query){
            return [
                'qty1' => number_format($query->quantity,0)
            ];
        })->editColumn('grqty', function ($query){
            return [
                'qty2' => number_format($query->grqty,0)
            ];
        })->editColumn('openqty', function ($query){
            return [
                'qty3' => number_format($query->openqty,0)
            ];
        })->editColumn('price', function ($query){
            return [
                'price1' => number_format($query->price,0)
            ];
        })->editColumn('podat', function ($query){
            return [
                'podat1' => \Carbon\Carbon::parse($query->podat)->format('d-m-Y')
             ];
        })
        ->toJson();
    }

    public function changePO($id){
        $department  = DB::table('t_department')->get();
        $pohdr       = DB::table('t_po01')->where('id', $id)->first();
        $podtl       = DB::table('t_po02')->where('ponum', $pohdr->ponum)->get();
        $costs       = DB::table('t_po03')->where('ponum', $pohdr->ponum)->get();
        $attachments = DB::table('t_attachments')->where('doc_object','PO')->where('doc_number', $pohdr->ponum)->get();
        $approvals   = DB::table('v_po_approval')->where('ponum', $pohdr->ponum)->get();
        $vendor      = DB::table('t_vendor')->where('vendor_code', $pohdr->vendor)->first();
        $sdepartment = DB::table('t_department')->where('deptid', $pohdr->deptid)->first();

        return view('transaksi.po.change',
            [
                'department'    => $department,
                'pohdr'         => $pohdr,
                'poitem'        => $podtl,
                'costs'         => $costs,
                'attachments'   => $attachments,
                'approvals'     => $approvals,
                'vendor'        => $vendor,
                'sdepartment'   => $sdepartment
            ]);
    }

    public function listPO(){
        $department = DB::table('t_department')->get();
        return view('transaksi.po.printpolist', ['department' => $department]);
    }

    public function getApprovedPR(Request $request){
        // v_approved_pr
        $params = $request->params;
        if(isset($request->params)){
            $whereClause = $params['sac'];
        }
        $deptid= $params['deptid'];
        $query = DB::table('v_approved_prv2')
                //  ->where('pocreated', 'N')
                 ->where('openqty', '>', 0)
                 ->orderBy('id');
        return DataTables::queryBuilder($query)->toJson();
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
            $tahun = substr($req['tglreq'], 0, 4);
            // return $tgl . ' - ' . $bulan . ' - ' . $tahun;
            $ptaNumber = generatePONumber($tahun, $bulan, $tgl);

            $poID = DB::table('t_po01')->insertGetId([
                'ponum'             => $ptaNumber,
                'ext_ponum'         => $ptaNumber,
                'deptid'            => $req['department'],
                'podat'             => $req['tglreq'],
                'delivery_date'     => $req['deldate'],
                'vendor'            => $req['vendor'],
                'note'              => $req['remark'],
                'ppn'               => $req['ppn'] ?? 0,
                'currency'          => $req['currency'],
                'tf_top'            => $req['termofpayment'] ?? null,
                'approvestat'       => 'O',
                'is_posolar'        => $req['poSolarInd'],
                'createdon'         => date('Y-m-d H:m:s'),
                'createdby'         => Auth::user()->email ?? Auth::user()->username
            ]);

            $parts    = $req['parts'];
            $partdsc  = $req['partdesc'];
            $quantity = $req['quantity'];
            $uom      = $req['uoms'];
            $price    = $req['unitprice'];
            $prnum    = $req['prnum'];
            $pritem   = $req['pritem'];
            $project  = $req['project'];

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

                $prdata = DB::table('v_pr_pbj')->where('prnum', $prnum[$i])->first();
                if($prdata){
                    $budgetCode  = $prdata->budget_cost_code ?? $req['budgetcode'];
                    $budgetPriod = $prdata->periode ?? $req['periode'];
                }else{
                    $budgetCode  = $req['budgetcode'];
                    $budgetPriod = $req['periode'];
                }

                // if($project){
                //     if($project[$i]){
                //         $checkProject = DB::table('t_projects')->where('idproject', $project[$i])->first();
                //         if($checkProject){
                //             $IDProject = $project[$i];
                //         }else{
                //             $IDProject = 0;
                //         }
                //     }
                // }

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
                    'idproject'    => $project[$i] ?? 0,
                    'budget_code'  => $budgetCode,
                    'budget_period'=> $budgetPriod,
                    'createdon'    => date('Y-m-d H:m:s'),
                    'createdby'    => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $data);
                array_push($poItems, $data);

                DB::table('t_pr02')->where('prnum', $prnum[$i])->where('pritem', $pritem[$i])
                ->update([
                    'pocreated' => 'Y'
                ]);

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
                        'createdon'    => date('Y-m-d H:m:s'),
                        'createdby'    => Auth::user()->email ?? Auth::user()->username
                    );
                    array_push($insertData, $costdata);
                }
                insertOrUpdate($insertData,'t_po03');
            }

            if($req['poSolarInd'] === "1"){

                $insertData = array();
                if(isset($req['solarpbbkb'])){
                    $costdata = array(
                        'ponum'        => $ptaNumber,
                        'costname'     => 'PBBKB',
                        'costvalue'    => $req['solarpbbkb'],
                        'is_posolar'   => 'Y',
                        'createdon'    => date('Y-m-d H:m:s'),
                        'createdby'    => Auth::user()->email ?? Auth::user()->username
                    );
                    array_push($insertData, $costdata);
                    insertOrUpdate($insertData,'t_po03');
                }

                $insertData = array();
                if(isset($req['solaroat'])){
                    $costdata = array(
                        'ponum'        => $ptaNumber,
                        'costname'     => 'OAT',
                        'costvalue'    => $req['solaroat'],
                        'is_posolar'   => 'Y',
                        'createdon'    => date('Y-m-d H:m:s'),
                        'createdby'    => Auth::user()->email ?? Auth::user()->username
                    );
                    array_push($insertData, $costdata);
                    insertOrUpdate($insertData,'t_po03');
                }

                $insertData = array();
                if(isset($req['ppnoat'])){
                    $costdata = array(
                        'ponum'        => $ptaNumber,
                        'costname'     => 'PPN OAT',
                        'costvalue'    => $req['ppnoat'],
                        'is_posolar'   => 'Y',
                        'createdon'    => date('Y-m-d H:m:s'),
                        'createdby'    => Auth::user()->email ?? Auth::user()->username
                    );
                    array_push($insertData, $costdata);
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
                        'createdby'  => Auth::user()->username ?? Auth::user()->email
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
            $approval = DB::table('v_workflow_budget')->where('object', 'PO')->where('requester', Auth::user()->id)->get();
            if(sizeof($approval) > 0){
                for($a = 0; $a < sizeof($poItems); $a++){
                    $insertApproval = array();
                    foreach($approval as $row){
                        $is_active = 'N';
                        if($row->approver_level == 1){
                            $is_active = 'Y';
                        }
                        $approvals = array(
                            'ponum'             => $ptaNumber,
                            'poitem'            => $poItems[$a]['poitem'],
                            'approver_level'    => $row->approver_level,
                            'approver'          => $row->approver,
                            'requester'         => Auth::user()->id,
                            'is_active'         => $is_active,
                            'createdon'         => getLocalDatabaseDateTime()
                        );
                        array_push($insertApproval, $approvals);
                    }
                    insertOrUpdate($insertApproval,'t_po_approval');
                }
            }else{
                DB::rollBack();
                return Redirect::to("/proc/po")->withError('Approval belum di tambahkan untuk user '. Auth::user()->name);
            }

            DB::commit();

            $approverId = DB::table('v_workflow_budget')->where('object', 'PO')
                            ->where('requester', Auth::user()->id)
                            ->where('approver_level', '1')
                            ->pluck('approver');

            $mailto = DB::table('users')
                    ->whereIn('id', $approverId)
                    ->pluck('email');

            $dataApprovePO = DB::table('v_po_duedate')
                    ->where('ponum', $ptaNumber)
                    ->orderBy('id')->get();

            Mail::to($mailto)->queue(new NotifApprovePoMail($dataApprovePO, $poID, $ptaNumber));

            return Redirect::to("/proc/po")->withSuccess('PO Berhasil dibuat dengan Nomor : '. $ptaNumber);
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/proc/po")->withError($e->getMessage());
        }
    }

    public function update(Request $req, $poid){
        DB::beginTransaction();
        try{
            if(!isset($req['parts'])){
                return Redirect::to("/proc/po")->withError('Item PO Blum di isi');
            }
            // $tgl   = substr($req['tglreq'], 8, 2);
            // $bulan = substr($req['tglreq'], 5, 2);
            // $tahun = substr($req['tglreq'], 0, 4);
            // return $tgl . ' - ' . $bulan . ' - ' . $tahun;
            $pohdr = DB::table('t_po01')->where('id', $poid)->first();
            $ptaNumber = $pohdr->ponum;

            $checkApproval = DB::table('v_po_approval')
                ->where('ponum', $ptaNumber)->where('approval_status', 'A')->first();

            if($checkApproval){
                $result = array(
                    'msgtype' => '500',
                    'message' => 'PO : '. $ptaNumber . ' sudah di approve, data tidak bisa diupdate'
                );
                return $result;
            }

            DB::table('t_po01')->where('id', $poid)->update([
                'deptid'            => $req['department'],
                'podat'             => $req['tglreq'],
                'delivery_date'     => $req['deldate'],
                'vendor'            => $req['vendor'],
                'note'              => $req['remark'],
                'ppn'               => $req['ppn'] ?? 0,
                'currency'          => $req['currency'],
                'tf_top'            => $req['termofpayment'] ?? null,
            ]);

            $parts    = $req['parts'];
            $partdsc  = $req['partdesc'];
            $quantity = $req['quantity'];
            $uom      = $req['uoms'];
            $price    = $req['unitprice'];
            $prnum    = $req['prnum'];
            $pritem   = $req['pritem'];
            $poitem   = $req['poitem'];

            $insertData = array();
            $poItems    = array();
            $count = 0;


            for($i = 0; $i < sizeof($parts); $i++){
                $qty    = $quantity[$i];
                $qty    = str_replace(',','',$qty);

                $uprice = $price[$i];
                $uprice = str_replace(',','',$uprice);

                // $count = $count + 1;
                if($poitem[$i]){
                    $count = $poitem[$i];
                }else{
                    $count += 1;
                }

                $prdata = DB::table('v_pr_pbj')->where('prnum', $prnum[$i])->first();
                if($prdata){
                    $budgetCode  = $prdata->budget_cost_code ?? $req['budgetcode'];
                    $budgetPriod = $prdata->periode ?? $req['periode'];
                }else{
                    $budgetCode  = $req['budgetcode'];
                    $budgetPriod = $req['periode'];
                }

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
                    'budget_code'  => $budgetCode,
                    'budget_period'=> $budgetPriod,
                    'createdon'    => date('Y-m-d H:m:s'),
                    'createdby'    => $pohdr->createdby,
                    // Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $data);
                array_push($poItems, $data);

                DB::table('t_pr02')->where('prnum', $prnum[$i])->where('pritem', $pritem[$i])
                ->update([
                    'pocreated' => 'Y'
                ]);

                DB::table('t_material')->where('material', $parts[$i])->update([
                    'last_purchase_price' => $uprice
                ]);
            }
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
                        'createdby'  => Auth::user()->username ?? Auth::user()->email
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
            $creator = DB::table('users')->where('email', $pohdr->createdby)->first();
            $approval = DB::table('v_workflow_budget')->where('object', 'PO')->where('requester', $creator->id)->get();
            if(sizeof($approval) > 0){
                DB::table('t_po_approval')->where('ponum', $ptaNumber)->delete();
                for($a = 0; $a < sizeof($poItems); $a++){
                    $insertApproval = array();
                    foreach($approval as $row){
                        $is_active = 'N';
                        if($row->approver_level == 1){
                            $is_active = 'Y';
                        }
                        $approvals = array(
                            'ponum'             => $ptaNumber,
                            'poitem'            => $poItems[$a]['poitem'],
                            'approver_level'    => $row->approver_level,
                            'approver'          => $row->approver,
                            'requester'         => $creator->id,
                            'is_active'         => $is_active,
                            'createdon'         => getLocalDatabaseDateTime()
                        );
                        array_push($insertApproval, $approvals);
                    }
                    insertOrUpdate($insertApproval,'t_po_approval');
                }
            }else{
                DB::rollBack();
                $result = array(
                    'msgtype' => '500',
                    'message' => 'Gagal Update PO, Approval belum di tambahkan untuk user '. Auth::user()->name
                );

                return $result;
                // return Redirect::to("/proc/po")->withError('Approval belum di tambahkan untuk user '. Auth::user()->name);
            }

            DB::commit();

            $approverId = DB::table('v_workflow_budget')->where('object', 'PO')
                            ->where('requester', Auth::user()->id)
                            ->where('approver_level', '1')
                            ->pluck('approver');

            $mailto = DB::table('users')
                    ->whereIn('id', $approverId)
                    ->pluck('email');

            $dataApprovePO = DB::table('v_po_duedate')
                    ->where('ponum', $ptaNumber)
                    ->orderBy('id')->get();

            Mail::to($mailto)->queue(new NotifApprovePoMail($dataApprovePO, $pohdr->id, $ptaNumber));

            // DB::commit();
            // return Redirect::to("/proc/po")->withSuccess('PO Berhasil dibuat dengan Nomor : '. $ptaNumber);
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
