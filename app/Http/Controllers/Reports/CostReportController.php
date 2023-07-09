<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

class CostReportController extends Controller
{
    public function costPerKendaraan(){
        return view('laporan.cost.cost01');
    }

    public function costPerProject(){
        $project = DB::table('t_projects')->get();
        return view('laporan.cost.cost02', ['project' => $project]);
    }

    public function costDetails(){
        $project = DB::table('t_projects')->get();
        return view('laporan.cost.cost03', ['project' => $project]);
    }

    public function costPerKendaraanList(Request $req){
        $query = DB::table('v_summary_cost01');

        if(isset($req->nopol)){
            $query->where('no_plat', $req->nopol);
        }

        if(isset($req->datefrom) && isset($req->dateto)){
            $query->whereBetween('postdate', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('postdate', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('postdate', '<=', $req->dateto);
        }

        $query->orderBy('id');

        return DataTables::queryBuilder($query)
        ->editColumn('postdate', function ($query){
            return [
                'postdate1' => \Carbon\Carbon::parse($query->postdate)->format('d-m-Y')
             ];
        })->editColumn('total_cost', function ($query){
            return [
                'totalprice' => number_format($query->total_cost,0),
                'totalprice2' => $query->total_cost
            ];
        })
        ->toJson();
    }

    public function costPerProjectList(Request $req){
        $query = DB::table('v_summary_cost02');

        if(isset($req->nopol)){
            $query->where('no_plat', $req->nopol);
        }

        if(isset($req->kodeproj)){
            if($req->kodeproj !== 'All'){
                $query->where('kode_project', $req->kodeproj);
            }
        }

        if(isset($req->datefrom) && isset($req->dateto)){
            $query->whereBetween('postdate', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('postdate', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('postdate', '<=', $req->dateto);
        }

        $query->orderBy('id');

        return DataTables::queryBuilder($query)
        ->editColumn('postdate', function ($query){
            return [
                'postdate1' => \Carbon\Carbon::parse($query->postdate)->format('d-m-Y')
             ];
        })->editColumn('total_cost', function ($query){
            return [
                'totalprice' => number_format($query->total_cost,0),
                'totalprice2' => $query->total_cost
            ];
        })
        ->toJson();
    }

    public function costDetailList(Request $req){
        $query = DB::table('v_detail_cost');

        if(isset($req->nopol)){
            $query->where('unit_desc', $req->nopol);
        }

        if(isset($req->kodeproj)){
            if($req->kodeproj !== 'All'){
                $query->where('kode_project', $req->kodeproj);
            }
        }

        if(isset($req->datefrom) && isset($req->dateto)){
            $query->whereBetween('postdate', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('postdate', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('postdate', '<=', $req->dateto);
        }

        $query->orderBy('id');

        return DataTables::queryBuilder($query)
        ->editColumn('postdate', function ($query){
            return [
                'postdate1' => \Carbon\Carbon::parse($query->postdate)->format('d-m-Y')
             ];
        })->editColumn('total_price', function ($query){
            return [
                'totalprice' => number_format($query->total_price,0),
                'totalprice2' => $query->total_price
            ];
        })->editColumn('quantity', function ($query){
            return [
                'quantity1' => number_format($query->quantity,0)
            ];
        })->editColumn('unit_price', function ($query){
            return [
                'uprice1' => number_format($query->unit_price,0)
            ];
        })
        ->toJson();
    }
}
