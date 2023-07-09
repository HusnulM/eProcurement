<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

class DetailCostExport implements FromCollection, WithHeadings, WithMapping
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
        $query = DB::table('v_detail_cost');

        if(isset($req->nopol)){
            $query->where('unit_desc', $req->nopol);
        }

        if(isset($req->kodeproj)){
            if($req->kodeproj !== 'All'){
                $query->where('kode_project', $req->kodeproj);
            }
        }

        if(isset($req->datefrom) && isset($req->dateto)){
            $query->whereBetween('postdate', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('postdate', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('postdate', '<=', $req->dateto);
        }

        $query->orderBy('id');
        return $query->get();
    }

    public function map($row): array{
        $fields = [
            $row->docnum,
            $row->postdate,
            $row->wonum,
            $row->woitem,
            $row->cheklistnumber,
            $row->unit_desc,
            $row->type_model,
            $row->material,
            $row->matdesc,
            $row->quantity,
            $row->unit,
            $row->nama_project,
            $row->unit_price,
            $row->total_price,
        ];

        return $fields;
    }

    public function headings(): array
    {
        return [
                "No. Issue",
                "Tanggal Issue",
                "No. PBJ",
                "PBJ. Item",
                "No. Checklist",
                "No. Plat",
                "Model Kendaraan",
                "Material",
                "Material Description",
                "Quantity",
                "Unit",
                "Project",
                "Unit Cost",
                "Total Cost",
        ];
    }
}
