<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class ObjectAuthController extends Controller
{
    public function index(){
        $data = DB::table('object_auth')->get();
        
        return view('config.objectauth.index', ['obauth' => $data]);
        // return "Document Types";
        // return view('master.doctype.index');
    }

    public function save(Request $req){
        DB::beginTransaction();
        try{
            DB::table('object_auth')->insert([
                'object_name'        => $req['objname'],
                'object_description' => $req['objdesc'],
                'createdon'          => date('Y-m-d H:m:s'),
                'createdby'          => Auth::user()->email ?? Auth::user()->username
            ]);
            DB::commit();
            return Redirect::to("/config/objectauth")->withSuccess('New Object authorization Created');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/objectauth")->withError($e->getMessage());
        }
    }

    public function update(Request $req){
        DB::beginTransaction();
        try{
            DB::table('object_auth')->where('object_name', $req['objname'])->update([
                'object_description'   => $req['objdesc'],
            ]);
            DB::commit();
            return Redirect::to("/config/objectauth")->withSuccess('Object authorization Updated');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/objectauth")->withError($e->getMessage());
        }
    }

    public function delete($id){
        DB::beginTransaction();
        try{
            DB::table('object_auth')->where('object_name', $id)->delete();
            DB::commit();
            return Redirect::to("/config/objectauth")->withSuccess('Object authorization Deleted');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/objectauth")->withError($e->getMessage());
        }
    }
}
