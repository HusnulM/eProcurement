<?php

namespace App\Imports;

use App\Models\Kendaraan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use Carbon\Carbon;
use DataTables, Auth, DB;

class KendaraanImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    // public function model(array $row)
    // {
    //     return new Kendaraan([
    //         //
    //     ]);
    // }

    public function collection(Collection $rows)
    {
        
        foreach ($rows as $index => $row) {
            // dd($row);
            $insertData = array();
            $excelData = array(
                'no_kendaraan'    => $row['no_kendaraan'],
                'model_kendaraan' => $row['model_kendaraan'],
                'engine_sn'       => $row['engine_sn'],
                'chassis_sn'      => $row['chassis_sn'],
                'engine_model'    => $row['engine_model'],
                'last_km'         => $row['last_km'],
                'last_hm'         => $row['last_hm'],
                'odometer'        => $row['odo_meter'],
                'tahun'           => $row['tahun'],
                'no_rangka'       => $row['nomor_rangka'],
                'bahan_bakar'     => $row['bahan_bakar'],
                'layak_tidak'     => $row['status_kendaraan'],
                'createdby'       => Auth::user()->email ?? Auth::user()->username,
                'createdon'       => getLocalDatabaseDateTime(),
            );
            array_push($insertData, $excelData);
            insertOrUpdate($insertData,'t_kendaraan');
        }
    }
}
