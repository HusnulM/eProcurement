<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;


class ItemMasterController extends Controller
{
    public function index(){
        return view('master.material.index');
    }

    public function create(){
        return view('master.material.create');
    }

    public function itemLists(Request $request){
        $params = $request->params;        
        $whereClause = $params['sac'];
        $query = DB::table('t_material')->orderBy('material');
        return DataTables::queryBuilder($query)->toJson();
    }
}
