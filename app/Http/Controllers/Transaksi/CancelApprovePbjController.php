<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class CancelApprovePbjController extends Controller
{
    public function index(){
        return view('transaksi.cancelapprove.cancelpbj');
    }

    public function listPBJ(Request $request){
        if(isset($request->params)){
            $params = $request->params;        
            $whereClause = $params['sac'];
        }
        $query = DB::table('t_pbj01')
                 ->select('id','wonum','wodate','description','schedule_type')
                 ->distinct()
                 ->where('pbj_created','N')
                 ->orderBy('id');
        return DataTables::queryBuilder($query)
        ->toJson();
    }

    public function resetApprovePBJ($id){
        DB::beginTransaction();
        try{
            $wodata = DB::table('t_wo01')->where('id', $id)->first();
            if($wodata){
                DB::table('t_wo01')->where('id', $id)->update([
                    'approvestat' => 'O',
                    'wo_status'   => 'O'
                ]);

                $firstApproval = DB::table('t_wo_approval')
                        ->where('wonum', $wodata->wonum)
                        ->orderBy('approver_level', 'ASC')
                        ->first();

                DB::table('t_wo_approval')->where('wonum', $wodata->wonum)->update([
                    'approval_status' => 'N',
                    'is_active'       => 'N'
                ]);

                DB::table('t_wo_approval')->where('wonum', $wodata->wonum)
                ->where('approver_level', $firstApproval->approver_level)
                ->update([
                    'is_active' => 'Y'
                ]);
                DB::commit();
                
                $result = array(
                    'msgtype' => '200',
                    'message' => 'Approval WO'. $wodata->wonum . ' berhasil direset'
                );
            }else{
                $result = array(
                    'msgtype' => '500',
                    'message' => 'WO tidak ditemukan'
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

    public function deletePBJ($id){
        DB::beginTransaction();
        try{
            $wodata = DB::table('t_wo01')->where('id', $id)->first();
            if($wodata){
                DB::table('t_wo01')->where('id', $id)->delete();
                DB::table('t_wo02')->where('wonum', $wodata->wonum)->delete();
                DB::table('t_wo_approval')->where('wonum', $wodata->wonum)->delete();
                DB::commit();

                $result = array(
                    'msgtype' => '200',
                    'message' => 'WO'. $wodata->wonum . ' berhasil dihapus'
                );
            }else{
                $result = array(
                    'msgtype' => '500',
                    'message' => 'WO tidak ditemukan'
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
