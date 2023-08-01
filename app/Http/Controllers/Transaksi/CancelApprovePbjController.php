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
                 ->select('id','pbjnumber', 'tgl_pbj','tujuan_permintaan','kepada','unit_desc','engine_model')
                 ->distinct()
                 ->where('bast_created','N')
                 ->orderBy('id');
        return DataTables::queryBuilder($query)
        ->toJson();
    }

    public function resetApprovePBJ($id){
        DB::beginTransaction();
        try{
            $wodata = DB::table('t_pbj01')->where('id', $id)->first();
            if($wodata){
                DB::table('t_pbj01')->where('id', $id)->update([
                    'pbj_status'   => 'N'
                ]);

                DB::table('t_pbj02')->where('pbjnumber', $wodata->pbjnumber)->update([
                    'approvestat'   => 'N'
                ]);

                $firstApproval = DB::table('t_pbj_approval')
                        ->where('pbjnumber', $wodata->pbjnumber)
                        ->orderBy('approver_level', 'ASC')
                        ->first();

                DB::table('t_pbj_approval')->where('pbjnumber', $wodata->pbjnumber)->update([
                    'approval_status' => 'N',
                    'is_active'       => 'N'
                ]);

                DB::table('t_pbj_approval')->where('pbjnumber', $wodata->pbjnumber)
                ->where('approver_level', $firstApproval->approver_level)
                ->update([
                    'is_active' => 'Y'
                ]);
                DB::commit();
                
                $result = array(
                    'msgtype' => '200',
                    'message' => 'Approval PBJ'. $wodata->pbjnumber . ' berhasil direset'
                );
            }else{
                $result = array(
                    'msgtype' => '500',
                    'message' => 'PBJ tidak ditemukan'
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
            $wodata = DB::table('t_pbj01')->where('id', $id)->first();
            if($wodata){
                if($wodata->bast_created === 'Y'){
                    $result = array(
                        'msgtype' => '500',
                        'message' => 'PBJ '. $wodata->pbjnumber . ' tidak bisa dihapus, sudah dibuat BAST'
                    );
                }else{
                    DB::table('t_pbj01')->where('id', $id)->delete();
                    DB::table('t_pbj02')->where('pbjnumber', $wodata->pbjnumber)->delete();
                    DB::table('t_pbj_approval')->where('pbjnumber', $wodata->pbjnumber)->delete();
                    DB::commit();
    
                    $result = array(
                        'msgtype' => '200',
                        'message' => 'PBJ '. $wodata->pbjnumber . ' berhasil dihapus'
                    );
                }
            }else{
                $result = array(
                    'msgtype' => '500',
                    'message' => 'PBJ tidak ditemukan'
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
