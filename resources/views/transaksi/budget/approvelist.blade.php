@extends('layouts/App')

@section('title', 'List Approve Pengajuan Budget')

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
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbl-budget-list" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                            <thead>
                                <th>No</th>
                                <th>Nomor PTA</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Nominal di Ajukan</th>
                                <th>Nominal di Setujui</th>
                                <th>Di Ajukan Oleh</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Remark</th>
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
<div class="modal fade" id="modal-approve-budget">
    <form action="{{ url('/approve/budget/save') }}" method="post">
        @csrf
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Approve Budget</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="ptanumber">Nomor PTA</label>
                            <input type="text" name="ptanumber" id="ptanumber" class="form-control" readonly>
                        </div>
                        <div class="col-lg-12">
                            <label for="tgl_aju">Tanggal Pengajuan</label>
                            <input type="date" name="tgl_aju" id="tgl_aju" class="form-control" readonly>
                        </div>
                        <div class="col-lg-12">
                            <label for="amount1">Nominal yg di Ajukan</label>
                            <input type="text" name="amount1" id="amount1" class="form-control" style="text-align:right;" readonly>
                        </div>
                        <div class="col-lg-12">
                            <label for="amount2">Nominal yg di setujui</label>
                            <input type="text" name="amount2" id="amount2" class="form-control inputNumber" style="text-align:right;" onkeypress='validate(event)'>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <label for="requester">Di Ajukan Oleh</label>
                            <input type="text" name="requester" id="requester" class="form-control" readonly>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <label for="department">Department</label>
                            <input type="text" name="department" id="department" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-check"></i> Approve Budget
                </button>
                </div>
            </div>
        </div>
    </form>
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
        $("#tbl-budget-list").DataTable({
            serverSide: true,
            ajax: {
                url: base_url+'/approve/budget/approvelist',
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
                {data: "ptanumber", className: 'uid'},
                {data: "tgl_aju", className: 'uid'},
                {data: "amount", "sortable": false,
                    render: function (data, type, row){
                        return ``+ row.amount.amount1 + ``;
                    },
                    "className": "text-right",
                },
                {data: "approved_amount",  "sortable": false,
                    render: function (data, type, row){
                        return ``+ row.approved_amount.amount2 + ``;
                    },
                    "className": "text-right",
                },
                {data: "requester_name"},
                {data: "deptname"},
                {data: "budget_status", 
                    render: function (data, type, row){
                        if(row.budget_status == "O"){
                            return `Open`;
                        }else if(row.budget_status == "A"){
                            return `Approved`;
                        }
                    }
                },                
                {data: "remark" },
                {"defaultContent": 
                    `
                    <button class='btn btn-success btn-sm button-approve-budget'> <i class='fa fa-check'></i> APPROVE</button>
                    `,
                    "className": "text-center",
                    "width": "10%"
                }
            ]  
        });

        $('#tbl-budget-list tbody').on( 'click', '.button-approve-budget', function () {
            var table = $('#tbl-budget-list').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();

            $('#ptanumber').val(selected_data.ptanumber);
            $('#tgl_aju').val(selected_data.tgl_aju);
            $('#amount1').val(selected_data.amount.amount1);
            $('#amount2').val(selected_data.approved_amount.amount2);
            $('#requester').val(selected_data.requester_name);
            $('#department').val(selected_data.deptname);

            console.log(selected_data)

            $('#modal-approve-budget').modal('show');
            // window.location = base_url+"/config/users/delete/"+selected_data.id;
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