<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class PurchaseRequestController extends Controller
{
    public function index(){
        return view('transaksi.pr.index');
    }

    public function listApprovedPbj(Request $request){
        if(isset($request->params)){
            $params = $request->params;        
            $whereClause = $params['sac'];
        }
        $query = DB::table('v_pbj02')
                 ->where('pbj_status', 'A')
                 ->orderBy('id');
        return DataTables::queryBuilder($query)->toJson();
    }

    public function save(Request $req){
        // return $req;
        DB::beginTransaction();
        try{
            $tgl   = substr($req['tglreq'], 8, 2);
            $bulan = substr($req['tglreq'], 5, 2);
            $tahun = substr($req['tglreq'], 0, 4);
            // return $tgl . ' - ' . $bulan . ' - ' . $tahun;
            $ptaNumber = generatePRNumber($tahun, $bulan, $tgl, Auth::user()->deptid, getUserDepartment());

            // return $ptaNumber;

            // $amount = $req['nominal'];
            // $amount = str_replace(',','',$amount);
            DB::table('t_pr01')->insert([
                'prnum'             => $ptaNumber,
                'prdate'            => $req['tglreq'],
                'deptid'            => Auth::user()->deptid,
                'requestby'         => $req['requestor'],
                'remark'            => $req['remark'],
                'createdon'         => date('Y-m-d H:m:s'),
                'createdby'         => Auth::user()->email ?? Auth::user()->username
            ]);

            $parts    = $req['parts'];
            $partdsc  = $req['partdesc'];
            $quantity = $req['quantity'];
            $uom      = $req['uoms'];
            $pbjnum   = $req['pbjnum'];
            $pbjitm   = $req['pbjitm'];

            $insertData = array();
            $count = 0;            

            for($i = 0; $i < sizeof($parts); $i++){
                $qty    = $quantity[$i];
                $qty    = str_replace(',','',$qty);

                $count = $count + 1;
                $data = array(
                    'prnum'        => $ptaNumber,
                    'pritem'       => $count,
                    'material'     => $parts[$i],
                    'matdesc'      => $partdsc[$i],
                    'quantity'     => $qty,
                    'unit'         => $uom[$i],
                    'pbjnumber'    => $pbjnum[$i] ?? 0,
                    'pbjitem'      => $pbjitm[$i] ?? 0,
                    'createdon'    => date('Y-m-d H:m:s'),
                    'createdby'    => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $data);

                DB::table('t_pbj02')->where('pbjnumber', $pbjnum[$i])->where('pbjitem', $pbjitm[$i])
                ->update([
                    'prcreated' => 'Y'
                ]);
            }
            insertOrUpdate($insertData,'t_pr02');

            //Set Approval
            $approval = DB::table('v_workflow_budget')->where('object', 'PR')->where('requester', Auth::user()->id)->get();
            if(sizeof($approval) > 0){
                $insertApproval = array();
                foreach($approval as $row){
                    $is_active = 'N';
                    if($row->approver_level == 1){
                        $is_active = 'Y';
                    }
                    $approvals = array(
                        'prnum'             => $ptaNumber,
                        'approver_level'    => $row->approver_level,
                        'approver'          => $row->approver,
                        'requester'         => Auth::user()->id,
                        'is_active'         => $is_active,
                        'createdon'         => getLocalDatabaseDateTime()
                    );
                    array_push($insertApproval, $approvals);
                }
                insertOrUpdate($insertApproval,'t_pr_approval');
            }else{
                DB::rollBack();
                return Redirect::to("/proc/pr")->withError('Approval belum di tambahkan untuk user '. Auth::user()->name);
            }

            DB::commit();
            return Redirect::to("/proc/pr")->withSuccess('PR Berhasil dibuat dengan Nomor : '. $ptaNumber);
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/proc/pr")->withError($e->getMessage());
            // dd($e->getMessage());
        }
    }
}
