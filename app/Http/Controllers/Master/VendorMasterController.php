<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class VendorMasterController extends Controller
{
    public function index(){
        return view('master.vendor.index');
    }

    public function create(){
        return view('master.vendor.create');
    }

    public function vendorLists(Request $request){
        $params = $request->params;        
        $whereClause = $params['sac'];
        $query = DB::table('t_vendor')->orderBy('vendor_code');
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
            $insertData = array();
            $data = array(
                'vendor_code'    => $req['vendorcode'],
                'vendor_name'    => $req['vendorname'],
                'vendor_address' => $req['address'],
                'vendor_telp'    => $req['telp'],
                'vendor_email'   => $req['email'],
                'contact_person' => $req['contactperson'],
                'createdon'      => date('Y-m-d H:m:s'),
                'createdby'      => Auth::user()->email ?? Auth::user()->username
            );
            array_push($insertData, $data);
            insertOrUpdate($insertData,'t_vendor');
            DB::commit();
            return Redirect::to("/master/vendor")->withSuccess('Vendor Berhasil dibuat');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/vendor")->withError($e->getMessage());
        }
    }
}
