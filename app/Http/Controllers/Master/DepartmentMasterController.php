<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class DepartmentMasterController extends Controller
{
    public function index(){
        return view('master.department.index');
    }

    public function create(){
        return view('master.department.create');
    }

    public function departmentLists(Request $request){
        $params = $request->params;        
        $whereClause = $params['sac'];
        $query = DB::table('t_department')->orderBy('deptid');
        return DataTables::queryBuilder($query)->toJson();
    }

    public function save(Request $req){
        DB::beginTransaction();
        try{
            $dept   = $req['deptname'];

            $insertData = array();
            for($i = 0; $i < sizeof($dept); $i++){
                $menus = array(
                    'department'    => $dept[$i],
                    'createdon'     => date('Y-m-d H:m:s'),
                    'createdby'     => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $menus);
            }
            insertOrUpdate($insertData,'t_department');
            DB::commit();
            return Redirect::to("/master/department")->withSuccess('New Department Created');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/department")->withError($e->getMessage());
        }
    }

    public function update(Request $req){
        DB::beginTransaction();
        try{
            DB::table('t_department')->where('deptid', $req['deptid'])->update([
                'department' => $req['deptname']
            ]);
            DB::commit();
            return Redirect::to("/master/department")->withSuccess('Department Updated');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/department")->withError($e->getMessage());
        }
    }

    public function delete($id){
        DB::beginTransaction();
        try{
            DB::table('t_department')->where('deptid', $id)->delete();
            DB::commit();
            return Redirect::to("/master/department")->withSuccess('Department Deleted');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/department")->withError($e->getMessage());
        }
    }
}
