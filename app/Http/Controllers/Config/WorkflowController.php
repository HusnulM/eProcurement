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
        return view('config.approval.index', ['ctgrs' => $ctgrs, 'groups' => $groups, 'users' => $users, 'wfassignments' => $wfassignments]);
    }

    public function saveCategories(Request $req){
        DB::beginTransaction();
        try{
            $wfgroups   = $req['approvalcategories'];

            $insertData = array();
            for($i = 0; $i < sizeof($wfgroups); $i++){
                $menus = array(
                    'workflow_category' => $wfgroups[$i],
                    'createdon'         => date('Y-m-d H:m:s'),
                    'createdby'         => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $menus);
            }
            insertOrUpdate($insertData,'workflow_categories');
            DB::commit();
            return Redirect::to("/config/workflow")->withSuccess('New Workflow Approval Category Created');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/workflow")->withError($e->getMessage());
        }
    }

    public function updateCategories(Request $req){
        DB::beginTransaction();
        try{
            DB::table('workflow_categories')->where('id', $req['wfcategoriesid'])->update([
                'workflow_category' => $req['wfcategories']
            ]);
            DB::commit();
            return Redirect::to("/config/workflow")->withSuccess('Workflow Approval Category Updated');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/workflow")->withError($e->getMessage());
        }
    }

    public function saveGroup(Request $req){
        DB::beginTransaction();
        try{
            $wfgroups   = $req['wfgroups'];

            $insertData = array();
            for($i = 0; $i < sizeof($wfgroups); $i++){
                $menus = array(
                    'workflow_group' => $wfgroups[$i],
                    'createdon'      => date('Y-m-d H:m:s'),
                    'createdby'      => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $menus);
            }
            insertOrUpdate($insertData,'workflow_groups');
            DB::commit();
            return Redirect::to("/config/workflow")->withSuccess('New Workflow Approval Group Created');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/workflow")->withError($e->getMessage());
        }
    }

    public function updateGroup(Request $req){
        DB::beginTransaction();
        try{
            DB::table('workflow_groups')->where('id', $req['wfgroupid'])->update([
                'workflow_group' => $req['wfgroupname']
            ]);
            DB::commit();
            return Redirect::to("/config/workflow")->withSuccess('Workflow Approval Group Updated');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/workflow")->withError($e->getMessage());
        }
    }

    public function saveAssignment(Request $req){
        DB::beginTransaction();
        try{
            $wfgroups   = $req['wfgroups'];
            $wflevels   = $req['wflevels'];
            $wfctegrs   = $req['wfctegrs'];
            $wfcreator  = $req['wfcreator'];
            $wfapprov   = $req['wfapprov'];

            $insertData = array();
            for($i = 0; $i < sizeof($wfgroups); $i++){
                $menus = array(
                    'workflow_group'      => $wfgroups[$i],
                    'approval_level'      => $wflevels[$i],
                    'workflow_categories' => $wfctegrs[$i],
                    'creator'             => $wfcreator[$i],
                    'approver'            => $wfapprov[$i],
                    'createdon'           => date('Y-m-d H:m:s'),
                    'createdby'           => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $menus);
            }
            insertOrUpdate($insertData,'workflow_assignments');
            DB::commit();
            return Redirect::to("/config/workflow")->withSuccess('Workflow Approval Assignment Created');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/workflow")->withError($e->getMessage());
        }
    }

    public function deleteCategories($id){
        DB::beginTransaction();
        try{
            DB::table('workflow_categories')->where('id', $id)->delete();
            DB::commit();
            return Redirect::to("/config/workflow")->withSuccess('Workflow Approval Category Deleted');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/workflow")->withError($e->getMessage());
        }
    }

    public function deleteGroup($id){
        DB::beginTransaction();
        try{
            DB::table('workflow_groups')->where('id', $id)->delete();
            DB::commit();
            return Redirect::to("/config/workflow")->withSuccess('Workflow Approval Group Deleted');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/workflow")->withError($e->getMessage());
        }
    }

    public function deleteAssignment($p1, $p2, $p3, $p4, $p5){
        DB::beginTransaction();
        try{
            DB::table('workflow_assignments')
                        ->where('workflow_group', $p1)
                        ->where('approval_level', $p2)
                        ->where('workflow_categories', $p3)
                        ->where('creator', $p4)
                        ->where('approver', $p5)
                        ->delete();
            DB::commit();
            return Redirect::to("/config/workflow")->withSuccess('Workflow Approval Assignement Deleted');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/workflow")->withError($e->getMessage());
        }
    }
}
