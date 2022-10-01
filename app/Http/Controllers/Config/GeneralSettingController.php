<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class GeneralSettingController extends Controller
{
    public function index(){
        $data   = DB::table('general_setting')->where('setting_name', 'COMPANY_LOGO')->first();
        $ipdapi = DB::table('general_setting')->where('setting_name', 'IPD_MODEL_API')->first();
        return view('config.generalsetting', ['complogo' => $data, 'ipdapi' => $ipdapi]);
    }

    public function save(Request $request){
        DB::beginTransaction();
        try{
            $companyLogo = $request->file('companylogo');
            $filename    = $companyLogo->getClientOriginalName();
            $filepath    = 'storage/files/companylogo/'. $filename;  
            $companyLogo->move('storage/files/companylogo/', $filename);  

            $check = DB::table('general_setting')->where('setting_name', 'COMPANY_LOGO')->first();
            if($check){
                DB::table('general_setting')->where('setting_name', 'COMPANY_LOGO')->update([
                    'setting_value' => $filepath
                ]);
            }else{
                DB::table('general_setting')->insert([
                    'setting_name'  => 'COMPANY_LOGO',
                    'setting_value' => $filepath,
                    'createdby'     => Auth::user()->username,
                    'createdon'     => getLocalDatabaseDateTime()
                ]);
            }
            DB::commit();
            return Redirect::to("/general/setting")->withSuccess('Company Logo Saved!');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/general/setting")->withError($e->getMessage());
        }
    }

    public function saveipdapid(Request $request){
        DB::beginTransaction();
        try{
            $check = DB::table('general_setting')->where('setting_name', 'IPD_MODEL_API')->first();
            if($check){
                DB::table('general_setting')->where('setting_name', 'IPD_MODEL_API')->update([
                    'setting_value' => $request['ipdapi']
                ]);
            }else{
                DB::table('general_setting')->insert([
                    'setting_name'  => 'IPD_MODEL_API',
                    'setting_value' => $request['ipdapi'],
                    'createdby'     => Auth::user()->username,
                    'createdon'     => getLocalDatabaseDateTime()
                ]);
            }
            DB::commit();
            return Redirect::to("/general/setting")->withSuccess('IPD API Saved!');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/general/setting")->withError($e->getMessage());
        }
    }
}
