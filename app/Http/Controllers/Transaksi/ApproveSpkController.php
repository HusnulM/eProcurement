<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class ApproveSpkController extends Controller
{
    public function index(){
        return view('transaksi.spk.approvelist');
    }

    public function approveDetail($id){
        $prhdr = DB::table('v_spk01')->where('id', $id)->first();
        if($prhdr){
            $items      = DB::table('v_wo02')->where('wonum', $prhdr->wonum)
                        ->where('approver', Auth::user()->id)
                        ->orderBy('woitem', 'asc')
                        ->get();
            $approvals  = DB::table('v_wo_approval')
                ->where('wonum', $prhdr->wonum)
                ->orderBy('approver','asc')
                ->orderBy('woitem', 'asc')
                ->get();
            // $department = DB::table('v_wo_approval')->where('wonum', $prhdr->wonum)->first();
            $attachments = DB::table('t_attachments')->where('doc_object','SPK')->where('doc_number', $prhdr->wonum)->get();

            // $purchases = DB::table('v_po02')
            // ->where('ponum', $prhdr->ponum)
            // ->sum('totalprice');

            // return $purchases;

            $isApprovedbyUser = DB::table('v_wo_approval')
                    ->where('wonum',    $prhdr->wonum)
                    ->where('approver', Auth::user()->id)
                    ->where('is_active', 'Y')
                    ->first();

            return view('transaksi.spk.approvedetail', 
                [   
                    'prhdr'     => $prhdr, 
                    'pritem'    => $items, 
                    'approvals' => $approvals, 
                    // 'department'=> $department,
                    'isApprovedbyUser' => $isApprovedbyUser,
                    // 'totalprice'       => $purchases,
                    'attachments'      => $attachments
                ]);
        }else{
            return Redirect::to("/approve/spk")->withError('Dokumen SPK/WO tidak ditemukan');
        }
    }

    public function ApprovalList(Request $request){
        
        if(isset($request->params)){
            $params = $request->params;        
            $whereClause = $params['sac'];
        }
        $query = DB::table('v_wo_approval')
                 ->select('id','wonum','wodate','description','schedule_type')
                 ->distinct()
                 ->where('approver',Auth::user()->id)
                 ->where('is_active','Y')
                 ->where('approval_status','N')
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

    public function getNextApproval($dcnNum){
        $userLevel = DB::table('t_wo_approval')
                    ->where('approver', Auth::user()->id)
                    ->first();

        $nextApproval = DB::table('t_wo_approval')
                        ->where('wonum', $dcnNum)
                        ->where('approver_level', '>', $userLevel->approver_level)
                        ->orderBy('approver_level', 'ASC')
                        ->first();

        // return $userLevel;
        if($nextApproval){
            return $nextApproval->approver_level;
        }else{
            return null;
        }
    }

    public function save(Request $req){
        DB::beginTransaction();
        try{
            $ptaNumber = $req['wonum'];
            

            $userAppLevel = DB::table('t_wo_approval')
                            ->select('approver_level')
                            ->where('wonum', $ptaNumber)
                            ->where('approver', Auth::user()->id)
                            ->first();

            $podata  = DB::table('t_wo01')->where('wonum', $ptaNumber)->first();
            $woitem  = DB::table('t_wo02')->where('wonum', $ptaNumber)->get();
            // return $woitem;
            foreach($woitem as $row){
                $latestStock = DB::table('t_inv_stock')
                ->where('material', $row->material)
                ->where('whscode',  $podata->whscode)->first();
                if($latestStock){
                    if($latestStock->quantity < $row->quantity){
                        DB::rollBack();
                        $result = array(
                            'msgtype' => '500',
                            'message' => 'Stock Tidak Mencukupi untuk material '. $row->material
                        );
                        return $result;
                        // return Redirect::to("/logistic/wo")->withError('Stock Tidak Mencukupi untuk part : '. $parts[$i]);
                    }else{
                        // DB::table('t_inv_stock')
                        // ->where('material', $parts[$i])
                        // ->where('whscode',  $req['whscode'])
                        // ->update([
                        //     'quantity'     => $latestStock->quantity - $qty
                        // ]);
                    }
                }else{
                    DB::rollBack();
                    $result = array(
                        'msgtype' => '500',
                        'message' => 'Stock Tidak Mencukupi untuk material '. $row->material
                    );
                    return $result;
                    // return Redirect::to("/logistic/wo")->withError('Stock Tidak Mencukupi untuk part : '. $parts[$i]);
                }
            }

            //Set Approval
            DB::table('t_wo_approval')
            ->where('wonum', $ptaNumber)
            // ->where('approver_id', Auth::user()->id)
            ->where('approver_level',$userAppLevel->approver_level)
            ->update([
                'approval_status' => 'A',
                'approval_remark' => $req['approvernote'],
                'approved_by'     => Auth::user()->username,
                'approval_date'   => getLocalDatabaseDateTime()
            ]);

            $nextApprover = $this->getNextApproval($ptaNumber);
            if($nextApprover  != null){
                DB::table('t_wo_approval')
                ->where('wonum', $ptaNumber)
                ->where('approver_level', $nextApprover)
                ->update([
                    'is_active' => 'Y'
                ]);
            }


            $checkIsFullApprove = DB::table('t_wo_approval')
                                      ->where('wonum', $ptaNumber)
                                      ->where('approval_status', '!=', 'A')
                                      ->get();
            if(sizeof($checkIsFullApprove) > 0){
                // go to next approver    
            }else{
                foreach($woitem as $row){
                    $latestStock = DB::table('t_inv_stock')
                    ->where('material', $row->material)
                    ->where('whscode',  $podata->whscode)->first();
                    if($latestStock){
                        if($latestStock->quantity < $row->quantity){
                            DB::rollBack();
                            $result = array(
                                'msgtype' => '500',
                                'message' => 'Stock Tidak Mencukupi untuk material '. $row->material
                            );
                            return $result;
                        }else{
                            DB::table('t_inv_stock')
                            ->where('material', $row->material)
                            ->where('whscode',  $podata->whscode)
                            ->update([
                                'quantity'     => $latestStock->quantity - $row->quantity
                            ]);
                        }
                    }else{
                        DB::rollBack();
                        $result = array(
                            'msgtype' => '500',
                            'message' => 'Stock Tidak Mencukupi untuk material '. $row->material
                        );
                        return $result;
                    }
                }

                //Full Approve
                DB::table('t_wo01')->where('wonum', $ptaNumber)->update([
                    // 'approved_amount' => $amount,
                    'approvestat'   => 'A'
                ]);

                
                // INSERT INTO t_budget_history (deptid,budget_period,amount,budget_type,note,refnum,refitem,createdon,createdby) 
                // VALUES(NEW.deptid,NEW.budget_period,NEW.amount,'C','Budget Allocation',NULL,NULL,NOW(),NEW.createdby)

            }

            DB::commit();

            $result = array(
                'msgtype' => '200',
                'message' => 'WO dengan Nomor : '. $ptaNumber . ' berhasil di approve'
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
            // return Redirect::to("/approve/pr")->withError($e->getMessage());
        }
    }

    public function approveItems(Request $data, $woID){
        DB::beginTransaction();
        try{
            $woHeader = DB::table('t_wo01')->where('id', $woID)->first();
            $items = join(",",$data['woitem']); 
            $ptaNumber = $woHeader->wonum;

            $podata  = DB::table('t_wo01')->where('wonum', $ptaNumber)->first();
            $woitem  = DB::table('t_wo02')->where('wonum', $ptaNumber)->whereIn('woitem', $data['woitem'])->get();
            // return $woitem;
            foreach($woitem as $row){
                $latestStock = DB::table('v_inv_summary_stock')
                ->where('material', $row->material)
                ->where('whsid',  $podata->whscode)->first();
                if($latestStock){
                    if((double)$latestStock->quantity < (double)$row->quantity){
                        DB::rollBack();
                        $result = array(
                            'msgtype' => '500',
                            'message' => 'Stock Tidak Mencukupi untuk material '. $row->material
                        );
                        return $result;
                    }
                }else{
                    DB::rollBack();
                    $result = array(
                        'msgtype' => '500',
                        'message' => 'Stock Tidak Mencukupi untuk material '. $row->material
                    );
                    return $result;
                }
            }

            $pbjItemData = DB::table('t_wo02')
                ->where('wonum', $ptaNumber)
                ->whereIn('woitem', $data['woitem'])->get();
            // return $pbjItemData;
            $userAppLevel = DB::table('t_wo_approval')
                            ->select('approver_level')
                            ->where('wonum', $ptaNumber)
                            ->whereIn('woitem', $data['woitem'])
                            ->where('approver', Auth::user()->id)
                            ->first();

            //Set Approval
            DB::table('t_wo_approval')
            ->where('wonum', $ptaNumber)
            ->whereIn('woitem', $data['woitem'])
            // ->where('approver_id', Auth::user()->id)
            ->where('approver_level',$userAppLevel->approver_level)
            ->update([
                'approval_status' => 'A',
                // 'approval_remark' => $req['approvernote'],
                'approval_remark' => null,
                'approved_by'     => Auth::user()->username,
                'approval_date'   => getLocalDatabaseDateTime()
            ]);

            $nextApprover = $this->getNextApproval($ptaNumber);
            if($nextApprover  != null){
                DB::table('t_wo_approval')
                ->where('wonum', $ptaNumber)
                ->whereIn('woitem', $data['woitem'])
                ->where('approver_level', $nextApprover)
                ->update([
                    'is_active' => 'Y'
                ]);
            }


            $checkIsFullApprove = DB::table('t_wo_approval')
                                      ->where('wonum', $ptaNumber)
                                      ->whereIn('woitem', $data['woitem'])
                                      ->where('approval_status', '!=', 'A')
                                      ->get();
            if(sizeof($checkIsFullApprove) > 0){
                // go to next approver    
            }else{
                //Full Approve
                // $tgl   = substr($req['grdate'], 8, 2);
                // $bulan = substr($req['grdate'], 5, 2);
                // $tahun = substr($req['grdate'], 0, 4);
            // return $tgl . ' - ' . $bulan . ' - ' . $tahun;
                $ptaNumber = generateIssueNumber(date('Y'), date('m'));

                foreach($woitem as $row){
                    $latestStock = DB::table('v_inv_summary_stock')
                    ->where('material', $row->material)
                    ->where('whsid',  $podata->whscode)->first();
                    if($latestStock){
                        if((double)$latestStock->quantity < (double)$row->quantity){
                            DB::rollBack();
                            $result = array(
                                'msgtype' => '500',
                                'message' => 'Stock Tidak Mencukupi untuk material '. $row->material
                            );
                            return $result;
                        }else{
                            // DB::table('t_inv_stock')
                            // ->where('material', $row->material)
                            // ->where('whscode',  $podata->whscode)
                            // ->update([
                            //     'quantity'     => $latestStock->quantity - $row->quantity
                            // ]);
                            // spIssueMaterialWithBatchFIFO
                            // DB::select('CALL spIssueMaterialWithBatchFIFO(
                            //     "'. $row->material .'",
                            //     "'. $podata->whscode .'",
                            //     "'. $row->quantity .'",
                            //     "'. $ptaNumber .'",
                            //     "'. date('Y') .'",
                            //     "201",
                            //     "'. $row->matdesc .'",
                            //     "'. $row->unit .'",
                            //     "-",
                            //     "'. $row->wonum .'",
                            //     "'. $row->woitem .'",
                            //     "'. Auth::user()->email ?? Auth::user()->username .'"
                            // )');

                            DB::select('call spIssueMaterialWithBatchFIFO(
                                "'. $row->material .'",
                                "'. $podata->whscode .'",
                                "'. $row->quantity .'",
                                "'. $ptaNumber .'",
                                "'. date('Y') .'",
                                "201",
                                "'. $row->matdesc .'",
                                "'. $row->unit .'",
                                "-",
                                "'. $row->wonum .'",
                                "'. $row->woitem .'",
                                "'. Auth::user()->email .'")');
                        }
                    }else{
                        DB::rollBack();
                        $result = array(
                            'msgtype' => '500',
                            'message' => 'Stock Tidak Mencukupi untuk material '. $row->material
                        );
                        return $result;
                    }
                }

                DB::table('t_wo01')->where('wonum', $ptaNumber)
                ->update([
                    'wo_status'   => 'A'
                ]);

                DB::table('t_wo02')->where('wonum', $ptaNumber)
                ->whereIn('woitem', $data['woitem'])
                ->update([
                    'approvestat'   => 'A'
                ]);

                
            }

            DB::commit();
            $result = array(
                'msgtype' => '200',
                'message' => 'WO/SPK dengan Nomor : '. $ptaNumber . ' berhasil di approve',
                'items'   => $pbjItemData
            );
            return $result;
        }
        catch(\Exception $e){
            DB::rollBack();
            $result = array(
                'msgtype' => '500',
                'message' => $e->getMessage()
            );
            return $result;
            // return Redirect::to("/approve/budget")->withError($e->getMessage());
        }
    }
}
