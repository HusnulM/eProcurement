<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;


class PbjExport implements FromCollection, WithHeadings, WithMapping
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
        $query = DB::table('v_rpbj01');

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
            $query->whereBetween('tgl_pbj', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('tgl_pbj', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('tgl_pbj', $req->dateto);
        }

        $query->orderBy('id');
        return $query->get();
    }

    public function map($row): array{
        $fields = [
            $row->pbjnumber,
            $row->tgl_pbj,
            $row->tujuan_permintaan,
            $row->kepada,
            $row->unit_desc,
            $row->requestor,
            $row->nama_project,
            $row->engine_model,
            $row->chassis_sn,
            $row->reference,
            $row->requestor,
            $row->type_model,
            $row->mekanik,
            $row->kode_brg_jasa,
            $row->periode,
            $row->engine_sn,
            $row->pbjitem,
            $row->partnumber,
            $row->description,
            $row->quantity,
            $row->unit,
            $row->figure,
            $row->remark,
        ];

        return $fields;
    }

    public function headings(): array
    {
        return [
                "No. PBJ",
                "Tanggal PBJ",
                "Tujuan Permintaan",
                "Kepada",
                "No. Plat",
                "Requestor",
                "Project",
                "Engine Model",
                "Chassis SN",
                "Reference",
                "Requestor",
                "Type Model",
                "Mekanik",
                "Kode Barang/Jasa",
                "Periode",
                "Engine SN",
                "PBJ Item",
                "Kode Item",
                "Deskripsi",
                "Quantity",
                "Unit",
                "Figure",
                "Remark",
        ];
    }
}
