@extends('layouts/App')

@section('title', 'Vendor Master')

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
                        <a href="{{ url('/master/vendor/create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Create Vendor
                        </a>

                        <a href="{{ url('/master/vendor/upload') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-upload"></i> Upload Master Vendor
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbl-item-master" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                            <thead>
                                <th>No</th>
                                <th>Kode Vendor</th>
                                <th>Nama Vendor</th>
                                <th>Alamat</th>
                                <th>No. Telephone</th>
                                <th>Email</th>
                                <th>Contact Person</th>
                                <th style="text-align:center;"></th>
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

@section('additional-js')
<script>
    $(document).ready(function(){
        $("#tbl-item-master").DataTable({
            serverSide: true,
            ajax: {
                url: base_url+'/master/vendor/vendorlists',
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
                {data: "vendor_code", className: 'uid'},
                {data: "vendor_name", className: 'fname'},
                {data: "vendor_address", className: 'uname'},
                {data: "vendor_telp", className: 'uname'},
                {data: "vendor_email", className: 'uname'},
                {data: "contact_person", className: 'uname'},
                {"defaultContent": 
                    `<button class='btn btn-danger btn-sm button-delete'> <i class='fa fa-trash'></i> DELETE</button> 
                    <button class='btn btn-primary btn-sm button-edit'> <i class='fa fa-edit'></i> EDIT</button>
                    `
                }
            ]  
        });

        $('#tbl-item-master tbody').on( 'click', '.button-delete', function () {
            var table = $('#tbl-item-master').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            window.location = base_url+"/master/vendor/delete/"+selected_data.vendor_code;
        });
        $('#tbl-item-master tbody').on( 'click', '.button-edit', function () {
            var table = $('#tbl-item-master').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            window.location = base_url+"/master/vendor/edit/"+selected_data.vendor_code;
        });
    });
</script>
@endsection