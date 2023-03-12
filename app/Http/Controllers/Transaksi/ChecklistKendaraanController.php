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
}
