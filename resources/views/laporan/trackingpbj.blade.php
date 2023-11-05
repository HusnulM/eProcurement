@extends('layouts/App')

@section('title', 'Laporan Tracking PBJ')

@section('additional-css')
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Tracking PBJ</h3>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="{{ url('report/exportpbj') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-2">
                                        <label for="">Tanggal PBJ</label>
                                        <input type="date" class="form-control" name="datefrom" id="datefrom" value="{{ $_GET['datefrom'] ?? '' }}">
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="">-</label>
                                        <input type="date" class="form-control" name="dateto" id="dateto" value="{{ $_GET['dateto'] ?? '' }}">
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="">Approval Status</label>
                                        <select name="approvalStatus" id="approvalStatus" class="form-control">
                                            <option value="All">All</option>
                                            <option value="O">Open</option>
                                            <option value="A">Approved</option>
                                            <option value="R">Rejected</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="">Department</label>
                                        <select name="department" id="department" class="form-control">
                                            <option value="All">All</option>
                                            @foreach($department as $key => $row)
                                                <option value="{{ $row->deptid }}">{{ $row->department }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3" style="text-align:center;">
                                        <br>
                                        <button type="button" class="btn btn-default mt-2 btn-search">
                                            <i class="fa fa-search"></i> Filter
                                        </button>
                                        <!-- <button type="submit" class="btn btn-success mt-2 btn-export">
                                            <i class="fa fa-download"></i> Export Data
                                        </button> -->
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="table-responsive">
                            <table id="tbl-budget-list" class="table table-bordered table-hover table-striped table-sm" style="width:130%;">
                                <thead>
                                    <th>No</th>
                                    <th>Tanggal PBJ</th>
                                    <th>Nomor PBJ</th>
                                    <th>Nama Item</th>
                                    <th>Status PBJ</th>
                                    <th>Next Approver</th>
                                    <th>Tanggal PR</th>
                                    <th>No. PR</th>
                                    <th>Status PR</th>
                                    <th>Tanggal PO</th>
                                    <th>No. PO</th>
                                    <th>Status PO</th>
                                    <th>Tanggal GR</th>
                                    <th>No. GR</th>
                                    <th>Status GR</th>
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
            var param = '?datefrom='+ $('#datefrom').val() +'&dateto='+ $('#dateto').val()+'&department='+$('#department').val()+'&pbjstatus='+$('#approvalStatus').val();
            loadDocument(param);
        });

        loadDocument('');

        function loadDocument(_params){
            $("#tbl-budget-list").DataTable({
                serverSide: true,
                // processing: true,
                ajax: {
                    url: base_url+'/report/trackingpbjlist'+_params,
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
                    {data: "tgl_pbj", className: 'uid',
                        // render: function (data, type, row){
                        //     return ``+ row.tgl_pbj.tgl_pbj1 + ``;
                        // }
                    },
                    {data: "pbjnumber", className: 'uid'},
                    {data: "description", className: 'uid'},
                    {data: "pbjstatus"},
                    {data: "pbjnextapproved"},
                    {data: "prdate",
                        // render: function (data, type, row){
                        //     return ``+ row.prdate.prdate1 + ``;
                        // }
                    },
                    {data: "prnum"},
                    {data: "prstatus"},
                    {data: "podat",
                        // render: function (data, type, row){
                        //     return ``+ row.podat.podat1 + ``;
                        // }
                    },
                    {data: "ponum"},
                    {data: "postatus"},
                    {data: "postdate",
                        // render: function (data, type, row){
                        //     return ``+ row.postdate.postdate1 + ``;
                        // }
                    },
                    {data: "docnum"},
                    {data: "grstatus"}
                ]
            });
        }

        // $.fn.dataTable.ext.errMode = 'throw';

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
