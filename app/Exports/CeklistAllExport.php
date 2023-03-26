<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

class CeklistAllExport implements FromCollection, WithHeadings, WithMapping
{
    protected $req;

    function __construct($req) {
        $this->req = $req;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = DB::table('v_checklist_kendaraan');

        if(isset($req->datefrom) && isset($req->dateto)){
            $query->whereBetween('tanggal_cek', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('tanggal_cek', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('tanggal_cek', $req->dateto);
        }

        $query->orderBy('id');
        return $query->get();
    }

    public function map($row): array{
        $fields = [
            $row->no_checklist,
            $row->tanggal_cek,
            $row->no_kendaraan,
            $row->jenis_kendaraan,
            $row->nomor_rangka,
            $row->bahan_bakar,
            $row->tahun,
            $row->odometer,
            $row->nama_driver,
            $row->hasil_pemeriksaan,
            $row->pbjnumber,
            $row->createdon,
            $row->createdby,
        ];

        return $fields;
    }

    public function headings(): array
    {
        return [
                "No. Ceklist",
                "Tanggal Ceklist",
                "No. Plat",
                "Jenis Kendaraan",
                "Nomor Rangka",
                "Bahan Bakar",
                "Tahun",
                "Odometer",
                "Nama Driver",
                "Hasil Pemeriksaan",
                "No. PBJ",
                "Created Date",
                "Created By",
        ];
    }
}
