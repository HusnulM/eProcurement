<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;


class ItemMasterController extends Controller
{
    public function index(){
        return view('master.material.index');
    }

    public function create(){
        return view('master.material.create');
    }

    public function itemLists(Request $request){
        $params = $request->params;        
        $whereClause = $params['sac'];
        $query = DB::table('t_material')->orderBy('material');
        return DataTables::queryBuilder($query)->toJson();
    }

    public function itemCategoryLists(Request $request){
        $params = $request->params;        
        $whereClause = $params['sac'];
        $query = DB::table('t_materialtype')->orderBy('id');
        return DataTables::queryBuilder($query)->toJson();
    }

    public function uomLists(Request $request){
        $params = $request->params;        
        $whereClause = $params['sac'];
        $query = DB::table('t_uom')->select('uom','uomdesc','createdby', 'createdon')->orderBy('uom');
        return DataTables::queryBuilder($query)->toJson();
    }

    public function saveitemcategory(Request $req){
        DB::beginTransaction();
        try{
            $itemcate   = $req['itemcate'];

            $insertData = array();
            for($i = 0; $i < sizeof($itemcate); $i++){
                $menus = array(
                    'mattypedesc'   => $itemcate[$i],
                    'createdon'     => date('Y-m-d H:m:s'),
                    'createdby'     => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $menus);
            }
            insertOrUpdate($insertData,'t_materialtype');
            DB::commit();
            return Redirect::to("/master/item")->withSuccess('New Item Category Created');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/item")->withError($e->getMessage());
        }
    }

    public function saveuom(Request $req){
        DB::beginTransaction();
        try{
            $uom     = $req['uom'];
            $uomdesc = $req['uomdesc'];

            $insertData = array();
            for($i = 0; $i < sizeof($uom); $i++){
                $menus = array(
                    'uom'        => $uom[$i],
                    'uomdesc'    => $uomdesc[$i],
                    'createdon'  => date('Y-m-d H:m:s'),
                    'createdby'  => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $menus);
            }
            insertOrUpdate($insertData,'t_uom');
            DB::commit();
            return Redirect::to("/master/item")->withSuccess('New Unit of measure created');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/item")->withError($e->getMessage());
        }
    }
}
