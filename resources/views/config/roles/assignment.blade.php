@extends('layouts/App')

@section('title', 'Role Assignment')

@section('additional-css')
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Role Assignment</h3>
                    <div class="card-tools">
                        <a href="{{ url('/config/roles') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ url('/config/roles/save') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="rolename">Role Name</label>
                                            <input type="text" name="rolename" id="rolename" class="form-control" value="{{ $datarole->rolename }}" readonly>
                                            <input type="hidden" name="roleid" id="roleid" class="form-control" value="{{ $datarole->id }}">
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <a href="{{ url('/config/roles') }}" class="btn btn-default btn-sm">
                                            <i class="fa fa-arrow-left"></i> Back
                                        </a>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fa fa-save"></i> SAVE
                                        </button>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <!-- <h4 class="mt-5 ">Custom Content Above</h4> -->
                                <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="custom-content-above-home-tab" data-toggle="pill" href="#custom-content-above-home" role="tab" aria-controls="custom-content-above-home" aria-selected="true">User Assignment</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-content-above-profile-tab" data-toggle="pill" href="#custom-content-above-profile" role="tab" aria-controls="custom-content-above-profile" aria-selected="false">Menu Assignment</a>
                                    </li>
                                </ul>
                                <!-- <div class="tab-custom-content">
                                    <p class="lead mb-0">Custom Content goes here</p>
                                </div> -->
                                <div class="tab-content" id="custom-content-above-tabContent">
                                    <div class="tab-pane fade show active" id="custom-content-above-home" role="tabpanel" aria-labelledby="custom-content-above-home-tab">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="card-tools">
                                                        <button type="button" class="btn btn-primary btn-sm btn-add-user">
                                                            <i class="fas fa-plus"></i> Add User
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <table id="tbl-user-roles" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                                        <thead>
                                                            <th>No</th>
                                                            <th>Name</th>
                                                            <th>User ID</th>
                                                            <th>Email</th>
                                                            <th></th>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="custom-content-above-profile" role="tabpanel" aria-labelledby="custom-content-above-profile-tab">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="card-tools">
                                                        <button type="button" class="btn btn-primary btn-sm btn-add-menu">
                                                            <i class="fas fa-plus"></i> Add Menu
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <table id="tbl-menu-roles" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                                        <thead>
                                                            <th>No</th>
                                                            <th>Menu</th>
                                                            <th>Group</th>
                                                            <th></th>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('additional-modal')
<div class="modal fade" id="modal-add-user">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add User</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12">
                    <table id="tbl-user-list" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                        <thead>
                            <th>No</th>
                            <th>Name</th>
                            <th>User ID</th>
                            <th>Email</th>
                            <th></th>
                        </thead>
                        <tbody></tbody>
                    </table>  
                </div> 
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="modal-add-menu">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Menu</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12">
                    <table id="tbl-menu-list" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                        <thead>
                            <th>No</th>
                            <th>Application Menu</th>
                            <th></th>
                        </thead>
                        <tbody></tbody>
                    </table>  
                </div> 
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
        </div>
      </div>
    </div>
</div>
@endsection

@section('additional-js')
<script>
    $(document).ready(function(){
        loadRoleUsers();
        loadRoleMenus();

        function loadRoleUsers(){
            $("#tbl-user-roles").DataTable({
                serverSide: true,
                ajax: {
                    url: base_url+'/config/roles/userroles/{{ $datarole->id }}',
                    data: function (data) {
                        data.params = {
                            sac: "sac"
                        }
                    }
                },
                buttons: false,
                columns: [
                    { "data": null,"sortable": false, "searchable": false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }  
                    },
                    {data: "name", className: 'uid'},
                    {data: "username", className: 'fname'},
                    {data: "email", className: 'uname'},
                    {"defaultContent": 
                        "<button type='button' class='btn btn-danger btn-sm button-delete'> <i class='fa fa-trash'></i> Delete Assignment</button>"
                    }
                ],
                "bDestroy": true,
            });
        }

        function loadRoleMenus(){
            $("#tbl-menu-roles").DataTable({
                serverSide: true,
                ajax: {
                    url: base_url+'/config/roles/rolemenus/{{ $datarole->id }}',
                    data: function (data) {
                        data.params = {
                            sac: "sac"
                        }
                    }
                },
                buttons: false,
                columns: [
                    { "data": null,"sortable": false, "searchable": false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }  
                    },
                    {data: "name", className: 'uid'},
                    {data: "group", className: 'fname'},
                    {"defaultContent": 
                        "<button type='button' class='btn btn-danger btn-sm button-delete'> <i class='fa fa-trash'></i> Delete Assignment</button>"
                    }
                ],
                "bDestroy": true,
            });
        }

        function loadListUser(){
            $("#tbl-user-list").DataTable({
                serverSide: true,
                ajax: {
                    url: base_url+'/config/roles/listuser/{{ $datarole->id }}',
                    data: function (data) {
                        data.params = {
                            sac: "sac"
                        }
                    }
                },
                buttons: false,
                columns: [
                    { "data": null,"sortable": false, "searchable": false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }  
                    },
                    {data: "name", className: 'uid'},
                    {data: "username", className: 'fname'},
                    {data: "email", className: 'fname'},
                    {"defaultContent": 
                        "<button type='button' class='btn btn-primary btn-sm button-add-user-role'> <i class='fa fa-plus'></i> Add</button>"
                    }
                ],
                "bDestroy": true,
            });
        }

        function loadListMenu(){
            $("#tbl-menu-list").DataTable({
                serverSide: true,
                ajax: {
                    url: base_url+'/config/roles/listmenu/{{ $datarole->id }}',
                    data: function (data) {
                        data.params = {
                            sac: "sac"
                        }
                    }
                },
                buttons: false,
                columns: [
                    { "data": null,"sortable": false, "searchable": false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }  
                    },
                    {data: "name", className: 'uid'},
                    {"defaultContent": 
                        "<button type='button' class='btn btn-primary btn-sm button-add-menu-role'> <i class='fa fa-plus'></i> Add</button>"
                    }
                ],
                "bDestroy": true,
            });
        }

        $('#tbl-user-list tbody').on( 'click', '.button-add-user-role', function () {
            var userTable = $('#tbl-user-list').DataTable();
            selected_data = [];
            selected_data = userTable.row($(this).closest('tr')).data();

            // console.log(selected_data);
            let _token   = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: base_url+'/config/roles/saveroleuser',
                type:"POST",
                data:{
                    roleid:{{ $datarole->id }},
                    userid:selected_data.id,
                    _token: _token
                },
                success:function(response){
                    console.log(response);
                    if(response) {
                        loadRoleUsers();
                        loadListUser();
                        // $('#modal-add-user').modal('hide');
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });

        $('#tbl-menu-list tbody').on( 'click', '.button-add-menu-role', function () {
            var menuTable = $('#tbl-menu-list').DataTable();
            selected_data = [];
            selected_data = menuTable.row($(this).closest('tr')).data();

            // console.log(selected_data);

            let _token   = $('meta[name="csrf-token"]').attr('content');
            
            $.ajax({
                url: base_url+'/config/roles/saverolemenu',
                type:"POST",
                data:{
                    roleid:{{ $datarole->id }},
                    menuid:selected_data.id,
                    _token: _token
                },
                success:function(response){
                    console.log(response);
                    if(response) {
                        loadRoleMenus();
                        loadListMenu();
                        // $('#modal-add-menu').modal('hide');
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });

        $("#tbl-menu-roles tbody").on('click', '.button-delete', function(){
            var menuTable = $('#tbl-menu-roles').DataTable();
            selected_data = [];
            selected_data = menuTable.row($(this).closest('tr')).data();

            console.log(selected_data)

            let _token    = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: base_url+'/config/roles/deletemenuassignment',
                type:"POST",
                data:{
                    roleid:{{ $datarole->id }},
                    menuid:selected_data.menuid,
                    _token: _token
                },
                success:function(response){
                    console.log(response);
                    if(response) {
                        alert(response.success);
                        loadRoleMenus();
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });

        $("#tbl-user-roles tbody").on('click', '.button-delete', function(){
            var menuTable = $('#tbl-user-roles').DataTable();
            selected_data = [];
            selected_data = menuTable.row($(this).closest('tr')).data();

            console.log(selected_data)

            let _token    = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: base_url+'/config/roles/deleteuserassignment',
                type:"POST",
                data:{
                    roleid:{{ $datarole->id }},
                    userid:selected_data.userid,
                    _token: _token
                },
                success:function(response){
                    console.log(response);
                    if(response) {
                        alert(response.success);
                        loadRoleUsers();
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
        
        $('.btn-add-user').on('click', function(e){
            loadListUser();
            $('#modal-add-user').modal('show');
        });

        $('.btn-add-menu').on('click', function(e){
            loadListMenu();
            $('#modal-add-menu').modal('show');
        });
    });
</script>
@endsection