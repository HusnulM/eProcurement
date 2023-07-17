<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Imports\VendorImport;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;
use Excel;

class VendorMasterController extends Controller
{
    public function index(){
        return view('master.vendor.index');
    }

    public function create(){
        return view('master.vendor.create');
    }

    public function upload(){
        return view('master.vendor.upload');
    }

    public function edit($code){
        $vendor = DB::table('t_vendor')->where('vendor_code', $code)->first();
        return view('master.vendor.edit', ['vendor' => $vendor]);
    }

    public function vendorLists(Request $request){
        $params = $request->params;        
        $whereClause = $params['sac'];
        $query = DB::table('v_vendor')->orderBy('vendor_code');
        return DataTables::queryBuilder($query)->toJson();
    }

    public function findVendor(Request $request){
        // $url    = parse_url($_SERVER['REQUEST_URI']);
        // $search = $url['query'];
        // $search = str_replace("searchName=","",$search);

        $query['data'] = DB::table('t_vendor')->where('vendor_name', 'like', '%'. $request->search . '%')->get();

        // return \Response::json($query);
        return $query;
        // return $this->successResponse('OK', $query);
    }

    public function save(Request $req){
        DB::beginTransaction();
        try{
            $kodeVendor = generateVendorCode();
            $insertData = array();
            $data = array(
                'vendor_code'    => $kodeVendor,
                'vendor_name'    => $req['vendorname'],
                'vendor_pt'      => $req['vendo_pt'],
                'vendor_profil'  => $req['vendor_profil'],
                'vendor_address' => $req['address'],
                'vendor_telp'    => $req['telp'],
                'vendor_email'   => $req['email'],
                'bank_holder'    => $req['bank_holder'],
                'bank'           => $req['nama_bank'],
                'no_rek'         => $req['no_rek'],
                'catatan'        => $req['catatan'],
                'vendor_id'      => $req['vendor_id'] ?? 0,
                'createdon'      => date('Y-m-d H:m:s'),
                'createdby'      => Auth::user()->email ?? Auth::user()->username
            );
            array_push($insertData, $data);
            insertOrUpdate($insertData,'t_vendor');
            DB::commit();
            return Redirect::to("/master/vendor")->withSuccess('Vendor created');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/vendor")->withError($e->getMessage());
        }
    }

    public function update(Request $req){
        DB::beginTransaction();
        try{
            DB::table('t_vendor')->where('vendor_code', $req['vendorcode'])->update([
                'vendor_name'    => $req['vendorname'],
                'vendor_pt'      => $req['vendo_pt'],
                'vendor_profil'  => $req['vendor_profil'],
                'vendor_address' => $req['address'],
                'vendor_telp'    => $req['telp'],
                'vendor_email'   => $req['email'],
                'bank_holder'    => $req['bank_holder'],
                'bank'           => $req['nama_bank'],
                'no_rek'         => $req['no_rek'],
                'catatan'        => $req['catatan'],
                'vendor_id'      => $req['vendor_id'] ?? 0,
            ]);
            DB::commit();
            return Redirect::to("/master/vendor")->withSuccess('Vendor updated');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/vendor")->withError($e->getMessage());
        }
    }

    public function delete($code){
        DB::beginTransaction();
        try{
            DB::table('t_vendor')->where('vendor_code', $code)->delete();
            DB::commit();
            return Redirect::to("/master/vendor")->withSuccess('Vendor deleted');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/vendor")->withError($e->getMessage());
        }
    }

    public function importVendor(Request $request){
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
        $import = Excel::import(new VendorImport(), 'excel/'.$file->getClientOriginalName());

        //remove from server
		unlink('excel/'.$file->getClientOriginalName());

        if($import) {
            return Redirect::to("/master/vendor")->withSuccess('Data Vendor Berhasil di Upload');
        } else {
            return Redirect::to("/master/vendor")->withError('Error');
        }
    }
}
