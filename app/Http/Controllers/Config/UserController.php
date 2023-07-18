<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class UserController extends Controller
{
    public function index(){
        return view('config.users.index');
    }

    public function account(){
        return view('config.users.account');
    }

    public function create(){
        $jabatan = DB::table('t_jabatan')->get();
        $departm = DB::table('t_department')->get();
        return view('config.users.create', ['jabatan' => $jabatan, 'department' => $departm]);
    }

    public function objectauth($userid){
        $datauser = DB::table('users')->where('id', $userid)->first();
        $objauth  = DB::table('object_auth')->get();
        $uobjauth = DB::table('v_user_obj_auth')->where('userid', $userid)->get();
        return view('config.users.objectauth', ['datauser' => $datauser, 'objauth' => $objauth, 'uobjauth' => $uobjauth]); 
    }

    public function edit($id){
        $data = DB::table('users')->where('id', $id)->first();
        $jabatan = DB::table('t_jabatan')->get();
        $departm = DB::table('t_department')->get();
        $ujabatan = DB::table('t_jabatan')->where('id', $data->jabatanid)->first();
        $udepartm = DB::table('t_department')->where('deptid',$data->deptid)->first();
        // dd($udepartm);
        return view('config.users.edit', 
            [
                'datauser'    => $data, 
                'jabatan'     => $jabatan, 
                'department'  => $departm,
                'ujabatan'    => $ujabatan, 
                'udepartm'    => $udepartm
            ]);
    }

    public function save(Request $request){
        // return $request;
        $validated = $request->validate([
            'email'    => 'required|unique:users|max:255',
            'name'     => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);

        $options = [
            'cost' => 12,
        ];
        $password = password_hash($request['password'], PASSWORD_BCRYPT, $options);

        $output = array();

        DB::beginTransaction();
        try{
            $esignfile = $request->file('esignfile');
            if($esignfile){
                $filename  = $esignfile->getClientOriginalName();
                $esignpath = 'storage/files/e_signature/'. $filename;  
            }
            // public_path().'/files/e_signature/', $filename;
            

            DB::table('users')->insert([
                'name'        => $request['name'],
                'email'       => $request['email'],
                'username'    => $request['username'],
                'password'    => $password,
                's_signfile'  => $esignpath ?? null,
                'deptid'      => $request['department'],
                'jabatanid'   => $request['jabatan'],
                'created_at'  => date('Y-m-d H:m:s'),
                'createdby'   => Auth::user()->email ?? Auth::user()->username
            ]);

            if($esignfile){
                $esignfile->move('storage/files/e_signature/', $filename);  
            }

            DB::commit();
            return Redirect::to("/config/users")->withSuccess('New user created');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/users")->withError($e->getMessage());
        }
    }

    public function update(Request $request){
        $validated = $request->validate([
            'email'    => 'required|max:255',
            'name'     => 'required',
            'username' => 'required',
        ]);

        $oldUserData = DB::table('users')->where('id',$request['iduser'])->first();
        
        DB::beginTransaction();
        try{
            if($request['password'] != null || $request['password'] != ""){
                $options = [
                    'cost' => 12,
                ];
                $password = password_hash($request['password'], PASSWORD_BCRYPT, $options);
        
                $output = array();
    
                DB::table('users')->where('id',$request['iduser'])->update([
                    'name'        => $request['name'],
                    'email'       => $request['email'],
                    'username'    => $request['username'],
                    'password'    => $password,
                    'deptid'      => $request['department'],
                    'jabatanid'   => $request['jabatan']
                ]);
            }else{
                DB::table('users')->where('id',$request['iduser'])->update([
                    'name'        => $request['name'],
                    'email'       => $request['email'],
                    'username'    => $request['username'],
                    'deptid'      => $request['department'],
                    'jabatanid'   => $request['jabatan']
                ]);
            }

            if($oldUserData->email != $request['email']){
                DB::table('t_po01')->where('createdby', $oldUserData->email)
                ->update([
                    'createdby' => $request['email']
                ]);

                DB::table('t_po02')->where('createdby', $oldUserData->email)
                ->update([
                    'createdby' => $request['email']
                ]);

                DB::table('t_pr01')->where('createdby', $oldUserData->email)
                ->update([
                    'createdby' => $request['email']
                ]);

                DB::table('t_pr02')->where('createdby', $oldUserData->email)
                ->update([
                    'createdby' => $request['email']
                ]);

                DB::table('t_pbj01')->where('createdby', $oldUserData->email)
                ->update([
                    'createdby' => $request['email']
                ]);

                DB::table('t_pbj02')->where('createdby', $oldUserData->email)
                ->update([
                    'createdby' => $request['email']
                ]);

                DB::table('t_wo01')->where('createdby', $oldUserData->email)
                ->update([
                    'createdby' => $request['email']
                ]);

                DB::table('t_wo02')->where('createdby', $oldUserData->email)
                ->update([
                    'createdby' => $request['email']
                ]);
            }

            $esignfile = $request->file('esignfile');
            if($esignfile){
                $filename  = $esignfile->getClientOriginalName();
                $esignpath = 'storage/files/e_signature/'. $filename;  

                DB::table('users')->where('id',$request['iduser'])->update([
                    's_signfile'     => $esignpath,
                ]);

                $esignfile->move('storage/files/e_signature/', $filename);  
            }
            
            DB::commit();
            return Redirect::to("/config/users")->withSuccess('User updated');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/users")->withError($e->getMessage());
        }
    }

    public function accountSave(Request $request){
        $validated = $request->validate([
            'email'    => 'required|max:255',
            'name'     => 'required',
            'username' => 'required',
        ]);

        DB::beginTransaction();
        try{
            if($request['password'] != null || $request['password'] != ""){
                $options = [
                    'cost' => 12,
                ];
                $password = password_hash($request['password'], PASSWORD_BCRYPT, $options);
        
                $output = array();
    
                DB::table('users')->where('id',$request['iduser'])->update([
                    'name'        => $request['name'],
                    'email'       => $request['email'],
                    'username'    => $request['username'],
                    'password'    => $password
                ]);
            }else{
                DB::table('users')->where('id',$request['iduser'])->update([
                    'name'        => $request['name'],
                    'email'       => $request['email'],
                    'username'    => $request['username']
                ]);
            }

            $esignfile = $request->file('esignfile');
            if($esignfile){
                $filename  = $esignfile->getClientOriginalName();
                $esignpath = 'storage/files/e_signature/'. $filename;  

                DB::table('users')->where('id',$request['iduser'])->update([
                    's_signfile'     => $esignpath,
                ]);

                $esignfile->move('storage/files/e_signature/', $filename);  
            }
            
            DB::commit();
            return Redirect::to("/user/account")->withSuccess('User Account updated');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/user/account")->withError($e->getMessage());
        }
    }

    public function saveObjectauth(Request $req){
        DB::beginTransaction();
        try{
            $objname  = $req['objauth'];
            $objval   = $req['objval'];

            $insertData = array();
            for($i = 0; $i < sizeof($objname); $i++){
                $menus = array(
                    'userid'        => $req['userid'],
                    'object_name'   => $objname[$i],
                    'object_val'    => $objval[$i],
                    'createdon'     => date('Y-m-d H:m:s'),
                    'createdby'     => Auth::user()->username ?? Auth::user()->email
                );
                array_push($insertData, $menus);
            }
            insertOrUpdate($insertData,'user_object_auth');
            DB::commit();

            // DB::table('user_object_auth')->where('id', $id)->delete();

            DB::commit();
            return Redirect::to("/config/users/objectauth/".$req['userid'])->withSuccess('User Object Authorization Added');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/users/objectauth/".$req['userid'])->withError($e->getMessage());
        }
    }

    public function delete($id){
        DB::beginTransaction();
        try{
            $checkSysAdm = DB::table('users')->where('id', $id)->first();
            if($checkSysAdm->username === "sys-admin"){
                return Redirect::to("/config/users")->withError('User sys-admin Tidak bisa dihapus!');
            }else{
                DB::table('users')->where('id', $id)->delete();    
                DB::commit();
            }
            return Redirect::to("/config/users")->withSuccess('User deleted');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/users")->withError($e->getMessage());
        }
    }

    public function deleteObjectauth($uid, $objname){
        DB::beginTransaction();
        try{
            DB::table('user_object_auth')
            ->where('userid', $uid)
            ->where('object_name', $objname)
            ->delete();

            DB::commit();
            return Redirect::to("/config/users/objectauth/".$uid)->withSuccess('User Object Authorization deleted');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/config/users/objectauth/".$uid)->withError($e->getMessage());
        }
    }

    public function userlist(Request $request){
        $params = $request->params;        
        $whereClause = $params['sac'];
        $query = DB::table('v_users')->orderBy('id');
        return DataTables::queryBuilder($query)->toJson();
    }
}
