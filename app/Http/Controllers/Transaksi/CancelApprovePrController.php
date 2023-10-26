<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class CancelApprovePrController extends Controller
{
    public function index(){
        return view('transaksi.cancelapprove.cancelpr');
    }

    public function listPR(Request $request){
        if(isset($request->params)){
            $params = $request->params;
            $whereClause = $params['sac'];
        }
        $query = DB::table('v_rpr01')
                 ->select('id', 'prnum', 'prdate', 'requestby', 'deptname')
                 ->distinct()
                 ->orderBy('id');
        return DataTables::queryBuilder($query)
        ->toJson();
    }

    public function resetApprovePR($id){
        DB::beginTransaction();
        try{
            $wodata = DB::table('t_pr01')->where('id', $id)->first();
            if($wodata){
                DB::table('t_pr01')->where('id', $id)->update([
                    'approvestat'   => 'N'
                ]);

                $firstApproval = DB::table('t_pr_approvalv2')
                        ->where('prnum', $wodata->prnum)
                        ->orderBy('approver_level', 'ASC')
                        ->first();

                DB::table('t_pr_approvalv2')->where('prnum', $wodata->prnum)->update([
                    'approval_status' => 'N',
                    'is_active'       => 'N'
                ]);

                DB::table('t_pr_approvalv2')->where('prnum', $wodata->prnum)
                ->where('approver_level', $firstApproval->approver_level)
                ->update([
                    'is_active' => 'Y'
                ]);
                DB::commit();

                $result = array(
                    'msgtype' => '200',
                    'message' => 'Approval PR '. $wodata->prnum . ' berhasil direset'
                );
            }else{
                $result = array(
                    'msgtype' => '500',
                    'message' => 'PR tidak ditemukan'
                );
            }
            return $result;
        }catch(\Exception $e){
            DB::rollBack();
            $result = array(
                'msgtype' => '500',
                'message' => $e->getMessage()
            );
            return $result;
        }
    }

    public function deletePR($id){
        DB::beginTransaction();
        try{
            $wodata = DB::table('t_pr01')->where('id', $id)->first();
            if($wodata){
                DB::table('t_pr01')->where('id', $id)->delete();
                DB::table('t_pr02')->where('prnum', $wodata->prnum)->delete();
                DB::table('t_pr_approvalv2')->where('prnum', $wodata->prnum)->delete();
                DB::commit();

                $result = array(
                    'msgtype' => '200',
                    'message' => 'PR '. $wodata->prnum . ' berhasil dihapus'
                );
            }else{
                $result = array(
                    'msgtype' => '500',
                    'message' => 'PR tidak ditemukan'
                );
            }
            return $result;
        }catch(\Exception $e){
            DB::rollBack();
            $result = array(
                'msgtype' => '500',
                'message' => $e->getMessage()
            );
            return $result;
        }
    }
}
