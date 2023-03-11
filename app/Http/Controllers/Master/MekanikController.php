<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class MekanikController extends Controller
{
    public function index(){
        return view('master.mekanik.index');
    }

    public function findMekanik(Request $request){
        $query['data'] = DB::table('t_mekanik')->where('nama', 'like', '%'. $request->search . '%')->get();

        // return \Response::json($query);
        return $query;
    }

    public function mekanikLists(Request $request){
        $params = $request->params;        
        $whereClause = $params['sac'];
        $query = DB::table('t_mekanik')->orderBy('id');
        return DataTables::queryBuilder($query)->toJson();
    }

    public function save(Request $req){
        // return $req;
        DB::beginTransaction();
        try{
            $nama = $req['nama'];

            $insertData = array();
            for($i = 0; $i < sizeof($nama); $i++){
                $data = array(
                    'nama'          => $nama[$i],
                    'createdon'     => date('Y-m-d H:m:s'),
                    'createdby'     => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $data);
            }
            insertOrUpdate($insertData,'t_mekanik');
            DB::commit();
            return Redirect::to("/master/mekanik")->withSuccess('Master Mekanik Berhasil dibuat');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/mekanik")->withError($e->getMessage());
        }
    }

    public function update(Request $req){
        DB::beginTransaction();
        try{
            DB::table('t_mekanik')->where('id', $req['mkid'])->update([
                'nama' => $req['nama']
            ]);
            DB::commit();
            return Redirect::to("/master/mekanik")->withSuccess('Data Mekanik Berhasil di update');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/mekanik")->withError($e->getMessage());
        }
    }

    public function delete($id){
        DB::beginTransaction();
        try{
            DB::table('t_mekanik')->where('id', $id)->delete();
            DB::commit();
            return Redirect::to("/master/mekanik")->withSuccess('Data Mekanik Berhasil di Hapus');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/mekanik")->withError($e->getMessage());
        }
    }
}
