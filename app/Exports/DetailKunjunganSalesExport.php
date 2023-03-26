<?php

namespace App\Exports;

use App\Models\DetailKunjunganSales;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

class DetailKunjunganSalesExport implements FromCollection, WithHeadings, WithMapping
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
        $query = DB::table('v_detail_data_kunjungan');

        if(isset($req->datefrom) && isset($req->dateto)){
            $query->whereBetween('tgl_visit', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('tgl_visit', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('tgl_visit', $req->dateto);
        }

        // if(isset($req->salesman)){
        //     $query->where('createdby', getUserEmail($req->salesman));
        // }

        if(getJabatanCode() == "SLS"){
            $query->where('createdby', Auth::user()->email);
        }

        $query->orderBy('id');
        return $query->get();
    }

    public function map($row): array{
        $fields = [
            $row->nomorvisit,
            $row->nama_outlet,
            $row->tgl_visit,
            $row->salesman,
            $row->material,
            $row->matdesc,
            $row->keterangan,
            $row->status_order,
        ];

        return $fields;
    }

    public function headings(): array
    {
        return [
                "Nomor Kunjungan",
                "Nama Outlet",
                "Tanggal Kunjungan",
                "Nama Sales",
                "Kode Barang",
                "Nama Barang",
                "Keterangan",
                "Order/Tidak"
        ];
    }
}
