<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class MenusController extends Controller
{
    public function index(){
        $menus  = DB::table('menus')
                    ->select('menus.id','menus.name','menus.route','menus.menugroup', 'menugroups.menugroup as group', 'menus.menu_idx')
                    ->join('menugroups','menus.menugroup','=','menugroups.id')
                    ->orderBy('menugroups._index', 'ASC')
                    ->orderBy('menus.menu_idx', 'ASC')
                    ->get();
        $groups = DB::table('menugroups')->orderBy('_index', 'ASC')->get();
        return view('config.menus.index', ['menus' => $menus, 'groups' => $groups]);
    }

    public function saveMenus(Request $req){
        DB::beginTransaction();
        try{
            $menuname   = $req['menunames'];
            $menuroute  = $req['menuroutes'];
            $menugroup  = $req['menugroups'];

            $insertData = array();
            for($i = 0; $i < sizeof($menuname); $i++){
                $menus = array(
                    'name'          => $menuname[$i],
                    'route'         => $menuroute[$i],
                    'menugroup'     => $menugroup[$i],
                    'created_at'    => date('Y-m-d H:m:s'),
                    'createdby'     => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $menus);
            }
            insertOrUpdate($insertData,'menus');
            DB::commit();
            return Redirect::to("/config/menus")->withSuccess('New Application Menu Created');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/menus")->withError($e->getMessage());
        }
    }

    public function saveGroup(Request $req){
        DB::beginTransaction();
        try{
            DB::table('menugroups')->insert([
                'menugroup'   => $req['groupname'],
                '_index'      => $req['displayidx'] ?? 1,
                'groupicon'   => $req['groupicon'],
                'created_at'  => date('Y-m-d H:m:s'),
                'createdby'   => Auth::user()->email ?? Auth::user()->username
            ]);
            DB::commit();
            return Redirect::to("/config/menus")->withSuccess('New Menu Group Created');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/menus")->withError($e->getMessage());
        }
    }

    public function updateMenu(Request $req){
        DB::beginTransaction();
        try{
            if(isset($req['menugroup'])){
                DB::table('menus')->where('id', $req['menuid'])->update([
                    'name'          => $req['menuname'],
                    'route'         => $req['menuroute'],
                    'menugroup'     => $req['menugroup'],
                    'updatedby'     => Auth::user()->email ?? Auth::user()->username
                ]);
            }else{
                DB::table('menus')->where('id', $req['menuid'])->update([
                    'name'          => $req['menuname'],
                    'route'         => $req['menuroute'],
                    'updatedby'     => Auth::user()->email ?? Auth::user()->username
                ]);
            }
                
            DB::commit();
            return Redirect::to("/config/menus")->withSuccess('Application Menu Updated');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/menus")->withError($e->getMessage());
        }
    }

    public function updateGroup(Request $req){
        DB::beginTransaction();
        try{
            DB::table('menugroups')->where('id', $req['egroupid'])->update([
                'menugroup'   => $req['egroupname'],
                '_index'      => $req['egroupidx'] ?? 1,
                // 'groupicon'   => $req['groupicon'],
                'updatedby'   => Auth::user()->email ?? Auth::user()->username
            ]);
            DB::commit();
            return Redirect::to("/config/menus")->withSuccess('Menu Group Updated');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/menus")->withError($e->getMessage());
        }
    }

    public function deleteMenu($id){
        DB::beginTransaction();
        try{
            DB::table('menus')->where('id', $id)->delete();
            DB::commit();
            return Redirect::to("/config/menus")->withSuccess('Application Menu Deleted');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/menus")->withError($e->getMessage());
        }
    }

    public function deleteGroup($id){
        DB::beginTransaction();
        try{
            DB::table('menugroups')->where('id', $id)->delete();
            DB::commit();
            return Redirect::to("/config/menus")->withSuccess('Menu Group Deleted');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/menus")->withError($e->getMessage());
        }
    }
}
