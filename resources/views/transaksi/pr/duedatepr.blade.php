@extends('layouts/App')

@section('title', 'List Due Date PR')

@section('additional-css')
@endsection

@section('content')        
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">List Due Date PR</h3>
                    <div class="card-tools">
                        <!-- <a href="{{ url('transaction/budgeting') }}" class="btn btn-success btn-sm btn-add-dept">
                            <i class="fas fa-plus"></i> Buat Pengajuan Budget
                        </a> -->
                        <!-- <a href="{{ url('/master/department/create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Create Department
                        </a> -->
                        {{-- <a href="{{ url('/proc/pr') }}" class='btn btn-default btn-sm'> 
                            <i class='fa fa-arrow-left'></i> Back
                        </a> --}}
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table id="tbl-budget-list" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                <thead>
                                    <th>No</th>
                                    <th>Nomor PR</th>
                                    <th>Tanggal PR</th>
                                    <th>Partnumber</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    {{-- <th>Department</th> --}}
                                    <th>Status</th>
                                    <th>Remark</th>
                                    <th>Aging Days</th>
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

        loadDocument('');

        function loadDocument(_params){
            $("#tbl-budget-list").DataTable({
                serverSide: true,
                ajax: {
                    url: base_url+'/proc/pr/duedateprlist',
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
                    {data: "prnum", className: 'uid',
                        // render: function (data, type, row){
                        //     return `<span style="color:red;">`+ row.prnum + `</span>`;
                        //     // $('td', row).eq(0).css('color', 'red');
                        // },
                    },
                    {data: "prdate", className: 'uid',
                        render: function (data, type, row){
                            return ``+ row.prdate.prdate1 + ``;
                        }
                    },
                    {data: "material", className: 'uid'},
                    {data: "matdesc", className: 'uid'},
                    {data: "quantity", "sortable": false,
                        render: function (data, type, row){
                            return ``+ row.quantity.quantity1 + ``;
                        },
                        "className": "text-right",
                    },
                    {data: "unit"},
                    // {data: "deptname"},
                    {data: "approvestat", 
                        render: function (data, type, row){
                            if(row.approvestat == "O"){
                                return `Open`;
                            }else if(row.approvestat == "A"){
                                return `Approved`;
                            }else if(row.approvestat == "R"){
                                return `Rejected`;
                            }else{
                                return `Open`;
                            }
                        }
                    },                
                    {data: "remark" },
                    {data: "duedate" },
                    // {data: "pbjitem" },
                    // {"defaultContent": 
                    //     `
                    //     <button class='btn btn-success btn-sm button-print'> <i class='fa fa-print'></i> Print</button>
                    //     <button class='btn btn-primary btn-sm button-detail'> <i class='fa fa-search'></i> View Detail</button>
                    //     <button class='btn btn-primary btn-sm button-change'> <i class='fa fa-edit'></i> Change</button>
                        
                    //     `,
                    //     "className": "text-center",
                    //     "width": "20%"
                    // }
                ]  
            });

            // <button class='btn btn-danger btn-sm button-delete'> <i class='fa fa-trash'></i> Delete</button>

            $('#tbl-budget-list tbody').on( 'click', '.button-print', function () {                
                var table = $('#tbl-budget-list').DataTable();
                selected_data = [];
                selected_data = table.row($(this).closest('tr')).data();
                // window.location = base_url+"/proc/pr/print";
                // if(selected_data.doctype === "Corporate Procedure"){
                    window.open(
                        base_url+"/proc/pr/print/"+selected_data.id,
                        '_blank' // <- This is what makes it open in a new window.
                    );
                // }
            });

            // $('#tbl-budget-list tbody').on( 'click', '.button-delete', function () {                
            //     var table = $('#tbl-budget-list').DataTable();
            //     selected_data = [];
            //     selected_data = table.row($(this).closest('tr')).data();
            //     window.location = "/proc/pr/delete/"+selected_data.id;
            // });

            $('#tbl-budget-list tbody').on( 'click', '.button-detail', function () {                
                var table = $('#tbl-budget-list').DataTable();
                selected_data = [];
                selected_data = table.row($(this).closest('tr')).data();
                window.location = "/proc/pr/detail/"+selected_data.id;
            });

            $('#tbl-budget-list tbody').on( 'click', '.button-change', function () {                
                var table = $('#tbl-budget-list').DataTable();
                selected_data = [];
                selected_data = table.row($(this).closest('tr')).data();
                window.location = "/proc/pr/change/"+selected_data.id;
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