@extends('layouts/App')

@section('title', 'User Object Authorization')

@section('additional-css')
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">User Object Authorization</h3>
                    <div class="card-tools">
                        <a href="{{ url('/config/users') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                        <button type="button" class="btn btn-primary btn-sm btn-add-object-auth">
                            <i class="fas fa-plus"></i> Add Object Auth
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="">User</label>
                            <input type="text" value="{{ $datauser->username }} : {{ $datauser->name }}" class="form-control" readonly>
                            <br>
                        </div>
                        <div class="col-lg-12">
                            <table id="tbl-user-roles" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                <thead>
                                    <th>No</th>
                                    <!-- <th>Object Auth</th> -->
                                    <th>Object Name</th>
                                    <th>Object Value</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    @foreach($uobjauth as $key => $row)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $row->object_description }}</td>
                                        <td>{{ $row->object_val }}</td>
                                        <td>
                                            <a href="{{ url('/config/users/objectauth/delete/') }}/{{ $datauser->id }}/{{ $row->object_name }}" class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash"></i>
                                            </a>
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
    </div>
</div>
@endsection

@section('additional-modal')
<div class="modal fade" id="modal-add-user-obj-auth">
    <form action="{{ url('/config/users/saveobjectauth') }}" method="post">
        @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Add User Object Authorization</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="userid" value="{{ $datauser->id }}">
                        <div class="col-lg-12">
                            <table id="tbl-user-list" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                <thead>
                                    <th>Object Auth</th>
                                    <th>Object Value</th>
                                    <th style="width:50px; text-align:center;">
                                        <button type="button" class="btn btn-success btn-sm btn-add-new-object-auth">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </th>
                                </thead>
                                <tbody id="tbl-object-auth-add"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('additional-js')
<script>
    $(document).ready(function(){

        $('.btn-add-object-auth').on('click', function(e){
            // loadListUser();
            $('#modal-add-user-obj-auth').modal('show');
        });

        $('.btn-add-new-object-auth').on('click', function(){
            $('#tbl-object-auth-add').append(`
                <tr>
                    <td>
                        <select name="objauth[]" class="form-control">
                            @foreach($objauth as $key => $row)
                                <option value="{{ $row->object_name }}">{{ $row->object_description }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" name="objval[]" class="form-control">
                    </td>
                    <td style="width:50px; text-align:center;">
                        <button type="button" class="btn btn-danger btn-sm btnRemove">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);

            $('.btnRemove').on('click', function(e){
                e.preventDefault();
                $(this).closest("tr").remove();
            });
        });
    });
</script>
@endsection
