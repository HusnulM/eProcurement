<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Imports\KendaraanImport;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables, Auth, DB;
use Validator,Redirect,Response;
use Excel;


class KendaraanController extends Controller
{
    public function index(){
        return view('master.kendaraan.index');
    }

    public function upload(){
        return view('master.kendaraan.upload');
    }

    public function findKendaraan(Request $request){
        $query['data'] = DB::table('t_kendaraan')
                ->select('no_kendaraan', 'model_kendaraan', 'engine_sn', 'chassis_sn', 'engine_model','last_km',
                         'last_hm', 'tahun', 'no_rangka', 'bahan_bakar', 'odometer', 'layak_tidak', 'createdby', 'createdon')
                ->where('no_kendaraan', 'like', '%'. $request->search . '%')
                ->orWhere('model_kendaraan', 'like', '%'. $request->search . '%')
                ->orWhere('engine_sn', 'like', '%'. $request->search . '%')
                ->orWhere('chassis_sn', 'like', '%'. $request->search . '%')
                ->orWhere('engine_model', 'like', '%'. $request->search . '%')
                ->get();

        // return \Response::json($query);
        return $query;
    }

    public function kendaraanLists(Request $request){
        $params = $request->params;        
        $whereClause = $params['sac'];
        $query = DB::table('t_kendaraan')->orderBy('id');
        return DataTables::queryBuilder($query)->toJson();
    }

    public function save(Request $req){
        // return $req;
        DB::beginTransaction();
        try{
            DB::table('t_kendaraan')->insert([
                'no_kendaraan'    => $req['nokend'],
                'model_kendaraan' => $req['model_kendaraan'],
                'engine_sn'       => $req['engine_sn'],
                'chassis_sn'      => $req['chassis_sn'],
                'engine_model'    => $req['engine_model'],
                'last_km'         => $req['last_km'],
                'last_hm'         => $req['last_hm'],
                'odometer'       => $req['odometer'],
                'tahun'           => $req['tahun'],
                'no_rangka'       => $req['no_rangka'],
                'bahan_bakar'     => $req['bahan_bakar'],
                'layak_tidak'     => $req['status_kend'],
                'createdby'       => Auth::user()->email ?? Auth::user()->username,
                'createdon'       => getLocalDatabaseDateTime(),
            ]);

            DB::commit();
            return Redirect::to("/master/kendaraan")->withSuccess('Master Kendaraan Berhasil dibuat');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/kendaraan")->withError($e->getMessage());
        }
    }

    public function update(Request $req){
        // return $req;
        DB::beginTransaction();
        try{
            DB::table('t_kendaraan')->where('id', $req['idkend'])->update([
                'no_kendaraan'    => $req['nokend'],
                'model_kendaraan' => $req['model_kendaraan'],
                'engine_sn'       => $req['engine_sn'],
                'chassis_sn'      => $req['chassis_sn'],
                'engine_model'    => $req['engine_model'],
                'last_km'         => $req['last_km'],
                'last_hm'         => $req['last_hm'],
                'odometer'        => $req['odometer'],
                'tahun'           => $req['tahun'],
                'no_rangka'       => $req['no_rangka'],
                'bahan_bakar'     => $req['bahan_bakar'],
                'layak_tidak'     => $req['status_kend'],
            ]);

            DB::commit();
            return Redirect::to("/master/kendaraan")->withSuccess('Master Kendaraan Berhasil update');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/kendaraan")->withError($e->getMessage());
        }
    }

    public function delete($id){
        DB::beginTransaction();
        try{
            DB::table('t_kendaraan')->where('id', $id)->delete();
            DB::commit();
            return Redirect::to("/master/kendaraan")->withSuccess('Data kendaraan Berhasil di Hapus');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/kendaraan")->withError($e->getMessage());
        }
    }

    public function importKendaraan(Request $request){
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
        $import = Excel::import(new KendaraanImport(), 'excel/'.$file->getClientOriginalName());

        //remove from server
		unlink('excel/'.$file->getClientOriginalName());

        if($import) {
            return Redirect::to("/master/kendaraan")->withSuccess('Data Kendaraan Berhasil di Upload');
        } else {
            return Redirect::to("/master/kendaraan")->withError('Error');
        }
    }
}
