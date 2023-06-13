<?php

namespace App\Imports;

use App\Models\Vendor;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use DataTables, Auth, DB;

class VendorImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    // public function model(array $row)
    // {
    //     return new Vendor([
    //         //
    //     ]);
    // }

    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try{
            foreach ($rows as $index => $row) {
                $kodeVendor = generateVendorCode();
                $insertData = array();
                $excelData = array(
                    'vendor_code'    => $kodeVendor,
                    'vendor_name'    => $row['nama_vendor'],
                    'vendor_pt'      => $row['vendor_pt'] ?? null,
                    'vendor_profil'  => $row['vendor_profil'] ?? null,
                    'vendor_address' => $row['alamat_vendor'] ?? null,
                    'vendor_telp'    => $row['vendor_telp'] ?? null,
                    'bank_holder'    => $row['bank_holder'] ?? null,
                    'bank'           => $row['bank'] ?? null,
                    'no_rek'         => $row['no_rekening'] ?? null,
                    'catatan'        => $row['catatan'] ?? null,
                    'vendor_email'   => $row['email'] ?? null,
                    'contact_person' => $row['contactperson'] ?? null,
                    'createdon'      => getLocalDatabaseDateTime(),
                    'createdby'      => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $excelData);
                insertOrUpdate($insertData,'t_vendor');                
                DB::commit();
            }

            // return Redirect::to("/master/vendor")->withSuccess('Vendor created');
        } catch(\Exception $e){
            DB::rollBack();
            // return Redirect::to("/master/vendor")->withError($e->getMessage());
        }

        
    }
}
