<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Mail\NotifApprovePbjMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class ApprovePbjController extends Controller
{
    public function index(){
        return view('transaksi.pbj.approvelist');
    }

    public function approveDetail($id){
        $pbjhdr = DB::table('t_pbj01')->where('id', $id)->first();
        if($pbjhdr){
            $pbjitem = DB::table('v_pbj03')->where('pbjnumber', $pbjhdr->pbjnumber)->where('approver', Auth::user()->id)->get();
            $approvals = DB::table('v_pbj_approval')
                ->where('pbjnumber', $pbjhdr->pbjnumber)
                ->orderBy('approver_level','asc')
                ->orderBy('pbjitem', 'asc')
                ->get();
            $attachments = DB::table('t_attachments')->where('doc_object','PBJ')->where('doc_number', $pbjhdr->pbjnumber)->get();

            $isApprovedbyUser = DB::table('v_pbj_approval')
                    ->where('pbjnumber',  $pbjhdr->pbjnumber)
                    ->where('approver', Auth::user()->id)
                    ->where('is_active', 'Y')
                    ->first();

            $pbjProject = DB::table('t_projects')->where('idproject', $pbjhdr->idproject)->first();
            if(!$pbjProject){
                $pbjProject = null;
            }
            return view('transaksi.pbj.approvedetail', ['pbjhdr' => $pbjhdr, 'pbjitem' => $pbjitem, 
                'approvals' => $approvals, 
                'isApprovedbyUser' => $isApprovedbyUser,
                'attachments'      => $attachments,
                'project'          => $pbjProject
            ]);
        }else{
            return Redirect::to("/approve/pbj")->withError('Dokumen PBJ tidak ditemukan');
        }
    }

    public function pbjApprovalList(Request $request){
        
        if(isset($request->params)){
            $params = $request->params;        
            $whereClause = $params['sac'];
        }
        $query = DB::table('v_pbj_approval')
                 ->select('id','pbjnumber', 'tgl_pbj','tujuan_permintaan','kepada','unit_desc','engine_model')
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
        $userLevel = DB::table('t_pbj_approval')
                    ->where('approver', Auth::user()->id)
                    ->first();

        $nextApproval = DB::table('t_pbj_approval')
                        ->where('pbjnumber', $dcnNum)
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
            $ptaNumber = $req['pbjNumber'];

            // return $ptaNumber;

            // $amount = $req['amount2'];
            // $amount = str_replace(',','',$amount);
            // DB::table('t_budget')->where('ptanumber', $ptaNumber)->update([
            //     'approved_amount' => $amount,
            // ]);

            $userAppLevel = DB::table('t_pbj_approval')
                            ->select('approver_level')
                            ->where('pbjnumber', $ptaNumber)
                            ->where('approver', Auth::user()->id)
                            ->first();

            //Set Approval
            DB::table('t_pbj_approval')
            ->where('pbjnumber', $ptaNumber)
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
                DB::table('t_pbj_approval')
                ->where('pbjnumber', $ptaNumber)
                ->where('approver_level', $nextApprover)
                ->update([
                    'is_active' => 'Y'
                ]);
            }


            $checkIsFullApprove = DB::table('t_pbj_approval')
                                      ->where('pbjnumber', $ptaNumber)
                                      ->where('approval_status', '!=', 'A')
                                      ->get();
            if(sizeof($checkIsFullApprove) > 0){
                // go to next approver    
            }else{
                //Full Approve
                DB::table('t_pbj01')->where('pbjnumber', $ptaNumber)->update([
                    'pbj_status'   => 'A'
                ]);
            }

            DB::commit();
            $result = array(
                'msgtype' => '200',
                'message' => 'PBJ dengan Nomor : '. $ptaNumber . ' berhasil di approve'
            );
            return $result;
        } catch(\Exception $e){
            DB::rollBack();
            $result = array(
                'msgtype' => '500',
                'message' => $e->getMessage()
            );
            // return Redirect::to("/approve/budget")->withError($e->getMessage());
        }
    }

    public function approveItems(Request $data, $pbjID){
        DB::beginTransaction();
        try{
            
            $pbjHeader  = DB::table('t_pbj01')->where('id', $pbjID)->first();
            $pbjCreator = DB::table('users')->where('email', $pbjHeader->createdby)->first();
            $items      = join(",",$data['pbjitem']); 
            $ptaNumber  = $pbjHeader->pbjnumber;
            
            $pbjItemData = DB::table('t_pbj02')
            ->where('pbjnumber', $ptaNumber)
            ->whereIn('pbjitem', $data['pbjitem'])->get();
            // return $items;
            $userAppLevel = DB::table('t_pbj_approval')
            ->select('approver_level')
            ->where('pbjnumber', $ptaNumber)
            ->whereIn('pbjitem', $data['pbjitem'])
            ->where('approver', Auth::user()->id)
            ->first();
            
            if($data['action'] === 'R'){
                DB::table('t_pbj_approval')
                ->where('pbjnumber', $ptaNumber)
                ->whereIn('pbjitem', $data['pbjitem'])
                // ->where('approver_level', $nextApprover)
                ->update([
                    'is_active'       => 'N',
                    'approval_status' => 'R',
                    'approval_remark' => null,
                    'approved_by'     => Auth::user()->username,
                    'approval_date'   => getLocalDatabaseDateTime()
                ]);

                DB::table('t_pbj01')->where('pbjnumber', $ptaNumber)
                    ->update([
                        'pbj_status'   => $data['action']
                    ]);
    
                DB::table('t_pbj02')->where('pbjnumber', $ptaNumber)
                    ->whereIn('pbjitem', $data['pbjitem'])
                    ->update([
                        'approvestat'   => $data['action']
                ]);
            }else{
                //Set Approval
                DB::table('t_pbj_approval')
                ->where('pbjnumber', $ptaNumber)
                ->whereIn('pbjitem', $data['pbjitem'])
                // ->where('approver_id', Auth::user()->id)
                ->where('approver_level',$userAppLevel->approver_level)
                ->update([
                    'approval_status' => $data['action'],
                    // 'approval_remark' => $req['approvernote'],
                    'approval_remark' => null,
                    'approved_by'     => Auth::user()->username,
                    'approval_date'   => getLocalDatabaseDateTime()
                ]);
    
                $nextApprover = $this->getNextApproval($ptaNumber);
                if($nextApprover  != null){
                    DB::table('t_pbj_approval')
                    ->where('pbjnumber', $ptaNumber)
                    ->whereIn('pbjitem', $data['pbjitem'])
                    ->where('approver_level', $nextApprover)
                    ->update([
                        'is_active' => 'Y'
                    ]);
                }
    
                $checkIsFullApprove = DB::table('t_pbj_approval')
                                          ->where('pbjnumber', $ptaNumber)
                                          ->whereIn('pbjitem', $data['pbjitem'])
                                          ->where('approval_status', '!=', 'A')
                                          ->get();
                if(sizeof($checkIsFullApprove) > 0){
                    // go to next approver    
                    $approverId = DB::table('v_workflow_budget')->where('object', 'PBJ')
                            ->where('requester', $pbjCreator->id)
                            ->where('approver_level', $nextApprover)
                            ->pluck('approver');

                    $mailto = DB::table('users')
                        ->whereIn('id', $approverId)
                        ->pluck('email');   

                    $dataApprovePBJ = DB::table('v_duedate_pbj')
                        ->where('pbjnumber', $ptaNumber)
                        ->orderBy('id')->get();

                    Mail::to($mailto)->queue(new NotifApprovePbjMail($dataApprovePBJ, $pbjID, $ptaNumber)); 
                }else{
                    //Full Approve
                    DB::table('t_pbj01')->where('pbjnumber', $ptaNumber)
                    ->update([
                        'pbj_status'   => $data['action']
                    ]);
    
                    DB::table('t_pbj02')->where('pbjnumber', $ptaNumber)
                    ->whereIn('pbjitem', $data['pbjitem'])
                    ->update([
                        'approvestat'   => $data['action']
                    ]);
                }
            }

            DB::commit();

            if($data['action'] === 'A'){
                $result = array(
                    'msgtype' => '200',
                    'message' => 'PBJ dengan Nomor : '. $ptaNumber . ' berhasil di approve',
                    'items'   => $pbjItemData
                );
            }elseif($data['action'] === 'R'){
                $result = array(
                    'msgtype' => '200',
                    'message' => 'PBJ dengan Nomor : '. $ptaNumber . ' berhasil di reject',
                    'items'   => $pbjItemData
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
}
