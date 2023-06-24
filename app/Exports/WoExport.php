<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

class WoExport implements FromCollection, WithHeadings, WithMapping
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
        $query = DB::table('v_rwo01');

        if(isset($req->mekanik)){
            if($req->mekanik !== 'All'){
                $query->where('mekanik', $req->mekanik);
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
            $query->whereBetween('wodate', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('wodate', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('wodate', $req->dateto);
        }

        $query->orderBy('id');
        return $query->get();
    }

    public function map($row): array{
        $fields = [
            $row->wonum,
            $row->woitem,
            $row->wodate,
            $row->license_number,
            $row->description,
            $row->material,
            $row->matdesc,
            $row->quantity,
            $row->unit,
            $row->mekanik,
            $row->wo_process,
            $row->whsname,
            $row->refdoc,
            $row->createdon,
            $row->createdby,
        ];

        return $fields;
    }

    public function headings(): array
    {
        return [
                "No. WO",
                "WO. Item",
                "Tanggal WO",
                "No. Plat",
                "Ketarangan",
                "Kode Item",
                "Deskripsi",
                "Quantity",
                "Unit",
                "Mekanik",
                "Status",
                "Warehouse",
                "No. PBJ",
                "Created Date",
                "Created By",
        ];
    }
}
