<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables, Auth, DB;
use Validator,Redirect,Response;


class ItemMasterController extends Controller
{
    public function index(){
        return view('master.material.index');
    }

    public function create(){
        $matcat = DB::table('t_materialtype')->get();
        $matuom = DB::table('t_uom')->get();
        return view('master.material.create', ['matcat' => $matcat, 'matuom' => $matuom]);
    }

    public function edit($id){
        $matcat = DB::table('t_materialtype')->get();
        $matuom = DB::table('t_uom')->get();
        $materialdata = DB::table('v_material')->where('matuniqid', $id)->first();
        // dd($id);
        $materialuom  = DB::table('t_material2')->where('material', $materialdata->material)->get();
        // dd($materialuom);
        return view('master.material.edit', ['matcat' => $matcat, 'matuom' => $matuom, 'materialdata' => $materialdata, 'materialuom' => $materialuom]);
    }

    public function itemLists(Request $request){
        $params = $request->params;        
        $whereClause = $params['sac'];
        $query = DB::table('v_material')->orderBy('material');
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

    public function findPartnumber(Request $request){
        // $url    = parse_url($_SERVER['REQUEST_URI']);
        // $search = $url['query'];
        // $search = str_replace("searchName=","",$search);

        $query['data'] = DB::table('v_material')->where('partnumber', 'like', '%'. $request->search . '%')->get();

        // return \Response::json($query);
        return $query;
        // return $this->successResponse('OK', $query);
    }

    public function save(Request $req){
        DB::beginTransaction();
        try{
            $current_timestamp = Carbon::now()->timestamp;
        
            DB::table('t_material')->insert([
                'material'   => $req['partnumber'],
                'matdesc'    => $req['partname'],
                'mattype'    => $req['itemtype'],
                'partname'   => $req['partname'],
                'partnumber' => $req['partnumber'],
                'matunit'    => $req['itemunit'],
                'matuniqid'  => $current_timestamp,
                'createdon'  => date('Y-m-d H:m:s'),
                'createdby'  => Auth::user()->email ?? Auth::user()->username
            ]);            

            $insertAltUom = array();
            $altUom = array(
                'material'  => $req['partnumber'],
                'altuom'    => $req['itemunit'],
                'convalt'   => '1',
                'baseuom'   => $req['itemunit'],
                'convbase'  => '1',
                'createdon' => date('Y-m-d H:m:s'),
                'createdby' => Auth::user()->email ?? Auth::user()->username
            );
            array_push($insertAltUom, $altUom);

            if(isset($req['convuom'])){
                $convuom    = $req['convuom'];
                $convalue   = $req['convalue'];
                $baseuom    = $req['baseuom'];
                $baseuomval = $req['baseuomval'];
                for($i = 0; $i < sizeof($convuom); $i++){
                    $altUom = array(
                        'material'  => $req['itemcode'],
                        'altuom'    => $convuom[$i],
                        'convalt'   => $convalue[$i],
                        'baseuom'   => $baseuom[$i],
                        'convbase'  => $baseuomval[$i],
                        'createdon' => date('Y-m-d H:m:s'),
                        'createdby' => Auth::user()->email ?? Auth::user()->username
                    );
                    array_push($insertAltUom, $altUom);
                }
            }
            insertOrUpdate($insertAltUom,'t_material2');
            DB::commit();
            return Redirect::to("/master/item")->withSuccess('New Item Master Created');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/item")->withError($e->getMessage());
        }
    }

    public function update(Request $req){
        DB::beginTransaction();
        try{
            $current_timestamp = Carbon::now()->timestamp;
        
            DB::table('t_material')->where('material', $req['partnumber'])->update([
                // 'material'   => $req['itemcode'],
                'matdesc'    => $req['partname'],
                'mattype'    => $req['itemtype'],
                'partname'   => $req['partname'],
                'partnumber' => $req['partnumber'],
                'matunit'    => $req['itemunit']
            ]);         
            
            DB::table('t_material2')->where('material', $req['partnumber'])->delete();

            $insertAltUom = array();
            $altUom = array(
                'material'  => $req['partnumber'],
                'altuom'    => $req['itemunit'],
                'convalt'   => '1',
                'baseuom'   => $req['itemunit'],
                'convbase'  => '1',
                'createdon' => date('Y-m-d H:m:s'),
                'createdby' => Auth::user()->email ?? Auth::user()->username
            );
            array_push($insertAltUom, $altUom);

            if(isset($req['convuom'])){
                $convuom    = $req['convuom'];
                $convalue   = $req['convalue'];
                $baseuom    = $req['baseuom'];
                $baseuomval = $req['baseuomval'];
                for($i = 0; $i < sizeof($convuom); $i++){
                    $altUom = array(
                        'material'  => $req['itemcode'],
                        'altuom'    => $convuom[$i],
                        'convalt'   => $convalue[$i],
                        'baseuom'   => $baseuom[$i],
                        'convbase'  => $baseuomval[$i],
                        'createdon' => date('Y-m-d H:m:s'),
                        'createdby' => Auth::user()->email ?? Auth::user()->username
                    );
                    array_push($insertAltUom, $altUom);
                }
            }
            insertOrUpdate($insertAltUom,'t_material2');
            DB::commit();
            return Redirect::to("/master/item")->withSuccess('Item Master Updated');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/item")->withError($e->getMessage());
        }
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
