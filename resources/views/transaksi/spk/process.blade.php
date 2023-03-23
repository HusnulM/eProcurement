@extends('layouts/App')

@section('title', 'List Work Order')

@section('additional-css')
@endsection

@section('content')        
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">List Work Order</h3>
                    <div class="card-tools">
                        
                        {{-- <a href="{{ url('/logistic/wo') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Back
                        </a> --}}
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbl-wo-list" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                            <thead>
                                <th>No</th>
                                <th>Nomor WO</th>
                                <th>Tanggal WO</th>
                                <th>Keterangan</th>
                                <th>Warehouse</th>
                                <th>Schedule Type</th>
                                <th>Issued</th>
                                <th>WO Status</th>
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
<div class="modal fade" id="modal-process-wo">
    <div class="modal-dialog modal-md">
        <form action="{{ url('logistic/wo/saveprocess') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update WO Status / Process</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="">WO / SPK Number</label>
                            <input type="text" name="wonum" id="wonum" class="form-control" readonly>
                        </div> 
                        <div class="col-lg-12">
                            <label for="">WO / SPK Status</label>
                            <select name="wo_process" id="wo_process" class="form-control" required>
                                <option value="">---</option>
                                <option value="Open">Open</option>
                                <option value="Waiting Sparepart">Waiting Sparepart</option>
                                <option value="Waiting ManPower">Waiting ManPower</option>
                                <option value="On Proses">On Proses</option>
                                <option value="Closed">Closed</option>
                            </select>
                        </div> 
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
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
        loadDocument();

        function loadDocument(){
            $("#tbl-wo-list").DataTable({
                serverSide: true,
                ajax: {
                    url: base_url+'/logistic/wo/listdatawotoprocess',
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
                    {data: "wonum", className: 'uid'},
                    {data: "wodate", className: 'uid',
                        render: function (data, type, row){
                            return ``+ row.wodate.wodate1 + ``;
                        }
                    },
                    {data: "description", className: 'uid'},
                    // {data: "nama_mekanik", className: 'uid'},
                    {data: "whsname", className: 'uid'},
                    // {data: "license_number", className: 'uid'},
                    {data: "schedule_type", className: 'uid'},
                    {data: "issued", className: 'uid'},
                    {data: "wo_process", className: 'uid'},
                    {"defaultContent": 
                        `
                        <button class='btn btn-primary btn-sm button-detail'> <i class='fa fa-search'></i> Update WO Status</button>
                        `,
                        "className": "text-center",
                    }
                ]  
            });

            // $('#tbl-wo-list tbody').on( 'click', '.button-print', function () {                
            //     var table = $('#tbl-wo-list').DataTable();
            //     selected_data = [];
            //     selected_data = table.row($(this).closest('tr')).data();
            //     // window.location = base_url+"/proc/pr/print";
            //     // if(selected_data.doctype === "Corporate Procedure"){
            //         window.open(
            //             base_url+"/printdoc/wo/print/"+selected_data.id,
            //             '_blank' // <- This is what makes it open in a new window.
            //         );
            //     // }
            // });

            $('#tbl-wo-list tbody').on( 'click', '.button-detail', function () {                
                var table = $('#tbl-wo-list').DataTable();
                selected_data = [];
                selected_data = table.row($(this).closest('tr')).data();

                $('#wonum').val(selected_data.wonum)
                $('#modal-process-wo').modal('show');
                // window.location = "/logistic/wo/detail/"+selected_data.id;
                // if(selected_data.doctype === "Corporate Procedure"){
                    // window.open(
                    //     base_url+"/printdoc/pr/print/"+selected_data.id,
                    //     '_blank' // <- This is what makes it open in a new window.
                    // );
                // }
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