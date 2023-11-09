<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables, Auth, DB;
use Validator,Redirect,Response;
use PDF;

class TrackingPBJController extends Controller
{
    public function index(){
        $department = DB::table('t_department')->get();
        return view('laporan.trackingpbj', ['department' => $department]);
    }

    public function getData(Request $req){
        $query = DB::table('v_pbj_trackingv3');
        //->select('id','prnum','prdate','approvestat','requestby','remark','deptname','createdby')->distinct();

        if(isset($req->department)){
            if($req->department !== 'All'){
                $query->where('deptid', $req->department);
            }
        }

        if(isset($req->pbjstatus)){
            if($req->pbjstatus === "O"){
                $query->where('pbjstatus', 'Open');
            }elseif($req->pbjstatus === "A"){
                $query->where('pbjstatus', 'Approved');
            }elseif($req->pbjstatus === "R"){
                $query->where('pbjstatus', 'Rejected');
            }elseif($req->pbjstatus === "C"){
                $query->where('pbjstatus', 'Closed');
            }
        }

        if(isset($req->datefrom) && isset($req->dateto)){
            $query->whereBetween('tgl_pbj', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('tgl_pbj', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('tgl_pbj', $req->dateto);
        }

        // $checkObjAuth = DB::table('user_object_auth')
        //                 ->where('object_name', 'ALLOW_DISPLAY_ALL_DEPT')
        //                 ->where('object_val', 'Y')
        //                 ->where('userid', Auth::user()->id)
        //                 ->first();
        // if($checkObjAuth){

        // }else{
        //     $query->where('createdby', Auth::user()->email);
        // }
        // $query->where('createdby', Auth::user()->email);

        $query->orderBy('id', 'DESC');

        return DataTables::queryBuilder($query)
        // ->editColumn('tgl_pbj', function ($query){
        //     return [
        //         'tgl_pbj1' => \Carbon\Carbon::parse($query->tgl_pbj)->format('d-m-Y')
        //      ];
        // })
        // ->editColumn('prdate', function ($query){
        //     return [
        //         'prdate1' => \Carbon\Carbon::parse($query->prdate)->format('d-m-Y')
        //      ];
        // })
        // ->editColumn('podat', function ($query){
        //     return [
        //         'podat1' => \Carbon\Carbon::parse($query->podat)->format('d-m-Y')
        //      ];
        // })->editColumn('postdate', function ($query){
        //     return [
        //         'postdate1' => \Carbon\Carbon::parse($query->postdate)->format('d-m-Y')
        //      ];
        // })
        ->toJson();
    }
}
