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
                    <a class="nav-link active" id="custom-content-categories-tab" data-toggle="pill" href="#custom-content-categories" role="tab" aria-controls="custom-content-categories" aria-selected="true">Approval Budget</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-content-above-home-tab" data-toggle="pill" href="#custom-content-above-home" role="tab" aria-controls="custom-content-above-home" aria-selected="true">Approval PBJ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-content-above-spk-tab" data-toggle="pill" href="#custom-content-above-spk" role="tab" aria-controls="custom-content-above-spk" aria-selected="true">Approval SPK</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-content-above-profile-tab" data-toggle="pill" href="#custom-content-above-profile" role="tab" aria-controls="custom-content-above-profile" aria-selected="false">Approval PR</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-content-above-po-tab" data-toggle="pill" href="#custom-content-above-po" role="tab" aria-controls="custom-content-above-po" aria-selected="false">Approval PO</a>
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
                                        <i class="fas fa-plus"></i> Tambah Approval Budget
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="tbl-worflow-budget" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                    <thead>
                                        <th>No</th>
                                        <th>Budget Requestor</th>
                                        <th>Approver</th>
                                        <th>Approval Level</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                        @foreach($budgetwf as $key => $row)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $row->requester_name }}</td>
                                            <td>{{ $row->approver_name }}</td>
                                            <td>{{ $row->approver_level }}</td>
                                            <td style="text-align:center;">
                                                <a href="{{ url('config/workflow/deletebudgetwf/') }}/{{$row->id}}" class='btn btn-danger btn-sm button-delete'> <i class='fa fa-trash'></i> DELETE</a> 
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
                                    <button type="button" class="btn btn-primary btn-sm btn-add-pbj-approval">
                                        <i class="fas fa-plus"></i> Tambah Approval PBJ
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="tbl-worflow-pbj" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                    <thead>
                                        <th>No</th>
                                        <th>PBJ Creator</th>
                                        <th>Approver</th>
                                        <th>Approval Level</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                        @foreach($pbjwf as $key => $row)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $row->requester_name }}</td>
                                            <td>{{ $row->approver_name }}</td>
                                            <td>{{ $row->approver_level }}</td>
                                            <td style="text-align:center;">
                                                <a href="{{ url('config/workflow/deletepbjwf/') }}/{{$row->id}}" class='btn btn-danger btn-sm button-delete'> 
                                                    <i class='fa fa-trash'></i> DELETE
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

                <div class="tab-pane fade show" id="custom-content-above-spk" role="tabpanel" aria-labelledby="custom-content-above-spk-tab">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary btn-sm btn-add-spk-approval">
                                        <i class="fas fa-plus"></i> Tambah Approval SPK
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="tbl-worflow-spk" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                    <thead>
                                        <th>No</th>
                                        <th>SPK Creator</th>
                                        <th>Approver</th>
                                        <th>Approval Level</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                        @foreach($spkwf as $key => $row)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $row->requester_name }}</td>
                                            <td>{{ $row->approver_name }}</td>
                                            <td>{{ $row->approver_level }}</td>
                                            <td style="text-align:center;">
                                                <a href="{{ url('config/workflow/deletewowf/') }}/{{$row->id}}" class='btn btn-danger btn-sm button-delete'> 
                                                    <i class='fa fa-trash'></i> DELETE
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

                <div class="tab-pane fade" id="custom-content-above-profile" role="tabpanel" aria-labelledby="custom-content-above-profile-tab">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary btn-sm btn-add-pr-approval">
                                        <i class="fas fa-plus"></i> Tambah Approval PR
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="tbl-worflow-pr" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                    <thead>
                                        <th>No</th>
                                        <th>PR Creator</th>
                                        <th>Approver</th>
                                        <th>Approval Level</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                        @foreach($prwf as $key => $row)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $row->requester_name }}</td>
                                            <td>{{ $row->approver_name }}</td>
                                            <td>{{ $row->approver_level }}</td>
                                            <td style="text-align:center;">
                                                <a href="{{ url('config/workflow/deleteprwf/') }}/{{$row->id}}" class='btn btn-danger btn-sm button-delete'> 
                                                    <i class='fa fa-trash'></i> DELETE
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

                <div class="tab-pane fade" id="custom-content-above-po" role="tabpanel" aria-labelledby="custom-content-above-po-tab">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary btn-sm btn-add-po-approval">
                                        <i class="fas fa-plus"></i> Tambah Approval PO
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="tbl-worflow-po" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                    <thead>
                                        <th>No</th>
                                        <th>PO Creator</th>
                                        <th>Approver</th>
                                        <th>Approval Level</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                        @foreach($powf as $key => $row)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $row->requester_name }}</td>
                                            <td>{{ $row->approver_name }}</td>
                                            <td>{{ $row->approver_level }}</td>
                                            <td style="text-align:center;">
                                                <a href="{{ url('config/workflow/deletepowf/') }}/{{$row->id}}" class='btn btn-danger btn-sm button-delete'> 
                                                    <i class='fa fa-trash'></i> DELETE
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
    <form action="{{ url('config/workflow/savebudgetwf') }}" method="post">
        @csrf
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Tambah Approval Budget</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                            <thead>
                                <th>Budget Requester</th>
                                <th>Budget Approver</th>
                                <th>Approval Level</th>
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

<div class="modal fade" id="modal-approval-pbj">
    <form action="{{ url('config/workflow/savepbjwf') }}" method="post">
        @csrf
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Tambah Approval PBJ</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                            <thead>
                                <th>PBJ Creator</th>
                                <th>PBJ Approver</th>
                                <th>Approval Level</th>
                                <th style="width:50px; text-align:center;">
                                    <button type="button" class="btn btn-success btn-sm btn-add-new-pbj-wf">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </th>
                            </thead>
                            <tbody id="tbl-pbj-wf-body">

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

<div class="modal fade" id="modal-approval-pr">
    <form action="{{ url('config/workflow/saveprwf') }}" method="post">
        @csrf
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Tambah Approval PR</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                            <thead>
                                <th>PR Creator</th>
                                <th>PR Approver</th>
                                <th>Approval Level</th>
                                <th style="width:50px; text-align:center;">
                                    <button type="button" class="btn btn-success btn-sm btn-add-new-pr-wf">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </th>
                            </thead>
                            <tbody id="tbl-pr-wf-body">

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

<div class="modal fade" id="modal-approval-po">
    <form action="{{ url('config/workflow/savepowf') }}" method="post">
        @csrf
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Tambah Approval PO</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                            <thead>
                                <th>PO Creator</th>
                                <th>PO Approver</th>
                                <th>Approval Level</th>
                                <th style="width:50px; text-align:center;">
                                    <button type="button" class="btn btn-success btn-sm btn-add-new-po-wf">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </th>
                            </thead>
                            <tbody id="tbl-po-wf-body">

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

<div class="modal fade" id="modal-approval-spk">
    <form action="{{ url('config/workflow/savespkwf') }}" method="post">
        @csrf
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Tambah Approval SPK</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                            <thead>
                                <th>SPK Creator</th>
                                <th>SPK Approver</th>
                                <th>Approval Level</th>
                                <th style="width:50px; text-align:center;">
                                    <button type="button" class="btn btn-success btn-sm btn-add-new-spk-wf">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </th>
                            </thead>
                            <tbody id="tbl-spk-wf-body">

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

@endsection

@section('additional-js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function(){
        //loadRoleUsers();
        //loadRoleMenus();
        $('#tbl-worflow-budget, #tbl-worflow-pbj, #tbl-worflow-spk, #tbl-worflow-pr, #tbl-worflow-po').DataTable();

        $('.btn-add-categories').on('click', function(){
            $('#modal-add-categories').modal('show');
        });

        $('.btn-add-pbj-approval').on('click', function(e){
            $('#modal-approval-pbj').modal('show');
        });

        $('.btn-add-assignment').on('click', function(e){
            $('#modal-add-assignment').modal('show');
        });

        $('.btn-add-pr-approval').on('click', function(){
            $('#modal-approval-pr').modal('show');
        });

        $('.btn-add-po-approval').on('click', function(){
            $('#modal-approval-po').modal('show');
        });

        $('.btn-add-spk-approval').on('click', function(){
            $('#modal-approval-spk').modal('show');
        });

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

        $('.btn-add-new-pbj-wf').on('click', function(){
            $('#tbl-pbj-wf-body').append(`
                <tr>
                    <td>
                        <select name="requester[]" class="form-control requester">
                            @foreach($users as $key => $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="approver[]" class="form-control approver">
                            @foreach($users as $key => $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" name="applevel[]" class="form-control"/>
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
                        <select name="requester[]" class="form-control requester">
                            @foreach($users as $key => $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="approver[]" class="form-control approver">
                            @foreach($users as $key => $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" name="applevel[]" class="form-control"/>
                    </td>
                    <td style="text-align:center;">
                        <button type="button" class="btn btn-danger btn-sm btnRemove">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);

            $(".requester, .approver").select2();

            $('.btnRemove').on('click', function(e){
                e.preventDefault();
                $(this).closest("tr").remove();
            });
        });
        

        $('.btn-add-new-pr-wf').on('click', function(){
            $('#tbl-pr-wf-body').append(`
                <tr>
                    <td>
                        <select name="requester[]" class="form-control requester">
                            @foreach($users as $key => $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="approver[]" class="form-control approver">
                            @foreach($users as $key => $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" name="applevel[]" class="form-control"/>
                    </td>
                    <td style="text-align:center;">
                        <button type="button" class="btn btn-danger btn-sm btnRemove">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);

            $(".requester, .approver").select2();

            $('.btnRemove').on('click', function(e){
                e.preventDefault();
                $(this).closest("tr").remove();
            });
        });

        $('.btn-add-new-po-wf').on('click', function(){
            $('#tbl-po-wf-body').append(`
                <tr>
                    <td>
                        <select name="requester[]" class="form-control requester">
                            @foreach($users as $key => $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="approver[]" class="form-control approver">
                            @foreach($users as $key => $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" name="applevel[]" class="form-control"/>
                    </td>
                    <td style="text-align:center;">
                        <button type="button" class="btn btn-danger btn-sm btnRemove">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);

            $(".requester, .approver").select2();

            $('.btnRemove').on('click', function(e){
                e.preventDefault();
                $(this).closest("tr").remove();
            });
        });

        $('.btn-add-new-spk-wf').on('click', function(){
            $('#tbl-spk-wf-body').append(`
                <tr>
                    <td>
                        <select name="requester[]" class="form-control requester">
                            @foreach($users as $key => $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="approver[]" class="form-control approver">
                            @foreach($users as $key => $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" name="applevel[]" class="form-control"/>
                    </td>
                    <td style="text-align:center;">
                        <button type="button" class="btn btn-danger btn-sm btnRemove">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);

            $(".requester, .approver").select2();

            $('.btnRemove').on('click', function(e){
                e.preventDefault();
                $(this).closest("tr").remove();
            });
        });
    });
</script>
@endsection