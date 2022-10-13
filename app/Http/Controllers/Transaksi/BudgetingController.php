<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class BudgetingController extends Controller
{
    public function index(){
        return view('transaksi.budget.index');
    }

    public function save(Request $req){
        DB::beginTransaction();
        try{
            
            DB::commit();
            return Redirect::to("/transaksi/budgeting")->withSuccess('Pengajuan Budget Berhasil dibuat');
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/transaksi/budgeting")->withError($e->getMessage());
        }
    }
}
