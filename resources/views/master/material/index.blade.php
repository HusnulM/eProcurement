@extends('layouts/App')

@section('title', 'Item Master')

@section('additional-css')
@endsection

@section('content')        
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-content-above-home-tab" data-toggle="pill" href="#custom-content-above-home" role="tab" aria-controls="custom-content-above-home" aria-selected="true">Item Master</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-content-above-itemtype-tab" data-toggle="pill" href="#custom-content-above-itemtype" role="tab" aria-controls="custom-content-above-itemtype" aria-selected="false">Item Category</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-content-above-itemunit-tab" data-toggle="pill" href="#custom-content-above-itemunit" role="tab" aria-controls="custom-content-above-itemunit" aria-selected="false">Item Unit</a>
                </li>
            </ul>
            <div class="tab-content" id="custom-content-above-tabContent">
                <div class="tab-pane fade show active" id="custom-content-above-home" role="tabpanel" aria-labelledby="custom-content-above-home-tab">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"></h3>
                            <div class="card-tools">
                                <a href="{{ url('/master/item/create') }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i> Create Item Master
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tbl-item-master" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                    <thead>
                                        <th>No</th>
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>Item Category</th>
                                        <th>Item Unit</th>
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
                                    <i class="fas fa-plus"></i> Create Item Category
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tbl-item-type" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                    <thead>
                                        <th>No</th>
                                        <th>Item Category</th>
                                        <th style="text-align:center; width: 50px;"></th>
                                    </thead>
                                    <tbody>
            
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="custom-content-above-itemunit" role="tabpanel" aria-labelledby="custom-content-above-itemunit-tab">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"></h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-success btn-sm btn-add-uom">
                                    <i class="fas fa-plus"></i> Create Item Unit
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tbl-item-unit" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                    <thead>
                                        <th>No</th>
                                        <th>Uom Code</th>
                                        <th>Uom Descripiton</th>
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
    </div>
</div>
@endsection

@section('additional-modal')
<div class="modal fade" id="modal-add-itemcategory">
    <form action="{{ url('/master/item/saveitemcategory') }}" method="post">
        @csrf
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add Item Category</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table id="tbl-item-category" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                            <thead>
                                <th>Item Category</th>
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
    <form action="{{ url('/master/item/updateitemcategory') }}" method="post">
        @csrf
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit Item Category</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <label for="item-cate">Item Category</label>
                        <input type="text" class="form-control" name="itemcate" id="edit-item-cate">
                        <input type="hidden" class="form-control" name="itemcatid" id="edit-item-id">
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

<div class="modal fade" id="modal-add-uom">
    <form action="{{ url('/master/item/saveuom') }}" method="post">
        @csrf
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add Unit of Measure</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table id="tbl-uom" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                            <thead>
                                <th>Uom Code</th>
                                <th>Uom Descripiton</th>
                                <th style="text-align:center;">
                                    <button type="button" class="btn btn-success btn-sm btn-add-new-uom">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </th>
                            </thead>
                            <tbody id="tbl-uom-body">
    
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

<div class="modal fade" id="modal-edit-uom">
    <form action="{{ url('/master/item/updateuom') }}" method="post">
        @csrf
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit Unit of Measure</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <label for="">Uom Code</label>
                        <input type="text" class="form-control" name="uom-code" id="uom-code">
                        <input type="hidden" class="form-control" name="uom-id" id="uom-id">
                    </div> 

                    <div class="col-lg-12">
                        <label for="">Uom Code</label>
                        <input type="text" class="form-control" name="uom-desc" id="uom-desc">
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
                url: base_url+'/master/item/itemlist',
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
                {data: "material", className: 'uid'},
                {data: "matdesc", className: 'fname'},
                {data: "mattypedesc", className: 'uname'},
                {data: "matunit", className: 'uname'},
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
            window.location = base_url+"/master/item/delete/"+selected_data.matuniqid;
        });
        $('#tbl-item-master tbody').on( 'click', '.button-edit', function () {
            var table = $('#tbl-item-master').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            console.log(selected_data)
            window.location = base_url+"/master/item/edit/"+selected_data.matuniqid;
        });


        $("#tbl-item-type").DataTable({
            serverSide: true,
            ajax: {
                url: base_url+'/master/item/itemcatlist',
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
                { "data": null,"sortable": false, "searchable": false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }  
                },
                {data: "mattypedesc"},
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
            window.location = base_url+"/master/item/deleteitemcat/"+selected_data.id;
        });
        $('#tbl-item-type tbody').on( 'click', '.button-itmcat-edit', function () {
            var table = $('#tbl-item-type').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            console.log(selected_data)
            $('#edit-item-cate').val(selected_data.mattypedesc);
            $('#edit-item-id').val(selected_data.id);
            $('#modal-edit-itemcategory').modal('show');
        });

        $("#tbl-item-unit").DataTable({
            serverSide: true,
            ajax: {
                url: base_url+'/master/item/uomlists',
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
                { "data": null,"sortable": false, "searchable": false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }  
                },
                {data: "uom"},
                {data: "uomdesc"},
                {"defaultContent": 
                    `<button class='btn btn-danger btn-sm button-uom-delete'> <i class='fa fa-trash'></i> DELETE</button> 
                    <button class='btn btn-primary btn-sm button-uom-edit'> <i class='fa fa-edit'></i> EDIT</button>
                    `,
                    "className": "text-center",
                    "width": "50px"
                }
            ]  
        }).columns.adjust();
        
        $('#tbl-item-unit tbody').on( 'click', '.button-uom-delete', function () {
            var table = $('#tbl-item-unit').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            window.location = base_url+"/master/item/deleteuom/"+selected_data.id;
        });
        $('#tbl-item-unit tbody').on( 'click', '.button-uom-edit', function () {
            var table = $('#tbl-item-unit').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            console.log(selected_data)
            $('#uom-desc').val(selected_data.uomdesc);
            $('#uom-code').val(selected_data.uom);
            $('#uom-id').val(selected_data.id);
            $('#modal-edit-uom').modal('show');
        });

        $('.btn-create-itemcat').on('click', function(){
            $('#modal-add-itemcategory').modal('show');
        });

        $('.btn-add-new-itemcate').on('click', function(){
            $('#tbl-item-category-body').append(`
                <tr>
                    <td>
                        <input type="text" class="form-control" name="itemcate[]" required>
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

        $('.btn-add-uom').on('click', function(){
            $('#modal-add-uom').modal('show');
        });

        $('.btn-add-new-uom').on('click', function(){
            $('#tbl-uom-body').append(`
                <tr>
                    <td>
                        <input type="text" class="form-control" name="uom[]" required>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="uomdesc[]" required>
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