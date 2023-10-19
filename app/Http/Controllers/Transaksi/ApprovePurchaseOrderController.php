<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Mail\NotifApprovePoMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class ApprovePurchaseOrderController extends Controller
{
    public function index(){
        return view('transaksi.po.approvelist');
    }

    public function approveDetail($id){
        $prhdr = DB::table('v_po01')->where('id', $id)->first();
        if($prhdr){
            $items       = DB::table('v_po_approval_v2')
                            ->where('ponum', $prhdr->ponum)
                            ->where('approver',Auth::user()->id)
                            ->get();
            $costs       = DB::table('t_po03')->where('ponum', $prhdr->ponum)->get();
            $approvals   = DB::table('v_po_approval_v2')
                            ->where('ponum', $prhdr->ponum)
                            ->get();
            $department  = DB::table('v_po_approval')->where('ponum', $prhdr->ponum)->first();
            $attachments = DB::table('t_attachments')->where('doc_object','PO')->where('doc_number', $prhdr->ponum)->get();

            $purchases = DB::table('v_po02')
            ->where('ponum', $prhdr->ponum)
            ->sum('totalprice');

            $prNumber = DB::table('t_po02')->where('ponum', $prhdr->ponum)->pluck('prnum');
            $prAttachments = DB::table('t_attachments')->where('doc_object','PR')
                              ->whereIn('doc_number', $prNumber)->get();

            $pbjNumber = DB::table('t_pr02')->whereIn('prnum', $prNumber)->pluck('pbjnumber');
            $pbjAttachments = DB::table('t_attachments')->where('doc_object','PBJ')
                              ->whereIn('doc_number', $pbjNumber)->get();

            // return $purchases;

            $isApprovedbyUser = DB::table('v_po_approval')
                    ->where('ponum',    $prhdr->ponum)
                    ->where('approver', Auth::user()->id)
                    ->where('is_active', 'Y')
                    ->first();

            return view('transaksi.po.approvedetail',
                [
                    'prhdr'     => $prhdr,
                    'pritem'    => $items,
                    'costs'     => $costs,
                    'approvals' => $approvals,
                    'department'=> $department,
                    'isApprovedbyUser' => $isApprovedbyUser,
                    'totalprice'       => $purchases,
                    'attachments'      => $attachments,
                    'prAttachments'    => $prAttachments,
                    'pbjAttachments'   => $pbjAttachments
                ]);
        }else{
            return Redirect::to("/approve/po")->withError('Dokumen PO tidak ditemukan');
        }
    }

    public function ApprovalList(Request $request){

        if(isset($request->params)){
            $params = $request->params;
            $whereClause = $params['sac'];
        }
        $query = DB::table('v_po_approval_v2')
                 ->select('id', 'ponum', 'podat', 'vendor', 'vendor_name', 'note')
                 ->distinct()
                 ->where('approver',Auth::user()->id)
                 ->where('is_active','Y')
                 ->where('approval_status','N')
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

    public function getNextApproval($dcnNum){
        $userLevel = DB::table('t_po_approval')
                    ->where('ponum', $dcnNum)
                    ->where('approver', Auth::user()->id)
                    ->first();

        $nextApproval = DB::table('t_po_approval')
                        ->where('ponum', $dcnNum)
                        ->where('approver_level', '>', $userLevel->approver_level)
                        ->orderBy('approver_level', 'ASC')
                        ->first();

        // return $userLevel;
        if($nextApproval){
            return $nextApproval->approver_level;
        }else{
            return null;
        }
    }

    public function save(Request $req){
        DB::beginTransaction();
        try{
            $poID      = $req['poid'];
            $ptaNumber = $req['ponum'];
            $poItems   = $req['poitem'];

            $userAppLevel = DB::table('t_po_approval')
                            ->select('approver_level')
                            ->where('ponum', $ptaNumber)
                            ->whereIn('poitem', $poItems)
                            ->where('approver', Auth::user()->id)
                            ->first();

            $podata = DB::table('t_po01')->where('id', $poID)->first();
            if($req['action'] === 'R'){
                DB::table('t_po_approval')
                ->where('ponum', $ptaNumber)
                ->whereIn('poitem', $poItems)
                // ->where('approver_id', Auth::user()->id)
                // ->where('approver_level',$userAppLevel->approver_level)
                ->update([
                    'approval_status' => 'R',
                    'approval_remark' => 'Level '. $userAppLevel->approver_level . ' Rejected PO',
                    'approved_by'     => Auth::user()->username,
                    'approval_date'   => getLocalDatabaseDateTime()
                ]);

                DB::table('t_po01')->where('ponum', $ptaNumber)->update([
                    // 'approved_amount' => $amount,
                    'approvestat'   => 'R'
                ]);

                DB::table('t_po02')
                ->where('ponum', $ptaNumber)
                ->whereIn('poitem', $poItems)
                ->update([
                    // 'approved_amount' => $amount,
                    'approvestat'   => 'R'
                ]);

                DB::table('t_po_approval')
                    ->where('ponum', $ptaNumber)
                    ->whereIn('poitem', $poItems)
                    // ->where('approver_level', $nextApprover)
                    ->update([
                        'is_active' => 'N'
                    ]);
                DB::commit();
                $result = array(
                    'msgtype' => '200',
                    'message' => 'PO dengan Nomor : '. $ptaNumber . ' berhasil di reject'
                );
            }else{
                //Set Approval
                DB::table('t_po_approval')
                ->where('ponum',    $ptaNumber)
                ->whereIn('poitem', $poItems)
                // ->where('approver_id', Auth::user()->id)
                ->where('approver_level',$userAppLevel->approver_level)
                ->update([
                    'approval_status' => 'A',
                    'approval_remark' => 'Level '. $userAppLevel->approver_level . ' Approved PO',
                    'approved_by'     => Auth::user()->username,
                    'approval_date'   => getLocalDatabaseDateTime()
                ]);

                $nextApprover = $this->getNextApproval($ptaNumber);
                if($nextApprover  != null){
                    DB::table('t_po_approval')
                    ->where('ponum', $ptaNumber)
                    ->whereIn('poitem', $poItems)
                    ->where('approver_level', $nextApprover)
                    ->update([
                        'is_active' => 'Y'
                    ]);
                }

                $checkIsFullApprove = DB::table('t_po_approval')
                                          ->where('ponum', $ptaNumber)
                                          ->where('approval_status', '!=', 'A')
                                          ->get();
                if(sizeof($checkIsFullApprove) > 0){
                    $poUser     = DB::table('users')->where('email', $podata->createdby)->first();
                    $approverId = DB::table('v_workflow_budget')->where('object', 'PO')
                                    ->where('requester', $poUser->id)
                                    ->where('approver_level', $nextApprover)
                                    ->pluck('approver');

                    $mailto = DB::table('users')
                            ->whereIn('id', $approverId)
                            ->pluck('email');

                    $dataApprovePO = DB::table('v_po_duedate')
                            ->where('ponum', $ptaNumber)
                            ->whereIn('poitem', $poItems)
                            ->orderBy('id')->get();

                    Mail::to($mailto)->queue(new NotifApprovePoMail($dataApprovePO, $podata->id, $ptaNumber));
                }else{
                    //Full Approve
                    DB::table('t_po01')->where('ponum', $ptaNumber)
                    ->update([
                        // 'approved_amount' => $amount,
                        'approvestat'   => 'A'
                    ]);

                    DB::table('t_po02')
                    ->where('ponum', $ptaNumber)
                    ->whereIn('poitem', $poItems)
                    ->update([
                        // 'approved_amount' => $amount,
                        'approvestat'   => 'A'
                    ]);

                    $totalPricePO = getTotalPricePO($ptaNumber);
                    // return $totalPricePO;
                    DB::table('t_budget_history')->insert([
                        'deptid'        => $podata->deptid,
                        'budget_period' => (int)date('M'),
                        'amount'        => $totalPricePO,
                        'budget_type'   => 'D',
                        'note'          => 'Pembelian dengan PO '. $ptaNumber,
                        'refnum'        => $ptaNumber,
                        'refitem'       => null,
                        'createdon'     => getLocalDatabaseDateTime(),
                        'createdby'     => Auth::user()->email ?? Auth::user()->username
                    ]);
                    // INSERT INTO t_budget_history (deptid,budget_period,amount,budget_type,note,refnum,refitem,createdon,createdby)
                    // VALUES(NEW.deptid,NEW.budget_period,NEW.amount,'C','Budget Allocation',NULL,NULL,NOW(),NEW.createdby)

                }

                DB::commit();

                $result = array(
                    'msgtype' => '200',
                    'message' => 'PO dengan Nomor : '. $ptaNumber . ' berhasil di approve'
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
            // return Redirect::to("/approve/pr")->withError($e->getMessage());
        }
    }

    public function approveItems(Request $data){
        DB::beginTransaction();
        try{
            $ptaNumber = $data['ponum'];
            $poItems   = $data['poitem'];

            $userAppLevel = DB::table('t_po_approval')
                            ->select('approver_level')
                            ->where('ponum', $ptaNumber)
                            ->whereIn('poitem', $poItems)
                            ->where('approver', Auth::user()->id)
                            ->first();

            $podata = DB::table('t_po01')->where('ponum', $ptaNumber)->first();
            if($req['action'] === 'R'){
                DB::table('t_po_approval')
                ->where('ponum', $ptaNumber)
                ->whereIn('poitem', $poItems)
                // ->where('approver_id', Auth::user()->id)
                // ->where('approver_level',$userAppLevel->approver_level)
                ->update([
                    'approval_status' => 'R',
                    'approval_remark' => 'Level '. $userAppLevel->approver_level . ' Rejected PO',
                    'approved_by'     => Auth::user()->username,
                    'approval_date'   => getLocalDatabaseDateTime()
                ]);

                DB::table('t_po01')->where('ponum', $ptaNumber)->update([
                    // 'approved_amount' => $amount,
                    'approvestat'   => 'R'
                ]);

                DB::table('t_po02')
                ->where('ponum', $ptaNumber)
                ->whereIn('poitem', $poItems)
                ->update([
                    // 'approved_amount' => $amount,
                    'approvestat'   => 'R'
                ]);

                DB::table('t_po_approval')
                    ->where('ponum', $ptaNumber)
                    ->whereIn('poitem', $poItems)
                    // ->where('approver_level', $nextApprover)
                    ->update([
                        'is_active' => 'N'
                    ]);
                DB::commit();
                $result = array(
                    'msgtype' => '200',
                    'message' => 'PO dengan Nomor : '. $ptaNumber . ' berhasil di reject'
                );
            }else{
                //Set Approval
                DB::table('t_po_approval')
                ->where('ponum', $ptaNumber)
                ->whereIn('poitem', $poItems)
                // ->where('approver_id', Auth::user()->id)
                ->where('approver_level',$userAppLevel->approver_level)
                ->update([
                    'approval_status' => 'A',
                    'approval_remark' => $req['approvernote'],
                    'approved_by'     => Auth::user()->username,
                    'approval_date'   => getLocalDatabaseDateTime()
                ]);

                $nextApprover = $this->getNextApproval($ptaNumber);
                if($nextApprover  != null){
                    DB::table('t_po_approval')
                    ->where('ponum', $ptaNumber)
                    ->whereIn('poitem', $poItems)
                    ->where('approver_level', $nextApprover)
                    ->update([
                        'is_active' => 'Y'
                    ]);
                }

                $checkIsFullApprove = DB::table('t_po_approval')
                                          ->where('ponum', $ptaNumber)
                                          ->where('approval_status', '!=', 'A')
                                          ->get();
                if(sizeof($checkIsFullApprove) > 0){
                    $poUser = DB::table('users')->where('email', $podata->createdby)->first();
                    $approverId = DB::table('v_workflow_budget')->where('object', 'PO')
                                    ->where('requester', $poUser->id)
                                    ->where('approver_level', $nextApprover)
                                    ->pluck('approver');

                    $mailto = DB::table('users')
                            ->whereIn('id', $approverId)
                            ->pluck('email');

                    $dataApprovePO = DB::table('v_po_duedate')
                            ->where('ponum', $ptaNumber)
                            ->whereIn('poitem', $poItems)
                            ->orderBy('id')->get();

                    Mail::to($mailto)->queue(new NotifApprovePoMail($dataApprovePO, $podata->id, $ptaNumber));
                }else{
                    //Full Approve
                    DB::table('t_po01')->where('ponum', $ptaNumber)
                    ->update([
                        // 'approved_amount' => $amount,
                        'approvestat'   => 'A'
                    ]);

                    DB::table('t_po02')
                    ->where('ponum', $ptaNumber)
                    ->whereIn('poitem', $poItems)
                    ->update([
                        // 'approved_amount' => $amount,
                        'approvestat'   => 'A'
                    ]);

                    $totalPricePO = getTotalPricePO($ptaNumber);
                    // return $totalPricePO;
                    DB::table('t_budget_history')->insert([
                        'deptid'        => $podata->deptid,
                        'budget_period' => (int)date('M'),
                        'amount'        => $totalPricePO,
                        'budget_type'   => 'D',
                        'note'          => 'Pembelian dengan PO '. $ptaNumber,
                        'refnum'        => $ptaNumber,
                        'refitem'       => null,
                        'createdon'     => getLocalDatabaseDateTime(),
                        'createdby'     => Auth::user()->email ?? Auth::user()->username
                    ]);
                    // INSERT INTO t_budget_history (deptid,budget_period,amount,budget_type,note,refnum,refitem,createdon,createdby)
                    // VALUES(NEW.deptid,NEW.budget_period,NEW.amount,'C','Budget Allocation',NULL,NULL,NOW(),NEW.createdby)

                }

                DB::commit();

                $result = array(
                    'msgtype' => '200',
                    'message' => 'PO dengan Nomor : '. $ptaNumber . ' berhasil di approve'
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
            // return Redirect::to("/approve/pr")->withError($e->getMessage());
        }
    }

    public function reGenerateApproval(){
        DB::beginTransaction();
        try{
            $oldPO = DB::table('t_po_approval')->where('poitem', 0)->get();
            // return $oldPO;
            foreach($oldPO as $po){
                $ptaNumber = $po->ponum;
                // return $ptaNumber;
                $pohdr    = DB::table('t_po01')->where('ponum', $ptaNumber)->first();
                if($pohdr){
                    $poItems  = DB::table('t_po02')->where('ponum', $ptaNumber)->get();
                    $creator  = DB::table('users')->where('email', $pohdr->createdby)->first();
                    $approval = DB::table('v_workflow_budget')
                                ->where('object', 'PO')
                                ->where('requester', $creator->id)->get();

                    // return $poItems;

                    DB::table('t_po_approval')->where('ponum', $ptaNumber)->delete();
                    for($a = 0; $a < sizeof($poItems); $a++){
                        $insertApproval = array();
                        foreach($approval as $row){
                            $approvals = array(
                                'ponum'             => $ptaNumber,
                                'poitem'            => $poItems[$a]->poitem,
                                'approver_level'    => $row->approver_level,
                                'approver'          => $po->approver,
                                'requester'         => $po->requester,
                                'is_active'         => $po->is_active,
                                'createdon'         => $po->createdon,
                                'approval_remark'   => $po->approval_remark,
                                'approval_date'     => $po->approval_date ?? getLocalDatabaseDateTime(),
                                'approved_by'       => $po->approved_by,
                                'approval_status'   => $pohdr->approvestat,
                            );
                            array_push($insertApproval, $approvals);
                        }
                        insertOrUpdate($insertApproval,'t_po_approval');
                    }

                    DB::commit();

                }
            }

            return "Success";
        }
        catch(\Exception $e){
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
