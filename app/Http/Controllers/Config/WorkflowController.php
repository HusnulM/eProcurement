<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class WorkflowController extends Controller
{
    public function index(){
        $users  = DB::table('users')->orderBy('id', 'ASC')->get();
        $ctgrs  = DB::table('workflow_categories')->orderBy('id', 'ASC')->get();
        $groups = DB::table('workflow_groups')->orderBy('id', 'ASC')->get();
        $wfassignments = DB::table('v_workflow_assignments')->get();

        $prwf     = DB::table('v_workflows')->where('object', 'PR')->get();
        $powf     = DB::table('v_workflows')->where('object', 'PO')->get();

        return view('config.approval.index',
            [
                'ctgrs'  => $ctgrs,
                'groups' => $groups,
                'users'  => $users,
                'prwf'   => $prwf,
                'powf'   => $powf
            ]);
    }



    public function savePRApproval(Request $req){
        DB::beginTransaction();
        try{
            $requester = $req['requester'];
            $approver  = $req['approver'];
            $applevel  = $req['applevel'];

            $insertData = array();
            for($i = 0; $i < sizeof($requester); $i++){
                $data = array(
                    'object'          => 'PR',
                    'requester'       => $requester[$i],
                    'approver'        => $approver[$i],
                    'approver_level'  => $applevel[$i],
                    'createdon'       => date('Y-m-d H:m:s'),
                    'createdby'       => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $data);
            }
            insertOrUpdate($insertData,'workflows');
            DB::commit();
            return Redirect::to("/config/workflow")->withSuccess('Approval PR Berhasil dibuat');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/workflow")->withError($e->getMessage());
        }
    }

    public function savePOApproval(Request $req){
        DB::beginTransaction();
        try{
            $requester = $req['requester'];
            $approver  = $req['approver'];
            $applevel  = $req['applevel'];

            $insertData = array();
            for($i = 0; $i < sizeof($requester); $i++){
                $data = array(
                    'object'          => 'PO',
                    'requester'       => $requester[$i],
                    'approver'        => $approver[$i],
                    'approver_level'  => $applevel[$i],
                    'createdon'       => date('Y-m-d H:m:s'),
                    'createdby'       => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $data);
            }
            insertOrUpdate($insertData,'workflows');
            DB::commit();
            return Redirect::to("/config/workflow")->withSuccess('Approval PO Berhasil dibuat');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/workflow")->withError($e->getMessage());
        }
    }

    public function deletePRwf($id){
        DB::beginTransaction();
        try{
            DB::table('workflows')->where('id', $id)->delete();
            DB::commit();
            return Redirect::to("/config/workflow")->withSuccess('Approval PR Berhasil dihapus');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/workflow")->withError($e->getMessage());
        }
    }

    public function deletePOwf($id){
        DB::beginTransaction();
        try{
            DB::table('workflows')->where('id', $id)->delete();
            DB::commit();
            return Redirect::to("/config/workflow")->withSuccess('Approval PO Berhasil dihapus');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/workflow")->withError($e->getMessage());
        }
    }
}
