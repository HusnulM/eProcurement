<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\InitialStockImport;
use DataTables, Auth, DB;
use Validator,Redirect,Response;
use Excel;

class InbalController extends Controller
{
    public function stock(){
        return view('transaksi.inbal.initialstock');
    }

    public function saveInbalStock(Request $request){
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');

        // membuat nama file unik
        $nama_file = $file->hashName();        

        $destinationPath = 'excel/';
        $file->move($destinationPath,$file->getClientOriginalName());

        config(['excel.import.startRow' => 2]);
        // import data
        $import = Excel::import(new InitialStockImport(), 'excel/'.$file->getClientOriginalName());

        //remove from server
		unlink('excel/'.$file->getClientOriginalName());

        if($import) {
            return Redirect::to("/inbal/stock")->withSuccess('Data Stock Berhasil di Upload');
        } else {
            return Redirect::to("/inbal/stock")->withError('Error');
        }
    }
}
