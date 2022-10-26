<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class WarehouseController extends Controller
{
    public function index(){
        return view('master.warehouse.index');
    }

    public function findWhs(Request $request){
        $query['data'] = DB::table('t_warehouse')->where('whsname', 'like', '%'. $request->search . '%')->get();

        // return \Response::json($query);
        return $query;
    }

    public function warehouseLists(Request $request){
        $params = $request->params;        
        $whereClause = $params['sac'];
        $query = DB::table('t_warehouse')->orderBy('id');
        return DataTables::queryBuilder($query)->toJson();
    }

    public function save(Request $req){
        // return $req;
        DB::beginTransaction();
        try{
            $whscode = $req['kodegudang'];
            $whsname = $req['namagudang'];

            $insertData = array();
            for($i = 0; $i < sizeof($whscode); $i++){
                $data = array(
                    'whscode'       => $whscode[$i],
                    'whsname'       => $whsname[$i],
                    'createdon'     => date('Y-m-d H:m:s'),
                    'createdby'     => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $data);
            }
            insertOrUpdate($insertData,'t_warehouse');
            DB::commit();
            return Redirect::to("/master/warehouse")->withSuccess('Master Gudang Berhasil dibuat');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/warehouse")->withError($e->getMessage());
        }
    }

    public function update(Request $req){
        DB::beginTransaction();
        try{
            DB::table('t_warehouse')->where('id', $req['whsid'])->update([
                'whsname' => $req['whsname']
            ]);
            DB::commit();
            return Redirect::to("/master/warehouse")->withSuccess('Data Gudang Berhasil di update');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/warehouse")->withError($e->getMessage());
        }
    }

    public function delete($id){
        DB::beginTransaction();
        try{
            DB::table('t_warehouse')->where('id', $id)->delete();
            DB::commit();
            return Redirect::to("/master/warehouse")->withSuccess('Data Gudang Berhasil di Hapus');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/warehouse")->withError($e->getMessage());
        }
    }
}
