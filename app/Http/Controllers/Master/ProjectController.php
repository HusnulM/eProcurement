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
        $approver = DB::table('t_master_approval')->get();
        return view('master.project.index', ['approver' => $approver]);
    }

    public function save(Request $req){
        DB::beginTransaction();
        try{
            $kodeProject    = $req['kodeProyek'];
            $namaProject    = $req['namaProyek'];
            $nilaiProject   = $req['nilaiProyek'];
            $projectManager = $req['projectManager'];
            $managerLapangan = $req['managerLapangan'];

            $insertData = array();
            $data = array(
                'kode_project'    => $kodeProject,
                'nama_project'    => $namaProject,
                'nilai_project'   => $nilaiProject,
                'project_manager' => $projectManager,
                'manager_lapangan' => $managerLapangan,
                'createdon'       => getLocalDatabaseDateTime(),
                'createdby'       => Auth::user()->email ?? Auth::user()->username
            );
            array_push($insertData, $data);

            insertOrUpdate($insertData,'t_projects');
            DB::commit();
            return Redirect::to("/master/project")->withSuccess('Master Proyek Berhasil dibuat');

        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/project")->withError($e->getMessage());
        }
    }

    public function update(Request $req){
        DB::beginTransaction();
        try{
            $idProject      = $req['idProject'];
            $kodeProject    = $req['kodeProyek'];
            $namaProject    = $req['namaProyek'];
            $nilaiProject   = $req['nilaiProyek'];
            $projectManager = $req['projectManager'];
            $managerLapangan = $req['managerLapangan'];

            DB::table('t_projects')->where('id', $idProject)->update([
                'nama_project'    => $namaProject,
                'nilai_project'   => $nilaiProject,
                'project_manager' => $projectManager,
                'manager_lapangan' => $managerLapangan,
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
        $query = DB::table('t_projects')->orderBy('id');
        return DataTables::queryBuilder($query)->toJson();
    }

    public function findproject(Request $request){
        $query['data'] = DB::table('t_projects')->where('nama_project', 'like', '%'. $request->search . '%')->get();

        // return \Response::json($query);
        return $query;
    }
}
