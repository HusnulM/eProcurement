<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class ChecklistKendaraanController extends Controller
{
    public function index(){
        $group1 = DB::table('md_checklist_kendaraan')->where('checklist_grp', 'A')->get();
        $group2 = DB::table('md_checklist_kendaraan')->where('checklist_grp', 'B')->get();
        $group3 = DB::table('md_checklist_kendaraan')->where('checklist_grp', 'C')->get();
        $group4 = DB::table('md_checklist_kendaraan')->where('checklist_grp', 'D')->get();
        return view('transaksi.pbj.checklistkendraan', 
            [
                'group1' => $group1,
                'group2' => $group2,
                'group3' => $group3,
                'group4' => $group4
            ]);
    }

    public function ViewdataCekList(){
        return view('transaksi.pbj.dataceklist');
    }

    public function ViewdataCekListTidakLayak(){
        return view('transaksi.pbj.ceklisttidaklayak');
    }

    public function detailCekList($id){
        $header = DB::table('v_checklist_kendaraan')->where('id', $id)->first(); 
        $group1 = DB::table('t_ck_administrasi')->where('no_checklist', $header->no_checklist)->get();
        $group2 = DB::table('t_ck_kelengkapan_kend')->where('no_checklist', $header->no_checklist)->get();
        $group3 = DB::table('t_ck_kondisi_kend')->where('no_checklist', $header->no_checklist)->get();
        $group4 = DB::table('t_ck_kondisi_ban')->where('no_checklist',  $header->no_checklist)->get();
        $attachments = DB::table('t_attachments')->where('doc_object','CKL')->where('doc_number', $header->no_checklist)->get();
        return view('transaksi.pbj.detailceklist', 
            [
                'header' => $header,
                'group1' => $group1,
                'group2' => $group2,
                'group3' => $group3,
                'group4' => $group4,
                'attachments'   => $attachments
            ]);
    }

    public function dataCekList(Request $request){
        if(isset($request->params)){
            $params = $request->params;        
            $whereClause = $params['sac'];
        }
        $query = DB::table('t_checklist_kendaraan')
                 ->orderBy('id');
        return DataTables::queryBuilder($query)->toJson();
    }

    public function dataCekListTidakLayak(Request $request){
        if(isset($request->params)){
            $params = $request->params;        
            $whereClause = $params['sac'];
        }
        $query = DB::table('t_checklist_kendaraan')->where('hasil_pemeriksaan','TIDAK LAYAK')
                 ->orderBy('id');
        return DataTables::queryBuilder($query)->toJson();
    }

    public function save(Request $req){
        // return $req;
        // t_checklist_kendaraan
        DB::beginTransaction();
        try{
            $tgl   = substr($req['tanggal_cek'], 8, 2);
            $bulan = substr($req['tanggal_cek'], 5, 2);
            $tahun = substr($req['tanggal_cek'], 0, 4);
            // return $tgl . ' - ' . $bulan . ' - ' . $tahun;
            $ptaNumber = generateCheckListNumber($tahun, $bulan);

            DB::table('t_checklist_kendaraan')->insert([
                'no_checklist'     => $ptaNumber,
                'tanggal_cek'      => $req['tanggal_cek'],
                'no_plat'          => $req['unitdesc'],
                'jenis_kendaraan'  => $req['jenis_kendaraan'],
                'nomor_rangka'     => $req['nomor_rangka'],
                'bahan_bakar'      => $req['bahan_bakar'],
                'tahun'            => $req['tahun'],
                'odometer'         => $req['odometer'],
                'nama_driver'      => $req['nama_driver'],
                'hasil_pemeriksaan'=> $req['hasil_pemeriksaan'],
                'createdon'        => getLocalDatabaseDateTime(),
                'createdby'        => Auth::user()->email ?? Auth::user()->username
            ]);

            // Cek list Administrasi
            $ck1_name     = $req['ckl_grp1_name'];
            $ck1_status   = $req['status_adm'];
            $ck1_expdate  = $req['masa_berlaku'];
            $ck1_jnsim    = $req['jenis_sim'];

            $exdate = '';
            $insertData = array();
            for($i = 0; $i < sizeof($ck1_name); $i++){
                if($ck1_expdate[$i] == null){
                    DB::table('t_ck_administrasi')->insert([
                        'no_checklist'    => $ptaNumber,
                        'ck_administrasi' => $ck1_name[$i],
                        'status_adm'      => $ck1_status[$i] ?? null,
                        'jenis_sim'       => $ck1_jnsim[$i] ?? null,
                        'createdon'       => getLocalDatabaseDateTime(),
                        'createdby'       => Auth::user()->email ?? Auth::user()->username
                    ]);
                }else{
                    $exdate = $ck1_expdate[$i];
                    DB::table('t_ck_administrasi')->insert([
                        'no_checklist'    => $ptaNumber,
                        'ck_administrasi' => $ck1_name[$i],
                        'status_adm'      => $ck1_status[$i] ?? null,
                        'masa_berlaku'    => $exdate,
                        'jenis_sim'       => $ck1_jnsim[$i] ?? null,
                        'createdon'       => getLocalDatabaseDateTime(),
                        'createdby'       => Auth::user()->email ?? Auth::user()->username
                    ]);
                }
            }
            // return $insertData;

            // Cek list Kelengkapan Kendaraan
            $ck2_name     = $req['ckl_grp2_name'];
            $ck2_status   = $req['grp2_ada_tidak'];
            $ck2_quantity = $req['quantity'];
            $ck2_kondisi  = $req['grp2_baik_rusak'];

            $insertData = array();
            for($i = 0; $i < sizeof($ck2_name); $i++){
                $data = array(
                    'no_checklist'       => $ptaNumber,
                    'nama_kelengkapan'   => $ck2_name[$i],
                    'status_kelengkapan' => $ck2_status[$i],
                    'jumlah'             => $ck2_quantity[$i] ?? 0,
                    'kondisi_kelengkapan'=> $ck2_kondisi[$i],
                    'createdon'          => getLocalDatabaseDateTime(),
                    'createdby'          => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $data);
            }
            insertOrUpdate($insertData,'t_ck_kelengkapan_kend');
            
            // Cek list Kondisi Kendaraan
            $ck3_name     = $req['ckl_grp3_name'];
            $ck3_status   = $req['grp3_ada_tidak'];
            $ck3_kondisi  = $req['grp3_baik_rusak'];
            $insertData = array();
            for($i = 0; $i < sizeof($ck3_name); $i++){
                $data = array(
                    'no_checklist'   => $ptaNumber,
                    'kondisi_cek'    => $ck3_name[$i],
                    'status_kondisi' => $ck3_status[$i],
                    'kondisi'        => $ck3_kondisi[$i],
                    'createdon'      => getLocalDatabaseDateTime(),
                    'createdby'      => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $data);
            }
            insertOrUpdate($insertData,'t_ck_kondisi_kend');

            // Cek list Kondisi Ban
            $ck4_name     = $req['ckl_grp4_name'];
            $ck4_status   = $req['grp4_ada_tidak'];
            $ck4_kondisi  = $req['grp4_baik_rusak'];
            $insertData = array();
            for($i = 0; $i < sizeof($ck4_name); $i++){
                $data = array(
                    'no_checklist'   => $ptaNumber,
                    'nama_kondisi'   => $ck4_name[$i],
                    'status_kondisi' => $ck4_status[$i],
                    'kondisi'        => $ck4_kondisi[$i],
                    'createdon'      => getLocalDatabaseDateTime(),
                    'createdby'      => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $data);
            }
            insertOrUpdate($insertData,'t_ck_kondisi_ban');

            //Insert Attachments | t_attachments
            if(isset($req['efile'])){
                $files = $req['efile'];
                $insertFiles = array();
    
                foreach ($files as $efile) {
                    $filename = $efile->getClientOriginalName();
                    $upfiles = array(
                        'doc_object' => 'CKL',
                        'doc_number' => $ptaNumber,
                        'efile'      => $filename,
                        'pathfile'   => '/files/CKL/'. $filename,
                        'createdon'  => getLocalDatabaseDateTime(),
                        'createdby'  => Auth::user()->username ?? Auth::user()->email
                    );
                    array_push($insertFiles, $upfiles);
    
                    // $efile->move(public_path().'/files/PBJ/', $filename);  
                    $efile->move('files/CKL/', $filename);  
                }
                if(sizeof($insertFiles) > 0){
                    insertOrUpdate($insertFiles,'t_attachments');
                }
            }

            DB::commit();
            return Redirect::to("/checklistkendaraan")->withSuccess('Data Cek List Kendaraan Berhasil dibuat dengan Nomor : '. $ptaNumber);
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/checklistkendaraan")->withError($e->getMessage());
        }
    }
}
