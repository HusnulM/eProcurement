<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class PurchaseOrderController extends Controller
{
    public function index(){
        $department = DB::table('t_department')->get();
        return view('transaksi.po.index', ['department' => $department]);
    }

    public function listPO(){
        $department = DB::table('t_department')->get();
        return view('transaksi.po.printpolist', ['department' => $department]);
    }

    public function getApprovedPR(Request $request){
        // v_approved_pr
        $params = $request->params;        
        if(isset($request->params)){
            $whereClause = $params['sac'];
        }
        $deptid= $params['deptid'];
        $query = DB::table('v_approved_pr')
                 ->where('pocreated', 'N')
                 ->where('deptid', $deptid)
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
            $ptaNumber = generatePONumber($tahun, $bulan, $tgl);

            DB::table('t_po01')->insert([
                'ponum'             => $ptaNumber,
                'ext_ponum'         => $ptaNumber,
                'deptid'            => $req['department'],
                'podat'             => $req['tglreq'],
                'vendor'            => $req['vendor'],
                'note'              => $req['remark'],
                'currency'          => $req['currency'],
                'createdon'         => date('Y-m-d H:m:s'),
                'createdby'         => Auth::user()->email ?? Auth::user()->username
            ]);

            $parts    = $req['parts'];
            $partdsc  = $req['partdesc'];
            $quantity = $req['quantity'];
            $uom      = $req['uoms'];
            $price    = $req['unitprice'];
            $prnum    = $req['prnum'];
            $pritem   = $req['pritem'];

            $insertData = array();
            $count = 0;
            

            for($i = 0; $i < sizeof($parts); $i++){
                $qty    = $quantity[$i];
                $qty    = str_replace(',','',$qty);

                $uprice = $price[$i];
                $uprice = str_replace(',','',$uprice);

                $count = $count + 1;
                $data = array(
                    'ponum'       => $ptaNumber,
                    'poitem'       => $count,
                    'material'     => $parts[$i],
                    'matdesc'      => $partdsc[$i],
                    'quantity'     => $qty,
                    'unit'         => $uom[$i],
                    'price'        => $uprice,
                    'prnum'        => $prnum[$i] ?? 0,
                    'pritem'       => $pritem[$i] ?? 0,
                    'createdon'    => date('Y-m-d H:m:s'),
                    'createdby'    => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $data);

                DB::table('t_pr02')->where('prnum', $prnum[$i])->where('pritem', $pritem[$i])
                ->update([
                    'pocreated' => 'Y'
                ]);
            }
            insertOrUpdate($insertData,'t_po02');

            //Insert Attachments | t_attachments
            $files = $req['efile'];
            $insertFiles = array();

            foreach ($files as $efile) {
                $filename = $efile->getClientOriginalName();
                $upfiles = array(
                    'doc_object' => 'PO',
                    'doc_number' => $ptaNumber,
                    'efile'      => $filename,
                    'pathfile'   => '/files/PO/'. $filename,
                    'createdon'  => getLocalDatabaseDateTime(),
                    'createdby'  => Auth::user()->username ?? Auth::user()->email
                );
                array_push($insertFiles, $upfiles);

                $efile->move(public_path().'/files/PO/', $filename);  
            }
            if(sizeof($insertFiles) > 0){
                insertOrUpdate($insertFiles,'t_attachments');
            }
            // insertOrUpdate($insertFiles,'t_attachments');

            //Set Approval
            $approval = DB::table('v_workflow_budget')->where('object', 'PO')->where('requester', Auth::user()->id)->get();
            if(sizeof($approval) > 0){
                $insertApproval = array();
                foreach($approval as $row){
                    $is_active = 'N';
                    if($row->approver_level == 1){
                        $is_active = 'Y';
                    }
                    $approvals = array(
                        'ponum'             => $ptaNumber,
                        'approver_level'    => $row->approver_level,
                        'approver'          => $row->approver,
                        'requester'         => Auth::user()->id,
                        'is_active'         => $is_active,
                        'createdon'         => getLocalDatabaseDateTime()
                    );
                    array_push($insertApproval, $approvals);
                }
                insertOrUpdate($insertApproval,'t_po_approval');
            }else{
                DB::rollBack();
                return Redirect::to("/proc/po")->withError('Approval belum di tambahkan untuk user '. Auth::user()->name);
            }

            DB::commit();
            return Redirect::to("/proc/po")->withSuccess('PO Berhasil dibuat dengan Nomor : '. $ptaNumber);
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/proc/po")->withError($e->getMessage());
        }
    }
}
