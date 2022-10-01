@extends('layouts/App')

@section('title', 'Document Approval')

@section('additional-css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style type="text/css">
        .select2-container {
            display: block
        }

        .select2-container .select2-selection--single {
            height: 36px;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <!-- <h4 class="mt-5 ">Custom Content Above</h4> -->
            <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-content-categories-tab" data-toggle="pill" href="#custom-content-categories" role="tab" aria-controls="custom-content-categories" aria-selected="true">Workflow Approval Categories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-content-above-home-tab" data-toggle="pill" href="#custom-content-above-home" role="tab" aria-controls="custom-content-above-home" aria-selected="true">Workflow Approval Group</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-content-above-profile-tab" data-toggle="pill" href="#custom-content-above-profile" role="tab" aria-controls="custom-content-above-profile" aria-selected="false">Workflow Approval Group Assignment</a>
                </li>
            </ul>
            <!-- <div class="tab-custom-content">
                <p class="lead mb-0">Custom Content goes here</p>
            </div> -->
            <div class="tab-content" id="custom-content-above-tabContent">
                <div class="tab-pane fade show active" id="custom-content-categories" role="tabpanel" aria-labelledby="custom-content-categories-tab">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary btn-sm btn-add-categories">
                                        <i class="fas fa-plus"></i> Add Workflow Approval Categories
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="tbl-categories" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                    <thead>
                                        <th>No</th>
                                        <th>Workflow Approval Categories</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                        @foreach($ctgrs as $key => $row)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $row->workflow_category }}</td>
                                            <td style="text-align:center;">
                                                <a href="{{ url('config/workflow/deletecategories/') }}/{{$row->id}}" class='btn btn-danger btn-sm button-delete'> <i class='fa fa-trash'></i> DELETE</a> 
                                                <button class='btn btn-primary btn-sm btn-edit-workflow-categories' data-workflow_categoryid="{{$row->id}}" data-workflow_category="{{$row->workflow_category}}"> <i class='fa fa-edit'></i> EDIT</button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade show" id="custom-content-above-home" role="tabpanel" aria-labelledby="custom-content-above-home-tab">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary btn-sm btn-add-user">
                                        <i class="fas fa-plus"></i> Add Workflow Approval Group
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="tbl-menu" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                    <thead>
                                        <th>No</th>
                                        <th>Workflow Approval Group</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                        @foreach($groups as $key => $row)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $row->workflow_group }}</td>
                                            <td style="text-align:center;">
                                                <a href="{{ url('config/workflow/deletegroup/') }}/{{$row->id}}" class='btn btn-danger btn-sm button-delete'> <i class='fa fa-trash'></i> DELETE</a> 
                                                <button class='btn btn-primary btn-sm btn-edit-group' data-workflow_groupid="{{$row->id}}" data-workflow_group="{{$row->workflow_group}}"> <i class='fa fa-edit'></i> EDIT</button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
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
                                    <button type="button" class="btn btn-primary btn-sm btn-add-assignment">
                                        <i class="fas fa-plus"></i> Add Assignment
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="tbl-worflow-assignment" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                    <thead>
                                        <th>No</th>
                                        <th>Approval Group</th>
                                        <th>Approval Level</th>
                                        <th>Approval Categories</th>
                                        <th>Creator</th>
                                        <th>Approver</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                        @foreach($wfassignments as $key => $row)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $row->wf_groupname }}</td>
                                            <td>{{ $row->approval_level }}</td>
                                            <td>{{ $row->wf_categoryname }}</td>
                                            <td>{{ $row->creator }}</td>
                                            <td>{{ $row->approver }}</td>
                                            <td style="text-align:center;">
                                                <a href="{{ url('config/workflow/deleteassignment/') }}/{{$row->workflow_group}}/{{$row->approval_level}}/{{$row->workflow_categories}}/{{$row->creatorid}}/{{$row->approverid}}" class='btn btn-danger btn-sm button-delete'> 
                                                    <i class='fa fa-trash'></i> DELETE ASSIGNMENT
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
</div>
@endsection

@section('additional-modal')
<div class="modal fade" id="modal-add-categories">
    <form action="{{ url('config/workflow/savecategories') }}" method="post">
        @csrf
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add New Approval Categories</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                            <thead>
                                <th>Approval Categories</th>
                                <th style="width:50px; text-align:center;">
                                    <button type="button" class="btn btn-success btn-sm btn-add-new-categories">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </th>
                            </thead>
                            <tbody id="tbl-new-categories-body">

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="1"></td>
                                    <td>
                                        
                                    </td>
                                </tr>
                            </tfoot>
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

<div class="modal fade" id="modal-add-user">
    <form action="{{ url('config/workflow/savegroup') }}" method="post">
        @csrf
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add New Approval Group</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                            <thead>
                                <th>Workflow Group</th>
                                <th style="width:50px; text-align:center;">
                                    <button type="button" class="btn btn-success btn-sm btn-add-new-menu">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </th>
                            </thead>
                            <tbody id="tbl-new-menu-body">

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="1"></td>
                                    <td>
                                        
                                    </td>
                                </tr>
                            </tfoot>
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

<div class="modal fade" id="modal-edit-group">
    <form action="{{ url('config/workflow/updategroup') }}" method="post">
        @csrf
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit Workflow Approval Group</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <label for="wfgroupname">Workflow Approval Group</label>
                        <input type="text" name="wfgroupname" id="wfgroupname" class="form-control" required>
                        <input type="hidden" name="wfgroupid" id="wfgroupid" class="form-control" required>
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

<div class="modal fade" id="modal-add-assignment">
    <div class="modal-dialog modal-xl">
        <form action="{{ url('config/workflow/saveassignment') }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Add Workflow Assignment</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <table class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                            <thead>
                                <th>Workflow Group</th>
                                <th>Approval Level</th>
                                <th>Workflow Categories</th>
                                <th>Creator</th>
                                <th>Approver</th>
                                <th style="width:50px; text-align:center;">
                                    <button type="button" class="btn btn-success btn-sm btn-add-new-assignment">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </th>
                            </thead>
                            <tbody id="tbl-new-assignment-body">

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4"></td>
                                    <td>
                                        
                                    </td>
                                </tr>
                            </tfoot>
                        </table>  
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal-edit-categories">
    <form action="{{ url('config/workflow/updatecategories') }}" method="post">
        @csrf
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit Workflow Approval Category</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <label for="wfcategories">Workflow Approval Category</label>
                        <input type="text"   name="wfcategories" id="wfcategories" class="form-control" required>
                        <input type="hidden" name="wfcategoriesid" id="wfcategoriesid" class="form-control" required>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function(){
        //loadRoleUsers();
        //loadRoleMenus();
        $('#tbl-categories, #tbl-worflow-assignment, #tbl-menu-group').DataTable();

        $('.btn-add-categories').on('click', function(){
            $('#modal-add-categories').modal('show');
        });

        $('.btn-add-user').on('click', function(e){
            $('#modal-add-user').modal('show');
        });

        $('.btn-add-assignment').on('click', function(e){
            $('#modal-add-assignment').modal('show');
        });

        $('.btn-edit-workflow-categories').on('click', function(e){
            var _mndata = $(this).data();
            console.log(_mndata)
            $('#wfcategoriesid').val(_mndata.workflow_categoryid);
            $('#wfcategories').val(_mndata.workflow_category);
            $('#modal-edit-categories').modal('show');
        });

        $('.btn-edit-group').on('click', function(e){
            var _mndata = $(this).data();
            console.log(_mndata)
            $('#wfgroupname').val(_mndata.workflow_group);
            $('#wfgroupid').val(_mndata.workflow_groupid);
            $('#modal-edit-group').modal('show');
        });

        // <td>
        //                 <select name="wfcreator[]" class="form-control wfcreator">
        //                     @foreach($users as $key => $row)
        //                         <option value="{{ $row->id }}">{{ $row->name }}</option>
        //                     @endforeach
        //                 </select>
        //             </td>

        $('.btn-add-new-assignment').on('click', function(){
            $('#tbl-new-assignment-body').append(`
                <tr>
                    <td>
                        <select name="wfgroups[]" class="form-control wfgroups">
                            @foreach($groups as $key => $row)
                                <option value="{{ $row->id }}">{{ $row->workflow_group }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="wflevels[]" class="form-control" required>
                    </td>
                    <td>
                        <select name="wfctegrs[]" class="form-control wfctegrs">
                            @foreach($ctgrs as $key => $row)
                                <option value="{{ $row->id }}">{{ $row->workflow_category }}</option>
                            @endforeach
                        </select>
                    </td>

                    <td>
                        <select name="wfcreator[]" class="form-control wfcreator">
                            @foreach($users as $key => $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    
                    <td>
                        <select name="wfapprov[]" class="form-control wfapprov">
                            @foreach($users as $key => $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td style="text-align:center;">
                        <button type="button" class="btn btn-danger btn-sm btnRemove">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);

            $(".wfgroups, .wfctegrs, .wfapprov, .wfcreator").select2();

            $('.btnRemove').on('click', function(e){
                e.preventDefault();
                $(this).closest("tr").remove();
            });
        });

        $('.btn-add-new-categories').on('click', function(){
            $('#tbl-new-categories-body').append(`
                <tr>
                    <td>
                        <input type="text" name="approvalcategories[]" class="form-control"/>
                    </td>
                    <td style="text-align:center;">
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
        

        $('.btn-add-new-menu').on('click', function(){
            $('#tbl-new-menu-body').append(`
                <tr>
                    <td>
                        <input type="text" name="wfgroups[]" class="form-control"/>
                    </td>
                    <td style="text-align:center;">
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