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
}
