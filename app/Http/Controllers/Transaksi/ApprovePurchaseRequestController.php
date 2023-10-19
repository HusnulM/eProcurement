<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Mail\NotifApprovePrMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class ApprovePurchaseRequestController extends Controller
{
    public function index(){
        return view('transaksi.pr.approvelist');
    }

    public function approveDetail($id){
        $prhdr = DB::table('t_pr01')->where('id', $id)->first();
        if($prhdr){
            $items       = DB::table('v_pr_approval_v2')
                            ->select('id','prnum', 'pritem', 'prdate', 'requestby', 'material', 'matdesc', 'quantity', 'unit', 'pbjnumber', 'pbjitem', 'approval_status')
                            ->distinct()
                            ->where('prnum', $prhdr->prnum)
                            ->where('approver', Auth::user()->id)
                            ->get();
            $approvals   = DB::table('v_pr_approval_v2')
                            ->where('prnum', $prhdr->prnum)
                            ->where('approver', Auth::user()->id)
                            ->get();
            $department  = DB::table('v_pr_approval_v2')->where('prnum', $prhdr->prnum)->first();
            $attachments = DB::table('t_attachments')->where('doc_object','PR')->where('doc_number', $prhdr->prnum)->get();

            $pbjNumber = DB::table('t_pr02')->where('prnum', $prhdr->prnum)->pluck('pbjnumber');
            $pbjAttachments = DB::table('t_attachments')->where('doc_object','PBJ')
                              ->whereIn('doc_number', $pbjNumber)->get();

            $isApprovedbyUser = DB::table('v_pr_approval_v2')
                    ->where('prnum',    $prhdr->prnum)
                    ->where('approver', Auth::user()->id)
                    ->where('is_active', 'Y')
                    ->first();

            return view('transaksi.pr.approvedetail',
                [
                    'prhdr'            => $prhdr,
                    'pritem'           => $items,
                    'approvals'        => $approvals,
                    'department'       => $department,
                    'attachments'      => $attachments,
                    'isApprovedbyUser' => $isApprovedbyUser,
                    'pbjAttachments'   => $pbjAttachments
                ]);
        }else{
            return Redirect::to("/approve/pr")->withError('Dokumen PR tidak ditemukan');
        }
    }

    public function ApprovalList(Request $request){

        if(isset($request->params)){
            $params = $request->params;
            $whereClause = $params['sac'];
        }
        $query = DB::table('v_pr_approval_v2')
                 ->select('id','prnum', 'prdate','requestby','department')
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
        $userLevel = DB::table('t_pr_approvalv2')
                    ->where('prnum', $dcnNum)
                    ->where('approver', Auth::user()->id)
                    ->first();

        $nextApproval = DB::table('t_pr_approvalv2')
                        ->where('prnum', $dcnNum)
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
            $ptaNumber = $req['prnum'];

            $prhdr = DB::table('t_pr01')->where('prnum', $ptaNumber)->first();
            $prUser = DB::table('users')->where('email', $prhdr->createdby)->first();
            // return $ptaNumber;

            // $amount = $req['amount2'];
            // $amount = str_replace(',','',$amount);
            // DB::table('t_budget')->where('ptanumber', $ptaNumber)->update([
            //     'approved_amount' => $amount,
            // ]);

            $userAppLevel = DB::table('t_pr_approvalv2')
                            ->select('approver_level')
                            ->where('prnum', $ptaNumber)
                            ->where('approver', Auth::user()->id)
                            ->first();

            //Set Approval

            if($req['action'] === 'A'){
                DB::table('t_pr_approvalv2')
                ->where('prnum', $ptaNumber)
                // ->where('approver_id', Auth::user()->id)
                ->where('approver_level',$userAppLevel->approver_level)
                ->update([
                    'approval_status' => $req['action'],
                    'approval_remark' => $req['approvernote'],
                    'approved_by'     => Auth::user()->username,
                    'approval_date'   => getLocalDatabaseDateTime()
                ]);
                $nextApprover = $this->getNextApproval($ptaNumber);
                if($nextApprover  != null){
                    DB::table('t_pr_approvalv2')
                    ->where('prnum', $ptaNumber)
                    ->where('approver_level', $nextApprover)
                    ->update([
                        'is_active' => 'Y'
                    ]);
                }
                $checkIsFullApprove = DB::table('t_pr_approvalv2')
                                          ->where('prnum', $ptaNumber)
                                          ->where('approval_status', '!=', 'A')
                                          ->get();
                if(sizeof($checkIsFullApprove) > 0){
                    $approverId = DB::table('v_workflow_budget')->where('object', 'PR')
                                ->where('requester', $prUser->id)
                                ->where('approver_level', $nextApprover)
                                ->pluck('approver');

                    $mailto = DB::table('users')
                            ->whereIn('id', $approverId)
                            ->pluck('email');

                    $dataApprovePBJ = DB::table('v_pr_duedate')
                            ->where('prnum', $ptaNumber)
                            ->orderBy('id')->get();

                    Mail::to($mailto)->queue(new NotifApprovePrMail($dataApprovePBJ, $prhdr->id, $ptaNumber));
                }else{
                    //Full Approve
                    DB::table('t_pr01')->where('prnum', $ptaNumber)->update([
                        // 'approved_amount' => $amount,
                        'approvestat'   => 'A'
                    ]);
                }
                $result = array(
                    'msgtype' => '200',
                    'message' => 'PR dengan Nomor : '. $ptaNumber . ' berhasil di approve'
                );
            }else{
                DB::table('t_pr_approvalv2')
                ->where('prnum', $ptaNumber)
                // ->where('approver_id', Auth::user()->id)
                // ->where('approver_level',$userAppLevel->approver_level)
                ->update([
                    'approval_status' => 'R',
                    'approval_remark' => $req['approvernote'],
                    'approved_by'     => Auth::user()->username,
                    'approval_date'   => getLocalDatabaseDateTime()
                ]);

                DB::table('t_pr01')->where('prnum', $ptaNumber)->update([
                    // 'approved_amount' => $amount,
                    'approvestat'   => 'R'
                ]);

                $result = array(
                    'msgtype' => '200',
                    'message' => 'PR dengan Nomor : '. $ptaNumber . ' berhasil di reject'
                );
            }

            DB::commit();

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
            // return $data;
            $prID       = $data['prnum'];
            $pbjHeader  = DB::table('t_pr01')->where('id', $prID)->first();
            $pbjCreator = DB::table('users')->where('email', $pbjHeader->createdby)->first();
            $items      = join(",",$data['pritem']);
            $ptaNumber  = $pbjHeader->prnum;

            $pbjItemData = DB::table('v_pr_approval_v2')
            ->where('prnum', $ptaNumber)
            ->whereIn('pritem', $data['pritem'])
            ->where('approver', Auth::user()->id)
            ->get();
            // return $pbjItemData;
            $userAppLevel = DB::table('t_pr_approvalv2')
            ->select('approver_level')
            ->where('prnum', $ptaNumber)
            ->whereIn('pritem', $data['pritem'])
            ->where('approver', Auth::user()->id)
            ->first();

            // return $userAppLevel;

            if($data['action'] === 'R'){
                DB::table('t_pr_approvalv2')
                ->where('prnumber', $ptaNumber)
                ->whereIn('pritem', $data['pritem'])
                // ->where('approver_level', $nextApprover)
                ->update([
                    'is_active'       => 'N',
                    'approval_status' => 'R',
                    'approval_remark' => 'Level '. $userAppLevel->approver_level . ' Rejected',
                    'approved_by'     => Auth::user()->username,
                    'approval_date'   => getLocalDatabaseDateTime()
                ]);

                DB::table('t_pr01')->where('prnum', $ptaNumber)
                    ->update([
                        'approvestat'   => $data['action']
                    ]);

                DB::table('t_pr02')->where('pbjnumber', $ptaNumber)
                    ->whereIn('pritem', $data['pritem'])
                    ->update([
                        'approvestat'   => $data['action']
                ]);
            }else{
                //Set Approval
                DB::table('t_pr_approvalv2')
                ->where('prnum', $ptaNumber)
                ->whereIn('pritem', $data['pritem'])
                ->where('approver_level',$userAppLevel->approver_level)
                ->update([
                    'approval_status' => 'A',
                    // 'approval_remark' => $req['approvernote'],
                    'approval_remark' => 'Level '. $userAppLevel->approver_level . ' Approved',
                    'approved_by'     => Auth::user()->username,
                    'approval_date'   => getLocalDatabaseDateTime()
                ]);

                $nextApprover = $this->getNextApproval($ptaNumber);
                if($nextApprover  != null){
                    DB::table('t_pr_approvalv2')
                    ->where('prnum', $ptaNumber)
                    ->whereIn('pritem', $data['pritem'])
                    ->where('approver_level', $nextApprover)
                    ->update([
                        'is_active' => 'Y'
                    ]);
                }

                $checkIsFullApprove = DB::table('t_pr_approvalv2')
                                          ->where('prnum', $ptaNumber)
                                        //   ->whereIn('pritem', $data['pritem'])
                                          ->where('approval_status', '!=', 'A')
                                          ->get();
                // return $checkIsFullApprove;
                if(sizeof($checkIsFullApprove) > 0){
                    // go to next approver
                    $approverId = DB::table('v_workflow_budget')->where('object', 'PR')
                            ->where('requester', $pbjCreator->id)
                            ->where('approver_level', $nextApprover)
                            ->pluck('approver');

                    $mailto = DB::table('users')
                        ->whereIn('id', $approverId)
                        ->pluck('email');

                    $dataApprovePBJ = DB::table('v_pr_duedate')
                            ->where('prnum', $ptaNumber)
                            ->whereIn('pritem', $data['pritem'])
                            ->orderBy('id')->get();

                    Mail::to($mailto)->queue(new NotifApprovePrMail($dataApprovePBJ, $pbjHeader->id, $ptaNumber));
                }else{
                    //Full Approve
                    DB::table('t_pr01')->where('prnum', $ptaNumber)
                    ->update([
                        'approvestat'   => 'A'
                    ]);

                    DB::table('t_pr02')
                    ->where('prnum', $ptaNumber)
                    ->whereIn('pritem', $data['pritem'])
                    ->update([
                        'approvestat'   => 'A'
                    ]);
                }
            }

            DB::commit();

            if($data['action'] === 'A'){
                $result = array(
                    'msgtype' => '200',
                    'message' => 'PR dengan Nomor : '. $ptaNumber . ' berhasil di approve'
                );
            }elseif($data['action'] === 'R'){
                $result = array(
                    'msgtype' => '200',
                    'message' => 'PR dengan Nomor : '. $ptaNumber . ' berhasil di reject'
                );
            }
            return $result;
        }
        catch(\Exception $e){
            DB::rollBack();
            $result = array(
                'msgtype' => '500',
                'message' => $e->getMessage()
            );
            return $result;
            // return Redirect::to("/approve/budget")->withError($e->getMessage());
        }
    }

    public function reGenerateApproval(){
        DB::beginTransaction();
        try{
            // $oldPO = DB::table('t_pr_approvalv2')->where('pritem', 0)->limit(40)->get();
            $pohdr    = DB::table('t_pr01')->get();

            foreach($pohdr as $po){
                $ptaNumber = $po->prnum;

                $poItems  = DB::table('t_pr02')->where('prnum', $ptaNumber)->get();
                $creator  = DB::table('users')->where('email', $po->createdby)->first();
                $approval = DB::table('v_workflow_budget')
                            ->where('object', 'PR')
                            ->where('requester', $creator->id)->get();

                DB::table('t_pr_approvalv2')->where('prnum', $ptaNumber)->delete();
                for($a = 0; $a < sizeof($poItems); $a++){
                    $insertApproval = array();
                    foreach($approval as $row){
                        $approvals = array(
                            'prnum'             => $ptaNumber,
                            'pritem'            => $poItems[$a]->pritem,
                            'approver_level'    => $row->approver_level,
                            'approver'          => $po->approver,
                            'requester'         => $po->requester,
                            'is_active'         => $po->is_active,
                            'createdon'         => $po->createdon,
                            'approval_remark'   => $po->approval_remark,
                            'approval_date'     => $po->approval_date ?? getLocalDatabaseDateTime(),
                            'approved_by'       => $po->approved_by,
                            'approval_status'   => $po->approvestat,
                        );
                        array_push($insertApproval, $approvals);
                    }
                    insertOrUpdate($insertApproval,'t_pr_approvalv2');
                }

                DB::commit();
            }

            return "Success";
        }
        catch(\Exception $e){
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
