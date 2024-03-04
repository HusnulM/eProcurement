@extends('layouts/App')

@section('title', 'Cost Code Master')

@section('additional-css')
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-content-above-home-tab" data-toggle="pill" href="#custom-content-above-home" role="tab" aria-controls="custom-content-above-home" aria-selected="true">Cost Code</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-content-above-itemtype-tab" data-toggle="pill" href="#custom-content-above-itemtype" role="tab" aria-controls="custom-content-above-itemtype" aria-selected="false">Cost Group</a>
                </li>
            </ul>
            <div class="tab-content" id="custom-content-above-tabContent">
                <div class="tab-pane fade show active" id="custom-content-above-home" role="tabpanel" aria-labelledby="custom-content-above-home-tab">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"></h3>
                            <div class="card-tools">
                                <a href="{{ url('/master/costmaster/create') }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i> Create cost code master
                                </a>

                                {{-- <a href="{{ url('/master/costmaster/upload') }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-upload"></i> Upload cost code master
                                </a> --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tbl-item-master" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                    <thead>
                                        <th>No</th>
                                        <th>Cost Code</th>
                                        <th>Cost Name</th>
                                        <th>Cost Group</th>
                                        <th>Cost Group Desc</th>
                                        <th style="text-align:center;"></th>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="custom-content-above-itemtype" role="tabpanel" aria-labelledby="custom-content-above-itemtype-tab">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"></h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-success btn-sm btn-create-itemcat">
                                    <i class="fas fa-plus"></i> Add Cost Group
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tbl-item-type" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                    <thead>
                                        <th>Cost Group</th>
                                        <th>Description</th>
                                        <th style="text-align:center; width: 50px;"></th>
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
</div>
@endsection

@section('additional-modal')
<div class="modal fade" id="modal-add-itemcategory">
    <form action="{{ url('/master/costmaster/savecostgroup') }}" method="post">
        @csrf
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add Cost Group</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table id="tbl-item-category" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                            <thead>
                                <th>Cost Group</th>
                                <th>Description</th>
                                <th style="text-align:center;">
                                    <button type="button" class="btn btn-success btn-sm btn-add-new-itemcate">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </th>
                            </thead>
                            <tbody id="tbl-item-category-body">

                            </tbody>
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

<div class="modal fade" id="modal-edit-itemcategory">
    <form action="{{ url('/master/costmaster/updatecostgroup') }}" method="post">
        @csrf
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit Cost Group</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <label for="item-cate">Cost Group</label>
                        <input type="text" class="form-control" name="costgroup" id="edit-cost-group">
                        <input type="hidden" class="form-control" name="costgroupid" id="edit-cost-group-id">
                    </div>
                    <div class="col-lg-12">
                        <label for="item-cate">Description</label>
                        <input type="text" class="form-control" name="costgroupdesc" id="edit-costgroup-desc">
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
        $("#tbl-item-master").DataTable({
            serverSide: true,
            ajax: {
                url: base_url+'/master/costmaster/costlist',
                data: function (data) {
                    data.params = {
                        sac: "sac"
                    }
                }
            },
            buttons: false,
            searching: true,
            // scrollY: 500,
            // scrollX: true,
            // scrollCollapse: true,
            columns: [
                { "data": null,"sortable": false, "searchable": false, "defaultContent": "",
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {data: "cost_code", className: 'uid'},
                {data: "cost_desc", className: 'fname'},
                {data: "cost_group", className: 'fname'},
                {data: "cost_group_desc", className: 'uname'},
                {"defaultContent":
                    `<button class='btn btn-danger btn-sm button-delete'> <i class='fa fa-trash'></i> DELETE</button>
                    <button class='btn btn-primary btn-sm button-edit'> <i class='fa fa-edit'></i> EDIT</button>
                    `,
                    "className": "text-center",
                    "width": "20%"
                }
            ]
        }).columns.adjust();

        $('#tbl-item-master tbody').on( 'click', '.button-delete', function () {
            var table = $('#tbl-item-master').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            window.location = base_url+"/master/costmaster/deletecostmaster/"+selected_data.id;
        });
        $('#tbl-item-master tbody').on( 'click', '.button-edit', function () {
            var table = $('#tbl-item-master').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            console.log(selected_data)
            window.location = base_url+"/master/costmaster/edit/"+selected_data.id;
        });


        $("#tbl-item-type").DataTable({
            serverSide: true,
            ajax: {
                url: base_url+'/master/costmaster/costgrouplist',
                data: function (data) {
                    data.params = {
                        sac: "sac"
                    }
                }
            },
            buttons: false,
            searching: true,
            columns: [
                {data: "cost_group"},
                {data: "cost_group_desc"},
                {"defaultContent":
                    `<button class='btn btn-danger btn-sm button-itmcat-delete'> <i class='fa fa-trash'></i> DELETE</button>
                    <button class='btn btn-primary btn-sm button-itmcat-edit'> <i class='fa fa-edit'></i> EDIT</button>
                    `,
                    "className": "text-center",
                    "width": "50px"
                }
            ]
        }).columns.adjust();

        $('#tbl-item-type tbody').on( 'click', '.button-itmcat-delete', function () {
            var table = $('#tbl-item-type').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            window.location = base_url+"/master/costmaster/deletecostgroup/"+selected_data.id;
        });
        $('#tbl-item-type tbody').on( 'click', '.button-itmcat-edit', function () {
            var table = $('#tbl-item-type').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            console.log(selected_data)
            $('#edit-cost-group').val(selected_data.cost_group);
            $('#edit-costgroup-desc').val(selected_data.cost_group_desc);
            $('#edit-cost-group-id').val(selected_data.id);
            $('#modal-edit-itemcategory').modal('show');
        });

        $('.btn-create-itemcat').on('click', function(){
            $('#modal-add-itemcategory').modal('show');
        });

        $('.btn-add-new-itemcate').on('click', function(){
            $('#tbl-item-category-body').append(`
                <tr>
                    <td>
                        <input type="text" class="form-control" name="costgroup[]" required>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="costgroupdesc[]" required>
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
