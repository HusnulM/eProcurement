<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class BudgetingController extends Controller
{
    public function index(){
        return view('transaksi.budget.index');
    }

    public function list(){
        return view('transaksi.budget.list');
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
            $tgl   = substr($req['tglaju'], 8, 2);
            $bulan = substr($req['tglaju'], 5, 2);
            $tahun = substr($req['tglaju'], 0, 4);
            // return $tgl . ' - ' . $bulan . ' - ' . $tahun;
            $ptaNumber = generateBudgetDcnNumber($tahun, $bulan, $tgl, Auth::user()->deptid, getUserDepartment());

            // return $ptaNumber;

            $amount = $req['nominal'];
            $amount = str_replace(',','',$amount);
            DB::table('t_budget')->insert([
                'ptanumber'     => $ptaNumber,
                'tgl_aju'       => $req['tglaju'],
                'deptid'        => Auth::user()->deptid,
                'budget_year'   => $tahun ?? date('Y'),
                'budget_month'  => $bulan,
                'budget_period' => $bulan,
                'amount'        => $amount,
                'currency'      => 'IDR',
                'budget_status' => 'O',
                'remark'        => $req['remark'],
                'requester'     => Auth::user()->id,
                'createdon'     => date('Y-m-d H:m:s'),
                'createdby'     => Auth::user()->email ?? Auth::user()->username
            ]);

            //Set Approval
            $approval = DB::table('v_workflow_budget')->where('object','BUDGET')->where('requester', Auth::user()->id)->get();
            if(sizeof($approval) > 0){
                $insertApproval = array();
                foreach($approval as $row){
                    $is_active = 'N';
                    if($row->approver_level == 1){
                        $is_active = 'Y';
                    }
                    $approvals = array(
                        'ptanumber'         => $ptaNumber,
                        'approver_level'    => $row->approver_level,
                        'approver'          => $row->approver,
                        'requester'         => Auth::user()->id,
                        'is_active'         => $is_active,
                        'createdon'         => getLocalDatabaseDateTime()
                    );
                    array_push($insertApproval, $approvals);
                }
                insertOrUpdate($insertApproval,'t_budget_approval');
            }

            DB::commit();
            return Redirect::to("/transaction/budgeting")->withSuccess('Pengajuan Budget Berhasil dibuat dengan Nomor : '. $ptaNumber);
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/transaction/budgeting")->withError($e->getMessage());
        }
    }
}
