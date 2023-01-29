@extends('layouts/App')

@section('title', 'Print GR PO')

@section('additional-css')
@endsection

@section('content')        
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">List GR PO</h3>
                    <div class="card-tools">
                        <!-- <a href="{{ url('transaction/budgeting') }}" class="btn btn-success btn-sm btn-add-dept">
                            <i class="fas fa-plus"></i> Buat Pengajuan Budget
                        </a> -->
                        <!-- <a href="{{ url('/master/department/create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Create Department
                        </a> -->
                        <!-- <a href="{{ url('/logistic/wo') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Back
                        </a> -->
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="{{ url('printdoc/grpo/export') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-2">
                                        <label for="">Receipt Date</label>
                                        <input type="date" class="form-control" name="datefrom" id="datefrom" value="{{ $_GET['datefrom'] ?? '' }}">
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="">-</label>
                                        <input type="date" class="form-control" name="dateto" id="dateto" value="{{ $_GET['dateto'] ?? '' }}">
                                    </div>
                                    <div class="col-lg-4" style="text-align:right;">
                                        <br>
                                        <button type="button" class="btn btn-default mt-2 btn-search"> 
                                            <i class="fa fa-search"></i> Filter
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table id="tbl-wo-list" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                    <thead>
                                        <th>No</th>
                                        <th>Receipt Number</th>
                                        <th>Receipt Date</th>
                                        <th>Received By</th>
                                        <th>Vendor</th>
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
        loadDocument();

        function loadDocument(){
            $("#tbl-wo-list").DataTable({
                serverSide: true,
                ajax: {
                    url: base_url+'/printdoc/grpo/printlist',
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
                    {data: "received_by", className: 'uid'},
                    {data: "vendor_name", className: 'uid'},
                    {data: "remark", className: 'uid'},
                    {"defaultContent": 
                        `
                        <button class='btn btn-success btn-sm button-print'> <i class='fa fa-print'></i> Print</button>
                        <button class='btn btn-primary btn-sm button-detail'> <i class='fa fa-search'></i> View Detail</button>
                        `,
                        "className": "text-center",
                    }
                ]  
            });

            $('#tbl-wo-list tbody').on( 'click', '.button-print', function () {                
                var table = $('#tbl-wo-list').DataTable();
                selected_data = [];
                selected_data = table.row($(this).closest('tr')).data();
                // window.location = base_url+"/proc/pr/print";
                // if(selected_data.doctype === "Corporate Procedure"){
                    window.open(
                        base_url+"/printdoc/grpo/print/"+selected_data.id,
                        '_blank' // <- This is what makes it open in a new window.
                    );
                // }
            });

            $('#tbl-wo-list tbody').on( 'click', '.button-detail', function () {                
                var table = $('#tbl-wo-list').DataTable();
                selected_data = [];
                selected_data = table.row($(this).closest('tr')).data();
                window.location = "/printdoc/grpo/detail/"+selected_data.id;
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