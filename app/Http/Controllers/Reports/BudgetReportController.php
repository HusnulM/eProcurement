<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class BudgetReportController extends Controller
{
    public function index(){
        return view('laporan.rbudgetsummary');
    }

    public function getBudgetSummary(Request $req){
        $query = DB::table('v_report_summary_budget');
        $query->orderBy('id');

        return DataTables::queryBuilder($query)
        ->editColumn('amount', function ($query){
            return [
                'amount1' => number_format($query->amount,0)
             ];
        })->editColumn('issuing_amount', function ($query){
            return [
                'amount2' => number_format($query->issuing_amount,0)
             ];
        })->editColumn('balance', function ($query){
            return [
                'amount3' => number_format($query->balance,0)
             ];
        })
        ->toJson();
    }
}
