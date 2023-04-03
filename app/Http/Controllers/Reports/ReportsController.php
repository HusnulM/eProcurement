<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class ReportsController extends Controller
{
    public function requestbudget(){
        $department = DB::table('t_department')->get();
        return view('laporan.requestbudget', ['department' => $department]);
    }

    public function budgetRequestlist(Request $req){
        $query = DB::table('v_rbudget');

        if(isset($req->department)){
            if($req->department !== 'All'){
                $query->where('deptid', $req->department);
            }
        }

        if(isset($req->approvalstat)){
            if($req->approvalstat === "O"){
                $query->where('budget_status', 'O');
            }elseif($req->approvalstat === "A"){
                $query->where('budget_status', 'A');                
            }elseif($req->approvalstat === "R"){
                $query->where('budget_status', 'R');                
            }
        }

        if(isset($req->datefrom) && isset($req->dateto)){
            $query->whereBetween('tgl_aju', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('tgl_aju', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('tgl_aju', $req->dateto);
        }

        $query->orderBy('id');

        return DataTables::queryBuilder($query)
        ->editColumn('amount', function ($query){
            return [
                'amount1' => number_format($query->amount,0)
             ];
        })->editColumn('approved_amount', function ($query){
            return [
                'amount2' => number_format($query->approved_amount,0)
             ];
        })
        ->toJson();
    }

    public function pbj(){
        $department = DB::table('t_department')->get();
        return view('laporan.pbj', ['department' => $department]);
    }
    
    public function pbjList(Request $req){
        $query = DB::table('v_rpbj');

        if(isset($req->department)){
            if($req->department !== 'All'){
                $query->where('deptid', $req->department);
            }
        }

        if(isset($req->approvalstat)){
            if($req->approvalstat === "O"){
                $query->where('pbj_status', 'O');
            }elseif($req->approvalstat === "A"){
                $query->where('pbj_status', 'A');                
            }elseif($req->approvalstat === "R"){
                $query->where('pbj_status', 'R');                
            }
        }

        if(isset($req->datefrom) && isset($req->dateto)){
            $query->whereBetween('tgl_pbj', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('tgl_pbj', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('tgl_pbj', $req->dateto);
        }

        $query->orderBy('id');

        return DataTables::queryBuilder($query)
        ->editColumn('quantity', function ($query){
            return [
                'qty1' => number_format($query->quantity,0)
             ];
        })->editColumn('issued_qty', function ($query){
            return [
                'qty2' => number_format($query->issued_qty,0)
             ];
        })
        ->toJson();
    }

    public function po(){
        $department = DB::table('t_department')->get();
        return view('laporan.rpo', ['department' => $department]);
    }

    public function poList(Request $req){
        $query = DB::table('v_rpo');

        if(isset($req->department)){
            if($req->department !== 'All'){
                $query->where('deptid', $req->department);
            }
        }

        if(isset($req->approvalstat)){
            if($req->approvalstat === "O"){
                $query->where('approvestat', 'O');
            }elseif($req->approvalstat === "A"){
                $query->where('approvestat', 'A');                
            }elseif($req->approvalstat === "R"){
                $query->where('approvestat', 'R');                
            }
        }

        if(isset($req->datefrom) && isset($req->dateto)){
            $query->whereBetween('podat', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('podat', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('podat', $req->dateto);
        }

        $query->orderBy('id');

        return DataTables::queryBuilder($query)
        ->editColumn('quantity', function ($query){
            return [
                'qty1' => number_format($query->quantity,0)
            ];
        })->editColumn('grqty', function ($query){
            return [
                'qty2' => number_format($query->grqty,0)
            ];
        })->editColumn('openqty', function ($query){
            return [
                'qty3' => number_format($query->openqty,0)
            ];
        })->editColumn('price', function ($query){
            return [
                'price1' => number_format($query->price,0)
            ];
        })->editColumn('podat', function ($query){
            return [
                'podat1' => \Carbon\Carbon::parse($query->podat)->format('d-m-Y')
             ];
        })
        ->toJson();
    }

    public function pr(){
        $department = DB::table('t_department')->get();
        return view('laporan.rpr', ['department' => $department]);
    }

    public function prList(Request $req){
        $query = DB::table('v_rpr');

        if(isset($req->department)){
            if($req->department !== 'All'){
                $query->where('deptid', $req->department);
            }
        }

        if(isset($req->approvalstat)){
            if($req->approvalstat === "O"){
                $query->where('approvestat', 'O');
            }elseif($req->approvalstat === "A"){
                $query->where('approvestat', 'A');                
            }elseif($req->approvalstat === "R"){
                $query->where('approvestat', 'R');                
            }
        }

        if(isset($req->datefrom) && isset($req->dateto)){
            $query->whereBetween('prdate', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('prdate', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('prdate', $req->dateto);
        }

        $query->orderBy('id');

        return DataTables::queryBuilder($query)
        ->editColumn('quantity', function ($query){
            return [
                'qty1' => number_format($query->quantity,0)
            ];
        })->editColumn('prdate', function ($query){
            return [
                'prdate1' => \Carbon\Carbon::parse($query->prdate)->format('d-m-Y')
             ];
        })
        ->toJson();
    }

    public function wo(){
        $mekanik = DB::table('t_mekanik')->get();
        return view('laporan.rwo', ['mekanik' => $mekanik]);
    }

    public function woList(Request $req){
        $query = DB::table('v_rwo');

        if(isset($req->mekanik)){
            if($req->mekanik !== 'All'){
                $query->where('mekanik', $req->mekanik);
            }
        }

        if(isset($req->approvalstat)){
            if($req->approvalstat === "O"){
                $query->where('wo_status', 'O');
            }elseif($req->approvalstat === "A"){
                $query->where('wo_status', 'A');                
            }elseif($req->approvalstat === "R"){
                $query->where('wo_status', 'R');                
            }
        }

        if(isset($req->datefrom) && isset($req->dateto)){
            $query->whereBetween('wodate', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('wodate', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('wodate', $req->dateto);
        }

        $query->orderBy('id');

        return DataTables::queryBuilder($query)
        ->editColumn('quantity', function ($query){
            return [
                'qty1' => number_format($query->quantity,0)
            ];
        })->editColumn('wodate', function ($query){
            return [
                'wodate1' => \Carbon\Carbon::parse($query->wodate)->format('d-m-Y')
             ];
        })
        ->toJson();
    }

    public function grpo(){
        return view('laporan.rgrpo');
    }

    public function grpoList(Request $req){
        $query = DB::table('v_rgrpo');

        if(isset($req->datefrom) && isset($req->dateto)){
            $query->whereBetween('docdate', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('docdate', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('docdate', $req->dateto);
        }

        $query->orderBy('id');

        return DataTables::queryBuilder($query)
        ->editColumn('quantity', function ($query){
            return [
                'qty1' => number_format($query->quantity,0)
            ];
        })->editColumn('docdate', function ($query){
            return [
                'docdate1' => \Carbon\Carbon::parse($query->docdate)->format('d-m-Y')
             ];
        })
        ->toJson();
    }

    public function issue(){
        return view('laporan.rissued');
    }

    public function issueList(Request $req){
        $query = DB::table('v_rissue');

        if(isset($req->datefrom) && isset($req->dateto)){
            $query->whereBetween('docdate', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('docdate', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('docdate', $req->dateto);
        }

        $query->orderBy('id');

        return DataTables::queryBuilder($query)
        ->editColumn('quantity', function ($query){
            return [
                'qty1' => number_format($query->quantity,0)
            ];
        })->editColumn('docdate', function ($query){
            return [
                'docdate1' => \Carbon\Carbon::parse($query->docdate)->format('d-m-Y')
             ];
        })
        ->toJson();
    }

    public function stock(){
        $warehouse = DB::table('t_warehouse')->get();
        return view('laporan.rstock', ['warehouse' => $warehouse]);
    }

    public function stockList(Request $req){
        $query = DB::table('v_inv_summary_stock');

        return DataTables::queryBuilder($query)
        ->editColumn('quantity', function ($query){
            return [
                'qty1' => number_format($query->quantity,0)
            ];
        })
        ->toJson();
    }

    public function batchStock(){
        $warehouse = DB::table('t_warehouse')->get();
        return view('laporan.rsbatchtock', ['warehouse' => $warehouse]);
    }

    public function batchStockList(Request $req){
        $query = DB::table('v_inv_batch_stock');

        return DataTables::queryBuilder($query)
        ->editColumn('quantity', function ($query){
            return [
                'qty1' => number_format($query->quantity,0)
            ];
        })
        ->toJson();
    }

    public function cost(){
        $mekanik = DB::table('t_mekanik')->get();
        return view('laporan.cost', ['mekanik' => $mekanik]);
    }

    public function costList(Request $req){
        // v_report_cost
        $query = DB::table('v_report_cost');

        if(isset($req->mekanik)){
            if($req->mekanik !== 'All'){
                $query->where('mekanik', $req->mekanik);
            }
        }

        if(isset($req->nopol)){
            $query->where('license_number', $req->nopol);
        }

        if(isset($req->datefrom) && isset($req->dateto)){
            $query->whereBetween('wodate', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('wodate', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('wodate', $req->dateto);
        }

        $query->orderBy('id');

        return DataTables::queryBuilder($query)
        ->editColumn('quantity', function ($query){
            return [
                'qty1' => number_format($query->quantity,0)
            ];
        })->editColumn('wodate', function ($query){
            return [
                'wodate1' => \Carbon\Carbon::parse($query->wodate)->format('d-m-Y')
             ];
        })->editColumn('total_price', function ($query){
            return [
                'totalprice' => number_format($query->total_price,0),
                'totalprice2' => $query->total_price
            ];
        })
        ->toJson();
    }
}
