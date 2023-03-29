<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

class CostExport implements FromCollection, WithHeadings, WithMapping
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
        $query = DB::table('v_report_cost');

        if(isset($req->mekanik)){
            if($req->mekanik !== 'All'){
                $query->where('mekanik', $req->mekanik);
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
            $row->description,
            $row->license_number,
            $row->material,
            $row->matdesc,
            $row->quantity,
            $row->unit,
            $row->mekanik,
            $row->total_price,
        ];

        return $fields;
    }

    public function headings(): array
    {
        return [
                "No. WO",
                "WO. Item",
                "Tanggal WO",
                "Deskripsi WO",
                "No. Plat Kendaraan",
                "Material",
                "Deskripsi Material",
                "Quantity",
                "Unit",
                "Mekanik",
                "Total Cost",
        ];
    }
}
