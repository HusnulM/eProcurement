<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

class TemplateItemExport implements FromCollection, WithHeadings, WithMapping
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
        $query = DB::table('t_material');
        $query->limit(1);
        $query->orderBy('id');
        return $query->get();
    }

    public function map($row): array{
        $fields = [
            $row->matdesc,
            $row->partname,
            $row->mattype,
            $row->matunit,
        ];

        return $fields;
    }

    public function headings(): array
    {
        return [
                "Partnumber",
                "Partname",
                "Itemtype",
                "Itemunit",
        ];
    }
}
