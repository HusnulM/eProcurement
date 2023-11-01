<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

class PoExport implements FromCollection, WithHeadings, WithMapping
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
        $query = DB::table('v_rpo');

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
            $query->whereBetween('podat', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('podat', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('podat', $req->dateto);
        }

        $query->orderBy('id');
        return $query->get();
    }

    public function map($row): array{
        $fields = [
            $row->ponum,
            $row->poitem,
            $row->podat,
            $row->note,
            $row->vendor_name,
            $row->deptname,
            $row->material,
            $row->matdesc,
            $row->quantity,
            $row->grqty,
            $row->openqty,
            $row->unit,
            $row->price,
            $row->price*$row->quantity,
            $row->currency,
            $row->prnum ?? null,
            $row->budget_code,
            $row->budget_period,
            $row->createdon,
            $row->createdby,
        ];

        return $fields;
    }

    public function headings(): array
    {
        return [
                "No. PO",
                "PO. Item",
                "Tanggal PO",
                "Remark",
                "Vendor",
                "Department",
                "Kode Item",
                "Deskripsi",
                "Quantity",
                "Receipt Qty",
                "Open Qty",
                "Unit",
                "Price Unit",
                "Total Price",
                "Currency",
                "No. PR",
                "Cost Code",
                "Budget Period",
                "Created Date",
                "Created By",
        ];
    }
}
