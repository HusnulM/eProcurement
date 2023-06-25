<?php

namespace App\Exports;

use App\Models\Issued;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

class IssuedExport implements FromCollection, WithHeadings, WithMapping
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
        $query = DB::table('v_rissue');

        if(isset($req->datefrom) && isset($req->dateto)){
            $query->whereBetween('docdate', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('docdate', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('docdate', $req->dateto);
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
            $row->wonum,
            $row->woitem,
            $row->whsname,
            $row->createdby,
        ];

        return $fields;
    }

    public function headings(): array
    {
        return [
                "No. Pengeluaran",
                "Tanggal Pengeluaran",
                "Remark",
                "Kode Item",
                "Deskripsi",
                "Quantity",
                "Unit",
                "No. PBJ",
                "PBJ Item",
                "Warehouse",
                "Created By",
        ];
    }
}
