<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables, Auth, DB;
use Validator,Redirect,Response;
use App\Imports\MaterialImport;
use App\Models\Material;

use Excel;

class ItemMasterController extends Controller
{
    public function index(){
        return view('master.material.index');
    }

    public function upload(){
        return view('master.material.upload');
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
        return DataTables::queryBuilder($query)->setRowId('id')->toJson();
    }

    public function itemCategoryLists(Request $request){
        $params = $request->params;        
        $whereClause = $params['sac'];
        $query = DB::table('t_materialtype')->orderBy('id');
        return DataTables::queryBuilder($query)->setRowId('id')->toJson();
    }

    public function uomLists(Request $request){
        $params = $request->params;        
        $whereClause = $params['sac'];
        $query = DB::table('t_uom')->select('id','uom','uomdesc','createdby', 'createdon')->orderBy('uom');
        return DataTables::queryBuilder($query)->setRowId('id')->toJson();
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

    public function updateitemcategory(Request $req){
        DB::beginTransaction();
        try{
            $itemcate   = $req['itemcate'];
            $id         = $req['itemcatid'];
            DB::table('t_materialtype')->where('id', $id)->update([
                'mattypedesc'   => $itemcate
            ]);
            DB::commit();
            return Redirect::to("/master/item")->withSuccess('Item Category Updated');
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

    public function updateuom(Request $req){
        DB::beginTransaction();
        try{
            $uom     = $req['uom-code'];
            $uomdesc = $req['uom-desc'];
            $uomid   = $req['uom-id'];

            DB::table('t_uom')->where('id', $uomid)->update([
                'uom'       => $uom,
                'uomdesc'   => $uomdesc
            ]);
            
            DB::commit();
            return Redirect::to("/master/item")->withSuccess('Unit of measure updated');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/item")->withError($e->getMessage());
        }
    }

    public function deleteuom($id){
        DB::beginTransaction();
        try{
            DB::table('t_uom')->where('id', $id)->delete();
            DB::commit();
            return Redirect::to("/master/item")->withSuccess('Unit of measure deleted');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/item")->withError($e->getMessage());
        }
    }

    public function delete($id){
        DB::beginTransaction();
        try{
            $material = DB::table('t_material')->where('matuniqid', $id)->first();
            DB::table('t_material')->where('matuniqid', $id)->delete();
            DB::table('t_material2')->where('material', $material->material)->delete();
            DB::commit();
            return Redirect::to("/master/item")->withSuccess('Material deleted');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/item")->withError($e->getMessage());
        }
    }

    public function deleteItemCategory($id){
        DB::beginTransaction();
        try{
            $material = DB::table('t_material')->where('mattype', $id)->first();
            if($material){
                return Redirect::to("/master/item")->withSuccess('Cannot delete Item Category, Item category already use in material master');
            }else{
                DB::table('t_materialtype')->where('id', $id)->delete();
                DB::commit();
                return Redirect::to("/master/item")->withSuccess('Item Category deleted');
            }
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/item")->withError($e->getMessage());
        }
    }

    public function importMaterial(Request $request){
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');

        // membuat nama file unik
        $nama_file = $file->hashName();        

        $destinationPath = 'excel/';
        $file->move($destinationPath,$file->getClientOriginalName());

        config(['excel.import.startRow' => 2]);
        // import data
        $import = Excel::import(new MaterialImport(), 'excel/'.$file->getClientOriginalName());

        //remove from server
		unlink('excel/'.$file->getClientOriginalName());

        if($import) {
            return Redirect::to("/master/item")->withSuccess('Data Material Berhasil di Upload');
        } else {
            return Redirect::to("/master/item")->withError('Error');
        }
    }
}
