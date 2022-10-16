<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class ApproveBudgetController extends Controller
{
    public function index(){
        return view('transaksi.budget.approvelist');
    }

    public function budgetApprovalList(Request $request){
        
        if(isset($request->params)){
            $params = $request->params;        
            $whereClause = $params['sac'];
        }
        $query = DB::table('v_budget_approval')
                 ->where('approver',Auth::user()->id)
                 ->where('is_active','Y')
                 ->where('approval_status','N')
                 ->orderBy('id');
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

    public function getNextApproval($dcnNum){
        $userLevel = DB::table('t_budget_approval')
                    ->where('approver', Auth::user()->id)
                    ->first();

        $nextApproval = DB::table('t_budget_approval')
                        ->where('ptanumber', $dcnNum)
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
            $ptaNumber = $req['ptanumber'];

            // return $ptaNumber;

            $amount = $req['amount2'];
            $amount = str_replace(',','',$amount);
            DB::table('t_budget')->where('ptanumber', $ptaNumber)->update([
                'approved_amount' => $amount,
            ]);

            $userAppLevel = DB::table('t_budget_approval')
                            ->select('approver_level')
                            ->where('ptanumber', $ptaNumber)
                            ->where('approver', Auth::user()->id)
                            ->first();

            //Set Approval
            DB::table('t_budget_approval')
            ->where('ptanumber', $ptaNumber)
            // ->where('approver_id', Auth::user()->id)
            ->where('approver_level',$userAppLevel->approver_level)
            ->update([
                'approval_status' => 'A',
                'approved_by'     => Auth::user()->username,
                'approval_date'   => getLocalDatabaseDateTime()
            ]);

            $nextApprover = $this->getNextApproval($ptaNumber);
            if($nextApprover  != null){
                DB::table('t_budget_approval')
                ->where('ptanumber', $ptaNumber)
                ->where('approver_level', $nextApprover)
                ->update([
                    'is_active' => 'Y'
                ]);
            }


            $checkIsFullApprove = DB::table('t_budget_approval')
                                      ->where('ptanumber', $ptaNumber)
                                      ->where('approval_status', '!=', 'A')
                                      ->get();
            if(sizeof($checkIsFullApprove) > 0){
                // go to next approver    
            }else{
                //Full Approve
                DB::table('t_budget')->where('ptanumber', $ptaNumber)->update([
                    'approved_amount' => $amount,
                    'budget_status'   => 'A'
                ]);
            }

            // $approval = DB::table('v_workflow_budget')->where('requester', Auth::user()->id)->get();
            // if(sizeof($approval) > 0){
            //     $insertApproval = array();
            //     foreach($approval as $row){
            //         $is_active = 'N';
            //         if($row->approver_level == 1){
            //             $is_active = 'Y';
            //         }
            //         $approvals = array(
            //             'ptanumber'         => $ptaNumber,
            //             'approver_level'    => $row->approver_level,
            //             'approver'          => $row->approver,
            //             'requester'         => Auth::user()->id,
            //             'is_active'         => $is_active,
            //             'createdon'         => getLocalDatabaseDateTime()
            //         );
            //         array_push($insertApproval, $approvals);
            //     }
            //     insertOrUpdate($insertApproval,'t_budget_approval');
            // }

            DB::commit();
            return Redirect::to("/approve/budget")->withSuccess('Pengajuan Budget dengan Nomor : '. $ptaNumber . ' berhasil di approve');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/approve/budget")->withError($e->getMessage());
        }
    }
}
