<?php

namespace App\Exports;

use App\Models\KunjunganSales;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

class KunjunganSalesExport implements FromCollection, WithHeadings, WithMapping
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
        // return KunjunganSales::all();
        $query = DB::table('v_total_waktu_kunjungan');

        if(isset($req->datefrom) && isset($req->dateto)){
            $query->whereBetween('date', [$req->datefrom, $req->dateto]);
        }elseif(isset($req->datefrom)){
            $query->where('date', $req->datefrom);
        }elseif(isset($req->dateto)){
            $query->where('date', $req->dateto);
        }

        if(isset($req->salesman)){
            $query->where('userid', $req->salesman);
        }

        if(getJabatanCode() == "SLS"){
            $query->where('userid', Auth::user()->id);
        }

        $query->orderBy('id');

        return $query->get();
    }

    public function map($row): array{
        $fields = [
           $row->nama_outlet,
           $row->date,
           $row->name,
           $row->checkin,
           $row->checkout,
           $row->totalJamKunjungan,
        ];

        return $fields;
    }

    public function headings(): array
    {
        return [
                "Nama Outlet",
                "Tanggal Kunjungan",
                "Nama Sales",
                "Jam Masuk",
                "Jam Keluar",
                "Total Waktu Kunjungan (menit)"
        ];
    }
}
