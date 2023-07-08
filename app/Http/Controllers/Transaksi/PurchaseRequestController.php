<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Mail\NotifApprovePrMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use DataTables, Auth, DB;
use Validator,Redirect,Response;
use PDF;

class PurchaseRequestController extends Controller
{
    public function index(){
        return view('transaksi.pr.index');
    }

    public function listPR(){
        $department = DB::table('t_department')->get();
        return view('transaksi.pr.printlist', ['department' => $department]);
    }

    public function duedate(){
        return view('transaksi.pr.duedatepr');
    }

    public function listDuedatePR(Request $request){
        if(isset($request->params)){
            $params = $request->params;        
            $whereClause = $params['sac'];
        }
        $query = DB::table('v_pr_duedate')
                ->where('duedate','>','3')
                ->where('pocreated','N')
                 ->orderBy('id');
        return DataTables::queryBuilder($query)
        ->editColumn('quantity', function ($query){
            return [
                'quantity1' => number_format($query->quantity,0)
             ];
        })
        ->editColumn('prdate', function ($query){
            return [
                'prdate1' => \Carbon\Carbon::parse($query->prdate)->format('d-m-Y')
            ];
        })
        ->toJson();
    }

    public function changePR($id){
        // return view('transaksi.pr.change');
        $department = DB::table('t_department')->get();
        $prhdr = DB::table('t_pr01')->where('id', $id)->first();
        $prdtl = DB::table('t_pr02')->where('prnum', $prhdr->prnum)->where('isdeleted', 'N')->get();
        $attachments = DB::table('t_attachments')->where('doc_object','PR')->where('doc_number', $prhdr->prnum)->get();
        $approvals   = DB::table('v_pr_approval')->where('prnum', $prhdr->prnum)->get();
        // return $attachments;
        return view('transaksi.pr.change', 
            [
                'department'    => $department, 
                'prhdr'         => $prhdr, 
                'pritem'        => $prdtl, 
                'attachments'   => $attachments, 
                'approvals'     => $approvals
            ]);
    }

    public function listApprovedPbj(Request $request){
        if(isset($request->params)){
            $params = $request->params;        
            $whereClause = $params['sac'];
        }
        $query = DB::table('v_pbj02')
                 ->where('pbj_status', 'A')
                 ->where('approvestat', 'A')
                 ->where('openqty', '>', 0)
                 ->orderBy('id');
        return DataTables::queryBuilder($query)->toJson();
    }    

    public function save(Request $req){
        // return $req;
        DB::beginTransaction();
        try{
            if(!isset($req['parts'])){
                return Redirect::to("/proc/pr")->withError('Item PR Blum di isi');
            }
            $tgl   = substr($req['tglreq'], 8, 2);
            $bulan = substr($req['tglreq'], 5, 2);
            $tahun = substr($req['tglreq'], 0, 4);
            // return $tgl . ' - ' . $bulan . ' - ' . $tahun;
            $ptaNumber = generatePRNumber($tahun, $bulan, $tgl, Auth::user()->deptid, getUserDepartment());

            // return $ptaNumber;

            // $amount = $req['nominal'];
            // $amount = str_replace(',','',$amount);
            $prID = DB::table('t_pr01')->insertGetId([
                'prnum'             => $ptaNumber,
                'prdate'            => $req['tglreq'],
                'deptid'            => Auth::user()->deptid,
                'requestby'         => $req['requestor'],
                'remark'            => $req['remark'],
                'createdon'         => getLocalDatabaseDateTime(),
                'createdby'         => Auth::user()->email ?? Auth::user()->username
            ]);

            $parts    = $req['parts'];
            $partdsc  = $req['partdesc'];
            $quantity = $req['quantity'];
            $uom      = $req['uoms'];
            $pbjnum   = $req['pbjnum'];
            $pbjitm   = $req['pbjitm'];
            $nopol    = $req['nopol'];
            $project  = $req['project'];

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
                    'no_plat'      => $nopol[$i] ?? null,
                    'idproject'    => $project[$i] ?? 0,
                    'createdon'    => getLocalDatabaseDateTime(),
                    'createdby'    => Auth::user()->email ?? Auth::user()->username
                );
                
                array_push($insertData, $data);

                DB::table('t_pbj02')->where('pbjnumber', $pbjnum[$i])->where('pbjitem', $pbjitm[$i])
                ->update([
                    'prcreated' => 'Y'
                ]);
            }
            // return $insertData;
            insertOrUpdate($insertData,'t_pr02');

            //Insert Attachments | t_attachments
            if(isset($req['efile'])){
                $files = $req['efile'];
                $insertFiles = array();
    
                foreach ($files as $efile) {
                    $filename = $efile->getClientOriginalName();
                    $upfiles = array(
                        'doc_object' => 'PR',
                        'doc_number' => $ptaNumber,
                        'efile'      => $filename,
                        'pathfile'   => '/files/PR/'. $filename,
                        'createdon'  => getLocalDatabaseDateTime(),
                        'createdby'  => Auth::user()->username ?? Auth::user()->email
                    );
                    array_push($insertFiles, $upfiles);
    
                    $efile->move('files/PR/', $filename);  
                    // $efile->move(public_path().'/files/PR/', $filename);  
                }
                if(sizeof($insertFiles) > 0){
                    insertOrUpdate($insertFiles,'t_attachments');
                }
            }
            // insertOrUpdate($insertFiles,'t_attachments');

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
                $result = array(
                    'msgtype' => '500',
                    'message' => 'Approval belum di tambahkan untuk user '. Auth::user()->name
                );
                return $result;
                // return Redirect::to("/proc/pr")->withError('Approval belum di tambahkan untuk user '. Auth::user()->name);
            }

            DB::commit();
            // return Redirect::to("/proc/pr")->withSuccess('PR Berhasil dibuat dengan Nomor : '. $ptaNumber);

            $approverId = DB::table('v_workflow_budget')->where('object', 'PR')
                            ->where('requester', Auth::user()->id)
                            ->where('approver_level', '1')
                            ->pluck('approver');

            $mailto = DB::table('users')
                    ->whereIn('id', $approverId)
                    ->pluck('email');   

            $dataApprovePBJ = DB::table('v_pr_duedate')
                    ->where('prnum', $ptaNumber)
                    ->orderBy('id')->get();

            Mail::to($mailto)->queue(new NotifApprovePrMail($dataApprovePBJ, $prID, $ptaNumber));

            $result = array(
                'msgtype' => '200',
                'message' => 'PR Berhasil dibuat dengan Nomor : '. $ptaNumber
            );
            return $result;
        } catch(\Exception $e){
            DB::rollBack();
            $result = array(
                'msgtype' => '500',
                'message' => $e->getMessage()
            );
            return $result;
            // return Redirect::to("/proc/pr")->withError($e->getMessage());
            // dd($e->getMessage());
        }
    }

    public function update(Request $req, $prid){
        // return $req;
        DB::beginTransaction();
        try{
            if(!isset($req['material'])){
                return Redirect::to("/proc/pr")->withError('Item PR Blum di isi');
            }

            $prhdr = DB::table('t_pr01')->where('id', $prid)->first();


            $tgl   = substr($req['tglreq'], 8, 2);
            $bulan = substr($req['tglreq'], 5, 2);
            $tahun = substr($req['tglreq'], 0, 4);
            // return $tgl . ' - ' . $bulan . ' - ' . $tahun;
            $ptaNumber = $prhdr->prnum;

            $checkApproval = DB::table('v_pr_approval')
                ->where('prnum', $ptaNumber)->where('approval_status', 'A')->first();
            
            if($checkApproval){
                $result = array(
                    'msgtype' => '500',
                    'message' => 'PR : '. $ptaNumber . ' sudah di approve, data tidak bisa diupdate'
                );
                return $result;
            }

            // $amount = $req['nominal'];
            // $amount = str_replace(',','',$amount);
            DB::table('t_pr01')->where('id', $prid)->update([
                'prnum'             => $ptaNumber,
                'prdate'            => $req['tglreq'],
                'deptid'            => Auth::user()->deptid,
                'requestby'         => $req['requestor'],
                'remark'            => $req['remark'],
                // 'createdon'         => getLocalDatabaseDateTime(),
                // 'createdby'         => Auth::user()->email ?? Auth::user()->username
            ]);

            $parts    = $req['material'];
            $partdsc  = $req['matdesc'];
            $quantity = $req['quantity'];
            $uom      = $req['unit'];
            $pbjnum   = $req['pbjnumber'];
            $pbjitm   = $req['pbjitem'];
            $nopol    = $req['nopol'];
            $pritem   = $req['pritem'];

            $insertData = array();
            $count = 0;            

            for($i = 0; $i < sizeof($parts); $i++){
                $qty    = $quantity[$i];
                $qty    = str_replace(',','',$qty);

                if($pritem[$i]){
                    $count = $pritem[$i];
                }else{
                    $count += 1;
                    // $itemExist = '0';
                    // // return $count;
                    // while($itemExist = '1') {
                    //     $checkItem = DB::table('t_pr02')
                    //                 ->where('prnum',$ptaNumber)->where('pritem', $count)->first();
                    //                 // return $checkItem;
                    //     if($checkItem){
                    //         $count = $checkItem->pritem + 1;
                    //         // $itemExist = 'true';
                    //     }else{
                    //         // $count += 1;
                    //         $itemExist = '1';                            
                    //     }
                    // }
                }
                
                $data = array(
                    'prnum'        => $ptaNumber,
                    'pritem'       => $count,
                    'material'     => $parts[$i],
                    'matdesc'      => $partdsc[$i],
                    'quantity'     => $qty,
                    'unit'         => $uom[$i],
                    'pbjnumber'    => $pbjnum[$i] ?? 0,
                    'pbjitem'      => $pbjitm[$i] ?? 0,
                    'no_plat'      => $nopol[$i] ?? null,
                    'createdon'    => getLocalDatabaseDateTime(),
                    'createdby'    => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $data);

                DB::table('t_pbj02')->where('pbjnumber', $pbjnum[$i])->where('pbjitem', $pbjitm[$i])
                ->update([
                    'prcreated' => 'Y'
                ]);
            }

            // return $insertData;
            insertOrUpdate($insertData,'t_pr02');

            //Insert Attachments | t_attachments
            if(isset($req['efile']) && $req['efile'][0] != null){
                $files = $req['efile'];
                $insertFiles = array();
    
                foreach ($files as $efile) {
                    $filename = $efile->getClientOriginalName();
                    $upfiles = array(
                        'doc_object' => 'PR',
                        'doc_number' => $ptaNumber,
                        'efile'      => $filename,
                        'pathfile'   => '/files/PR/'. $filename,
                        'createdon'  => getLocalDatabaseDateTime(),
                        'createdby'  => Auth::user()->username ?? Auth::user()->email
                    );
                    array_push($insertFiles, $upfiles);
    
                    $efile->move('files/PR/', $filename);  
                    // $efile->move(public_path().'/files/PR/', $filename);  
                }
                if(sizeof($insertFiles) > 0){
                    insertOrUpdate($insertFiles,'t_attachments');
                }
            }
            // insertOrUpdate($insertFiles,'t_attachments');

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
            //             'prnum'             => $ptaNumber,
            //             'approver_level'    => $row->approver_level,
            //             'approver'          => $row->approver,
            //             'requester'         => Auth::user()->id,
            //             'is_active'         => $is_active,
            //             'createdon'         => getLocalDatabaseDateTime()
            //         );
            //         array_push($insertApproval, $approvals);
            //     }
            //     insertOrUpdate($insertApproval,'t_pr_approval');
            // }else{
            //     DB::rollBack();
            //     $result = array(
            //         'msgtype' => '500',
            //         'message' => 'Approval belum di tambahkan untuk user '. Auth::user()->name
            //     );
            //     return $result;
            //     // return Redirect::to("/proc/pr/change/".$prid)->withError('Approval belum di tambahkan untuk user '. Auth::user()->name);
            // }

            DB::commit();
            // return Redirect::to("/proc/pr/change/".$prid)->withSuccess('PR '. $ptaNumber .'Berhasil di update');
            $result = array(
                'msgtype' => '200',
                'message' => 'PR : '. $ptaNumber . ' Berhasil di update'
            );
            return $result;
        } catch(\Exception $e){
            DB::rollBack();
            $result = array(
                'msgtype' => '500',
                'message' => $e->getMessage()
            );
            return $result;
            // return Redirect::to("/proc/pr/change/".$prid)->withError($e->getMessage());
            // dd($e->getMessage());
        }
    }

    public function deletePR($id){
        DB::beginTransaction();
        try{
            $prhdr = DB::table('t_pr01')->where('id', $id)->first();
            $pbjdoc = DB::table('t_pr02')
                        ->where('prnum', $prhdr->prnum)->get();

            $checkApproval = DB::table('v_pr_approval')
                        ->where('prnum', $prhdr->prnum)->where('approval_status', 'A')->first();
                        
            if($checkApproval){
                return Redirect::to("/proc/pr/listpr")->withError('PR : '. $prhdr->prnum . ' sudah di approve, data tidak bisa dihapus');
            }
                        
            DB::table('t_pr01')->where('id', $id)->delete();
            DB::table('t_attachments')->where('doc_object', 'PR')->where('doc_number',$prhdr->prnum)->delete();
            DB::table('t_pr_approval')->where('prnum', $prhdr->prnum)->delete();
            
            // return $pbjdoc;
            foreach($pbjdoc as $row){
                DB::table('t_pbj02')
                    ->where('pbjnumber', $row->pbjnumber)
                    ->where('pbjitem', $row->pbjitem)->update([
                        'prcreated' => 'N'
                ]);

                // DB::commit();
            }

            DB::commit();
            return Redirect::to("/proc/pr/listpr")->withSuccess('PR '. $prhdr->prnum .' Berhasil dihapus');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/proc/pr/listpr")->withError($e->getMessage());
            // dd($e->getMessage());
        }
    }

    public function deletePRItem(Request $req){
        DB::beginTransaction();
        try{
            $checkApproval = DB::table('v_pr_approval')
                ->where('prnum', $req['prnum'])->where('approval_status', 'A')->first();
            
            if($checkApproval){
                $result = array(
                    'msgtype' => '500',
                    'message' => 'PR : '. $req['prnum'] . ' sudah di approve, data tidak bisa dihapus'
                );
                return $result;
            }

            $pbjdoc = DB::table('t_pr02')
                        ->where('prnum', $req['prnum'])
                        ->where('pritem', $req['pritem'])->get();

            // $prhdr = DB::table('t_pr01')->where('prnum', $req['prnum'])->first();
            // DB::table('t_pr02')->where('prnum', $req['prnum'])->where('pritem', $req['pritem'])->update([
            //     'isdeleted' => 'Y'
            // ]);
            DB::table('t_pr02')->where('prnum', $req['prnum'])->where('pritem', $req['pritem'])->delete();
            // DB::table('t_pr_approval')->where('prnum', $prhdr->prnum)->delete();

            foreach($pbjdoc as $row){
                DB::table('t_pbj02')
                    ->where('pbjnumber', $row->pbjnumber)
                    ->where('pbjitem', $row->pbjitem)->update([
                        'prcreated' => 'N'
                ]);
            }

            DB::commit();

            $result = array(
                'msgtype' => '200',
                'message' => 'Item PR : '. $req['prnum'] . ' - ' . $req['pritem'] . ' berhasil dihapus'
            );
            // return Redirect::to("/approve/pr")->withSuccess('PR dengan Nomor : '. $ptaNumber . ' berhasil di approve');
            return $result;
        } catch(\Exception $e){
            DB::rollBack();
            $result = array(
                'msgtype' => '500',
                'message' => $e->getMessage()
            );
            return $result;
            // return Redirect::to("/proc/pr")->withError($e->getMessage());
            // dd($e->getMessage());
        }
    }
}
