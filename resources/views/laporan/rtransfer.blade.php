@extends('layouts/App')

@section('title', 'Laporan Transfer Barang')

@section('additional-css')
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Transfer Barang</h3>
                    <div class="card-tools">

                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="{{ url('report/exporttransfer') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-2">
                                        <label for="">Tanggal Transfer</label>
                                        <input type="date" class="form-control" name="datefrom" id="datefrom" value="{{ $_GET['datefrom'] ?? '' }}">
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="">-</label>
                                        <input type="date" class="form-control" name="dateto" id="dateto" value="{{ $_GET['dateto'] ?? '' }}">
                                    </div>
                                    <div class="col-lg-8" style="text-align:right;">
                                        <br>
                                        <button type="button" class="btn btn-default mt-2 btn-search">
                                            <i class="fa fa-search"></i> Filter
                                        </button>
                                        <button type="submit" class="btn btn-success mt-2 btn-export">
                                            <i class="fa fa-download"></i> Export Data
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="table-responsive">
                            <table id="tbl-budget-list" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                <thead>
                                    <th>No</th>
                                    <th>Nomor Transfer</th>
                                    <th>Tanggal Transfer</th>
                                    <th>Remark</th>
                                    <th>Partnumber</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th>Batch Number</th>
                                    <th>Warehouse Asal</th>
                                    <th>Warehouse Tujuan</th>
                                    <th>User</th>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('additional-modal')

@endsection

@section('additional-js')
<script>
    function validate(evt) {
        var theEvent = evt || window.event;

        // Handle paste
        if (theEvent.type === 'paste') {
            key = event.clipboardData.getData('text/plain');
        } else {
        // Handle key press
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
        }
        var regex = /[0-9]|\./;
        if( !regex.test(key) ) {
            theEvent.returnValue = false;
            if(theEvent.preventDefault) theEvent.preventDefault();
        }
    }

    $(document).ready(function(){

        $('.btn-search').on('click', function(){
            var param = '?datefrom='+ $('#datefrom').val() +'&dateto='+ $('#dateto').val();
            loadDocument(param);
        });

        loadDocument('');

        function loadDocument(_params){
            $("#tbl-budget-list").DataTable({
                serverSide: true,
                ajax: {
                    url: base_url+'/report/transferlist'+_params,
                    data: function (data) {
                        data.params = {
                            sac: "sac"
                        }
                    }
                },
                buttons: false,
                searching: true,
                scrollY: 500,
                scrollX: true,
                scrollCollapse: true,
                bDestroy: true,
                columns: [
                    { "data": null,"sortable": false, "searchable": false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {data: "docnum", className: 'uid'},
                    {data: "postdate", className: 'uid',
                        render: function (data, type, row){
                            return ``+ row.postdate.postdate1 + ``;
                        }
                    },
                    {data: "remark", className: 'uid'},

                    {data: "material", className: 'uid'},
                    {data: "matdesc", className: 'uid'},
                    {data: "quantity", "sortable": false,
                        render: function (data, type, row){
                            return ``+ row.quantity.qty1 + ``;
                        },
                        "className": "text-right",
                    },
                    {data: "unit"},
                    {data: "batch_number"},
                    {data: "whs_source"},
                    {data: "whsdestination"},
                    {data: "createdby", className: 'uid'},
                ]
            });
        }


        $('.inputNumber').on('change', function(){
            this.value = formatRupiah(this.value,'');
        });

        function formatRupiah(angka, prefix){
            var number_string = angka.toString().replace(/[^.\d]/g, '').toString(),
            split   		  = number_string.split('.'),
            sisa     		  = split[0].length % 3,
            rupiah     		  = split[0].substr(0, sisa),
            ribuan     		  = split[0].substr(sisa).match(/\d{3}/gi);

            if(ribuan){
                separator = sisa ? ',' : '';
                rupiah += separator + ribuan.join(',');
            }

            rupiah = split[1] != undefined ? rupiah + '.' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
        }
    });
</script>
@endsection
