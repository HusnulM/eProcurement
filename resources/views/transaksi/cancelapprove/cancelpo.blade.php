@extends('layouts/App')

@section('title', 'Reset Approval PO')

@section('additional-css')
@endsection

@section('content')        
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Reset Approval PO</h3>
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
                        <table id="tbl-pr-list" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                            <thead>
                                <th>No</th>
                                <th>Nomor PO</th>
                                <th>Tanggal PO</th>
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

        $("#tbl-pr-list").DataTable({
            serverSide: true,
            ajax: {
                url: base_url+'/cancel/approve/po/list',
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
                {data: "ponum", className: 'uid'},
                {data: "podat", className: 'uid'},
                {data: "vendor_name"},
                {data: "note"},      
                {"defaultContent": 
                    `<button class='btn btn-default btn-sm button-reset-approval'> <i class='fa fa-search'></i> Reset Approval</button>
                    <button class='btn btn-danger btn-sm button-delete'> <i class='fa fa-trash'></i> Delete</button>
                    `,
                    "className": "text-center",
                    "width": "15%"
                }
            ]  
        });

        $('#tbl-pr-list tbody').on( 'click', '.button-reset-approval', function () {
            var table = $('#tbl-pr-list').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            console.log(selected_data)
            
            $.ajax({
                url: base_url+'/cancel/approve/po/reset/'+selected_data.id,
                type:"POST",
                data:{
                    _token: _token
                },
                success:function(response){
                    console.log(response);
                    if(response.msgtype === "200"){
                        toastr.success(response.message)

                        setTimeout(function(){ 
                            window.location.href = base_url+'/cancel/approve/po';
                        }, 2000);
                    }else if(response.msgtype === "500"){
                        toastr.error(response.message);
                        setTimeout(function(){ 
                            location.reload();
                        }, 2000);
                    }
                },
                error: function(error) {
                    console.log(error);
                    toastr.error(error)

                    setTimeout(function(){ 
                        location.reload();
                    }, 2000);
                }
            }).done(function(response){
                console.log(response);
            });
        });

        $('#tbl-pr-list tbody').on( 'click', '.button-delete', function () {
            var table = $('#tbl-pr-list').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            console.log(selected_data)
            
            $.ajax({
                url: base_url+'/cancel/approve/po/delete/'+selected_data.id,
                type:"POST",
                data:{
                    _token: _token
                },
                success:function(response){
                    console.log(response);
                    if(response.msgtype === "200"){
                        toastr.success(response.message)

                        setTimeout(function(){ 
                            window.location.href = base_url+'/cancel/approve/po';
                        }, 2000);
                    }else if(response.msgtype === "500"){
                        toastr.error(response.message);
                        setTimeout(function(){ 
                            location.reload();
                        }, 2000);
                    }
                },
                error: function(error) {
                    console.log(error);
                    toastr.error(error)

                    setTimeout(function(){ 
                        location.reload();
                    }, 2000);
                }
            }).done(function(response){
                console.log(response);
            });
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