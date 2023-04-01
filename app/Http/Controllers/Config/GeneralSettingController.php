<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class GeneralSettingController extends Controller
{
    public function index(){
        $data    = DB::table('general_setting')->where('setting_name', 'COMPANY_LOGO')->first();
        $theme   = DB::table('general_setting')->where('setting_name', 'APP_THEME')->first();
        $bgimage = DB::table('general_setting')->where('setting_name', 'APP_BGIMAGE')->first();
        $address = DB::table('general_setting')->where('setting_name', 'COMPANY_ADDRESS')->first();
        return view('config.generalsetting', ['complogo' => $data, 'theme' => $theme, 'bgimage' => $bgimage, 'address' => $address]);
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

    public function saveBgImage(Request $request){
        DB::beginTransaction();
        try{
            $companyLogo = $request->file('bgimage');
            $filename    = $companyLogo->getClientOriginalName();
            $filepath    = 'assets/img/'. $filename;  
            $companyLogo->move('assets/img/', $filename);  

            $check = DB::table('general_setting')->where('setting_name', 'APP_BGIMAGE')->first();
            if($check){
                DB::table('general_setting')->where('setting_name', 'APP_BGIMAGE')->update([
                    'setting_value' => $filepath
                ]);
            }else{
                DB::table('general_setting')->insert([
                    'setting_name'  => 'APP_BGIMAGE',
                    'setting_value' => $filepath,
                    'createdby'     => Auth::user()->username,
                    'createdon'     => getLocalDatabaseDateTime()
                ]);
            }
            DB::commit();
            return Redirect::to("/general/setting")->withSuccess('Application background image updated!');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/general/setting")->withError($e->getMessage());
        }
    }

    public function saveAppTheme(Request $request){
        DB::beginTransaction();
        try{
            $check = DB::table('general_setting')->where('setting_name', 'APP_THEME')->first();
            if($check){
                DB::table('general_setting')->where('setting_name', 'APP_THEME')->update([
                    'setting_value' => $request['apptheme']
                ]);
            }else{
                DB::table('general_setting')->insert([
                    'setting_name'  => 'APP_THEME',
                    'setting_value' => $request['apptheme'],
                    'createdby'     => Auth::user()->username,
                    'createdon'     => getLocalDatabaseDateTime()
                ]);
            }
            DB::commit();
            return Redirect::to("/general/setting")->withSuccess('Application Theme updated!');
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

    public function saveAddress(Request $request){
        DB::beginTransaction();
        try{
            $check = DB::table('general_setting')->where('setting_name', 'COMPANY_ADDRESS')->first();
            if($check){
                DB::table('general_setting')->where('setting_name', 'COMPANY_ADDRESS')->update([
                    'setting_value' => $request['companyAddress']
                ]);
            }else{
                DB::table('general_setting')->insert([
                    'setting_name'  => 'COMPANY_ADDRESS',
                    'setting_value' => $request['companyAddress'],
                    'createdby'     => Auth::user()->username,
                    'createdon'     => getLocalDatabaseDateTime()
                ]);
            }
            DB::commit();
            return Redirect::to("/general/setting")->withSuccess('Company Address Updated!');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/general/setting")->withError($e->getMessage());
        }
    }
}
