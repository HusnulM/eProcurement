<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class PbjController extends Controller
{
    public function index(){
        // $mekanik    = DB::table('t_mekanik')->get();
        // $department = DB::table('t_department')->get();
        // return view('transaksi.pbj.index', ['mekanik' => $mekanik, 'department' => $department]);
        return view('transaksi.pbj.ceklisttidaklayak');
    }

    public function create($id){
        $mekanik    = DB::table('t_mekanik')->get();
        $department = DB::table('t_department')->get();
        $cklist     = DB::table('v_checklist_kendaraan')->where('id', $id)->first();
        $kendaraan  = DB::table('t_kendaraan')->where('id', $cklist->no_plat)->first();
        return view('transaksi.pbj.create', ['mekanik' => $mekanik, 'department' => $department, 'cklist' => $cklist, 'kendaraan' => $kendaraan]);
    }

    public function changePBJ($id){
        $pbjhdr = DB::table('t_pbj01')->where('id', $id)->first();
        if($pbjhdr){
            $pbjitem     = DB::table('t_pbj02')->where('pbjnumber', $pbjhdr->pbjnumber)->get();
            $department  = DB::table('t_department')->get();
            $attachments = DB::table('t_attachments')->where('doc_object','PBJ')->where('doc_number', $pbjhdr->pbjnumber)->get();
            $mekanik     = DB::table('t_mekanik')->get();

            $approvals   = DB::table('v_pbj_approval')
            ->where('pbjnumber', $pbjhdr->pbjnumber)
            ->orderBy('approver_level','asc')
            ->orderBy('pbjitem', 'asc')
            ->get();
            return view('transaksi.pbj.change', 
                [
                    'department'  => $department, 
                    'pbjhdr'      => $pbjhdr, 
                    'pbjitem'     => $pbjitem,
                    'attachments' => $attachments, 
                    'approvals'   => $approvals,
                    'mekanik'     => $mekanik
                ]);
        }else{
            return Redirect::to("/transaction/pbj")->withError('Dokumen PBJ tidak ditemukan');
        }
    }

    public function list(){
        $department = DB::table('t_department')->get();
        return view('transaksi.pbj.list', ['department' => $department]);
    }

    public function dataCekListTidakLayak(Request $request){
        if(isset($request->params)){
            $params = $request->params;        
            $whereClause = $params['sac'];
        }
        $query = DB::table('v_checklist_kendaraan')->where('hasil_pemeriksaan','TIDAK LAYAK')->where('pbj_created', 'N')
                 ->orderBy('id');
        return DataTables::queryBuilder($query)->toJson();
    }

    public function listPBJ(Request $request){
        if(isset($request->params)){
            $params = $request->params;        
            $whereClause = $params['sac'];
        }
        $query = DB::table('v_pbj01')
                 ->where('createdby',Auth::user()->email)
                //  ->where('is_active','Y')
                //  ->where('approval_status','N')
                 ->orderBy('id');
        return DataTables::queryBuilder($query)
        // ->editColumn('amount', function ($query){
        //     return [
        //         'amount1' => number_format($query->amount,0)
        //      ];
        // })->editColumn('approved_amount', function ($query){
        //     return [
        //         'amount2' => number_format($query->approved_amount,0)
        //      ];
        // })
        ->toJson();
    }

    public function detailPBJ($id){
        $pbjhdr = DB::table('t_pbj01')->where('id', $id)->first();
        if($pbjhdr){
            $pbjitem     = DB::table('t_pbj02')->where('pbjnumber', $pbjhdr->pbjnumber)->get();
            $department  = DB::table('t_department')->get();
            $attachments = DB::table('t_attachments')->where('doc_object','PBJ')->where('doc_number', $pbjhdr->pbjnumber)->get();
            $approvals   = DB::table('v_pbj_approval')
            ->where('pbjnumber', $pbjhdr->pbjnumber)
            ->orderBy('approver_level','asc')
            ->orderBy('pbjitem', 'asc')
            ->get();
            return view('transaksi.pbj.pbjdetail', 
                [
                    'department' => $department, 
                    'pbjhdr' => $pbjhdr, 
                    'pbjitem' => $pbjitem,
                    'attachments'   => $attachments, 
                    'approvals'     => $approvals
                ]);
        }else{
            return Redirect::to("/transaction/pbj")->withError('Dokumen PBJ tidak ditemukan');
        }
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
        DB::beginTransaction();
        try{
            if(isset($req['parts'])){
                $tgl   = substr($req['tglpbj'], 8, 2);
                $bulan = substr($req['tglpbj'], 5, 2);
                $tahun = substr($req['tglpbj'], 0, 4);
                // return $tgl . ' - ' . $bulan . ' - ' . $tahun;
                $ptaNumber = generatePbjNumber($tahun, $bulan, $tgl);
    
                // return $ptaNumber;
    
                // $amount = $req['nominal'];
                // $amount = str_replace(',','',$amount);
                DB::table('t_pbj01')->insert([
                    'pbjnumber'         => $ptaNumber,
                    'deptid'            => Auth::user()->deptid,
                    'tgl_pbj'           => $req['tglpbj'],
                    'tujuan_permintaan' => $req['requestto'],
                    'kepada'            => $req['kepada'],
                    'unit_desc'         => $req['unitdesc'],
                    'engine_model'      => $req['engine'],
                    'chassis_sn'        => $req['chassis'],
                    'reference'         => $req['refrensi'],
                    'requestor'         => $req['requestor'],
                    'type_model'        => $req['typeModel'],
                    'user'              => $req['user'],
                    'kode_brg_jasa'     => $req['kodeJasa'],
                    'engine_sn'         => $req['nginesn'],
                    'hm_km'             => $req['hmkm'],
                    'km'                => $req['km'],
                    'budget_cost_code'  => $req['budgetcode'],
                    'cheklistnumber'    => $req['checklistnum'] ?? null,
                    'createdon'         => getLocalDatabaseDateTime(),
                    'createdby'         => Auth::user()->email ?? Auth::user()->username
                ]);
    
                $parts    = $req['parts'];
                $partdsc  = $req['partdesc'];
                $quantity = $req['quantity'];
                $uom      = $req['uoms'];
                $figure   = $req['figures'];
                $remark   = $req['remarks'];
    
                $insertData = array();
                $pbjItems   = array();
                $count = 0;
                for($i = 0; $i < sizeof($parts); $i++){
                    $qty    = $quantity[$i];
                    $qty    = str_replace(',','',$qty);
    
                    $count = $count + 1;
                    $data = array(
                        'pbjnumber'    => $ptaNumber,
                        'pbjitem'      => $count,
                        'partnumber'   => $parts[$i],
                        'description'  => $partdsc[$i],
                        'quantity'     => $qty,
                        'unit'         => $uom[$i],
                        'figure'       => $figure[$i],
                        'remark'       => $remark[$i],
                        'createdon'    => getLocalDatabaseDateTime(),
                        'createdby'    => Auth::user()->email ?? Auth::user()->username
                    );
                    array_push($insertData, $data);
                    array_push($pbjItems, $data);
                }
                insertOrUpdate($insertData,'t_pbj02');
                
                // return $pbjItems;
                //Insert Attachments | t_attachments
                if(isset($req['efile'])){
                    $files = $req['efile'];
                    $insertFiles = array();
        
                    foreach ($files as $efile) {
                        $filename = $efile->getClientOriginalName();
                        $upfiles = array(
                            'doc_object' => 'PBJ',
                            'doc_number' => $ptaNumber,
                            'efile'      => $filename,
                            'pathfile'   => '/files/PBJ/'. $filename,
                            'createdon'  => getLocalDatabaseDateTime(),
                            'createdby'  => Auth::user()->username ?? Auth::user()->email
                        );
                        array_push($insertFiles, $upfiles);
        
                        // $efile->move(public_path().'/files/PBJ/', $filename);  
                        $efile->move('files/PBJ/', $filename);  
                    }
                    if(sizeof($insertFiles) > 0){
                        insertOrUpdate($insertFiles,'t_attachments');
                    }
                }
                // insertOrUpdate($insertFiles,'t_attachments');
    
                //Set Approval
                $approval = DB::table('v_workflow_budget')->where('object', 'PBJ')->where('requester', Auth::user()->id)->get();
                if(sizeof($approval) > 0){
                    // foreach($pbjItems as $pbitem){
                    for($a = 0; $a < sizeof($pbjItems); $a++){
                        $insertApproval = array();
                        foreach($approval as $row){
                            $is_active = 'N';
                            if($row->approver_level == 1){
                                $is_active = 'Y';
                            }
                            $approvals = array(
                                'pbjnumber'         => $ptaNumber,
                                'pbjitem'           => $pbjItems[$a]['pbjitem'],
                                'approver_level'    => $row->approver_level,
                                'approver'          => $row->approver,
                                'requester'         => Auth::user()->id,
                                'is_active'         => $is_active,
                                'createdon'         => getLocalDatabaseDateTime()
                            );
                            array_push($insertApproval, $approvals);
                        }
                        insertOrUpdate($insertApproval,'t_pbj_approval');
                    }
                }    

                DB::table('t_checklist_kendaraan')->where('no_checklist',$req['checklistnum'])->update([
                    'pbj_created' => 'Y',
                    'pbjnumber'   => $ptaNumber
                ]);
                DB::commit();
                return Redirect::to("/transaction/pbj")->withSuccess('PBJ Berhasil dibuat dengan Nomor : '. $ptaNumber);
            }else{
                return Redirect::to("/transaction/pbj")->withError('PBJ Item Belum di Pilih');
            }
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/transaction/pbj")->withError($e->getMessage());
        }
    }
}
