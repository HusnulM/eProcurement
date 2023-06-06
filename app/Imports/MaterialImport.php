<?php

namespace App\Imports;

use App\Models\Material;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use Carbon\Carbon;
use DataTables, Auth, DB;

class MaterialImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    // public function model(array $row)
    // {
    //     return new Material([
    //         //
    //     ]);
    // }

    public function collection(Collection $rows)
    {
        
        foreach ($rows as $index => $row) {
            $current_timestamp = Carbon::now()->timestamp;
            $insertData = array();
            $excelData = array(
                'material'   => $row['partnumber'],
                'matdesc'    => $row['partname'],
                'mattype'    => $row['itemtype'],
                'partname'   => $row['partname'],
                'partnumber' => $row['partnumber'],
                'matunit'    => $row['itemunit'],
                'matuniqid'  => $current_timestamp,
                'createdon'  => getLocalDatabaseDateTime(),
                'createdby'  => Auth::user()->email ?? Auth::user()->username
            );
            array_push($insertData, $excelData);
            insertOrUpdate($insertData,'t_material');

            $insertAltUom = array();
            $altUom = array(
                'material'  => $row['partnumber'],
                'altuom'    => $row['itemunit'],
                'convalt'   => '1',
                'baseuom'   => $row['itemunit'],
                'convbase'  => '1',
                'createdon' => getLocalDatabaseDateTime(),
                'createdby' => Auth::user()->email ?? Auth::user()->username
            );
            array_push($insertAltUom, $altUom);
            insertOrUpdate($insertAltUom,'t_material2');
        }
    }
}
