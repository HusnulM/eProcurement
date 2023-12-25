@extends('layouts/App')

@section('title', 'List PBJ')

@section('additional-css')
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                    <div class="card-tools">
                        <!-- <a href="{{ url('transaction/budgeting') }}" class="btn btn-success btn-sm btn-add-dept">
                            <i class="fas fa-plus"></i> Buat Pengajuan Budget
                        </a> -->
                        <!-- <a href="{{ url('/master/department/create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Create Department
                        </a> -->
                        <a href="{{ url('/transaction/pbj') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbl-pbj-list" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                            <thead>
                                <th>No</th>
                                <th>Nomor PBJ</th>
                                <th>Tanggal PBJ</th>
                                <th>Tujuan Permintaan</th>
                                <th>Kepada</th>
                                <th>Unit Desc / Code</th>
                                <th>Engine Model</th>
                                <th>Created By</th>
                                <th>Creator Dept.</th>
                                <th>Draft</th>
                                <th></th>
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
        $("#tbl-pbj-list").DataTable({
            serverSide: true,
            ajax: {
                url: base_url+'/transaction/pbj/listpbj',
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
            columns: [
                { "data": null,"sortable": false, "searchable": false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {data: "pbjnumber", className: 'uid'},
                {data: "tgl_pbj", className: 'uid'},
                {data: "tujuan_permintaan"},
                {data: "kepada"},
                {data: "unit_desc" },
                {data: "engine_model"},
                {data: "createdby"},
                {data: "department"},
                {data: "is_draft"},
                {"defaultContent":
                    `
                    <button class='btn btn-success btn-sm button-print'> <i class='fa fa-print'></i> Print</button>
                    <button class='btn btn-primary btn-sm button-detail'> <i class='fa fa-search'></i> Detail</button>
                    <button class='btn btn-primary btn-sm button-change'> <i class='fa fa-edit'></i> Change</button>
                    `,
                    "className": "text-center",
                    "width": "20%"
                }
            ]
        });

        $('#tbl-pbj-list tbody').on( 'click', '.button-detail', function () {
            var table = $('#tbl-pbj-list').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            console.log(selected_data)
            window.location = base_url+"/transaction/pbj/detail/"+selected_data.id;
        });

        $('#tbl-pbj-list tbody').on( 'click', '.button-print', function () {
            var table = $('#tbl-pbj-list').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
                window.open(
                    base_url+"/transaction/pbj/print/"+selected_data.id,
                    '_blank'
                );
        });

        $('#tbl-pbj-list tbody').on( 'click', '.button-change', function () {
            var table = $('#tbl-pbj-list').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            window.location = "/transaction/pbj/change/"+selected_data.id;
        });



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
