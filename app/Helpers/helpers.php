<?php

use Illuminate\Support\Facades\DB;


function userMenu(){
    $mnGroups = DB::table('v_usermenus')
                ->select('menugroup', 'groupname', 'groupicon','group_idx')
                ->distinct()
                ->where('userid', Auth::user()->id)
                ->orderBy('group_idx','ASC')
                ->get();
    return $mnGroups;
}

function userSubMenu(){
    $mnGroups = DB::table('v_usermenus')
                ->select('menugroup', 'route', 'menu_desc','menu_idx', 'icon')
                ->distinct()
                ->where('userid', Auth::user()->id)
                ->orderBy('menu_idx','ASC')
                ->get();
    return $mnGroups;
}

function getLocalDatabaseDateTime(){
    // SELECT now()
    $localDateTime = DB::select('SELECT fGetDatabaseLocalDatetime() as lcldate');
    return $localDateTime[0]->lcldate;
}

function getTotalPricePO($ponum){
    // fGetTotalPricePO
    $totalPrice = DB::select("SELECT fGetTotalPricePO('$ponum') as price");
    return $totalPrice[0]->price;
}

function formatDate($date, $format = "d-m-Y")
{
    if (is_null($date)) {
        return '-';
    }
    return date($format, strtotime($date));
}

function formatDateTime($dateTime, $format = "d-m-Y h:i A")
{
    if (is_null($dateTime)) {
        return '-';
    }
    return ($dateTime) ? date($format, strtotime($dateTime)) : $dateTime;
}

function generateBatchNumber(){
    $dcnNumber = 'BATCH-';
    $getdata = DB::table('dcn_nriv')->where('year', date('Y'))
                ->where('month', date('m'))
                ->where('object','BATCH')->first();
    if($getdata){
        DB::beginTransaction();
        try{
            $leadingZero = '';
            if(strlen($getdata->current_number) == 5){
                $leadingZero = '0';
            }elseif(strlen($getdata->current_number) == 4){
                $leadingZero = '00';
            }elseif(strlen($getdata->current_number) == 3){
                $leadingZero = '000';
            }elseif(strlen($getdata->current_number) == 2){
                $leadingZero = '0000';
            }elseif(strlen($getdata->current_number) == 1){
                $leadingZero = '00000';
            }

            $lastnum = ($getdata->current_number*1) + 1;

            if($leadingZero == ''){
                $dcnNumber = date('Y').date('m').$lastnum; 
            }else{
                $dcnNumber = date('Y').date('m'). $leadingZero . $lastnum; 
            }

            DB::table('dcn_nriv')->where('year',$getdata->year)->where('month', date('m'))
            ->where('object','BATCH')->update([
                'current_number' => $lastnum
            ]);

            DB::commit();
            return $dcnNumber;
        }catch(\Exception $e){
            DB::rollBack();
            return null;
        }
    }else{
        $dcnNumber = date('Y').date('m').'000001';
        DB::beginTransaction();
        try{
            DB::table('dcn_nriv')->insert([
                'year'            => date('Y'),
                'month'           => date('m'),
                'object'          => 'BATCH',
                'current_number'  => '1',
                'createdon'       => date('Y-m-d H:m:s'),
                'createdby'       => Auth::user()->email ?? Auth::user()->username
            ]);
            DB::commit();
            return $dcnNumber;
        }catch(\Exception $e){
            DB::rollBack();
            return null;
        }
    }
}

function generateDcnNumber($doctype){
    $dcnNumber = '';
    $getdata = DB::table('dcn_nriv')->where('year', date('Y'))->where('object',$doctype)->first();
    if($getdata){
        DB::beginTransaction();
        try{
            $leadingZero = '';
            if(strlen($getdata->current_number) == 5){
                $leadingZero = '0';
            }elseif(strlen($getdata->current_number) == 4){
                $leadingZero = '00';
            }elseif(strlen($getdata->current_number) == 3){
                $leadingZero = '000';
            }elseif(strlen($getdata->current_number) == 2){
                $leadingZero = '0000';
            }elseif(strlen($getdata->current_number) == 1){
                $leadingZero = '00000';
            }

            $lastnum = ($getdata->current_number*1) + 1;

            if($leadingZero == ''){
                $dcnNumber = $doctype . '-' . substr($getdata->year,2) .'-'. $lastnum; 
            }else{
                $dcnNumber = $doctype . '-' . substr($getdata->year,2) .'-'. $leadingZero . $lastnum; 
            }

            DB::table('dcn_nriv')->where('year',$getdata->year)->where('object',$doctype)->update([
                'current_number' => $lastnum
            ]);

            DB::commit();
            return $dcnNumber;
        }catch(\Exception $e){
            DB::rollBack();
            return null;
        }
    }else{
        $dcnNumber = $doctype . '-' .substr(date('Y'),2).'-000001';
        DB::beginTransaction();
        try{
            DB::table('dcn_nriv')->insert([
                'year'            => date('Y'),
                'object'          => $doctype,
                'current_number'  => '1',
                'createdon'       => date('Y-m-d H:m:s'),
                'createdby'       => Auth::user()->email ?? Auth::user()->username
            ]);
            DB::commit();
            return $dcnNumber;
        }catch(\Exception $e){
            DB::rollBack();
            return null;
        }
    }
    
}

function generateCheckListNumber($tahun, $bulan){
    $dcnNumber = '';
    $getdata = DB::table('dcn_nriv')->where('year', $tahun)->where('month', $bulan)
              ->where('object','CKL')->first();
    $doctype = 'CKL';

    if($getdata){
        DB::beginTransaction();
        try{
            $leadingZero = '';
            if(strlen($getdata->current_number) == 5){
                $leadingZero = '0';
            }elseif(strlen($getdata->current_number) == 4){
                $leadingZero = '00';
            }elseif(strlen($getdata->current_number) == 3){
                $leadingZero = '000';
            }elseif(strlen($getdata->current_number) == 2){
                $leadingZero = '0000';
            }elseif(strlen($getdata->current_number) == 1){
                $leadingZero = '00000';
            }

            $lastnum = ($getdata->current_number*1) + 1;

            if($leadingZero == ''){
                $dcnNumber = $doctype . '-' . substr($getdata->year,2). $bulan .'-'. $lastnum; 
            }else{
                $dcnNumber = $doctype . '-' . substr($getdata->year,2). $bulan .'-'. $leadingZero . $lastnum; 
            }

            DB::table('dcn_nriv')->where('year', $tahun)->where('month', $bulan)->where('object','CKL')->update([
                'current_number' => $lastnum
            ]);

            DB::commit();
            return $dcnNumber;
        }catch(\Exception $e){
            DB::rollBack();
            return null;
        }
    }else{
        $dcnNumber = $doctype . '-' .substr(date('Y'),2).$bulan.'-000001';
        DB::beginTransaction();
        try{
            DB::table('dcn_nriv')->insert([
                'year'            => $tahun,
                'month'           => $bulan,
                'object'          => 'CKL',
                'current_number'  => '1',
                'createdon'       => date('Y-m-d H:m:s'),
                'createdby'       => Auth::user()->email ?? Auth::user()->username
            ]);
            DB::commit();
            return $dcnNumber;
        }catch(\Exception $e){
            DB::rollBack();
            return null;
        }
    }
}

function getWfGroup($doctype){

    $wfgroup = DB::table('doctypes')->where('id', $doctype)->first();
    if($wfgroup){
        return $wfgroup->workflow_group;
    }else{
        return 0;
    }
}

function groupOpen($groupid){
    $routeName = \Route::current()->uri();
    $selectMenu = DB::table('menus')->where('route', $routeName)->first();
    if($selectMenu){
        return $groupid == $selectMenu->menugroup ? 'menu-open' : '';
    }
    // return request()->is("*".$groupname."*") ? 'menu-open' : '';
}

function currentURL(){
    $routeName = \Route::current()->uri();
    $selectMenu = DB::table('menus')->where('route', $routeName)->first();
    if($selectMenu){

    }
    dd(\Route::current()->uri());
}

function active($partialUrl){
    // return $partialUrl;
    return request()->is("*".$partialUrl."*") ? 'active' : '';
}

function insertOrUpdate(array $rows, $table){
    $first = reset($rows);

    $columns = implode(
        ',',
        array_map(function ($value) {
            return "$value";
        }, array_keys($first))
    );

    $values = implode(',', array_map(function ($row) {
            return '('.implode(
                ',',
                array_map(function ($value) {
                    return '"'.str_replace('"', '""', $value).'"';
                }, $row)
            ).')';
    }, $rows));

    $updates = implode(
        ',',
        array_map(function ($value) {
            return "$value = VALUES($value)";
        }, array_keys($first))
    );

    $sql = "INSERT INTO {$table}({$columns}) VALUES {$values} ON DUPLICATE KEY UPDATE {$updates}";

    return \DB::statement($sql);
}

function userAllowDownloadDocument(){
    $checkData = DB::table('user_object_auth')
                ->where('userid', Auth::user()->id)
                ->where('object_name', 'ALLOW_DOWNLOAD_DOC')
                ->first();
    if($checkData){
        if($checkData->object_val === "Y"){
            return 1;
        }else{
            return 0;
        }
    }else{
        return 0;
    }
}

function userAllowChangeDocument(){
    $checkData = DB::table('user_object_auth')
                ->where('userid', Auth::user()->id)
                ->where('object_name', 'ALLOW_CHANGE_DOC')
                ->first();
    if($checkData){
        if($checkData->object_val === "Y"){
            return 1;
        }else{
            return 0;
        }
    }else{
        return 0;
    }
}

function checkIsLocalhost(){
    if(request()->getHost() == "localhost"){
        return 1;
    }else{
        return 0;
    }
}

function getbaseurl(){
    $baseurl = env('APP_URL');
    return $baseurl;
}

function allowUplodOrginalDoc(){
    $checkData = DB::table('user_object_auth')
    ->where('userid', Auth::user()->id)
    ->where('object_name', 'ALLOW_UPLOAD_ORIGINAL_DOC')
    ->first();
    if($checkData){
        if($checkData->object_val === "Y"){
            return 1;
        }else{
            return 0;
        }
    }else{
        return 0;
    }
}

function allowDownloadOrginalDoc(){
    $checkData = DB::table('user_object_auth')
    ->where('userid', Auth::user()->id)
    ->where('object_name', 'ALLOW_DOWNLOAD_ORIGINAL_DOC')
    ->first();
    if($checkData){
        if($checkData->object_val === "Y"){
            return 1;
        }else{
            return 0;
        }
    }else{
        return 0;
    }
}

function apiIpdApp(){
    $ipdapi    = DB::table('general_setting')->where('setting_name', 'IPD_MODEL_API')->first();
    return $ipdapi->setting_value;
}

function getAppTheme(){
    $ipdapi    = DB::table('general_setting')->where('setting_name', 'APP_THEME')->first();
    return $ipdapi->setting_value;
}

function getAppBgImage(){
    $ipdapi    = DB::table('general_setting')->where('setting_name', 'APP_BGIMAGE')->first();
    return $ipdapi->setting_value;
}

function getUserDepartment(){
    $userDept = DB::table('t_department')->where('deptid', Auth::user()->deptid)->first();
    return $userDept->department;
}

function getCompanyAddress(){
    $addr = DB::table('general_setting')->where('setting_name', 'COMPANY_ADDRESS')->first();
    return $addr->setting_value;
}

function getCompanyLogo(){
    $addr = DB::table('general_setting')->where('setting_name', 'COMPANY_LOGO')->first();
    return $addr->setting_value;
}

function getDepartmentByID($id){
    $userDept = DB::table('t_department')->where('deptid', $id)->first();
    if($userDept){
        return $userDept->department;
    }else{
        return '';
    }
}

function getUserNameByID($id){
    $userDept = DB::table('users')->where('id', $id)->orWhere('email', $id)->first();
    return $userDept->name;
}

function generateBudgetDcnNumber($tahun, $bulan, $tgl, $dept, $deptname){
    $dcnNumber = 'PTA-'.$deptname.'/'.$tahun.$bulan.$tgl;

    $getdata = DB::table('t_nriv_budget')
               ->where('tahun',  $tahun)
               ->where('object', 'BUDGET')
               ->where('bulan',  $bulan)
               ->where('tanggal',  $tgl)
               ->where('deptid', $dept)
               ->first();
    
    if($getdata){
        DB::beginTransaction();
        try{
            $leadingZero = '';
            if(strlen($getdata->lastnumber) == 5){
                $leadingZero = '0';
            }elseif(strlen($getdata->lastnumber) == 4){
                $leadingZero = '00';
            }elseif(strlen($getdata->lastnumber) == 3){
                $leadingZero = '000';
            }elseif(strlen($getdata->lastnumber) == 2){
                $leadingZero = '0000';
            }elseif(strlen($getdata->lastnumber) == 1){
                $leadingZero = '00000';
            }

            $lastnum = ($getdata->lastnumber*1) + 1;

            if($leadingZero == ''){
                $dcnNumber = $dcnNumber. $lastnum; 
            }else{
                $dcnNumber = $dcnNumber . $leadingZero . $lastnum; 
            }

            // dd($leadingZero);

            DB::table('t_nriv_budget')
            ->where('tahun',  $tahun)
            ->where('object', 'BUDGET')
            ->where('bulan',  $bulan)
            ->where('tanggal',  $tgl)
            ->where('deptid', $dept)
            ->update([
                'lastnumber' => $lastnum
            ]);

            DB::commit();
            return $dcnNumber;
        }catch(\Exception $e){
            DB::rollBack();
            return null;
        }
    }else{
        $dcnNumber = $dcnNumber.'000001';
        DB::beginTransaction();
        try{
            DB::table('t_nriv_budget')->insert([
                'object'          => 'BUDGET',
                'tahun'           => $tahun,
                'bulan'           => $bulan,
                'tanggal'         => $tgl,
                'deptid'          => $dept,
                'lastnumber'      => '1',
                'createdon'       => date('Y-m-d H:m:s'),
                'createdby'       => Auth::user()->email ?? Auth::user()->username
            ]);
            DB::commit();
            return $dcnNumber;
        }catch(\Exception $e){
            DB::rollBack();
            return null;
        }
    }    
}

function generatePbjNumber($tahun, $bulan, $tgl){
    $dcnNumber = 'PBJ/'.$tahun.$bulan.$tgl;
    // dd($dcnNumber);
    $getdata = DB::table('t_nriv_budget')
               ->where('tahun',  $tahun)
               ->where('object', 'PBJ')
               ->where('bulan',  $bulan)
               ->where('tanggal',  $tgl)
            //    ->where('deptid', $dept)
               ->first();
    
    if($getdata){
        DB::beginTransaction();
        try{
            $leadingZero = '';
            if(strlen($getdata->lastnumber) == 5){
                $leadingZero = '0';
            }elseif(strlen($getdata->lastnumber) == 4){
                $leadingZero = '00';
            }elseif(strlen($getdata->lastnumber) == 3){
                $leadingZero = '000';
            }elseif(strlen($getdata->lastnumber) == 2){
                $leadingZero = '0000';
            }elseif(strlen($getdata->lastnumber) == 1){
                $leadingZero = '00000';
            }

            $lastnum = ($getdata->lastnumber*1) + 1;

            if($leadingZero == ''){
                $dcnNumber = $dcnNumber. $lastnum; 
            }else{
                $dcnNumber = $dcnNumber . $leadingZero . $lastnum; 
            }

            // dd($leadingZero);

            DB::table('t_nriv_budget')
            ->where('tahun',  $tahun)
            ->where('object', 'PBJ')
            ->where('bulan',  $bulan)
            ->where('tanggal',  $tgl)
            // ->where('deptid', $dept)
            ->update([
                'lastnumber' => $lastnum
            ]);

            DB::commit();
            return $dcnNumber;
        }catch(\Exception $e){
            DB::rollBack();
            return null;
        }
    }else{
        $dcnNumber = $dcnNumber.'000001';
        DB::beginTransaction();
        try{
            DB::table('t_nriv_budget')->insert([
                'object'          => 'PBJ',
                'tahun'           => $tahun,
                'bulan'           => $bulan,
                'tanggal'         => $tgl,
                // 'deptid'          => $dept,
                'lastnumber'      => '1',
                'createdon'       => date('Y-m-d H:m:s'),
                'createdby'       => Auth::user()->email ?? Auth::user()->username
            ]);
            DB::commit();
            return $dcnNumber;
        }catch(\Exception $e){
            DB::rollBack();
            return null;
        }
    }   
}

function generatePRNumber($tahun, $bulan, $tgl, $dept, $deptname){
    $dcnNumber = 'PR-'.$deptname.'/'.$tahun.$bulan.$tgl;
    // dd($dcnNumber);
    $getdata = DB::table('t_nriv_budget')
               ->where('tahun',  $tahun)
               ->where('object', 'PR')
               ->where('bulan',  $bulan)
               ->where('tanggal',  $tgl)
               ->where('deptid', $dept)
               ->first();
    
    if($getdata){
        DB::beginTransaction();
        try{
            $leadingZero = '';
            if(strlen($getdata->lastnumber) == 5){
                $leadingZero = '0';
            }elseif(strlen($getdata->lastnumber) == 4){
                $leadingZero = '00';
            }elseif(strlen($getdata->lastnumber) == 3){
                $leadingZero = '000';
            }elseif(strlen($getdata->lastnumber) == 2){
                $leadingZero = '0000';
            }elseif(strlen($getdata->lastnumber) == 1){
                $leadingZero = '00000';
            }

            $lastnum = ($getdata->lastnumber*1) + 1;

            if($leadingZero == ''){
                $dcnNumber = $dcnNumber. $lastnum; 
            }else{
                $dcnNumber = $dcnNumber . $leadingZero . $lastnum; 
            }

            // dd($leadingZero);

            DB::table('t_nriv_budget')
            ->where('tahun',  $tahun)
            ->where('object', 'PR')
            ->where('bulan',  $bulan)
            ->where('tanggal',  $tgl)
            ->where('deptid', $dept)
            ->update([
                'lastnumber' => $lastnum
            ]);

            DB::commit();
            return $dcnNumber;
        }catch(\Exception $e){
            DB::rollBack();
            return null;
        }
    }else{
        $dcnNumber = $dcnNumber.'000001';
        DB::beginTransaction();
        try{
            DB::table('t_nriv_budget')->insert([
                'object'          => 'PR',
                'tahun'           => $tahun,
                'bulan'           => $bulan,
                'tanggal'         => $tgl,
                'deptid'          => $dept,
                'lastnumber'      => '1',
                'createdon'       => date('Y-m-d H:m:s'),
                'createdby'       => Auth::user()->email ?? Auth::user()->username
            ]);
            DB::commit();
            return $dcnNumber;
        }catch(\Exception $e){
            DB::rollBack();
            return null;
        }
    }   
}

function generatePONumber($tahun, $bulan, $tgl){
    $dcnNumber = 'PO/'.$tahun.$bulan.$tgl;
    // dd($dcnNumber);
    $getdata = DB::table('t_nriv_budget')
               ->where('tahun',  $tahun)
               ->where('object', 'PO')
               ->where('bulan',  $bulan)
               ->where('tanggal',  $tgl)
            //    ->where('deptid', $dept)
               ->first();
    
    if($getdata){
        DB::beginTransaction();
        try{
            $leadingZero = '';
            if(strlen($getdata->lastnumber) == 5){
                $leadingZero = '0';
            }elseif(strlen($getdata->lastnumber) == 4){
                $leadingZero = '00';
            }elseif(strlen($getdata->lastnumber) == 3){
                $leadingZero = '000';
            }elseif(strlen($getdata->lastnumber) == 2){
                $leadingZero = '0000';
            }elseif(strlen($getdata->lastnumber) == 1){
                $leadingZero = '00000';
            }

            $lastnum = ($getdata->lastnumber*1) + 1;

            if($leadingZero == ''){
                $dcnNumber = $dcnNumber. $lastnum; 
            }else{
                $dcnNumber = $dcnNumber . $leadingZero . $lastnum; 
            }

            // dd($leadingZero);

            DB::table('t_nriv_budget')
            ->where('tahun',  $tahun)
            ->where('object', 'PO')
            ->where('bulan',  $bulan)
            ->where('tanggal',  $tgl)
            // ->where('deptid', $dept)
            ->update([
                'lastnumber' => $lastnum
            ]);

            DB::commit();
            return $dcnNumber;
        }catch(\Exception $e){
            DB::rollBack();
            return null;
        }
    }else{
        $dcnNumber = $dcnNumber.'000001';
        DB::beginTransaction();
        try{
            DB::table('t_nriv_budget')->insert([
                'object'          => 'PO',
                'tahun'           => $tahun,
                'bulan'           => $bulan,
                'tanggal'         => $tgl,
                // 'deptid'          => $dept,
                'lastnumber'      => '1',
                'createdon'       => date('Y-m-d H:m:s'),
                'createdby'       => Auth::user()->email ?? Auth::user()->username
            ]);
            DB::commit();
            return $dcnNumber;
        }catch(\Exception $e){
            DB::rollBack();
            return null;
        }
    }   
}

function generateGRPONumber($tahun, $bulan){
    $dcnNumber = 'RPO/'.$tahun.$bulan;
    // dd($dcnNumber);
    $getdata = DB::table('t_nriv_budget')
               ->where('tahun',  $tahun)
               ->where('object', 'GRPO')
               ->where('bulan',  $bulan)
            //    ->where('tanggal',  $tgl)
            //    ->where('deptid', $dept)
               ->first();
    
    if($getdata){
        DB::beginTransaction();
        try{
            $leadingZero = '';
            if(strlen($getdata->lastnumber) == 5){
                $leadingZero = '0';
            }elseif(strlen($getdata->lastnumber) == 4){
                $leadingZero = '00';
            }elseif(strlen($getdata->lastnumber) == 3){
                $leadingZero = '000';
            }elseif(strlen($getdata->lastnumber) == 2){
                $leadingZero = '0000';
            }elseif(strlen($getdata->lastnumber) == 1){
                $leadingZero = '00000';
            }

            $lastnum = ($getdata->lastnumber*1) + 1;

            if($leadingZero == ''){
                $dcnNumber = $dcnNumber. $lastnum; 
            }else{
                $dcnNumber = $dcnNumber . $leadingZero . $lastnum; 
            }

            // dd($leadingZero);

            DB::table('t_nriv_budget')
            ->where('tahun',  $tahun)
            ->where('object', 'GRPO')
            ->where('bulan',  $bulan)
            // ->where('tanggal',  $tgl)
            // ->where('deptid', $dept)
            ->update([
                'lastnumber' => $lastnum
            ]);

            DB::commit();
            // dd($dcnNumber);
            return $dcnNumber;
        }catch(\Exception $e){
            DB::rollBack();
            return null;
        }
    }else{
        $dcnNumber = $dcnNumber.'000001';
        DB::beginTransaction();
        try{
            DB::table('t_nriv_budget')->insert([
                'object'          => 'GRPO',
                'tahun'           => $tahun,
                'bulan'           => $bulan,
                'tanggal'         => '01',
                // 'deptid'          => $dept,
                'lastnumber'      => '1',
                'createdon'       => date('Y-m-d H:m:s'),
                'createdby'       => Auth::user()->email ?? Auth::user()->username
            ]);
            DB::commit();
            return $dcnNumber;
        }catch(\Exception $e){
            DB::rollBack();
            // dd($e->getMessage());
            return null;
        }
    }   
}

function generateIssueNumber($tahun, $bulan){
    $dcnNumber = 'ISSUE/'.$tahun.$bulan;
    // dd($dcnNumber);
    $getdata = DB::table('t_nriv_budget')
               ->where('tahun',  $tahun)
               ->where('object', 'ISSUE')
               ->where('bulan',  $bulan)
            //    ->where('tanggal',  $tgl)
            //    ->where('deptid', $dept)
               ->first();
    
    if($getdata){
        DB::beginTransaction();
        try{
            $leadingZero = '';
            if(strlen($getdata->lastnumber) == 5){
                $leadingZero = '0';
            }elseif(strlen($getdata->lastnumber) == 4){
                $leadingZero = '00';
            }elseif(strlen($getdata->lastnumber) == 3){
                $leadingZero = '000';
            }elseif(strlen($getdata->lastnumber) == 2){
                $leadingZero = '0000';
            }elseif(strlen($getdata->lastnumber) == 1){
                $leadingZero = '00000';
            }

            $lastnum = ($getdata->lastnumber*1) + 1;

            if($leadingZero == ''){
                $dcnNumber = $dcnNumber. $lastnum; 
            }else{
                $dcnNumber = $dcnNumber . $leadingZero . $lastnum; 
            }

            // dd($leadingZero);

            DB::table('t_nriv_budget')
            ->where('tahun',  $tahun)
            ->where('object', 'ISSUE')
            ->where('bulan',  $bulan)
            // ->where('tanggal',  $tgl)
            // ->where('deptid', $dept)
            ->update([
                'lastnumber' => $lastnum
            ]);

            DB::commit();
            // dd($dcnNumber);
            return $dcnNumber;
        }catch(\Exception $e){
            DB::rollBack();
            return null;
        }
    }else{
        $dcnNumber = $dcnNumber.'000001';
        DB::beginTransaction();
        try{
            DB::table('t_nriv_budget')->insert([
                'object'          => 'ISSUE',
                'tahun'           => $tahun,
                'bulan'           => $bulan,
                'tanggal'         => '01',
                // 'deptid'          => $dept,
                'lastnumber'      => '1',
                'createdon'       => date('Y-m-d H:m:s'),
                'createdby'       => Auth::user()->email ?? Auth::user()->username
            ]);
            DB::commit();
            return $dcnNumber;
        }catch(\Exception $e){
            DB::rollBack();
            // dd($e->getMessage());
            return null;
        }
    }   
}

function generateWONumber($tahun, $bulan){
    $dcnNumber = 'WO/'.$tahun.$bulan;
    // dd($dcnNumber);
    $getdata = DB::table('t_nriv_budget')
               ->where('tahun',  $tahun)
               ->where('object', 'WO')
               ->where('bulan',  $bulan)
            //    ->where('tanggal',  $tgl)
            //    ->where('deptid', $dept)
               ->first();
    
    if($getdata){
        DB::beginTransaction();
        try{
            $leadingZero = '';
            if(strlen($getdata->lastnumber) == 5){
                $leadingZero = '0';
            }elseif(strlen($getdata->lastnumber) == 4){
                $leadingZero = '00';
            }elseif(strlen($getdata->lastnumber) == 3){
                $leadingZero = '000';
            }elseif(strlen($getdata->lastnumber) == 2){
                $leadingZero = '0000';
            }elseif(strlen($getdata->lastnumber) == 1){
                $leadingZero = '00000';
            }

            $lastnum = ($getdata->lastnumber*1) + 1;

            if($leadingZero == ''){
                $dcnNumber = $dcnNumber. $lastnum; 
            }else{
                $dcnNumber = $dcnNumber . $leadingZero . $lastnum; 
            }

            // dd($leadingZero);

            DB::table('t_nriv_budget')
            ->where('tahun',  $tahun)
            ->where('object', 'WO')
            ->where('bulan',  $bulan)
            // ->where('tanggal',  $tgl)
            // ->where('deptid', $dept)
            ->update([
                'lastnumber' => $lastnum
            ]);

            DB::commit();
            // dd($dcnNumber);
            return $dcnNumber;
        }catch(\Exception $e){
            DB::rollBack();
            return null;
        }
    }else{
        $dcnNumber = $dcnNumber.'000001';
        DB::beginTransaction();
        try{
            DB::table('t_nriv_budget')->insert([
                'object'          => 'WO',
                'tahun'           => $tahun,
                'bulan'           => $bulan,
                'tanggal'         => '01',
                // 'deptid'          => $dept,
                'lastnumber'      => '1',
                'createdon'       => date('Y-m-d H:m:s'),
                'createdby'       => Auth::user()->email ?? Auth::user()->username
            ]);
            DB::commit();
            return $dcnNumber;
        }catch(\Exception $e){
            DB::rollBack();
            // dd($e->getMessage());
            return null;
        }
    } 
}

function generateVendorCode(){
    $getdata = DB::table('t_nriv')
    ->where('object', 'VENDOR')
    ->first();

    if($getdata){
        DB::beginTransaction();
        try{
            $dcnNumber = ($getdata->currentnum*1) + 1;

            DB::table('t_nriv')->where('object', 'VENDOR')->update([
                'currentnum' => $dcnNumber
            ]);
            DB::commit();
            return $dcnNumber;
        }catch(\Exception $e){
            DB::rollBack();
            // dd($e->getMessage());
            return '3000000000';
        }
    }else{
        $dcnNumber = '3000000000';
        DB::beginTransaction();
        try{
            DB::table('t_nriv')->insert([
                'object'          => 'VENDOR',
                'fromnum'         => '300000',
                'tonumber'        => '399999',
                'currentnum'      => '300000',
                'nyear'           => 0,
            ]);
            DB::commit();
            return $dcnNumber;
        }catch(\Exception $e){
            DB::rollBack();
            // dd($e->getMessage());
            return '3000000000';
        }
    }
}