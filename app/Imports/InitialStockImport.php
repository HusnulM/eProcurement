<?php

namespace App\Imports;

use App\Models\InitialStock;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use Carbon\Carbon;
use DataTables, Auth, DB;

class InitialStockImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    // public function model(array $row)
    // {
    //     return new InitialStock([
    //         //
    //     ]);
    // }

    public function collection(Collection $rows)
    {
        
        foreach ($rows as $index => $row) {
            // dd($row);
            $insertData = array();
            $excelData = array(
                'docnum'       => $ptaNumber,
                'docyear'      => $tahun,
                'docitem'      => $count,
                'movement_code'=> '561',
                'material'     => $parts[$i],
                'matdesc'      => $partdsc[$i],
                'batch_number' => $batchNumber,
                'quantity'     => $qty,
                'unit'         => $uom[$i],
                'unit_price'   => $matPrice,
                'total_price'  => $matPrice*$qty,
                'ponum'        => $ponum[$i] ?? null,
                'poitem'       => $poitem[$i] ?? null,
                'whscode'      => $whscode[$i],
                'shkzg'        => '+',
                'createdon'    => getLocalDatabaseDateTime(),
                'createdby'    => Auth::user()->email ?? Auth::user()->username
                
            );
            array_push($insertData, $excelData);
            insertOrUpdate($insertData,'t_inv02');

            DB::table('t_inv_batch_stock')->insert([
                'material'     => $parts[$i],
                'whscode'      => $whscode[$i],
                'batchnum'     => $batchNumber,
                'quantity'     => $qty,
                'unit'         => $uom[$i],
                'last_udpate'  => getLocalDatabaseDateTime()
            ]);

            DB::table('t_inv_stock')->insert([
                'material'     => $parts[$i],
                'whscode'      => $whscode[$i],
                'batchnum'     => $batchNumber,
                'quantity'     => $qty,
                'unit'         => $uom[$i],
                'last_udpate'  => getLocalDatabaseDateTime()
            ]);
        }
    }
}
