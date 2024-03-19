<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB, Validator, Redirect, Response, Excel;

class ApprovalMasterController extends Controller
{
    var $table = 't_master_approval';

    public function index()
    {
        $jabatan = DB::table('t_jabatan')->get();
        return view('master.approval.index', ['jabatan' => $jabatan]);
    }

    public function listApproval(Request $request)
    {
        $params = $request->params;
        $whereClause = $params['sac'];
        $query = DB::table('t_master_approval');
        return DataTables::queryBuilder($query)->setRowId('id')->toJson();
    }

    public function save(Request $req)
    {
        DB::beginTransaction();
        try{
            $nama    = $req['nama'];
            $jabatan = $req['jabatan'];

            $insertData = array();
            for($i = 0; $i < sizeof($nama); $i++){
                $menus = array(
                    'nama'       => $nama[$i],
                    'jabatan'    => $jabatan[$i],
                    'createdon'  => getLocalDatabaseDateTime(),
                    'createdby'  => Auth::user()->username
                );
                array_push($insertData, $menus);
            }
            insertOrUpdate($insertData,'t_master_approval');
            DB::commit();
            return Redirect::to("/master/approval")->withSuccess('Master Approval Berhasil ditambahkan');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/approval")->withError($e->getMessage());
        }
    }

    public function update(Request $req)
    {
        DB::beginTransaction();
        try{
            $mkid    = $req['mkid'];
            $nama    = $req['nama'];
            $jabatan = $req['jabatan'];

            DB::table('t_master_approval')->where('id', $mkid)->update([
                'nama'    => $nama,
                'jabatan' => $jabatan
            ]);

            DB::commit();
            return Redirect::to("/master/approval")->withSuccess('Master Approval Berhasil diupdate');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/approval")->withError($e->getMessage());
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try{
            DB::table('t_master_approval')->where('id', $id)->delete();

            DB::commit();
            return Redirect::to("/master/approval")->withSuccess('Master Approval Berhasil dihapus');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/approval")->withError($e->getMessage());
        }
    }
}
