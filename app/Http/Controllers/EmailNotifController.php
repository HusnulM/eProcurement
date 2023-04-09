<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifPBJDueDateMail;
use App\Mail\NotifPRDueDateMail;
use App\Mail\NotifPODueDateMail;
use DB, Auth;

class EmailNotifController extends Controller
{
    public function notifDueDatePBJ(){
        $dept = DB::table('v_duedate_pbj')
               ->select('deptid')->distinct()
               ->where('duedate','>','3')
               ->where('prcreated','N')->get();

        foreach($dept as $row){
            $data = DB::table('v_duedate_pbj')
             ->where('duedate','>','3')
             ->where('prcreated','N')
             ->where('deptid',$row->deptid)
             ->orderBy('id')->get();
    
            $mailto = DB::table('users')
                    ->where('deptid', $row->deptid)
                    ->pluck('email');    
    
            Mail::to($mailto)->cc('husnulmub@gmail.com')->queue(new NotifPBJDueDateMail($data));
        }
    }

    public function notifDueDatePR(){
        $dept = DB::table('v_pr_duedate')
               ->select('deptid')->distinct()
               ->where('duedate','>','3')
               ->where('pocreated','N')->get();

        foreach($dept as $row){
            $data = DB::table('v_pr_duedate')
             ->where('duedate','>','3')
             ->where('pocreated','N')
             ->where('deptid',$row->deptid)
             ->orderBy('id')->get();

            $mailto = DB::table('users')
                    ->where('deptid', $row->deptid)
                    ->pluck('email'); 

            Mail::to($mailto)->cc('husnulmub@gmail.com')->queue(new NotifPRDueDateMail($data));
            
        }
    }

    public function notifDueDatePO(){
        $dept = DB::table('v_pr_duedate')
               ->select('deptid')->distinct()
               ->where('openqty','>',0)->get();
        
       foreach($dept as $row){
            $data = DB::table('v_po_duedate')
                    ->where('duedate','>','3')
                    ->where('openqty','>',0)
                    ->where('deptid', $row->deptid)
                    ->orderBy('id')->get();
    
            $mailto = DB::table('users')
                        ->where('deptid', $row->deptid)
                        ->pluck('email'); 
    
            Mail::to($mailto)->cc('husnulmub@gmail.com')->queue(new NotifPODueDateMail($data));
                
        }
    }
}
