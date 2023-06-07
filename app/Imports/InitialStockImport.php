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
        DB::beginTransaction();
        try{
            // dd($_POST);
            $tgl   = substr($_POST['tglupload'], 8, 2);
            $bulan = substr($_POST['tglupload'], 5, 2);
            $tahun = substr($_POST['tglupload'], 0, 4);
            $ptaNumber = generateGRPONumber($tahun, $bulan);
            
            DB::table('t_inv01')->insert([
                'docnum'            => $ptaNumber,
                'docyear'           => $tahun,
                'docdate'           => $_POST['tglupload'],
                'postdate'          => $_POST['tglupload'],
                'received_by'       => Auth::user()->username,
                'movement_code'     => '561',
                'remark'            => $_POST['remark'],
                'createdon'         => getLocalDatabaseDateTime(),
                'createdby'         => Auth::user()->email ?? Auth::user()->username
            ]);

            $count = 0;
            foreach ($rows as $index => $row) {
                // dd($row);
                $batchNumber = generateBatchNumber();
                $count = $count + 1;
                $insertData = array();
                $excelData = array(
                    'docnum'       => $ptaNumber,
                    'docyear'      => $tahun,
                    'docitem'      => $count,
                    'movement_code'=> '561',
                    'material'     => $row['material'],
                    'matdesc'      => $row['material_desc'],
                    'batch_number' => $batchNumber,
                    'quantity'     => $row['quantity'],
                    'unit'         => $row['unit'],
                    'unit_price'   => $row['unit_price'],
                    'total_price'  => $row['quantity']*$row['unit_price'],
                    'whscode'      => $row['warehouse'],
                    'shkzg'        => '+',
                    'createdon'    => getLocalDatabaseDateTime(),
                    'createdby'    => Auth::user()->email ?? Auth::user()->username
                    
                );
                array_push($insertData, $excelData);
                insertOrUpdate($insertData,'t_inv02');
    
                DB::table('t_inv_batch_stock')->insert([
                    'material'     => $row['material'],
                    'whscode'      => $row['warehouse'],
                    'batchnum'     => $batchNumber,
                    'quantity'     => $row['quantity'],
                    'unit'         => $row['unit'],
                    'last_udpate'  => getLocalDatabaseDateTime()
                ]);
    
                DB::table('t_inv_stock')->insert([
                    'material'     => $row['material'],
                    'whscode'      => $row['warehouse'],
                    'batchnum'     => $batchNumber,
                    'quantity'     => $row['quantity'],
                    'unit'         => $row['unit'],
                    'last_udpate'  => getLocalDatabaseDateTime()
                ]);
            }

            DB::commit();  
        }catch(\Exception $e){
            DB::rollBack();
            // return Redirect::to("/transaksi/withdraw")->withError($e->getMessage());
        }

        
    }
}
