<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

class TransferExport implements FromCollection, WithHeadings, WithMapping
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
        $query = DB::table('v_report_transfer');

        if(isset($req->datefrom) && isset($req->dateto)){
            $query->whereBetween('postdate', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('postdate', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('postdate', $req->dateto);
        }

        $query->orderBy('id');
        return $query->get();
    }

    public function map($row): array{
        $fields = [
            $row->docnum,
            $row->docdate,
            $row->remark,
            $row->material,
            $row->matdesc,
            $row->quantity,
            $row->unit,
            $row->batch_number,
            $row->whs_source,
            $row->whsdestination,
            $row->createdby,
        ];

        return $fields;
    }

    public function headings(): array
    {
        return [
                "No. Transfer",
                "Tanggal Transfer",
                "Remark",
                "Kode Item",
                "Deskripsi",
                "Quantity",
                "Unit",
                "No. Batch",
                "Warehouse Asal",
                "Warehouse Tujuan",
                "Created By",
        ];
    }
}
