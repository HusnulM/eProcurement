<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables, Auth, DB;
use Validator,Redirect,Response;
use Excel;

class ProjectController extends Controller
{
    public function index(){
        return view('master.project.index');
    }

    public function save(Request $req){
        DB::beginTransaction();
        try{
            $kodeProject = $req['kodeproject'];
            $namaProject = $req['namaproject'];
            $insertData = array();
            for($i = 0; $i < sizeof($kodeProject); $i++){
                $data = array(
                    'kode_project'  => $kodeProject[$i],
                    'nama_project'  => $namaProject[$i],
                    'createdon'     => getLocalDatabaseDateTime(),
                    'createdby'     => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $data);
            }
            insertOrUpdate($insertData,'t_projects');
            DB::commit();
            return Redirect::to("/master/project")->withSuccess('Master Project Berhasil dibuat');

        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/project")->withError($e->getMessage());
        }
    }

    public function update(Request $req){
        DB::beginTransaction();
        try{
            $idProject   = $req['idProject'];
            $kodeProject = $req['kodeproject'];
            $namaProject = $req['namaproject'];
            
            DB::table('t_projects')->where('idproject', $idProject)->update([
                'nama_project' => $namaProject
            ]);

            DB::commit();
            return Redirect::to("/master/project")->withSuccess('Master Project Berhasil di update');

        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/project")->withError($e->getMessage());
        }
    }

    public function delete($idProject){
        DB::beginTransaction();
        try{
            DB::table('t_projects')->where('idproject', $idProject)->delete();

            DB::commit();
            return Redirect::to("/master/project")->withSuccess('Master Project Berhasil dihapus');

        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/project")->withError($e->getMessage());
        }
    }

    public function projectlist(Request $request){
        $params = $request->params;        
        $whereClause = $params['sac'];
        $query = DB::table('t_projects')->orderBy('idproject');
        return DataTables::queryBuilder($query)->toJson();
    }

    public function findproject(Request $request){
        $query['data'] = DB::table('t_projects')->where('nama_project', 'like', '%'. $request->search . '%')->get();

        // return \Response::json($query);
        return $query;
    }
}
