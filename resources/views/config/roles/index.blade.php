@extends('layouts/App')

@section('title', 'Roles')

@section('additional-css')
@endsection

@section('content')        
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Role List</h3>
                    <div class="card-tools">
                        <a href="{{ url('/config/roles/create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Create Role
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
                    <table id="tbl-roles" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                        <thead>
                            <th style="width:100px;">No</th>
                            <th>Role Name</th>
                            <th style="width:240px; text-align:center;"></th>
                        </thead>
                        <tbody>
                            @foreach($role as $key => $row)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{ $row->rolename }}</td>
                                <td style="text-align:center;">
                                    <a href="{{ url('config/roles/delete') }}/{{$row->id}}" class='btn btn-danger btn-sm button-delete'> <i class='fa fa-trash'></i> DELETE</a> 
                                    <a href="{{ url('config/roles/assignment') }}/{{$row->id}}" class='btn btn-primary btn-sm button-edit'> <i class='fa fa-edit'></i> ASSIGNMENT</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('additional-js')
<script>
    $(document).ready(function(){
        $('#tbl-roles').DataTable();

        $('#tbl-roles tbody').on( 'click', '.button-delete', function () {
            var table = $('#tbl-users').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            window.location = base_url+"/config/users/delete/"+selected_data.id;
        });
        $('#tbl-roles tbody').on( 'click', '.button-edit', function () {
            var table = $('#tbl-users').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            window.location = base_url+"/config/users/edit/"+selected_data.id;
        });
    });
</script>
@endsection