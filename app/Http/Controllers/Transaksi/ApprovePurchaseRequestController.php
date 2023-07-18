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
            $items       = DB::table('t_pr02')->where('prnum', $prhdr->prnum)->get();
            $approvals   = DB::table('v_pr_approval')->where('prnum', $prhdr->prnum)->get();
            $department  = DB::table('v_pr_approval')->where('prnum', $prhdr->prnum)->first();
            $attachments = DB::table('t_attachments')->where('doc_object','PR')->where('doc_number', $prhdr->prnum)->get();

            $isApprovedbyUser = DB::table('v_pr_approval')
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
                    'isApprovedbyUser' => $isApprovedbyUser
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
        $query = DB::table('v_pr_approval')
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
        $userLevel = DB::table('t_pr_approval')
                    ->where('prnum', $dcnNum)
                    ->where('approver', Auth::user()->id)
                    ->first();

        $nextApproval = DB::table('t_pr_approval')
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

            $userAppLevel = DB::table('t_pr_approval')
                            ->select('approver_level')
                            ->where('prnum', $ptaNumber)
                            ->where('approver', Auth::user()->id)
                            ->first();

            //Set Approval
            
            if($req['action'] === 'A'){
                DB::table('t_pr_approval')
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
                    DB::table('t_pr_approval')
                    ->where('prnum', $ptaNumber)
                    ->where('approver_level', $nextApprover)
                    ->update([
                        'is_active' => 'Y'
                    ]);
                }
                $checkIsFullApprove = DB::table('t_pr_approval')
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
                DB::table('t_pr_approval')
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
}
