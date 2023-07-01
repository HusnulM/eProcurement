<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

class CostPerkendaraanExport implements FromCollection, WithHeadings, WithMapping
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
        $query = DB::table('v_summary_cost01');

        if(isset($req->nopol)){
            $query->where('no_plat', $req->nopol);
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
            $row->no_plat,
            $row->type_model,
            $row->postdate,
            $row->total_cost,
        ];

        return $fields;
    }

    public function headings(): array
    {
        return [
                "No. Plat",
                "Model Kendaraan",
                "Tanggal",
                "Total Cost",
        ];
    }
}
