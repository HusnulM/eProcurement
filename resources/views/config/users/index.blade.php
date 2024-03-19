@extends('layouts/App')

@section('title', 'User List')

@section('additional-css')
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">User List</h3>
                    <div class="card-tools">
                        <a href="{{ url('/config/users/create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Create User
                        </a>
                        <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                            <i class="fas fa-times"></i>
                        </button> -->
                    </div>
                </div>
                <div class="card-body">
                    <div class="dataTables_wrapper dt-bootstrap4">
                        <table id="tbl-users" class="table table-bordered table-hover table-striped table-sm dataTable dtr-inline" style="width:100%;">
                            <thead>
                                <th>No</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Name</th>
                                {{-- <th>Department</th> --}}
                                <th>Jabatan</th>
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
        $("#tbl-users").DataTable({
            serverSide: true,
            ajax: {
                url: base_url+'/config/users/userlist',
                data: function (data) {
                    data.params = {
                        sac: "sac"
                    }
                }
            },
            responsive: true,
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
                {data: "username", className: 'uid'},
                {data: "email", className: 'fname'},
                {data: "name", className: 'uname'},
                // {data: "department", className: 'fname'},
                {data: "jabatan", className: 'uname'},
                {"defaultContent":
                    `<button class='btn btn-danger btn-sm button-delete'> <i class='fa fa-trash'></i> DELETE</button>
                    <button class='btn btn-primary btn-sm button-edit'> <i class='fa fa-edit'></i> EDIT</button>
                    <button class='btn btn-success btn-sm button-object-auth'> <i class='fa fa-find'></i> OBJECT AUTH</button>
                    `,
                    "className": "text-center",
                    "width": "20%"
                }
            ]
        });

        $('#tbl-users tbody').on( 'click', '.button-delete', function () {
            var table = $('#tbl-users').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            window.location = base_url+"/config/users/delete/"+selected_data.id;
        });
        $('#tbl-users tbody').on( 'click', '.button-edit', function () {
            var table = $('#tbl-users').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            window.location = base_url+"/config/users/edit/"+selected_data.id;
        });
        $('#tbl-users tbody').on( 'click', '.button-object-auth', function () {
            var table = $('#tbl-users').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            window.location = base_url+"/config/users/objectauth/"+selected_data.id;
        });
    });
</script>
@endsection
