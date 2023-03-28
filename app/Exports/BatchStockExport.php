<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

class BatchStockExport implements FromCollection, WithHeadings, WithMapping
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
        $query = DB::table('v_inv_batch_stock');

        $query->orderBy('material','asc');
        $query->orderBy('whsid','asc');
        $query->orderBy('batchnum','asc');
        return $query->get();
    }

    public function map($row): array{
        $fields = [
            $row->material,
            $row->matdesc,
            "BATCH".$row->batchnum,
            $row->whsname,
            $row->quantity,
            $row->unit,
        ];

        return $fields;
    }

    public function headings(): array
    {
        return [
                "Material",
                "Deskripsi",
                "Batch Number",
                "Warehouse",
                "Quantity",
                "Unit",
        ];
    }
}
