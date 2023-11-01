<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

class PrExport implements FromCollection, WithHeadings, WithMapping
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
        $query = DB::table('v_rpr01');

        if(isset($req->department)){
            if($req->department !== 'All'){
                $query->where('deptid', $req->department);
            }
        }

        if(isset($req->approvalstat)){
            if($req->approvalstat === "O"){
                $query->where('approvestat', 'O');
            }elseif($req->approvalstat === "A"){
                $query->where('approvestat', 'A');
            }elseif($req->approvalstat === "R"){
                $query->where('approvestat', 'R');
            }
        }

        if(isset($req->datefrom) && isset($req->dateto)){
            $query->whereBetween('prdate', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('prdate', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('prdate', $req->dateto);
        }

        $query->orderBy('id');
        return $query->get();
    }

    public function map($row): array{
        $fields = [
            $row->prnum,
            $row->pritem,
            $row->prdate,
            $row->remark,
            $row->deptname,
            $row->material,
            $row->matdesc,
            $row->quantity,
            $row->unit,
            $row->pbjnumber,
            $row->pbjitem,
            $row->budget_code,
            $row->periode,
            $row->createdon,
            $row->createdby,
        ];

        return $fields;
    }

    public function headings(): array
    {
        return [
                "No. PR",
                "PR. Item",
                "Tanggal PR",
                "Remark",
                "Department",
                "Kode Item",
                "Deskripsi",
                "Quantity",
                "Unit",
                "NO. PBJ",
                "PBJ. Item",
                "Budget Cost Code",
                "Budget Period",
                "Created Date",
                "Created By",
        ];
    }
}
