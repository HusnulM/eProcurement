<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class BastController extends Controller
{
    public function index(){
        return view('transaksi.bast.index');
    }

    public function create($pbjID){
        $pbjheader = DB::table('v_pbj_to_bast')->where('id', $pbjID)->first();
        if($pbjheader){
            $pbjitems = DB::table('t_pbj02')->where('pbjnumber', $pbjheader->pbjnumber)->get();
            // return $pbjitems;
            return view('transaksi.bast.create',[
                'pbjheader' => $pbjheader,
                'pbjitems'  => $pbjitems
            ]);
        }
    }

    public function viewListBast(){
        return view('transaksi.bast.list');
    }

    public function dataBast(Request $request){
        $params = $request->params;        
        $whereClause = $params['sac'];
        $query = DB::table('v_pbj_to_bast')->orderBy('id');
        return DataTables::queryBuilder($query)
        ->toJson();
    }

    public function findUser(Request $request){
        $query['data'] = DB::table('users')->select('id','name','email','username')
        ->where('name', 'like', '%'. $request->search . '%')->get();
        return $query;
    }

    public function save(Request $req){
        // return $req;
        DB::beginTransaction();
        try{

            $bastID = DB::table('t_bast01')->insertGetId([
                'no_bast'         => $req['nomorbast'],
                'userid_pemberi'  => Auth::user()->id,
                'userid_penerima' => $req['penerima'],
                'tanggal_bast'    => $req['tglbast'],
                'remark'          => $req['remark'],
                'createdon'       => getLocalDatabaseDateTime(),
                'createdby'       => Auth::user()->email ?? Auth::user()->username
            ]);

            $parts    = $req['material'];
            $partdsc  = $req['matdesc'];
            $quantity = $req['quantity'];
            $uom      = $req['unit'];
            $pbjnum   = $req['pbjnumber'];
            $pbjitm   = $req['pbjitem'];
            $wonum    = $req['wonum'];
            // return $wonum;
            $insertData = array();
            $count = 0;            

            for($i = 0; $i < sizeof($parts); $i++){
                $qty    = $quantity[$i];
                $qty    = str_replace(',','',$qty);

                $wodata = DB::table('t_wo01')->where('wonum', $wonum[$i])->first();

                $latestStock = DB::table('v_inv_summary_stock')
                               ->where('material', $parts[$i])
                               ->where('whsid',    $wodata->whscode)->first();
                if($latestStock){
                    if($latestStock->quantity < $qty){
                        DB::rollBack();
                        return Redirect::to("/logistic/bast/create/".$req['pbjID'])->withError('Stock Tidak Mencukupi untuk part : '. $parts[$i]);
                    }else{
                        DB::table('t_inv_stock')
                            ->where('material', $parts[$i])
                            ->where('whscode',  $wodata->whscode)
                            ->update([
                                'quantity'     => $latestStock->quantity - $qty
                            ]);
                    }
                }else{
                    DB::rollBack();
                    return Redirect::to("/logistic/bast/create/".$req['pbjID'])->withError('Stock Tidak Mencukupi untuk part : '. $parts[$i]);
                }
                $data = array(
                    'bast_id'      => $bastID,
                    'no_bast'      => $req['nomorbast'],
                    'material'     => $parts[$i],
                    'matdesc'      => $partdsc[$i],
                    'quantity'     => $qty,
                    'unit'         => $uom[$i],
                    'refdoc'       => $pbjnum[$i] ?? 0,
                    'refdocitem'   => $pbjitm[$i] ?? 0,
                    'createdon'    => getLocalDatabaseDateTime(),
                    'createdby'    => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $data);

                DB::table('t_pbj01')->where('pbjnumber', $pbjnum[$i])
                ->update([
                    'bast_created' => 'Y'
                ]);

                DB::table('t_pbj02')->where('pbjnumber', $pbjnum[$i])->where('pbjitem', $pbjitm[$i])
                ->update([
                    'bast_created' => 'Y'
                ]);
            }
            insertOrUpdate($insertData,'t_bast02');

            //Insert Attachments | t_attachments
            if(isset($req['efile'])){
                $files = $req['efile'];
                $insertFiles = array();
    
                foreach ($files as $efile) {
                    $filename = $efile->getClientOriginalName();
                    $upfiles = array(
                        'doc_object' => 'BAST',
                        'doc_number' => $bastID,
                        'efile'      => $filename,
                        'pathfile'   => '/files/BAST/'. $filename,
                        'createdon'  => getLocalDatabaseDateTime(),
                        'createdby'  => Auth::user()->username ?? Auth::user()->email
                    );
                    array_push($insertFiles, $upfiles);
    
                    // $efile->move(public_path().'/files/PO/', $filename);  
                    $efile->move('files/BAST/', $filename);  
                }
                if(sizeof($insertFiles) > 0){
                    insertOrUpdate($insertFiles,'t_attachments');
                }
            }
            DB::commit();
            return Redirect::to("/logistic/bast")->withSuccess('BAST Berhasil disimpan');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/logistic/bast/create/".$req['pbjID'])->withError($e->getMessage());
        }
    }
}
