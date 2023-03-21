<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;
use PDF;

class SpkController extends Controller
{
    public function index(){
        return view('transaksi.spk.index');
    }

    public function listwoview(){
        return view('transaksi.spk.listspk');
    }

    public function listApprovedPbj(Request $request){
        if(isset($request->params)){
            $params = $request->params;        
            $whereClause = $params['sac'];
        }
        $query = DB::table('v_pbj02')
                 ->where('pbj_status', 'A')
                 ->where('approvestat', 'A')
                //  ->where('openqty', '>', 0)
                 ->orderBy('id');
        return DataTables::queryBuilder($query)->toJson();
    }  

    public function wodetail($id){
        $wohdr = DB::table('v_wo01')->where('id', $id)->first();
        if($wohdr){
            $mekanik    = DB::table('t_mekanik')->where('id', $wohdr->mekanik)->first();
            $warehouse  = DB::table('t_warehouse')->where('id', $wohdr->whscode)->first();
            $kendaraan  = DB::table('t_kendaraan')->where('id', $wohdr->license_number)->first();
            $woitem     = DB::table('t_wo02')->where('wonum', $wohdr->wonum)->get();
            $attachments = DB::table('t_attachments')->where('doc_object','SPK')->where('doc_number', $wohdr->wonum)->get();
            // $attachments = DB::table('t_attachments')->where('doc_object','SPK')->where('doc_number', $prhdr->wonum)->get();
            $approvals  = DB::table('v_wo_approval')->where('wonum', $wohdr->wonum)->get();
            // $department = DB::table('v_wo_approval')->where('wonum', $prhdr->wonum)->first();
            // return $woitem;

            return view('transaksi.spk.detailspk', 
                [
                    'prhdr'       => $wohdr, 
                    'pritem'      => $woitem,
                    'mekanik'     => $mekanik,
                    'warehouse'   => $warehouse,
                    'kendaraan'   => $kendaraan,
                    'attachments' => $attachments,
                    'approvals'   => $approvals, 
                ]);
        }else{
            return Redirect::to("/logistic/wo/listwo")->withError('Data SPK/Work Order tidak ditemukan');
        }
    }

    public function listdatawo(Request $request){
        $query = DB::table('v_wo01');

        $query->where('createdby', Auth::user()->email);

        $query->orderBy('id', 'DESC');

        return DataTables::queryBuilder($query)
        ->editColumn('wodate', function ($query){
            return [
                'wodate1' => \Carbon\Carbon::parse($query->wodate)->format('d-m-Y')
             ];
        })
        ->toJson();
    }

    public function save(Request $req){
        DB::beginTransaction();
        try{
            $tgl   = substr($req['servicedate'], 8, 2);
            $bulan = substr($req['servicedate'], 5, 2);
            $tahun = substr($req['servicedate'], 0, 4);
            // return $tgl . ' - ' . $bulan . ' - ' . $tahun;
            $ptaNumber = generateWONumber($tahun, $bulan);

            // return $ptaNumber;

            // $amount = $req['nominal'];
            // $amount = str_replace(',','',$amount);
            DB::table('t_wo01')->insert([
                'wonum'             => $ptaNumber,
                'wodate'            => $req['servicedate'],
                'description'       => $req['descr'],
                'mekanik'           => $req['mekanik'],
                'whscode'           => $req['whscode'],
                'license_number'    => $req['licenseNumber'],
                'last_odo_meter'    => $req['lastOdoMeter'],
                'schedule_type'     => $req['schedule'],
                'issued'            => $req['issued'],
                'createdon'         => date('Y-m-d H:m:s'),
                'createdby'         => Auth::user()->email ?? Auth::user()->username
            ]);

            $parts    = $req['parts'];
            $partdsc  = $req['partdesc'];
            $quantity = $req['quantity'];
            $uom      = $req['uoms'];

            $insertData = array();
            $count = 0;            

            for($i = 0; $i < sizeof($parts); $i++){
                $qty    = $quantity[$i];
                $qty    = str_replace(',','',$qty);

                $latestStock = DB::table('t_inv_stock')
                               ->where('material', $parts[$i])
                               ->where('whscode',  $req['whscode'])->first();
                if($latestStock){
                    if($latestStock->quantity < $qty){
                        DB::rollBack();
                        return Redirect::to("/logistic/wo")->withError('Stock Tidak Mencukupi untuk part : '. $parts[$i]);
                    }else{
                        DB::table('t_inv_stock')
                        ->where('material', $parts[$i])
                        ->where('whscode',  $req['whscode'])
                        ->update([
                            'quantity'     => $latestStock->quantity - $qty
                        ]);
                    }
                }else{
                    DB::rollBack();
                    return Redirect::to("/logistic/wo")->withError('Stock Tidak Mencukupi untuk part : '. $parts[$i]);
                }

                $count = $count + 1;
                $data = array(
                    'wonum'        => $ptaNumber,
                    'woitem'       => $count,
                    'material'     => $parts[$i],
                    'matdesc'      => $partdsc[$i],
                    'quantity'     => $qty,
                    'unit'         => $uom[$i],
                    'createdon'    => date('Y-m-d H:m:s'),
                    'createdby'    => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $data);
            }
            insertOrUpdate($insertData,'t_wo02');

            //Insert Attachments | t_attachments
            $files = $req['efile'];
            $insertFiles = array();

            foreach ($files as $efile) {
                $filename = $efile->getClientOriginalName();
                $upfiles = array(
                    'doc_object' => 'SPK',
                    'doc_number' => $ptaNumber,
                    'efile'      => $filename,
                    'pathfile'   => '/files/SPK/'. $filename,
                    'createdon'  => getLocalDatabaseDateTime(),
                    'createdby'  => Auth::user()->username ?? Auth::user()->email
                );
                array_push($insertFiles, $upfiles);

                $efile->move('files/SPK/', $filename);  
                // $efile->move(public_path().'/files/SPK/', $filename);  
            }

            if(sizeof($insertFiles) > 0){
                insertOrUpdate($insertFiles,'t_attachments');
            }

            //Set Approval
            $approval = DB::table('v_workflow_budget')->where('object', 'SPK')->where('requester', Auth::user()->id)->get();
            if(sizeof($approval) > 0){
                $insertApproval = array();
                foreach($approval as $row){
                    $is_active = 'N';
                    if($row->approver_level == 1){
                        $is_active = 'Y';
                    }
                    $approvals = array(
                        'wonum'             => $ptaNumber,
                        'approver_level'    => $row->approver_level,
                        'approver'          => $row->approver,
                        'creator'           => Auth::user()->id,
                        'is_active'         => $is_active,
                        'createdon'         => getLocalDatabaseDateTime()
                    );
                    array_push($insertApproval, $approvals);
                }
                insertOrUpdate($insertApproval,'t_wo_approval');
            }else{
                DB::rollBack();
                return Redirect::to("/logistic/wo")->withError('Approval belum di tambahkan untuk user '. Auth::user()->name);
            }

            DB::commit();
            return Redirect::to("/logistic/wo")->withSuccess('WO Berhasil dibuat dengan Nomor : '. $ptaNumber);
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/logistic/wo")->withError($e->getMessage());
            // dd($e->getMessage());
        }
    }
}
