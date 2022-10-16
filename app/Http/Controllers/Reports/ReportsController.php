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

    public function budgetRequestlist(Request $request){
        $params = $request->params;        
        $whereClause = $params['sac'];
        $query = DB::table('v_budget_approval')
                //  ->where('approver',Auth::user()->id)
                //  ->where('is_active','Y')
                //  ->where('approval_status','N')
                 ->orderBy('id');
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
}
