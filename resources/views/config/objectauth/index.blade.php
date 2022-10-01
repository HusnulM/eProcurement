@extends('layouts/App')

@section('title', 'Object Authorization')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Object Authorization List</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-success btn-sm btn-add-doctype">
                            <i class="fas fa-plus"></i> Create Object Authorization
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tbl-doctype" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                        <thead>
                            <th style="width:100px;">No</th>
                            <th>Object Authorization</th>
                            <th>Description</th>
                            <th style="width:280px; text-align:center;"></th>
                        </thead>
                        <tbody>
                            @foreach($obauth as $key => $row)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{ $row->object_name }}</td>
                                <td>{{ $row->object_description }}</td>
                                <td style="text-align:center;">
                                    <a href="{{ url('config/objectauth/delete') }}/{{$row->object_name}}" class="btn btn-danger btn-sm button-delete"> 
                                        <i class='fa fa-trash'></i> DELETE
                                    </a> 
                                    <!-- <button type="button" class="btn btn-primary btn-sm button-edit" data-objname="{{$row->object_name}}" data-objdesc="{{$row->object_description}}"> 
                                        <i class='fa fa-edit'></i> EDIT
                                    </button> -->
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

@section('additional-modal')
<div class="modal fade" id="modal-add-doctype">
    <form action="{{ url('config/objectauth/save') }}" method="post">
        @csrf
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Create Object Authorization</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <label for="objname">Object Name</label>
                        <input type="text" name="objname" class="form-control" autocomplete="off" required>
                    </div> 
                    <div class="col-lg-12">
                        <label for="objdesc">Object Description</label>
                        <input type="text" name="objdesc" class="form-control" autocomplete="off" required>
                    </div> 
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Save
                </button>
            </div>
          </div>
        </div>
    </form>
</div>


@endsection

@section('additional-js')
<script>
    $(document).ready(function(){
        $('#tbl-doctype').DataTable();

        $('.btn-add-doctype').on('click', function(){
            $('#modal-add-doctype').modal('show');
        });

        
    });
</script>
@endsection