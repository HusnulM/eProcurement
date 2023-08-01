<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class CancelApprovePoController extends Controller
{
    public function index(){
        return view('transaksi.cancelapprove.cancelpo');
    }

    public function listPO(Request $request){
        if(isset($request->params)){
            $params = $request->params;        
            $whereClause = $params['sac'];
        }
        $query = DB::table('t_po01')
                 ->orderBy('id');
        return DataTables::queryBuilder($query)
        ->toJson();
    }

    public function resetApprovePO($id){
        DB::beginTransaction();
        try{
            $wodata = DB::table('t_po01')->where('id', $id)->first();
            if($wodata){
                DB::table('t_po01')->where('id', $id)->update([
                    'approvestat'   => 'N'
                ]);

                $firstApproval = DB::table('t_po_approval')
                        ->where('ponum', $wodata->ponum)
                        ->orderBy('approver_level', 'ASC')
                        ->first();

                DB::table('t_po_approval')->where('ponum', $wodata->ponum)->update([
                    'approval_status' => 'N',
                    'is_active'       => 'N'
                ]);

                DB::table('t_po_approval')->where('ponum', $wodata->ponum)
                ->where('approver_level', $firstApproval->approver_level)
                ->update([
                    'is_active' => 'Y'
                ]);
                DB::commit();
                
                $result = array(
                    'msgtype' => '200',
                    'message' => 'Approval PO '. $wodata->ponum . ' berhasil direset'
                );
            }else{
                $result = array(
                    'msgtype' => '500',
                    'message' => 'PO tidak ditemukan'
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

    public function deletePO($id){
        DB::beginTransaction();
        try{
            $wodata = DB::table('t_po01')->where('id', $id)->first();
            if($wodata){
                DB::table('t_po01')->where('id', $id)->delete();
                DB::table('t_po02')->where('ponum', $wodata->ponum)->delete();
                DB::table('t_po_approval')->where('ponum', $wodata->ponum)->delete();
                DB::commit();

                $result = array(
                    'msgtype' => '200',
                    'message' => 'PO '. $wodata->ponum . ' berhasil dihapus'
                );
            }else{
                $result = array(
                    'msgtype' => '500',
                    'message' => 'PO tidak ditemukan'
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
