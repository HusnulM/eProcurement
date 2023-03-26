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

        $budgetwf = DB::table('v_workflow_budget')->where('object', 'BUDGET')->get();
        $pbjwf    = DB::table('v_workflow_budget')->where('object', 'PBJ')->get();
        $spkwf    = DB::table('v_workflow_budget')->where('object', 'SPK')->get();
        $prwf     = DB::table('v_workflow_budget')->where('object', 'PR')->get();
        $powf     = DB::table('v_workflow_budget')->where('object', 'PO')->get();

        return view('config.approval.index', 
            [ 'ctgrs' => $ctgrs, 'groups'   => $groups, 
              'users' => $users, 'budgetwf' => $budgetwf,
              'pbjwf' => $pbjwf, 'spkwf'    => $spkwf,
              'prwf'  => $prwf,  'powf'     => $powf
            ]);
    }

    public function saveBudgetApproval(Request $req){
        DB::beginTransaction();
        try{
            $requester = $req['requester'];
            $approver  = $req['approver'];
            $applevel  = $req['applevel'];

            $insertData = array();
            for($i = 0; $i < sizeof($requester); $i++){
                $data = array(
                    'object'          => 'BUDGET',
                    'requester'       => $requester[$i],
                    'approver'        => $approver[$i],
                    'approver_level'  => $applevel[$i],
                    'createdon'       => date('Y-m-d H:m:s'),
                    'createdby'       => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $data);
            }
            insertOrUpdate($insertData,'workflow_budget');
            DB::commit();
            return Redirect::to("/config/workflow")->withSuccess('Approval Budget Berhasil dibuat');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/workflow")->withError($e->getMessage());
        }
    }

    public function savePbjApproval(Request $req){
        DB::beginTransaction();
        try{
            $requester = $req['requester'];
            $approver  = $req['approver'];
            $applevel  = $req['applevel'];

            $insertData = array();
            for($i = 0; $i < sizeof($requester); $i++){
                $data = array(
                    'object'          => 'PBJ',
                    'requester'       => $requester[$i],
                    'approver'        => $approver[$i],
                    'approver_level'  => $applevel[$i],
                    'createdon'       => date('Y-m-d H:m:s'),
                    'createdby'       => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $data);
            }
            insertOrUpdate($insertData,'workflow_budget');
            DB::commit();
            return Redirect::to("/config/workflow")->withSuccess('Approval PBJ Berhasil dibuat');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/workflow")->withError($e->getMessage());
        }
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
            insertOrUpdate($insertData,'workflow_budget');
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
            insertOrUpdate($insertData,'workflow_budget');
            DB::commit();
            return Redirect::to("/config/workflow")->withSuccess('Approval PO Berhasil dibuat');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/workflow")->withError($e->getMessage());
        }
    }

    public function saveSPKApproval(Request $req){
        DB::beginTransaction();
        try{
            $requester = $req['requester'];
            $approver  = $req['approver'];
            $applevel  = $req['applevel'];

            $insertData = array();
            for($i = 0; $i < sizeof($requester); $i++){
                $data = array(
                    'object'          => 'SPK',
                    'requester'       => $requester[$i],
                    'approver'        => $approver[$i],
                    'approver_level'  => $applevel[$i],
                    'createdon'       => date('Y-m-d H:m:s'),
                    'createdby'       => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $data);
            }
            insertOrUpdate($insertData,'workflow_budget');
            DB::commit();
            return Redirect::to("/config/workflow")->withSuccess('Approval SPK Berhasil dibuat');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/workflow")->withError($e->getMessage());
        }
    }

    public function deleteBudgetwf($id){
        DB::beginTransaction();
        try{
            DB::table('workflow_budget')->where('id', $id)->delete();
            DB::commit();
            return Redirect::to("/config/workflow")->withSuccess('Approval Budget Berhasil dihapus');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/workflow")->withError($e->getMessage());
        }
    }

    public function deletePBJwf($id){
        DB::beginTransaction();
        try{
            DB::table('workflow_budget')->where('id', $id)->delete();
            DB::commit();
            return Redirect::to("/config/workflow")->withSuccess('Approval PBJ Berhasil dihapus');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/workflow")->withError($e->getMessage());
        }
    }

    public function deletePRwf($id){
        DB::beginTransaction();
        try{
            DB::table('workflow_budget')->where('id', $id)->delete();
            DB::commit();
            return Redirect::to("/config/workflow")->withSuccess('Approval PR Berhasil dihapus');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/workflow")->withError($e->getMessage());
        }
    }

    public function deleteSPKwf($id){
        DB::beginTransaction();
        try{
            DB::table('workflow_budget')->where('id', $id)->delete();
            DB::commit();
            return Redirect::to("/config/workflow")->withSuccess('Approval WO Berhasil dihapus');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/workflow")->withError($e->getMessage());
        }
    }

    public function deletePOwf($id){
        DB::beginTransaction();
        try{
            DB::table('workflow_budget')->where('id', $id)->delete();
            DB::commit();
            return Redirect::to("/config/workflow")->withSuccess('Approval PO Berhasil dihapus');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/workflow")->withError($e->getMessage());
        }
    }
}
