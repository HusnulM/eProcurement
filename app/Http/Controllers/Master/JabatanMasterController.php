<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class JabatanMasterController extends Controller
{
    public function index(){
        return view('master.jabatan.index');
    }

    public function jabatanLists(Request $request){
        $params = $request->params;        
        $whereClause = $params['sac'];
        $query = DB::table('t_jabatan')->orderBy('id');
        return DataTables::queryBuilder($query)->toJson();
    }

    public function save(Request $req){
        // return $req;
        DB::beginTransaction();
        try{
            $dept   = $req['jabatan'];

            $insertData = array();
            for($i = 0; $i < sizeof($dept); $i++){
                $data = array(
                    'jabatan'       => $dept[$i],
                    'createdon'     => date('Y-m-d H:m:s'),
                    'createdby'     => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $data);
            }
            insertOrUpdate($insertData,'t_jabatan');
            DB::commit();
            return Redirect::to("/master/jabatan")->withSuccess('Jabatan Berhasil dibuat');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/jabatan")->withError($e->getMessage());
        }
    }

    public function update(Request $req){
        DB::beginTransaction();
        try{
            DB::table('t_jabatan')->where('id', $req['jabatanid'])->update([
                'jabatan' => $req['jabatanname']
            ]);
            DB::commit();
            return Redirect::to("/master/jabatan")->withSuccess('Data Jabatan Berhasil di update');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/jabatan")->withError($e->getMessage());
        }
    }

    public function delete($id){
        DB::beginTransaction();
        try{
            DB::table('t_jabatan')->where('id', $id)->delete();
            DB::commit();
            return Redirect::to("/master/jabatan")->withSuccess('Jabatan di Hapus');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/jabatan")->withError($e->getMessage());
        }
    }
}
