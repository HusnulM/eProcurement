<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class InventoryMovementController extends Controller
{
    public function index(){
        return view('transaksi.movement.transfer');
    }

    public function save(Request $req){
        // return $req;
        DB::beginTransaction();
        try{
            $tgl   = substr($req['grdate'], 8, 2);
            $bulan = substr($req['grdate'], 5, 2);
            $tahun = substr($req['grdate'], 0, 4);
            // return $tgl . ' - ' . $bulan . ' - ' . $tahun;
            $ptaNumber = generateTransferNumber($tahun, $bulan);

            DB::table('t_inv01')->insert([
                'docnum'            => $ptaNumber,
                'docyear'           => $tahun,
                'docdate'           => $req['grdate'],
                'postdate'          => $req['grdate'],
                'received_by'       => $req['recipient'],
                'movement_code'     => '301',
                'remark'            => $req['remark'],
                'createdon'         => date('Y-m-d H:m:s'),
                'createdby'         => Auth::user()->email ?? Auth::user()->username
            ]);

            $parts       = $req['parts'];
            $partdsc     = $req['partdesc'];
            $quantity    = $req['quantity'];
            $uom         = $req['uoms'];
            $whscode     = $req['whscode'];
            $whscode2    = $req['whscodeto'];

            $insertData = array();
            $count = 0;

            for($i = 0; $i < sizeof($parts); $i++){
                $qty    = $quantity[$i];
                $qty    = str_replace(',','',$qty);
                $count  = $count + 1;

                $latestStock = DB::table('t_inv_stock')
                               ->where('material', $parts[$i])
                               ->where('whscode', $whscode)->first();
                if($latestStock){
                    if($latestStock->quantity < $qty){
                        DB::rollBack();
                        return Redirect::to("/logistic/transfer")->withError('Stock Tidak Mencukupi untuk part : '. $parts[$i]);
                    }else{

                        DB::select('call spTransferMaterialWithBatchFIFO(
                            "'. $parts[$i] .'",
                            "'. $whscode .'",
                            "'. $whscode2 .'",
                            "'. $qty .'",
                            "'. $ptaNumber .'",
                            "'. date('Y') .'",
                            "301",
                            "'. $partdsc[$i] .'",
                            "'. $uom[$i] .'",
                            "-",
                            "'. null .'",
                            "'. 0 .'",
                            "'. Auth::user()->email .'")');

                    }
                }else{
                    DB::rollBack();
                    return Redirect::to("/logistic/transfer")->withError('Stock Tidak Mencukupi untuk part : '. $parts[$i]);
                }
            }

            //Insert Attachments | t_attachments
            if(isset($req['efile'])){
                $files = $req['efile'];
                $insertFiles = array();

                foreach ($files as $efile) {
                    $filename = $efile->getClientOriginalName();
                    $upfiles = array(
                        'doc_object' => 'TRANSFER',
                        'doc_number' => $ptaNumber,
                        'efile'      => $filename,
                        'pathfile'   => '/files/TRANSFER/'. $filename,
                        'createdon'  => getLocalDatabaseDateTime(),
                        'createdby'  => Auth::user()->username ?? Auth::user()->email
                    );
                    array_push($insertFiles, $upfiles);

                    // $efile->move(public_path().'/files/PBJ/', $filename);
                    $efile->move('files/TRANSFER/', $filename);
                }
                if(sizeof($insertFiles) > 0){
                    insertOrUpdate($insertFiles,'t_attachments');
                }
            }

            DB::commit();
            return Redirect::to("/logistic/transfer")->withSuccess('Transfer Barang Berhasil dengan Nomor : '. $ptaNumber);
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/logistic/transfer")->withError($e->getMessage());
            // dd($e->getMessage());
        }
    }
}
