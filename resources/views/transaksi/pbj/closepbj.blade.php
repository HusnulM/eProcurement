@extends('layouts/App')

@section('title', 'Close PBJ')

@section('additional-css')
<style>
    td.details-control {
        background: url("{{ ('/assets/dist/img/show_detail.png') }}") no-repeat center center;
        cursor: pointer;
    }
    tr.shown td.details-control {
        background: url("{{ ('/assets/dist/img/close_detail.png') }}") no-repeat center center;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Close PBJ</h3>
                    <div class="card-tools">

                        {{-- <a href="{{ url('/transaction/pbj') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Back
                        </a> --}}
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
                                <th>Department</th>
                                <th style="width:5%;"></th>
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
        let _token   = $('meta[name="csrf-token"]').attr('content');

        $("#tbl-pbj-list").DataTable({
            serverSide: true,
            ajax: {
                url: base_url+'/pbj/listopenpbj',
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
                {
                    "className":      'details-control',
                    "orderable":      false,
                    "searchable":     false,
                    "data":           null,
                    "defaultContent": '',
                    "width": "30px"
                },
                {data: "pbjnumber", className: 'uid'},
                {data: "tgl_pbj", className: 'uid'},
                {data: "tujuan_permintaan"},
                {data: "kepada"},
                {data: "unit_desc" },
                {data: "engine_model"},
                {data: "createdby"},
                {data: "department"},
                {"defaultContent":
                    `
                    <button class='btn btn-primary btn-sm button-close'> <i class='fa fa-save'></i> Close PBJ</button>
                    `,
                    "className": "text-center",
                    "width": "20%"
                }
            ]
        });

        $('#tbl-pbj-list tbody').on( 'click', '.button-close', function () {
            var table = $('#tbl-pbj-list').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            console.log(selected_data)

            $.ajax({
                url: base_url+'/pbj/closepbj',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    pbjnumber: selected_data.pbjnumber,
                    _token: _token
                },
                dataType: 'json',
                cache:false,
                success: function(result){
                },
                error: function(err){
                    console.log(err)
                }
            }).done(function(data){
                toastr.info(data.message);

                setTimeout(function(){
                    location.reload();
                }, 2000);
            });
        });


        $('#tbl-pbj-list tbody').on('click', 'tr td.details-control', function () {

            var tabledata = $('#tbl-pbj-list').DataTable();
            var tr = $(this).closest('tr');
            var row = tabledata.row( tr );
            var d = row.data();
            console.log(d)
            console.log(row.child.isShown())
            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
            }else{
                $.ajax({
                    url: base_url+'/pbj/getitems',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data:{
                        pbjnumber: d.pbjnumber,
                        _token: _token
                    },
                    dataType: 'json',
                    cache:false,
                    success: function(result){
                    },
                    error: function(err){
                        console.log(err)
                    }
                }).done(function(data){

                    if ( row.child.isShown() ) {
                        row.child.hide();
                        tr.removeClass('shown');
                    }
                    else {
                        row.child( format(row.data(), data) ).show();
                        tr.addClass('shown');
                    }
                });
            }

            function format ( d, results ) {
                // console.log(results)
                var tdStyle = '';
                var appStat = '';
                var appNote = '';
                var appDate = '';
                var appBy   = '';

                var html = '';
                html = `<table class="table table-bordered table-hover table-striped table-sm">
                    <thead>
                            <th>PBJ Item</th>
                            <th>Kode Item</th>
                            <th>Nama Item</th>
                            <th>Quantity</th>
                            <th>Figure</th>
                            <th>Remark</th>
                    </thead>
                    <tbody>`;
                    for(var i = 0; i < results.length; i++){
                        html +=`
                        <tr style="background-color:gray; color:white;">
                            <td> `+ results[i].pbjitem +` </td>
                            <td> `+ results[i].partnumber +` </td>
                            <td> `+ results[i].description +` </td>
                            <td style="text-align:right;"> `+ (results[i].quantity*1) +` `+ results[i].unit +` </td>
                            <td style="text-align:left;"> `+ results[i].figure +` </td>
                            <td style="text-align:left;"> `+ results[i].remark +` </td>
                        </tr>
                        `;
                    }

                html +=`</tbody>
                        </table>`;
                return html;
            }
        });

    });
</script>
@endsection
