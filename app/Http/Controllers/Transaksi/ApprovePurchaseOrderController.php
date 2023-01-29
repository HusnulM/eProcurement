<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
            $items      = DB::table('t_po02')->where('ponum', $prhdr->ponum)->get();
            $approvals  = DB::table('v_po_approval')->where('ponum', $prhdr->ponum)->get();
            $department = DB::table('v_po_approval')->where('ponum', $prhdr->ponum)->first();
            $attachments = DB::table('t_attachments')->where('doc_object','PO')->where('doc_number', $prhdr->ponum)->get();

            $purchases = DB::table('v_po02')
            ->where('ponum', $prhdr->ponum)
            ->sum('totalprice');

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
                    'approvals' => $approvals, 
                    'department'=> $department,
                    'isApprovedbyUser' => $isApprovedbyUser,
                    'totalprice'       => $purchases,
                    'attachments'      => $attachments
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
        $query = DB::table('v_po_approval')
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
            $ptaNumber = $req['ponum'];

            $userAppLevel = DB::table('t_po_approval')
                            ->select('approver_level')
                            ->where('ponum', $ptaNumber)
                            ->where('approver', Auth::user()->id)
                            ->first();

            $podata = DB::table('t_po01')->where('ponum', $ptaNumber)->first();
            //Set Approval
            DB::table('t_po_approval')
            ->where('ponum', $ptaNumber)
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
                // go to next approver    
            }else{
                //Full Approve
                DB::table('t_po01')->where('ponum', $ptaNumber)->update([
                    // 'approved_amount' => $amount,
                    'approvestat'   => 'A'
                ]);

                $totalPricePO = getTotalPricePO($ptaNumber);

                DB::table('t_budget_history')->insert([
                    'deptid'        => $podata->deptid,
                    'budget_period' => date('M'),
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
