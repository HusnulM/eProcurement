<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
            $pbjitem = DB::table('t_pbj02')->where('pbjnumber', $pbjhdr->pbjnumber)->get();
            $approvals = DB::table('v_pbj_approval')->where('pbjnumber', $pbjhdr->pbjnumber)->get();
            $attachments = DB::table('t_attachments')->where('doc_object','PBJ')->where('doc_number', $pbjhdr->pbjnumber)->get();

            $isApprovedbyUser = DB::table('v_pbj_approval')
                    ->where('pbjnumber',  $pbjhdr->pbjnumber)
                    ->where('approver', Auth::user()->id)
                    ->where('is_active', 'Y')
                    ->first();

            return view('transaksi.pbj.approvedetail', ['pbjhdr' => $pbjhdr, 'pbjitem' => $pbjitem, 
                'approvals' => $approvals, 
                'isApprovedbyUser' => $isApprovedbyUser,
                'attachments'      => $attachments]);
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
}
