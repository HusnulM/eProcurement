<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DataTables, Auth, DB, Validator, Redirect, Response, Excel;

class CostMasterController extends Controller
{
    public function index()
    {
        return view('master.costmaster.index');
    }

    public function create()
    {
        $costgroup = DB::table('t_cost_group')->get();
        return view('master.costmaster.create', ['costgroup' => $costgroup]);
    }

    public function edit($id)
    {
        $costgroup  = DB::table('t_cost_group')->get();
        $costmaster = DB::table('t_cost_code_master')->where('id', $id)->first();
        $scgroup    = DB::table('t_cost_group')->where('cost_group', $costmaster->cost_group)->first();

        return view('master.costmaster.edit',
            [
                'costgroup'  => $costgroup,
                'costmaster' => $costmaster,
                'scgroup'    => $scgroup
            ]);
    }

    public function costLists(Request $request){
        $params = $request->params;
        $whereClause = $params['sac'];
        $query = DB::table('v_cost_master')->where('deleted', 'N')->orderBy('cost_code');
        return DataTables::queryBuilder($query)->setRowId('id')->toJson();
    }

    public function findcostCode(Request $request){
        $query['data'] = DB::table('v_cost_master')
        ->where('cost_code', 'like', '%'. $request->search . '%')
        ->orWhere('cost_desc', 'like', '%'. $request->search . '%')
        ->orWhere('cost_group_desc', 'like', '%'. $request->search . '%')
        ->get();
        return $query;
    }

    public function costGroupLists(Request $request){
        $params = $request->params;
        $whereClause = $params['sac'];
        $query = DB::table('t_cost_group')->orderBy('id');
        return DataTables::queryBuilder($query)->setRowId('id')->toJson();
    }

    public function save(Request $req)
    {
        DB::beginTransaction();
        try{
            $costCode   = $req['costcode'];
            $costDesc   = $req['costdesc'];
            $costGroup  = $req['costgroup'];

            $insertData = array();
            for($i = 0; $i < sizeof($costCode); $i++){
                $menus = array(
                    'cost_code'   => $costCode[$i],
                    'cost_desc'   => $costDesc[$i],
                    'cost_group'  => $costGroup[$i],
                    'createdon'   => getLocalDatabaseDateTime(),
                    'createdby'   => Auth::user()->username
                );
                array_push($insertData, $menus);
            }
            insertOrUpdate($insertData,'t_cost_code_master');
            DB::commit();
            return Redirect::to("/master/costmaster")->withSuccess('New Cost Master Created');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/costmaster")->withError($e->getMessage());
        }
    }

    public function update(Request $req)
    {
        DB::beginTransaction();
        try{
            DB::table('t_cost_code_master')->where('id', $req['costcodeid'])->update([
                'cost_code'  => $req['costCode'],
                'cost_desc'  => $req['costName'],
                'cost_group' => $req['costGroup'],
                'changedon'  => getLocalDatabaseDateTime(),
                'changedby'  => Auth::user()->username
            ]);
            DB::commit();
            return Redirect::to("/master/costmaster")->withSuccess('Cost Group Updated');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/costmaster")->withError($e->getMessage());
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try{
            DB::table('t_cost_code_master')->where('id', $id)->update([
                'deleted' => 'Y'
            ]);
            DB::commit();
            return Redirect::to("/master/costmaster")->withSuccess('Cost Master Deleted');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/costmaster")->withError($e->getMessage());
        }
    }

    public function saveCostGroup(Request $req)
    {
        DB::beginTransaction();
        try{
            $costgroup   = $req['costgroup'];
            $costDesc    = $req['costgroupdesc'];

            $insertData = array();
            for($i = 0; $i < sizeof($costgroup); $i++){
                $menus = array(
                    'cost_group'      => $costgroup[$i],
                    'cost_group_desc' => $costDesc[$i],
                    'createdon'       => getLocalDatabaseDateTime(),
                    'createdby'       => Auth::user()->username
                );
                array_push($insertData, $menus);
            }
            insertOrUpdate($insertData,'t_cost_group');
            DB::commit();
            return Redirect::to("/master/costmaster")->withSuccess('New Cost Group Created');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/costmaster")->withError($e->getMessage());
        }
    }

    public function updateCostGroup(Request $req)
    {
        DB::beginTransaction();
        try{
            DB::table('t_cost_group')->where('id', $req['costgroupid'])->update([
                'cost_group'      => $req['costgroup'],
                'cost_group_desc' => $req['costgroupdesc'],
            ]);
            DB::commit();
            return Redirect::to("/master/costmaster")->withSuccess('Cost Group Updated');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/costmaster")->withError($e->getMessage());
        }
    }

    public function deleteGroup($id)
    {
        DB::beginTransaction();
        try{
            DB::table('t_cost_group')->where('id', $id)->delete();
            DB::commit();
            return Redirect::to("/master/costmaster")->withSuccess('Cost Group Deleted');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/costmaster")->withError($e->getMessage());
        }
    }
}
