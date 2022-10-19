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
        return view('transaksi.po.index');
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
                'podat'             => $req['tglreq'],
                'vendor'            => $req['vendor'],
                'note'              => $req['remark'],
                'currency'          => 'IDR',
                'createdon'         => date('Y-m-d H:m:s'),
                'createdby'         => Auth::user()->email ?? Auth::user()->username
            ]);

            $parts    = $req['parts'];
            $partdsc  = $req['partdesc'];
            $quantity = $req['quantity'];
            $uom      = $req['uoms'];
            $price    = $req['unitprice'];

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
                    'createdon'    => date('Y-m-d H:m:s'),
                    'createdby'    => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $data);
            }
            insertOrUpdate($insertData,'t_po02');

            //Set Approval
            // $approval = DB::table('v_workflow_budget')->where('object', 'PR')->where('requester', Auth::user()->id)->get();
            // if(sizeof($approval) > 0){
            //     $insertApproval = array();
            //     foreach($approval as $row){
            //         $is_active = 'N';
            //         if($row->approver_level == 1){
            //             $is_active = 'Y';
            //         }
            //         $approvals = array(
            //             'pbjnumber'         => $ptaNumber,
            //             'approver_level'    => $row->approver_level,
            //             'approver'          => $row->approver,
            //             'requester'         => Auth::user()->id,
            //             'is_active'         => $is_active,
            //             'createdon'         => getLocalDatabaseDateTime()
            //         );
            //         array_push($insertApproval, $approvals);
            //     }
            //     insertOrUpdate($insertApproval,'t_pbj_approval');
            // }

            DB::commit();
            return Redirect::to("/proc/po")->withSuccess('PO Berhasil dibuat dengan Nomor : '. $ptaNumber);
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/proc/po")->withError($e->getMessage());
        }
    }
}