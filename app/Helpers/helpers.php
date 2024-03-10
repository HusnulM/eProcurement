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

function getAuthorizedProject(){
    $checkObjAuth = DB::table('user_object_auth')
        ->where('object_name', 'ALLOW_DISPLAY_PROJECT')
        ->where('object_val', '*')
        ->where('userid', Auth::user()->id)
        ->first();

    if($checkObjAuth){
        $proyek = DB::table('t_projects')->get();
    }else{
        $authProject = DB::table('user_object_auth')
            ->where('object_name', 'ALLOW_DISPLAY_PROJECT')
            ->where('userid', Auth::user()->id)
            ->pluck('object_val');

        $proyek = DB::table('t_projects')
            ->whereIn('kode_project', $authProject)
            ->get();
    }

    return $proyek;
}

function getTotalPricePO($ponum){
    // fGetTotalPricePO
    $totalPrice = DB::select("SELECT fGetTotalPricePO('$ponum') as price");
    return $totalPrice[0]->price;
}

function getTotalPOCreated($prnum, $pritem){
    $poQty = DB::select("SELECT fGetQuantityCreatedPRItem('$prnum','$pritem') as poqty");
    $prQty = DB::table('t_pr02')->where('prnum', $prnum)->where('pritem', $pritem)->first();
    if($prQty){
        if($prQty->quantity == $poQty[0]->poqty){
            return '1';
        }else{
            return '0';
        }
    }else{
        return '0';
    }
    // return $poQty[0]->poqty;
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

function getWfGroup($doctype){

    $wfgroup = DB::table('doctypes')->where('id', $doctype)->first();
    if($wfgroup){
        return $wfgroup->workflow_group;
    }else{
        return 0;
    }
}

function groupOpen($groupid){
    $routeName = null;
    $routes = explode('/',\Route::current()->uri());
    $count = 0;
    foreach($routes as $row){
        $count = $count + 1;
        $routeName = $routeName . '/' . $row;
        if($count == 1){
            $routeName = substr($routeName,1);
        }

        $selectMenu = DB::table('menus')->where('route', $routeName)->first();
        if($selectMenu){
            return $groupid == $selectMenu->menugroup ? 'menu-open' : '';
            break;
        }
    }
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

    $checkRoute = DB::table('menus')->where('route', \Route::current()->uri())->first();
    if($checkRoute){
        if(\Route::current()->uri() === $partialUrl){
            return 'active';
        }else{
            return '';
        }
    }else{
        return request()->is("*".$partialUrl."*") ? 'active' : '';
    }
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
    $userDept = DB::table('users')->where('id', $id)
            ->orWhere('email', $id)
            ->orWhere('username', $id)->first();
    return $userDept->name;
}

function generatePRNumber($tahun, $bulan, $prtype, $project){
    if($prtype === 'AA'){
        $dcnNumber = 'SPB/'.$prtype.'/'.$bulan.'/'.$tahun;
    }else{
        $prtype    = $project;
        $dcnNumber = 'SPB/'.$project.'/'.$bulan.'/'.$tahun;
    }
    $getdata = DB::table('nriv_pr')
               ->where('year',   $tahun)
               ->where('month',  $bulan)
               ->where('prtype', $prtype)
               ->where('prefix', $project)
               ->first();

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
                $dcnNumber = $dcnNumber.'/'.$lastnum;
            }else{
                $dcnNumber = $dcnNumber .'/'. $leadingZero . $lastnum;
            }

            // dd($leadingZero);

            DB::table('nriv_pr')
            ->where('year',   $tahun)
            ->where('month',  $bulan)
            ->where('prtype', $prtype)
            ->where('prefix', $project)
            ->update([
                'current_number' => $lastnum
            ]);

            DB::commit();
            return $dcnNumber;
        }catch(\Exception $e){
            DB::rollBack();
            return null;
        }
    }else{
        $dcnNumber = $dcnNumber.'/000001';
        DB::beginTransaction();
        try{
            DB::table('nriv_pr')->insert([
                'year'            => $tahun,
                'month'           => $bulan,
                'prtype'          => $prtype,
                'prefix'          => $project,
                'current_number'  => '1',
                'createdon'       => date('Y-m-d H:m:s'),
                'createdby'       => Auth::user()->username
            ]);
            DB::commit();
            return $dcnNumber;
        }catch(\Exception $e){
            DB::rollBack();
            return null;
        }
    }
}

function generatePONumber($tahun, $bulan, $prefix){
    $dcnNumber = 'KRS/PO/'.$prefix.'/'.$bulan.'/'.$tahun;
    // dd($dcnNumber);
    $getdata = DB::table('nriv_po')
               ->where('prefix', $prefix)
               ->where('month',  $bulan)
               ->where('year',   $tahun)
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
                $dcnNumber = $dcnNumber.'/'.$lastnum;
            }else{
                $dcnNumber = $dcnNumber .'/'.$leadingZero . $lastnum;
            }

            // dd($leadingZero);

            DB::table('nriv_po')
            ->where('prefix', $prefix)
            ->where('month',  $bulan)
            ->where('year',   $tahun)
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
        $dcnNumber = $dcnNumber.'/000001';
        DB::beginTransaction();
        try{
            DB::table('nriv_po')->insert([
                'prefix'          => $prefix,
                'month'           => $bulan,
                'year'            => $tahun,
                'lastnumber'      => '1',
                'createdon'       => date('Y-m-d H:m:s'),
                'createdby'       => Auth::user()->username
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

function generateTransferNumber($tahun, $bulan){
    $dcnNumber = 'TF/'.$tahun.$bulan;
    // dd($dcnNumber);
    $getdata = DB::table('t_nriv_budget')
               ->where('tahun',  $tahun)
               ->where('object', 'TF')
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
            ->where('object', 'TF')
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
                'object'          => 'TF',
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
            // return '3000000000';
        }
    }else{
        $dcnNumber = '300000';
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
            // return '3000000000';
        }
    }
}



function mbpAPI($url, $apikey, $data=array()){
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_POST, 1);
    if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data) );

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'APIKEY: '.$apikey,
        'Content-Type: application/json',
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    $result = curl_exec($curl);
    if(!$result){die("Connection Failure");}
    curl_close($curl);
    return $result;
}
