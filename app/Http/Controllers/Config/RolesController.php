<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class RolesController extends Controller
{
    public function index(){
        $roles = DB::table('roles')->get();
        return view('config.roles.index', ['role' => $roles]);
    }

    public function create(){
        return view('config.roles.create');
    }

    public function assignment($id){
        $data = DB::table('roles')->where('id', $id)->first();
        return view('config.roles.assignment', ['datarole' => $data]);
    }

    public function userroles($roleid, Request $request){
        $params = $request->params;        
        $whereClause = $params['sac'];
        $query = DB::table('v_userroles')->where('roleid', $roleid)->orderBy('userid');
        return DataTables::queryBuilder($query)->toJson();
    }

    public function rolemenus($roleid, Request $request){
        $params = $request->params;        
        $whereClause = $params['sac'];
        $query = DB::table('v_menuroles')->where('roleid', $roleid)
                                         ->orderBy('menugroup', 'ASC')
                                         ->orderBy('menuid', 'ASC');
        return DataTables::queryBuilder($query)->toJson();
    }

    public function dataadduser($roleid, Request $request){
        $userid = DB::table('userroles')->where('roleid', $roleid)->pluck('userid');
        $params = $request->params;        
        $whereClause = $params['sac'] ?? '';
        $query = DB::table('users')->whereNotIn('id', $userid);
        return DataTables::queryBuilder($query)->toJson();
    }

    public function dataaddmenu($roleid, Request $request){
        $menuid = DB::table('menuroles')->where('roleid', $roleid)->pluck('menuid');
        $params = $request->params;        
        $whereClause = $params['sac'] ?? '';
        $query = DB::table('menus')->whereNotIn('id', $menuid)->orderBy('menugroup', 'ASC')->orderBy('id', 'ASC');
        return DataTables::queryBuilder($query)->toJson();
    }

    public function saveroleuser(Request $request){
        // return $request;
        DB::beginTransaction();
        try{
            DB::table('userroles')->insert([
                'userid'      => $request['userid'],
                'roleid'      => $request['roleid'],
                'created_at'  => date('Y-m-d H:m:s'),
                'createdby'   => Auth::user()->email ?? Auth::user()->username
            ]);

            DB::commit();
            return response()->json(['success'=>'Ajax request submitted successfully']);
            // return Redirect::to("/config/roles")->withSuccess('New role created');
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['errors'=>$e->getMessage()]);
            // return Redirect::to("/config/roles")->withError($e->getMessage());
        }
    }

    public function saverolemenu(Request $request){
        DB::beginTransaction();
        try{
            DB::table('menuroles')->insert([
                'menuid'      => $request['menuid'],
                'roleid'      => $request['roleid'],
                'created_at'  => date('Y-m-d H:m:s'),
                'createdby'   => Auth::user()->email ?? Auth::user()->username
            ]);

            DB::commit();
            return response()->json(['success'=>'Ajax request submitted successfully']);
            // return Redirect::to("/config/roles")->withSuccess('New role created');
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['errors'=>$e->getMessage()]);
            // return Redirect::to("/config/roles")->withError($e->getMessage());
        }
    }

    public function save(Request $request){
        $validated = $request->validate([
            'rolename' => 'required|unique:roles|max:255'
        ]);

        DB::beginTransaction();
        try{
            DB::table('roles')->insert([
                'rolename'   => $request['rolename'],
                'rolestatus' => 1,
                'created_at'  => date('Y-m-d H:m:s'),
                'createdby'   => Auth::user()->email ?? Auth::user()->username
            ]);

            DB::commit();
            return Redirect::to("/config/roles")->withSuccess('New role created');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/roles")->withError($e->getMessage());
        }
    }

    public function delete($id){
        DB::beginTransaction();
        try{
            DB::table('roles')->where('id', $id)->delete();

            DB::commit();
            return Redirect::to("/config/roles")->withSuccess('Role deleted');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/roles")->withError($e->getMessage());
        }
    }

    public function deleteMenuAssignment(Request $req){
        // return $req;
        DB::beginTransaction();
        try{
            DB::table('menuroles')->where('menuid', $req['menuid'])->where('roleid', $req['roleid'])->delete();

            DB::commit();
            return response()->json(['success'=>'Assignment Menu Role deleted']);
            // return Redirect::to("/config/roles")->withSuccess('Assignment Menu Role deleted');
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['error'=>$e->getMessage()]);
            // return Redirect::to("/config/roles")->withError($e->getMessage());
        }
    }

    public function deleteUserAssignment(Request $req){
        DB::beginTransaction();
        try{
            DB::table('userroles')->where('userid', $req['userid'])->where('roleid', $req['roleid'])->delete();

            DB::commit();
            return response()->json(['success'=>'Assignment User Role deleted']);
            // return Redirect::to("/config/roles")->withSuccess('Assignment Menu Role deleted');
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['error'=>$e->getMessage()]);
            // return Redirect::to("/config/roles")->withError($e->getMessage());
        }
    }
}
