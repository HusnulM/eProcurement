<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use DataTables, Auth, DB;
use Validator,Redirect,Response;
use PDF;

class ChangeApprovedPrController extends Controller
{
    public function index()
    {
        $department = DB::table('t_department')->get();
        return view('transaksi.pr.changeapprovedlist', ['department' => $department]);
    }

    public function change($id)
    {
        $department = DB::table('t_department')->get();
        $prhdr = DB::table('t_pr01')->where('id', $id)->first();
        $prdtl = DB::table('t_pr02')->where('prnum', $prhdr->prnum)->where('isdeleted', 'N')->get();
        $attachments = DB::table('t_attachments')->where('doc_object','PR')->where('doc_number', $prhdr->prnum)->get();
        $approvals   = DB::table('v_pr_approval_v2')->where('prnum', $prhdr->prnum)->get();
        // return $attachments;
        return view('transaksi.pr.changeapproved',
            [
                'department'    => $department,
                'prhdr'         => $prhdr,
                'pritem'        => $prdtl,
                'attachments'   => $attachments,
                'approvals'     => $approvals
            ]);
    }

    public function approvedList(Request $req){
        $query = DB::table('v_rpr')->select('id','prnum','prdate','approvestat','requestby','remark','deptname','createdby')->distinct();

        if(isset($req->department)){
            if($req->department !== 'All'){
                $query->where('deptid', $req->department);
            }
        }

        $query->where('approvestat', 'A');
        $query->where('pocreated', 'N');

        if(isset($req->datefrom) && isset($req->dateto)){
            $query->whereBetween('prdate', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('prdate', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('prdate', $req->dateto);
        }

        $checkObjAuth = DB::table('user_object_auth')
                        ->where('object_name', 'ALLOW_DISPLAY_ALL_DEPT')
                        ->where('object_val', 'Y')
                        ->where('userid', Auth::user()->id)
                        ->first();
        if($checkObjAuth){

        }else{
            $query->where('createdby', Auth::user()->email);
        }
        // $query->where('createdby', Auth::user()->email);

        $query->orderBy('id');

        return DataTables::queryBuilder($query)
        ->editColumn('prdate', function ($query){
            return [
                'prdate1' => \Carbon\Carbon::parse($query->prdate)->format('d-m-Y')
             ];
        })
        ->toJson();
    }

    public function update(Request $req){
        // return $req;
        DB::beginTransaction();
        try{

            $qty    = $req['newQuantity'];
            $qty    = str_replace(',','',$qty);

            $material = DB::table('v_material')->where('id', $req['newMaerial'])->first();

            $prItem = DB::table('t_pr02')
                      ->where('prnum', $req['oldPrnum'])
                      ->where('pritem', $req['oldPritem'])
                      ->first();
            // return $prItem;

            if($prItem){
                DB::table('t_pr02')
                      ->where('prnum', $req['oldPrnum'])
                      ->where('pritem', $req['oldPritem'])
                      ->update([
                            'material'  => $material->material,
                            'matdesc'   => $material->matdesc,
                            'quantity'  => $qty,
                            'unit'      => $req['newUom'],
                            'remark'    => $req['subTitRemark']
                      ]);

                if($prItem->pbjnumber){
                    DB::table('t_pbj02')
                          ->where('pbjnumber', $prItem->pbjnumber)
                          ->where('pbjitem', $prItem->pbjitem)->update([
                              'partnumber'  => $material->material,
                              'description' => $material->matdesc,
                              'quantity'    => $qty,
                              'unit'        => $req['newUom'],
                              'remark'      => $req['subTitRemark']
                          ]);
                }
            }



            DB::commit();
            $result = array(
                'msgtype' => '200',
                'message' => 'Subtitut Material PR : '. $req['oldPrnum'] . ' Berhasil'
            );
            return $result;
        } catch(\Exception $e){
            DB::rollBack();
            $result = array(
                'msgtype' => '500',
                'message' => $e->getMessage()
            );
            return $result;
        }
    }
}
